<?php

namespace Radmin\command;

use plugin\radmin\app\admin\model\data\Table;
use Radmin\Command;
use Radmin\Event;
use Radmin\orm\Rdb;
use Radmin\util\FileUtil;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class DataBackup extends Command
{
    protected static $defaultName        = 'data:backup';
    protected static $defaultDescription = 'Backup specified database tables';

    const BACKUP_PATH  = '';
    const VERSION_PATH = 'version/';

    private string  $version;
    protected float $startTime;
    protected int   $startMemory;
    private float   $totalSize      = 0;
    private int     $successBackups = 0;
    private int     $recordCount    = 0;
    protected       $admin_id       = 0;

    public function __construct()
    {
        parent::__construct();
        $this->version = date("YmdHis");
    }

    protected function configure()
    {
        $this->setName(self::$defaultName)
            ->setDescription('数据库表备份工具')
            ->addArgument('tables', InputArgument::OPTIONAL, '要备份的表名，多个用逗号分隔', '')
            ->addOption('all', 'a', InputOption::VALUE_NONE, '备份所有表')
            ->addOption('admin', 'admin', InputOption::VALUE_OPTIONAL, '备份所有表')
            ->addUsage('使用方法:')
            ->addUsage('  php webman data:backup --all  # 备份所有表并压缩')
            ->addUsage('  php webman data:backup users,posts     # 备份指定表并压缩')
            ->addUsage('  php webman data:backup                 # 备份所有表(默认)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output      = $output;
        $this->startTime   = microtime(true);
        $this->startMemory = memory_get_usage();
        $versionTimestamp  = date("YmdHis");
        if ($input->getOption('admin')) {
            $this->admin_id = $input->getOption('admin');
        }

        // 初始化目录
        $baseBackupDir = DataBackup . phpruntime_path() . get_sys_config('backup_path') . self::BACKUP_PATH;
        $versionDir    = $baseBackupDir . self::VERSION_PATH;
        FileUtil::mkdir($baseBackupDir);
        FileUtil::mkdir($versionDir);

        // 获取要备份的表
        $tables = $this->getTablesToBackup($input, $output);
        if (empty($tables)) {
            $this->outputStatistics($output);
            return self::SUCCESS;
        }

        // 输出备份开始信息
        $this->outputHeader('Webman Data Backup', 'Webman-Radmin Backup Tool By albert <albert@rocareer.com>', 'V1.0.9');

        // 备份每个表
        foreach ($tables as $table) {
            usleep(300000); // 避免CPU占用过高
            $this->backupTable($table, $input, $output, $baseBackupDir, $versionTimestamp);
        }

        $output->writeln("<info>All backups completed</info>");

        // 创建版本包
        $this->createVersionPackage($tables, $input, $output, $baseBackupDir, $versionDir, $versionTimestamp);

        $this->outputStatistics($output);
        return self::SUCCESS;
    }

    /**
     * 获取需要备份的表列表
     */
    private function getTablesToBackup(InputInterface $input, OutputInterface $output): array
    {
        $tablesArg = $input->getArgument('tables') ?? '';
        $tables    = $input->getOption('all') || empty($tablesArg)
            ? array_column(Table::select()->toArray(), 'name')
            : explode(',', $tablesArg);

        // 排除data_backup表本身，避免循环备份
        return array_filter($tables, function ($table) {
            return $table !== getDbPrefix() . 'data_backup';
        });
    }

    /**
     * 创建SQL备份文件
     */
    private function createSqlBackupFile(string $table, string $tableBackupDir, string $versionTimestamp, array $data): string
    {
        $backupFile  = $tableBackupDir . "{$versionTimestamp}.sql";
        $createTable = Rdb::query("SHOW CREATE TABLE `{$table}`")[0]['Create TableUtil'];

        $content = "-- TableUtil structure for {$table}\n";
        $content .= $createTable . ";\n\n";
        $content .= "-- Data for {$table}\n";

        foreach ($data as $row) {
            $values  = array_map(function ($value) {
                return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
            }, $row);
            $content .= "INSERT INTO `{$table}` VALUES (" . implode(', ', $values) . ");\n";
        }

        file_put_contents($backupFile, $content);
        return $backupFile;
    }

    /**
     * 压缩备份文件
     */
    private function compressBackupFile(string $backupFile, string $tableBackupDir, string $versionTimestamp): string
    {
        $zipFile = $tableBackupDir . "{$versionTimestamp}.zip";
        $zip     = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
            $zip->addFile($backupFile, basename($backupFile));
            $zip->close();
        }
        return $zipFile;
    }

    /**
     * 格式化备份完成输出
     */
    private function formatBackupCompleteOutput(string $zipFile): string
    {
        $outputPath = str_replace(runtime_path(), '', $zipFile);
        $zipSize    = File::formatSize(filesize($zipFile));
        $padding    = str_pad($zipSize, 71 - mb_strwidth($outputPath, 'UTF-8'), ' ', STR_PAD_LEFT);
        return "Backup completed: {$outputPath} {$padding}";
    }

    /**
     * 创建版本包
     */
    private function createVersionPackage(
        array $tables, InputInterface $input, OutputInterface $output,
        string $baseBackupDir, string $versionDir, string $versionTimestamp
    ): void {
        $timestampZip = $versionDir . $versionTimestamp . '.zip';
        $zip          = new ZipArchive();
        $zipCreated   = false;

        if ($zip->open($timestampZip, ZipArchive::CREATE) === true) {
            $filesAdded = 0;
            foreach ($tables as $table) {
                $tableZipFile = $baseBackupDir . "table/{$table}/{$versionTimestamp}.zip";
                if (file_exists($tableZipFile)) {
                    if ($zip->addFile($tableZipFile, "{$table}.zip")) {
                        $filesAdded++;
                    } else {
                        $output->writeln("<error>Failed to add {$table}.zip to version package</error>");
                    }
                }
            }

            if ($filesAdded > 0) {
                $zip->close();
                $zipCreated = true;
            } else {
                $zip->close();
                @unlink($timestampZip);
                $output->writeln("<error>No valid table backups found to create version package</error>");
            }
        }

        if ($zipCreated && file_exists($timestampZip)) {
            $versionZipPath  = str_replace(runtime_path(), '', $timestampZip);
            $outputZipSize   = filesize($timestampZip);
            $this->totalSize += $outputZipSize;

            // record
            // 记录备份记录
            $data = [
                'version'  => $versionTimestamp,
                'file'     => $versionZipPath,
            ];
            if ($input->getOption('all')) {
                $data['table_name'] = 'all';
            } else {
                if (count($tables)>1){
                    $data['table_name'] = 'multi';
                }
            }

            Event::emit('data.backup.record', $data);
            $data['admin_id']=$this->admin_id;
            Event::emit('log.data.backup', $data);


            $output->writeln($this->formatVersionPackageOutput($versionZipPath, $outputZipSize));
            usleep(1000000);
        } else {
            $output->writeln("<error>Failed to create version zip file</error>");
        }
    }

    /**
     * 格式化版本包输出
     */
    private function formatVersionPackageOutput(string $zipPath, int $size): string
    {
        $sizeFormatted = File::formatSize($size);
        $padding       = str_pad($sizeFormatted, 74 - mb_strwidth($zipPath, 'UTF-8'), ' ', STR_PAD_LEFT);
        usleep(1000000);
        return "<info>Total Zip Done: {$zipPath}{$padding}</info>";
    }

    /**
     * 输出统计信息
     */
    private function outputStatistics(OutputInterface $output): void
    {
        $executionTime    = round(microtime(true) - $this->startTime, 2);
        $memoryUsage      = round((memory_get_peak_usage() - $this->startMemory) / 1024 / 1024, 2);
        $totalSize        = round($this->totalSize / 1024 / 1024, 2);
        $compressionRatio = $this->totalSize > 0
            ? round(($this->totalSize - filesize(DataBackup . phpruntime_path() . get_sys_config('backup_path') . self::VERSION_PATH . $this->version . '.zip')) / $this->totalSize * 100, 1)
            : 0;

        $output->writeln(['', '<comment>备份统计信息:</comment>']);

        $table = new \Symfony\Component\Console\Helper\Table($output);
        $table->setHeaders(['统计项', '数值', '单位']);
        $table->setRows([
            ['备份表数量', $this->successBackups, '个'],
            ['总记录数', $this->recordCount, '条'],
            ['总文件大小', $totalSize, 'MB'],
            ['压缩率', $compressionRatio, '%'],
            ['执行耗时', $executionTime, '秒'],
            ['内存使用', $memoryUsage, 'MB']
        ]);
        $table->setStyle('box-double');
        $table->render();
    }


    /**
     * 备份单个表
     */
    private function backupTable(string $table, InputInterface $input, OutputInterface $output, string $baseBackupDir, string $versionTimestamp): void
    {
        try {
            $data              = Rdb::table($table)->select()->toArray();
            $this->recordCount += count($data);

            $output->writeln($this->formatOutput("Backing up table: {$table}", count($data) . " 条", 90));

            // 创建表备份目录
            $tableBackupDir = $baseBackupDir . "table/{$table}/";
            File::mkdir($tableBackupDir);

            // 生成SQL备份文件
            $backupFile = $this->createSqlBackupFile($table, $tableBackupDir, $versionTimestamp, $data);
            $this->successBackups++;

            // 压缩备份文件
            $zipFile = $this->compressBackupFile($backupFile, $tableBackupDir, $versionTimestamp);

            // 记录备份 - 始终为每个表创建一条记录
            $data = [
                'admin_id'   => $this->admin_id,
                'table_name' => $table,
                'file'       => str_replace(runtime_path(), '', $zipFile),
                'version'    => $versionTimestamp
            ];

            Event::emit('data.backup.record', $data);
            $data['admin_id']=$this->admin_id;
            Event::emit('log.data.backup', $data);

            // 更新总大小
            $this->totalSize += filesize($backupFile) + filesize($zipFile);

            // 输出备份完成信息
            $output->writeln($this->formatBackupCompleteOutput($zipFile));
        } catch (\Exception $e) {
            $output->writeln("<error>Backup failed for {$table}: " . $e->getMessage() . "</error>");
            error_log("Backup failed for {$table}: " . $e->getMessage());
        }
    }


}
