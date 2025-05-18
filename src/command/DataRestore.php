<?php

namespace Radmin\command;

use plugin\radmin\app\admin\model\data\Backup;
use Radmin\Event;
use Radmin\Log;
use Radmin\orm\Model;
use Radmin\orm\Rdb;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class DataRestore extends Command
{
    protected static $defaultName        = 'data:restore';
    protected static $defaultDescription = 'Restore database from backup';

    private float $startTime;
    private int   $startMemory;
    private int   $restoredTables  = 0;
    private int   $restoredRecords = 0;
    private float $peakMemory      = 0;
    private int   $admin_id        = 0;

    protected function configure()
    {
        $this->setName(self::$defaultName)
            ->setDescription('数据库还原工具')
            ->addArgument('table', InputArgument::OPTIONAL, '要还原的表 (all|multi|table_name)', 'all')
            ->addOption('ver', 'b', InputOption::VALUE_OPTIONAL, '通过版本号还原 (默认使用最新版本)')
            ->addOption('admin', 'a', InputOption::VALUE_OPTIONAL, '管理员')
            ->addOption('force', 'f', InputOption::VALUE_NONE, '强制还原（不提示确认）')
            ->addOption('force-all', 'fff', InputOption::VALUE_NONE, '强制还原所有表（不检查表是否存在）')
            ->addUsage('使用方法:')
            ->addUsage('  php webman data:restore -b 20250516000000  # 还原指定版本的所有表')
            ->addUsage('  php webman data:restore users  # 还原users表的最新备份')
            ->addUsage('  php webman data:restore users,posts -b 20250516000000  # 还原指定版本的多个表')
            ->addUsage('  php webman data:restore -f users  # 强制还原users表的最新备份')
            ->addUsage('')
            ->addUsage('参数说明:')
            ->addUsage('  table: 可选参数，默认还原所有表')
            ->addUsage('  ver: 可选参数，不指定则使用最新备份版本')
            ->addUsage('  force: 跳过确认提示，直接执行还原');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->startTime   = microtime(true);
        $this->startMemory = memory_get_usage();


        if ($input->getOption('admin')){
            $this->admin_id=$input->getOption('admin');
        }

        try {
            // 获取备份记录
            $backup = $this->getBackupRecord($input);
            if (!$backup) {
                $output->writeln("<error>找不到指定的备份记录</error>");
                return self::FAILURE;
            }

            // 输出还原信息并确认
            $this->outputRestoreHeader($output, $backup);
            if (!$input->getOption('force') && !$this->confirmRestore($input, $output)) {
                $output->writeln("<comment>还原操作已取消</comment>");
                return self::SUCCESS;
            }

            // 执行还原并输出统计
            usleep(2000000);
            $this->restoreFromBackup($backup, $input, $output);
            $this->outputStatistics($output);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>还原失败: " . $e->getMessage() . "</error>");
            return self::FAILURE;
        }
    }

    /**
     * 获取备份记录
     */
    private function getBackupRecord(InputInterface $input): ?Model
    {
        $tables  = $input->getArgument('table');
        $version = $input->getOption('ver');

        // 构建查询
        $query = Backup::where(true);

        // 如果没有指定版本，获取最新备份
        if (!$version) {
            $query->order('create_time', 'desc');
        } else {
            $query->where('version', $version);
        }

        // 处理表名
        if ($tables == 'all' || $tables == 'multi') {
            $query->where('table_name', 'all');
        } else {
            $tableNames = explode(',', $tables);

            // 检查表是否存在并过滤掉data_backup表
            $filteredTables = [];
            foreach ($tableNames as $tableName) {
                Log::debug($tableName);
                if ($tableName === getDbPrefix() . 'data_backup') {
                    continue; // 跳过data_backup表
                }
                if ($tableName === getDbPrefix() . 'log_data_backup') {
                    continue; // 跳过log_data_backup表
                }
                if ($tableName === getDbPrefix() . 'log_data_restore') {
                    continue; // 跳过log_data_restore表
                }
                if (!$this->tableExists($tableName, $input->getOption('force-all'))) {
                    throw new \Exception("表 {$tableName} 不存在");
                }
                $filteredTables[] = $tableName;
            }

            if (empty($filteredTables)) {
                throw new \Exception("没有有效的表可以恢复");
            }

            $query->where('table_name', 'in', $filteredTables);
        }

        $backup = $query->find();

        if (!$backup) {
            $msg = $version
                ? "找不到表 {$tables} 版本 {$version} 的备份记录"
                : "找不到表 {$tables} 的备份记录";
            throw new \Exception($msg);
        }

        // 验证备份记录是否匹配请求的表
        if ($tables !== 'all' && $tables !== 'multi') {
            $backupTables  = explode(',', $backup->table_name);
            $requestTables = explode(',', $tables);

            if (array_diff($requestTables, $backupTables)) {
                throw new \Exception(
                    sprintf(
                        "备份记录中的表 (%s) 与请求的表 (%s) 不匹配",
                        $backup->table_name,
                        $tables
                    )
                );
            }
        }

        return $backup;
    }

    /**
     * 检查表是否存在
     */
    private function tableExists(string $tableName, bool $forceAll = false): bool
    {
        // 如果启用了force-all参数，直接返回true
        if ($forceAll) {
            return true;
        }
        
        try {
            return (bool)Rdb::table('information_schema.tables')
                ->where('table_schema', config('plugin.radmin.think-orm.connections.mysql.database'))
                ->where('table_name', $tableName)
                ->find();
        } catch (\Exception $e) {
            return true;
        }
    }

    /**
     * 输出还原头部信息
     */
    private function outputRestoreHeader(OutputInterface $output, Backup $backup): void
    {
        $output->writeln([
            "\n<bg=blue;fg=white> Webman-Radmin 数据还原工具 </>\n",
            "<comment>作者:</comment> albert <albert@rocareer.com>",
            "<comment>版本:</comment> V1.0.4",
            "",
            "<bg=cyan;fg=black> 备份信息 </>\n",
            "<info>版本号:</info>    " . $backup->version,
            "<info>备份文件:</info>  " . $backup->file . " (" .
            (file_exists(DataRestore . phpruntime_path() . $backup->file)
                ? $this->format_bytes(filesize(DataRestore . phpruntime_path() . $backup->file))
                : '文件不存在') . ")",
            "<info>备份时间:</info>  " . $backup->create_time,
            ""
        ]);
        usleep(1000000); // 减少等待时间
    }

    /**
     * 确认还原操作
     */
    private function confirmRestore(InputInterface $input, OutputInterface $output): bool
    {
        $helper   = $this->getHelper('question');
        $question = new \Symfony\Component\Console\Question\ConfirmationQuestion(
            '确定要执行还原操作吗？这将覆盖现有数据。 (y/N) ',
            false
        );

        return $helper->ask($input, $output, $question);
    }

    /**
     * 从备份还原数据
     */
    private function restoreFromBackup(Backup $backup, InputInterface $input, OutputInterface $output): void
    {
        $backupFile = DataRestore . phpruntime_path() . $backup->file;
        if (!file_exists($backupFile)) {
            throw new \Exception("备份文件不存在: " . $backupFile);
        }

        $tempDir = runtime_path() . '/temp/restore_' . time() . '/';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        try {
            // 解压备份文件
            $zip = new ZipArchive();
            if ($zip->open($backupFile) !== true) {
                throw new \Exception("无法打开备份文件: " . $backupFile);
            }

            $output->writeln("<info>  正在解压备份文件......</info>");

            // 如果是版本包，先解压到临时目录
            if ($backup->table_name === 'all') {
                $zip->extractTo($tempDir);
                $zip->close();

                // 获取要还原的表
                $tables = $input->getArgument('table');
                if ($tables === 'all' || $tables === 'multi') {
                    // 检查备份文件完整性
                    $backupFiles = glob($tempDir . '*.zip');
                    if (empty($backupFiles)) {
                        throw new \Exception("备份文件中未找到任何表数据");
                    }

                    // 获取所有表名并验证
                    $tables = $this->getTablesFromDirectory($tempDir);
                    foreach ($tables as $table) {
                        $tableZip = $tempDir . $table . '.zip';
                        if (!file_exists($tableZip)) {
                            throw new \Exception("表 {$table} 的备份文件缺失");
                        }

                        // 跳过系统表
                        if ($table === getDbPrefix() . 'data_backup' || 
                            $table === getDbPrefix() . 'log_data_backup' || 
                            $table === getDbPrefix() . 'log_data_restore') {
                            $output->writeln("<comment>跳过系统表: {$table}</comment>");
                            $tables = array_diff($tables, [$table]);
                            continue;
                        }

                        if (!$this->tableExists($table, $input->getOption('force-all'))) {
                            $output->writeln("<comment>警告: 表 {$table} 不存在，将跳过还原</comment>");
                            $tables = array_diff($tables, [$table]);
                        }
                    }

                    if (empty($tables)) {
                        throw new \Exception("没有找到任何有效的表可以还原");
                    }

                    $output->writeln(sprintf("找到 %d 个有效的表备份文件", count($tables)));
                    usleep(2000000);
                } else {
                    $tables = explode(',', $tables);
                    // 验证指定的表是否都存在
                    foreach ($tables as $key => $table) {
                        if (!$this->tableExists($table, $input->getOption('force-all'))) {
                            $output->writeln("<error>错误: 指定的表 {$table} 不存在</error>");
                            unset($tables[$key]);
                        }
                    }

                    if (empty($tables)) {
                        throw new \Exception("没有找到任何有效的表");
                    }
                    usleep(2000000);
                    $output->writeln(sprintf("准备还原指定的 %d 个表", count($tables)));
                }

                // 还原每个表
                $output->writeln("\n<bg=blue;fg=white> 开始还原数据 </>\n");
                usleep(500000); // 0.5秒延时

                // 初始化进度条
                $progress = new ProgressBar($output, count($tables));
                $progress->setFormat(
                    "
 %current%/%max% [%bar%] %percent:3s%%
 耗时: %elapsed:6s% 预计: %estimated:-6s% 内存: %memory:6s%
 状态: %message%
"
                );
                $progress->setBarWidth(50);
                $progress->setRedrawFrequency(1);
                $progress->start();

                foreach ($tables as $table) {
                    $progress->setMessage("<comment>准备还原表 {$table}...</comment>");
                    usleep(200000); // 0.2秒延时

                    $tableZipFile = $tempDir . $table . '.zip';
                    if (file_exists($tableZipFile)) {
                        try {
                            try {
                                $recordCount = $this->restoreTable($table, $tableZipFile, $output);
                                $fileSize    = file_exists($tableZipFile) ? $this->format_bytes(filesize($tableZipFile)) : '未知';
                                $progress->setMessage(
                                    sprintf(
                                        "<info>✓ 表 %s 还原成功\n  记录数: %d\n  备份大小: %s\n  备份时间: %s\n  当前时间: %s</info>",
                                        $table,
                                        $recordCount,
                                        $fileSize,
                                        date('Y-m-d H:i:s', filemtime($tableZipFile)),
                                        date('Y-m-d H:i:s')
                                    )
                                );

                                // 记录日志
                                $data['admin_id'] = $this->admin_id;
                                $data['table_name'] =$table;
                                $data['version'] =$backup->version;
                                Event::emit('log.data.restore', $data);

                            } catch (\Exception $e) {
                                $progress->setMessage("<error>✗ 表 {$table} 还原失败: " . $e->getMessage() . "</error>");
                                throw $e;
                            }
                        } catch (\Exception $e) {
                            $progress->setMessage("<error>✗ 表 {$table} 还原失败: " . $e->getMessage() . "</error>");
                        }
                    } else {
                        $progress->setMessage("<comment>! 找不到表 {$table} 的备份文件</comment>");
                    }

                    $progress->advance();
                    usleep(300000); // 0.3秒延时

                    // 优化清除输出
                    $progress->clear();
                    $progress->display();
                }

                $progress->finish();
                $output->writeln("\n");
                $output->writeln("<bg=green;fg=white> 数据还原完成 " . date('Y-m-d H:i:s') . " </>");
                $output->writeln("");
                usleep(500000); // 0.5秒延时
            } else {
                // 单表还原
                $output->writeln(sprintf("准备还原表 %s", $backup->table_name));
                usleep(1000000);
                $recordCount = $this->restoreTable($backup->table_name, $backupFile, $output);
                $output->writeln(
                    sprintf(
                        "<info>表 %s 还原完成，共还原 %d 条记录</info>",
                        $backup->table_name,
                        $recordCount
                    )
                );
            }

        } catch (\Exception $e) {
            throw new \Exception("还原过程中出错: " . $e->getMessage());
        } finally {
            // 清理临时文件
            $this->cleanupTempFiles($tempDir);
        }
    }

    /**
     * 从目录中获取所有表名
     */
    private function getTablesFromDirectory(string $dir): array
    {
        $tables = [];
        foreach (glob($dir . '*.zip') as $file) {
            $tables[] = pathinfo($file, PATHINFO_FILENAME);
        }
        return $tables;
    }

    /**
     * 还原单个表
     */
    private function restoreTable(string $table, string $zipFile, OutputInterface $output): int
    {
        $tempDir = dirname($zipFile) . 'DataRestore.php/' . $table . '_temp/';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        try {
            // 解压SQL文件
            $zip = new ZipArchive();
            if ($zip->open($zipFile) !== true) {
                throw new \Exception("无法打开备份文件");
            }

            $zip->extractTo($tempDir);
            $zip->close();
            $this->updateMemoryUsage();

            // 获取SQL文件
            $sqlFiles = glob($tempDir . '*.sql');
            if (empty($sqlFiles)) {
                throw new \Exception("在备份文件中找不到SQL文件");
            }

            $sqlFile = $sqlFiles[0];

            // 使用生成器分批读取SQL文件，避免内存溢出
            $queries        = $this->readSqlFile($sqlFile);
            $totalQueries   = 0;
            $successQueries = 0;

            // 开始事务
            Rdb::startTrans();
            try {
                // 先删除已存在的表
                Rdb::execute("DROP TABLE IF EXISTS `{$table}`");

                foreach ($queries as $query) {
                    $totalQueries++;
                    if (!empty(trim($query))) {
                        // 执行SQL语句
                        Rdb::execute($query);
                        $successQueries++;
                        $this->restoredRecords++;
                        $this->updateMemoryUsage();
                    }
                }

                Rdb::commit();
                $this->restoredTables++;
                return $this->restoredRecords;
            } catch (\Exception $e) {
                Rdb::rollback();
                throw new \Exception("执行SQL失败: " . $e->getMessage(), 0, $e, $this->restoredRecords);
            }
        } catch (\Exception $e) {
            throw new \Exception("还原表 {$table} 失败: " . $e->getMessage(), 0, $e, $this->restoredRecords);
        } finally {
            // 清理临时文件
            $this->cleanupTempFiles($tempDir);
        }
    }

    /**
     * 使用生成器分批读取SQL文件
     */
    private function readSqlFile(string $file): \Generator
    {
        $handle = fopen($file, 'r');
        if ($handle === false) {
            throw new \Exception("无法打开SQL文件");
        }

        $query = '';
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            // 跳过注释和空行
            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '#') === 0) {
                continue;
            }

            $query .= ' ' . $line;

            // 如果是语句结束，yield当前查询并重置
            if (substr(rtrim($query), -1) === ';') {
                yield trim($query);
                $query = '';
            }
        }

        // 如果最后一个查询没有分号，也要yield
        if (!empty(trim($query))) {
            yield trim($query);
        }

        fclose($handle);
    }

    /**
     * 清理临时文件
     */
    private function cleanupTempFiles(string $dir): void
    {
        if (is_dir($dir)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
        }
    }

    /**
     * 输出统计信息
     */
    private function format_bytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' DataRestore.php' . $units[$pow];
    }

    private function updateMemoryUsage(): void
    {
        $currentMemory = memory_get_usage(true) / 1024 / 1024;
        if ($currentMemory > $this->peakMemory) {
            $this->peakMemory = $currentMemory;
        }
    }

    private function outputStatistics(OutputInterface $output): void
    {
        $executionTime = microtime(true) - $this->startTime;
        $speed         = $executionTime > 0 ? round($this->restoredRecords / $executionTime) : 0;
        $this->updateMemoryUsage();

        $output->writeln(['', '<bg=blue;fg=white> 执行统计 ' . date('Y-m-d H:i:s') . ' </>']);
        usleep(300000); // 0.3秒延时

        $table = new \Symfony\Component\Console\Helper\Table($output);
        $table->setStyle('box')
            ->setHeaderTitle('<info>还原结果统计</info>')
            ->setHeaders([
                '<comment>统计项</comment>',
                '<comment>数值</comment>'
            ])
            ->setRows([
                ['<fg=green>还原表数</>', sprintf("<fg=white>%d 个</>", $this->restoredTables)],
                ['<fg=green>还原记录</>', sprintf("<fg=white>%d 条</>", $this->restoredRecords)],
                ['<fg=yellow>执行耗时</>', sprintf("<fg=white>%.2f 秒</>", $executionTime)],
                ['<fg=cyan>平均速度</>', sprintf("<fg=white>%d 条/秒</>", $speed)],
                ['<fg=magenta>内存峰值</>', sprintf("<fg=white>%.2f MiB</>", $this->peakMemory)],
                ['<fg=blue>开始时间</>', date('Y-m-d H:i:s', (int)$this->startTime)],
                ['<fg=blue>完成时间</>', date('Y-m-d H:i:s')],
            ]);

        $table->render();
        $output->writeln('');
        usleep(500000); // 0.5秒延时
    }
}
