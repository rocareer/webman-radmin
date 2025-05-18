<?php

namespace Radmin\command;

use Radmin\Command;
use Radmin\Event;
use Radmin\orm\Rdb;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class DataMigrate extends Command
{
    protected static $defaultName        = 'data:migrate';
    protected static $defaultDescription = 'Database migration tool';

    const MIGRATIONS_PATH  = 'migrations/';
    const MIGRATIONS_TABLE = 'migrations';
    protected      $admin_id = 0;
    protected string $migrate_path;

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName(self::$defaultName)
            ->setDescription('数据库迁移工具')
            ->addArgument('action', InputArgument::REQUIRED, '迁移操作 (create|up|down|status)')
            ->addOption('name', 'n', InputOption::VALUE_OPTIONAL, '迁移名称 (用于create操作)')
            ->addOption('table', 't', InputOption::VALUE_OPTIONAL, '数据表名 (用于create操作 all|table_name)')
            ->addOption('all', 'a', InputOption::VALUE_NONE, '为所有表创建迁移文件')
            ->addOption('prefix', 'p', InputOption::VALUE_OPTIONAL, '表前缀 (默认为配置中的前缀)')
            ->addOption('steps', 's', InputOption::VALUE_OPTIONAL, '迁移步数 (用于up/down操作)', 1)
            ->addOption('admin', 'admin', InputOption::VALUE_OPTIONAL, '管理员ID')
            ->addOption('force', 'f', InputOption::VALUE_NONE, '强制执行（不提示确认）')
            ->addUsage('使用方法:')
            ->addUsage('  php webman data:migrate create -n create_users_table -a 1  # 创建迁移文件')
            ->addUsage('  php webman data:migrate up -s 1 -a 1                       # 执行1个未应用的迁移')
            ->addUsage('  php webman data:migrate down -s 1 -a 1                     # 回滚1个已应用的迁移')
            ->addUsage('  php webman data:migrate status -a 1                        # 查看迁移状态')
            ->addUsage('')
            ->addUsage('参数说明:')
            ->addUsage('  action: 必需参数，指定操作类型 (create|up|down|status)')
            ->addUsage('  name: 可选参数，指定迁移名称 (create操作必需)')
            ->addUsage('  steps: 可选参数，指定迁移步数 (默认为1)')
            ->addUsage('  admin: 可选参数，指定执行迁移的管理员ID')
            ->addUsage('  force: 可选参数，跳过确认提示直接执行');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->input  = $input;
        $this->migrate_path=base_path().'/plugin/radmin/database/migrations/';
        // 初始化
        $this->initializeStats();
        // 执行操作
        try {

            $action = $input->getArgument('action');
            switch ($action) {
                case 'create':
                    return $this->handleCreate($input, $output);
                case 'up':
                    return $this->handleUp($input, $output);
                case 'down':
                    return $this->handleDown($input, $output);
                case 'status':
                    return $this->handleStatus($input, $output);
                default:
                    throw new \InvalidArgumentException("未知的操作类型: {$action}");
            }
        } catch (\Exception $e) {
            $output->writeln("<error>迁移失败: " . $e->getMessage() . "</error>");
            return self::FAILURE;
        }
    }

    public function initializeStats(): void
    {
        // 1. 初始化基本参数
        parent::initializeStats();

        $this->admin_id = 0;
        // 2. 初始化配置
        $this->ensureMigrationsDirectoryExists();

        // 3. 初始化数据库连接
        try {
            // 测试数据库连接是否正常
            Rdb::query("SELECT 1");
        } catch (\Exception $e) {
            throw new \RuntimeException("数据库连接失败: " . $e->getMessage());
        }

        // 4. 确保迁移表存在
        $this->ensureMigrationsTableExists();

        // 5. 初始化日志记录
        Event::on('data.migrate.*', function ($event, $data) {
            // 可以在这里添加统一的日志处理逻辑
            error_log("Migration Event: {$event} - " . json_encode($data));
        });
    }

    /**
     * 处理创建迁移文件操作
     */
    private function handleCreate(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getOption('name');
        if (empty($name)) {
            $helper   = $this->getHelper('question');
            $question = new Question('请输入迁移名称 (例如: create_users_table): ');
            $name     = $helper->ask($input, $output, $question);

            if (empty($name)) {
                throw new \InvalidArgumentException("迁移名称不能为空");
            }
        }

        // 格式化名称，确保符合命名规范
        $name = $this->formatMigrationName($name);

        // 创建迁移文件
        $timestamp = date('YmdHis');
        $className = $this->getMigrationClassName($name);
        $filename  = $timestamp . '_' . $name . '.php';
        $filePath  = $this->migrate_path . $filename;

        // 生成迁移文件内容
        $content = $this->generateMigrationContent($className);

        // 写入文件
        if (!file_put_contents($filePath, $content)) {
            throw new \Exception("无法创建迁移文件: {$filePath}");
        }
        $output->writeln([
            "",
            "<bg=green;fg=white> 迁移文件创建成功 </>\n",
            "<info>文件路径:</info> {$filePath}",
            "<info>类名:</info> {$className}",
            "",
            "<comment>请编辑此文件，在up()方法中添加迁移逻辑，在down()方法中添加回滚逻辑。</comment>",
            ""
        ]);

        // 记录日志
        $data = [
            'admin_id'  => $this->admin_id,
            'action'    => 'create',
            'migration' => $filename,
        ];
        Event::emit('data.migrate.create', $data);
        Event::emit('log.data.migrate', $data);

        return self::SUCCESS;
    }

    /**
     * 处理执行迁移操作
     */
    private function handleUp(InputInterface $input, OutputInterface $output): int
    {
        $steps = (int)$input->getOption('steps');
        if ($steps <= 0) {
            throw new \InvalidArgumentException("迁移步数必须大于0");
        }

        // 获取未执行的迁移
        $pendingMigrations = $this->getPendingMigrations();

        if (empty($pendingMigrations)) {
            $output->writeln("<comment>没有待执行的迁移</comment>");
            return self::SUCCESS;
        }

        // 限制执行数量
        $migrationsToRun = array_slice($pendingMigrations, 0, $steps);

        $output->writeln([
            "",
            "<bg=blue;fg=white> 准备执行以下迁移 </>\n"
        ]);

        foreach ($migrationsToRun as $migration) {
            $output->writeln("- {$migration}");
        }
        $output->writeln("");

        // 确认操作
        if (!$input->getOption('force') && !$this->confirmAction($input, $output, "确定要执行这些迁移吗？")) {
            $output->writeln("<comment>迁移操作已取消</comment>");
            return self::SUCCESS;
        }

        $output->writeln("<bg=blue;fg=white> 开始执行迁移 </>\n");

        $executedCount = 0;
        foreach ($migrationsToRun as $migration) {
            try {
                $output->write("<info>执行迁移 {$migration} ... </info>");

                // 加载迁移类
                $className = $this->loadMigrationClass($migration);
                $instance  = new $className();

                // 执行迁移
                Rdb::startTrans();
                try {
                    $instance->up();

                    // 记录迁移
                    $this->recordMigration($migration);

                    Rdb::commit();
                    $executedCount++;
                    $output->writeln("<info>成功</info>");
                } catch (\Exception $e) {
                    Rdb::rollback();
                    throw $e;
                }
            } catch (\Exception $e) {
                $output->writeln("<error>失败: " . $e->getMessage() . "</error>");
                return self::FAILURE;
            }
        }

        $output->writeln([
            "",
            "<bg=green;fg=white> 迁移完成 </>\n",
            "<info>成功执行 {$executedCount} 个迁移</info>",
            ""
        ]);

        // 记录日志
        $data = [
            'admin_id' => $this->admin_id,
            'action'   => 'up',
            'count'    => $executedCount,
        ];
        Event::emit('data.migrate.up', $data);
        Event::emit('log.data.migrate', $data);

        return self::SUCCESS;
    }

    /**
     * 处理回滚迁移操作
     */
    private function handleDown(InputInterface $input, OutputInterface $output): int
    {
        $steps = (int)$input->getOption('steps');
        if ($steps <= 0) {
            throw new \InvalidArgumentException("回滚步数必须大于0");
        }

        // 获取已执行的迁移
        $appliedMigrations = $this->getAppliedMigrations();

        if (empty($appliedMigrations)) {
            $output->writeln("<comment>没有可回滚的迁移</comment>");
            return self::SUCCESS;
        }

        // 限制回滚数量
        $migrationsToRollback = array_slice($appliedMigrations, 0, $steps);

        $output->writeln([
            "",
            "<bg=yellow;fg=black> 准备回滚以下迁移 </>\n"
        ]);

        foreach ($migrationsToRollback as $migration) {
            $output->writeln("- {$migration}");
        }
        $output->writeln("");

        // 确认操作
        if (!$input->getOption('force') && !$this->confirmAction($input, $output, "确定要回滚这些迁移吗？这可能会导致数据丢失！")) {
            $output->writeln("<comment>回滚操作已取消</comment>");
            return self::SUCCESS;
        }

        $output->writeln("<bg=yellow;fg=black> 开始回滚迁移 </>\n");

        $rolledBackCount = 0;
        foreach ($migrationsToRollback as $migration) {
            try {
                $output->write("<info>回滚迁移 {$migration} ... </info>");

                // 加载迁移类
                $className = $this->loadMigrationClass($migration);
                $instance  = new $className();

                // 执行回滚
                Rdb::startTrans();
                try {
                    $instance->down();

                    // 删除迁移记录
                    $this->removeMigration($migration);

                    Rdb::commit();
                    $rolledBackCount++;
                    $output->writeln("<info>成功</info>");
                } catch (\Exception $e) {
                    Rdb::rollback();
                    throw $e;
                }
            } catch (\Exception $e) {
                $output->writeln("<error>失败: " . $e->getMessage() . "</error>");
                return self::FAILURE;
            }
        }

        $output->writeln([
            "",
            "<bg=green;fg=white> 回滚完成 </>\n",
            "<info>成功回滚 {$rolledBackCount} 个迁移</info>",
            ""
        ]);

        // 记录日志
        $data = [
            'admin_id' => $this->admin_id,
            'action'   => 'down',
            'count'    => $rolledBackCount,
        ];
        Event::emit('data.migrate.down', $data);
        Event::emit('log.data.migrate', $data);

        return self::SUCCESS;
    }

    /**
     * 处理查看迁移状态操作
     */
    private function handleStatus(InputInterface $input, OutputInterface $output): int
    {
        $migrations        = $this->getAllMigrations();
        $appliedMigrations = $this->getAppliedMigrations();

        $output->writeln([
            "",
            "<bg=blue;fg=white> 迁移状态 </>\n"
        ]);

        if (empty($migrations)) {
            $output->writeln("<comment>没有找到任何迁移文件</comment>");
            return self::SUCCESS;
        }

        $table = new Table($output);
        $table->setHeaders(['迁移文件', '状态', '执行时间']);

        foreach ($migrations as $migration) {
            $status     = in_array($migration, $appliedMigrations) ? '已执行' : '未执行';
            $executedAt = in_array($migration, $appliedMigrations)
                ? $this->getMigrationExecutedTime($migration)
                : '-';

            $table->addRow([
                $migration,
                $status === '已执行' ? "<info>{$status}</info>" : "<comment>{$status}</comment>",
                $executedAt
            ]);
        }

        $table->render();
        $output->writeln("");

        return self::SUCCESS;
    }

    /**
     * 确保迁移目录存在
     */
    private function ensureMigrationsDirectoryExists(): void
    {
        $migrationsPath = $this->migrate_path;
        if (!is_dir($migrationsPath)) {
            if (!mkdir($migrationsPath, 0755, true)) {
                throw new \Exception("无法创建迁移目录: {$migrationsPath}");
            }
        }
    }

    /**
     * 确保迁移表存在
     */
    private function ensureMigrationsTableExists(): void
    {
        $tableName     = self::MIGRATIONS_TABLE;
        $prefix        = config('plugin.radmin.think-orm.connections.mysql.prefix');
        $fullTableName = $prefix . $tableName;

        // todo 没有fetch 方法
        // if (!Rdb::query("SHOW TABLES LIKE '{$fullTableName}'")->fetch()) {
        //     $sql = "CREATE TABLE `{$fullTableName}` (
        //         `id` int(11) NOT NULL AUTO_INCREMENT,
        //         `migration` varchar(255) NOT NULL,
        //         `batch` int(11) NOT NULL,
        //         `executed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        //         `admin_id` int(11) DEFAULT NULL,
        //         PRIMARY KEY (`id`),
        //         UNIQUE KEY `migration` (`migration`)
        //     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        //
        //     Rdb::execute($sql);
        // }
    }


    /**
     * 格式化迁移名称
     */
    private function formatMigrationName(string $name): string
    {
        return preg_replace('/[^a-z0-9_]+/', '_', strtolower($name));
    }

    /**
     * 生成迁移类名
     */
    private function getMigrationClassName(string $name): string
    {
        $parts     = explode('_', $name);
        $className = '';
        foreach ($parts as $part) {
            $className .= ucfirst($part);
        }
        return $className;
    }

    /**
     * 生成迁移文件内容
     */
    private function generateMigrationContent(string $className, ?string $table = null): string
    {
        // 读取模板文件
        $templatePath = base_path() . '/plugin/radmin/database/migrations/template.php';
        if (!file_exists($templatePath)) {
            throw new \Exception("迁移模板文件不存在: {$templatePath}");
        }
        
        $content = file_get_contents($templatePath);
        
        // 替换类名
        $content = str_replace('class YourMigrationName', "class {$className}", $content);
        
        // 如果指定了表名，生成更智能的表结构
        if ($table) {
            $tableName = str_replace(config('plugin.radmin.think-orm.connections.mysql.prefix'), '', $table);
            
            // 检查表是否已存在
            $tableExists = Rdb::query("SHOW TABLES LIKE '{$table}'")->fetch();
            
            if ($tableExists) {
                // 获取现有表结构
                $createTableSql = Rdb::query("SHOW CREATE TABLE `{$table}`")->fetch()['Create TableUtil'] ?? '';
                
                // 生成修改表结构的迁移代码
                $tableCode = <<<PHP
        // 修改表 {$tableName} 结构
        if (\$this->tableExists('{$table}')) {
            // 获取当前表结构
            \$currentStructure = Rdb::query("SHOW CREATE TABLE `{$table}`")->fetch()['Create TableUtil'] ?? '';
            
            // 这里添加需要修改的表结构逻辑
            // 示例：添加新字段
            /*
            if (!\$this->columnExists('{$table}', 'new_column')) {
                Rdb::execute("
                    ALTER TABLE `{$table}` 
                    ADD COLUMN `new_column` varchar(255) NULL COMMENT '新字段' AFTER `existing_column`,
                    ADD INDEX `idx_new_column` (`new_column`);
                ");
            }
            */
        } else {
            // 表不存在时创建
            Rdb::execute("
                {$createTableSql}
            ");
        }
PHP;
            } else {
                // 生成创建新表的代码
                $tableCode = <<<PHP
        // 创建表 {$tableName}
        Rdb::execute("
            CREATE TABLE IF NOT EXISTS `{$table}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
                `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='{$tableName}表';
        ");
PHP;
            }
            
            $content = str_replace('// todo 插入数据', $tableCode, $content);
            
            // 添加辅助方法
            $helperMethods = <<<PHP
    
    /**
     * 检查表是否存在
     */
    private function tableExists(string \$table): bool
    {
        return (bool)Rdb::query("SHOW TABLES LIKE ?", [\$table])->fetch();
    }
    
    /**
     * 检查字段是否存在
     */
    private function columnExists(string \$table, string \$column): bool
    {
        return (bool)Rdb::query("SHOW COLUMNS FROM `{\$table}` LIKE ?", [\$column])->fetch();
    }
    
    /**
     * 检查索引是否存在
     */
    private function indexExists(string \$table, string \$index): bool
    {
        return (bool)Rdb::query("SHOW INDEX FROM `{\$table}` WHERE Key_name = ?", [\$index])->fetch();
    }
PHP;
            $content = str_replace('// todo 辅助方法', $helperMethods, $content);
        }
        
        return $content;
    }

    /**
     * 获取所有迁移文件
     */
    private function getAllMigrations(): array
    {
        $files      = glob($this->getMigrationsPath() . '*.php');
        $migrations = [];

        foreach ($files as $file) {
            $migrations[] = basename($file);
        }

        sort($migrations);
        return $migrations;
    }

    /**
     * 获取已应用的迁移
     */
    private function getAppliedMigrations(): array
    {
        $prefix    = config('plugin.radmin.think-orm.connections.mysql.prefix');
        $tableName = $prefix . self::MIGRATIONS_TABLE;

        $result = Rdb::query("SELECT migration FROM `{$tableName}` ORDER BY id ASC")->fetchAll();

        $migrations = [];
        foreach ($result as $row) {
            $migrations[] = $row['migration'];
        }

        return $migrations;
    }

    /**
     * 获取未执行的迁移
     */
    private function getPendingMigrations(): array
    {
        $allMigrations     = $this->getAllMigrations();
        $appliedMigrations = $this->getAppliedMigrations();

        return array_diff($allMigrations, $appliedMigrations);
    }

    /**
     * 记录迁移
     */
    private function recordMigration(string $migration): void
    {
        $prefix    = config('plugin.radmin.think-orm.connections.mysql.prefix');
        $tableName = $prefix . self::MIGRATIONS_TABLE;

        // 获取当前最大批次号
        $batch = Rdb::query("SELECT MAX(batch) as max_batch FROM `{$tableName}`")->fetch();
        $batch = $batch['max_batch'] ?? 0;
        $batch++;

        Rdb::execute(
            "INSERT INTO `{$tableName}` (migration, batch, admin_id) VALUES (?, ?, ?)",
            [$migration, $batch, $this->admin_id]
        );
    }

    /**
     * 删除迁移记录
     */
    private function removeMigration(string $migration): void
    {
        $prefix    = config('plugin.radmin.think-orm.connections.mysql.prefix');
        $tableName = $prefix . self::MIGRATIONS_TABLE;

        Rdb::execute("DELETE FROM `{$tableName}` WHERE migration = ?", [$migration]);
    }

    /**
     * 获取迁移执行时间
     */
    private function getMigrationExecutedTime(string $migration): string
    {
        $prefix    = config('plugin.radmin.think-orm.connections.mysql.prefix');
        $tableName = $prefix . self::MIGRATIONS_TABLE;

        $result = Rdb::query(
            "SELECT executed_at FROM `{$tableName}` WHERE migration = ?",
            [$migration]
        )->fetch();

        return $result ? $result['executed_at'] : '-';
    }

    /**
     * 加载迁移类
     */
    private function loadMigrationClass(string $migration): string
    {
        $filePath = $this->getMigrationsPath() . $migration;
        if (!file_exists($filePath)) {
            throw new \Exception("迁移文件不存在: {$migration}");
        }

        require_once $filePath;

        $className = $this->getMigrationClassName(
            substr(basename($migration, '.php'), 18) // 去掉时间戳部分
        );

        if (!class_exists($className)) {
            throw new \Exception("迁移类 {$className} 不存在");
        }

        return $className;
    }

    /**
     * 确认操作
     */
    private function confirmAction(InputInterface $input, OutputInterface $output, string $question): bool
    {
        $helper   = $this->getHelper('question');
        $question = new \Symfony\Component\Console\Question\ConfirmationQuestion(
            $question . ' (y/N) ',
            false
        );

        return $helper->ask($input, $output, $question);
    }
}
