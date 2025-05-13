<?php

namespace app\command;

use plugin\radmin\app\admin\model\data\Backup;
use plugin\radmin\app\admin\model\data\Table;
use Symfony\Component\Console\Input\InputOption;
use upport\think\Db;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class DataBackup extends Command
{
    protected static $defaultName = 'data:backup';
    protected static $defaultDescription = 'Backup specified database tables';
    
    const BACKUP_PATH = '';
    const VERSION_PATH = 'version/';
    
    private string $version;
    private float $startTime;
    private int $startMemory;
    private float $totalSize = 0;
    private int $successBackups = 0;
    private int $recordCount = 0;

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
            ->addOption('delete', 'd', InputOption::VALUE_NONE, '压缩后删除原SQL文件')
            ->addOption('incremental', 'i', InputOption::VALUE_NONE, '增量备份(基于最后修改时间)')
            ->addOption('strict', 's', InputOption::VALUE_NONE, '严格模式(额外比对SQL文件内容)')
            ->addUsage('使用方法:')
            ->addUsage('  php webman data:backup --all --delete  # 备份所有表并压缩，然后删除原文件')
            ->addUsage('  php webman data:backup users,posts     # 备份指定表并压缩')
            ->addUsage('  php webman data:backup                 # 备份所有表(默认)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();
        $versionTimestamp = date("YmdHis");

        // 初始化目录
        $baseBackupDir = runtime_path() . get_sys_config('backup_path') . self::BACKUP_PATH;
        $versionDir = $baseBackupDir . self::VERSION_PATH;
        $this->createDirectory($baseBackupDir);
        $this->createDirectory($versionDir);

        // 获取要备份的表
        $tables = $this->getTablesToBackup($input, $output);
        if (empty($tables)) {
            $this->outputStatistics($output);
            return self::SUCCESS;
        }

        // 输出备份开始信息
        $this->outputBackupHeader($output);

        // 备份每个表
        foreach ($tables as $table) {
            usleep(200000); // 避免CPU占用过高
            $this->backupTable($table, $input, $output, $baseBackupDir, $versionTimestamp);
        }

        $output->writeln("<info>All backups completed</info>");

        // 创建版本包
        $this->createVersionPackage($tables, $input, $output, $baseBackupDir, $versionDir, $versionTimestamp);

        // 删除原SQL文件(如果启用)
        if ($input->getOption('delete')) {
            $this->deleteOriginalSqlFiles($tables, $baseBackupDir, $output);
        }

        $this->outputStatistics($output);
        return self::SUCCESS;
    }

    /**
     * 获取需要备份的表列表
     */
    private function getTablesToBackup(InputInterface $input, OutputInterface $output): array
    {
        $tablesArg = $input->getArgument('tables') ?? '';
        $tables = $input->getOption('all') || empty($tablesArg)
            ? array_column(Table::select()->toArray(), 'name')
            : explode(',', $tablesArg);

        // 增量备份处理
        if ($input->getOption('incremental')) {
            $tables = $this->filterIncrementalTables($tables, $output);
        }

        return $tables;
    }

    /**
     * 过滤增量备份的表
     */
    private function filterIncrementalTables(array $tables, OutputInterface $output): array
    {
        $lastBackup = Backup::order('create_time', 'desc')->find();
        if (!$lastBackup) {
            $output->writeln("<comment>增量备份模式: 没有找到上次备份记录，将执行完整备份</comment>");
            return $tables;
        }

        $output->writeln("<info>增量备份模式: 只备份自上次备份后有修改的表</info>");
        $output->writeln("<comment>上次备份时间: " . $lastBackup->create_time . "</comment>");

        // 获取上次备份时各表的记录数
        $lastBackupCounts = [];
        $lastBackupRecords = Backup::where('version', $lastBackup->version)
            ->where('table_name', '<>', 'all')
            ->where('table_name', '<>', 'multi')
            ->select()
            ->toArray();
        
        foreach ($lastBackupRecords as $record) {
            $lastBackupCounts[$record['table_name']] = Db::table($record['table_name'])->count();
        }

        $filteredTables = array_filter($tables, function($table) use ($lastBackup, $output, $lastBackupCounts) {
            $currentCount = Db::table($table)->count();
            $lastBackupCount = $lastBackupCounts[$table] ?? $currentCount;
            
            $needBackup = $this->shouldBackupTable($table, strtotime($lastBackup->create_time), $lastBackupCount);
            
            $output->writeln(sprintf(
                "表 %-20s 记录数: %d->%d 最后修改: %s %s",
                $table,
                $lastBackupCount,
                $currentCount,
                date('Y-m-d H:i:s', $this->getTableStatus($table)['lastModified']),
                $needBackup ? '<info>需要备份</info>' : '<comment>无需备份</comment>'
            ));
            
            return $needBackup;
        });

        if (empty($filteredTables)) {
            $output->writeln("<comment>没有检测到需要备份的表，所有表在上次备份后都没有修改</comment>");
        }

        return $filteredTables;
    }

    /**
     * 输出备份头部信息
     */
    private function outputBackupHeader(OutputInterface $output): void
    {
        $output->writeln("Webman-Radmin Backup Tool By albert <albert@rocareer.com>");
        $output->writeln("Version  V1.0.4 ...");
        $output->writeln("Backup Starting ...");
    }

    /**
     * 创建SQL备份文件
     */
    private function createSqlBackupFile(string $table, string $tableBackupDir, string $versionTimestamp, array $data): string
    {
        $backupFile = $tableBackupDir . "{$versionTimestamp}.sql";
        $createTable = Db::query("SHOW CREATE TABLE `{$table}`")[0]['Create Table'];

        $content = "-- Table structure for {$table}\n";
        $content .= $createTable . ";\n\n";
        $content .= "-- Data for {$table}\n";

        foreach ($data as $row) {
            $values = array_map(function ($value) {
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
        $zip = new ZipArchive();
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
        $zipSize = $this->formatSize(filesize($zipFile));
        $padding = str_pad($zipSize, 81 - mb_strwidth($outputPath, 'UTF-8'), ' ', STR_PAD_LEFT);
        return "Backup completed: {$outputPath} {$padding}";
    }

    /**
     * 创建版本包
     */
    private function createVersionPackage(array $tables, InputInterface $input, OutputInterface $output, 
                                        string $baseBackupDir, string $versionDir, string $versionTimestamp): void
    {
        $timestampZip = $versionDir . $versionTimestamp . '.zip';
        $zip = new ZipArchive();
        $zipCreated = false;
        
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
            $this->recordBackup(
                $input->getOption('all') ? 'all' : (count($tables) > 1 ? 'multi' : $tables[0]),
                str_replace(runtime_path(), '', $timestampZip),
                $versionTimestamp
            );
            
            $outputZip = str_replace(runtime_path(), '', $timestampZip);
            $outputZipSize = filesize($timestampZip);
            $this->totalSize += $outputZipSize;
            
            $output->writeln($this->formatVersionPackageOutput($outputZip, $outputZipSize));
        } else {
            $output->writeln("<error>Failed to create version zip file</error>");
        }
    }

    /**
     * 格式化版本包输出
     */
    private function formatVersionPackageOutput(string $zipPath, int $size): string
    {
        $sizeFormatted = $this->formatSize($size);
        $padding = str_pad($sizeFormatted, 83 - mb_strwidth($zipPath, 'UTF-8'), ' ', STR_PAD_LEFT);
        return "<info>Total Zip Done: {$zipPath}{$padding}</info>";
    }

    /**
     * 输出统计信息
     */
    private function outputStatistics(OutputInterface $output): void
    {
        $executionTime = microtime(true) - $this->startTime;
        $memoryUsage = round((memory_get_peak_usage() - $this->startMemory) / 1024 / 1024, 2);
        $totalSize = round($this->totalSize / 1024 / 1024, 2);

        $output->writeln(['', '<comment>执行统计:</comment>']);
        $table = new \Symfony\Component\Console\Helper\Table($output);
        $table->setStyle('borderless');
        $table
            ->addRow(['备份表数:', $this->successBackups, '个'])
            ->addRow(['数据记录:', $this->recordCount, '条'])
            ->addRow(['执行耗时:', $executionTime, '秒'])
            ->addRow(['内存使用:', $memoryUsage, 'MB'])
            ->addRow(['文件大小:', $totalSize, 'MB']);

        $table->render();
    }

    /**
     * 格式化文件大小
     */
    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = 0;
        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }
        return round($bytes, 2) . ' ' . $units[$index];
    }

    /**
     * 备份单个表
     */
    private function backupTable(string $table, InputInterface $input, OutputInterface $output, string $baseBackupDir, string $versionTimestamp): void
    {
        try {
            $data = Db::table($table)->select()->toArray();
            $this->recordCount += count($data);

            $output->writeln($this->formatOutput("Backing up table: {$table}", count($data) . " 条", 100));

            // 创建表备份目录
            $tableBackupDir = $baseBackupDir . "table/{$table}/";
            $this->createDirectory($tableBackupDir);

            // 生成SQL备份文件
            $backupFile = $this->createSqlBackupFile($table, $tableBackupDir, $versionTimestamp, $data);
            $this->successBackups++;

            // 压缩备份文件
            $zipFile = $this->compressBackupFile($backupFile, $tableBackupDir, $versionTimestamp);

            // 记录备份
            $this->recordBackup(
                $input->getOption('all') ? 'all' : $table,
                str_replace(runtime_path(), '', $zipFile),
                $versionTimestamp
            );

            // 更新总大小
            $this->totalSize += filesize($backupFile) + filesize($zipFile);

            // 输出备份完成信息
            $output->writeln($this->formatBackupCompleteOutput($zipFile));
        } catch (\Exception $e) {
            $output->writeln("<error>Backup failed for {$table}: " . $e->getMessage() . "</error>");
            error_log("Backup failed for {$table}: " . $e->getMessage());
        }
    }

    private function createDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    private function recordBackup(string $tableName, string $filePath, string $versionTimestamp): void
    {
        $backupTable = new Backup();
        $backupTable->save([
            'table_name'  => $tableName,
            'file'        => $filePath,
            'status'      => 1,
            'runnow'      => 1,
            'run_time'    => time(),
            'create_time' => time(),
            'version'     => $versionTimestamp
        ]);
    }

    private function compressFile(string $filePath, string $zipFilePath): void
    {
        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
            $zip->addFile($filePath, basename($filePath));
            $zip->close();
        }
    }

    private function formatOutput(string $message, string $data, int $totalWidth): string
    {
        $messageWidth = mb_strwidth($message, 'UTF-8');
        $dataWidth = mb_strwidth($data, 'UTF-8');
        $padding = $totalWidth - $messageWidth - $dataWidth;
        return $message . str_repeat(' ', $padding) . $data;
    }
    private function deleteOriginalSqlFiles(array $tables, string $baseBackupDir, OutputInterface $output): void
    {
        $deleted = 0;
        foreach ($tables as $table) {
            $tableBackupDir = $baseBackupDir . "table/{$table}/";
            foreach (glob($tableBackupDir . '/*.sql') as $file) {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }
        $output->writeln("<info>Deleted {$deleted} original SQL files</info>");
    }


    private function getTableStatus(string $table): array
    {
        try {
            // 获取表状态和记录数
            $status = Db::query("SHOW TABLE STATUS LIKE '{$table}'")[0] ?? [];
            $recordCount = Db::table($table)->count();
            
            return [
                'lastModified' => isset($status['Update_time']) 
                    ? strtotime($status['Update_time']) 
                    : (isset($status['Create_time']) 
                        ? strtotime($status['Create_time']) 
                        : time()),
                'recordCount' => $recordCount
            ];
        } catch (\Exception $e) {
            return ['lastModified' => time(), 'recordCount' => 0];
        }
    }

    /**
     * 检查表是否需要备份
     * @param string $table 表名
     * @param int $lastBackupTime 上次备份时间
     * @param int $lastBackupCount 上次备份记录数
     * @return bool 是否需要备份
     */
    private function shouldBackupTable(string $table, int $lastBackupTime, int $lastBackupCount, ?InputInterface $input = null): bool
    {
        $currentStatus = $this->getTableStatus($table);
        
        // 如果记录数减少，强制备份
        if ($currentStatus['recordCount'] < $lastBackupCount) {
            return true;
        }
        
        // 检查表是否修改
        $tableModified = $currentStatus['lastModified'] > $lastBackupTime;
        
        // 严格模式下，即使表未修改也检查SQL文件内容
        if ($input && $input->getOption('strict') && !$tableModified) {
            return $this->compareWithLastBackupSql($table, $lastBackupTime);
        }
        
        return $tableModified;
    }

    /**
     * 与上次备份的SQL文件内容比较
     */
    private function compareWithLastBackupSql(string $table, int $lastBackupTime): bool
    {
        try {
            // 获取上次备份的SQL文件路径
            $lastBackup = Backup::where('table_name', $table)
                ->where('create_time', '>=', $lastBackupTime)
                ->order('create_time', 'desc')
                ->find();
            
            if (!$lastBackup) {
                return true;
            }
            
            $lastSqlFile = runtime_path() . $lastBackup->file;
            $lastSqlFile = str_replace('.zip', '.sql', $lastSqlFile);
            
            if (!file_exists($lastSqlFile)) {
                return true;
            }
            
            // 生成当前表结构的SQL
            $currentSql = $this->generateTableSql($table);
            $lastSql = file_get_contents($lastSqlFile);
            
            return $currentSql !== $lastSql;
        } catch (\Exception $e) {
            error_log("SQL compare failed for {$table}: " . $e->getMessage());
            return true; // 出错时保守处理，进行备份
        }
    }

    /**
     * 生成表的SQL内容
     */
    private function generateTableSql(string $table): string
    {
        $createTable = Db::query("SHOW CREATE TABLE `{$table}`")[0]['Create Table'];
        $data = Db::table($table)->select()->toArray();

        $content = "-- Table structure for {$table}\n";
        $content .= $createTable . ";\n\n";
        $content .= "-- Data for {$table}\n";

        foreach ($data as $row) {
            $values = array_map(function ($value) {
                return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
            }, $row);
            $content .= "INSERT INTO `{$table}` VALUES (" . implode(', ', $values) . ");\n";
        }

        return $content;
    }
}
