<?php

namespace Radmin\util;

use Radmin\orm\Rdb;
use ZipArchive;


/**
 * 数据库表操作工具类
 * 
 * 提供数据库表的创建、修改、查询等操作，支持多数据库连接
 */
class TableUtil
{
    // 支持的数据库引擎
    public const VALID_ENGINES = [
        'InnoDB' => '支持事务、行级锁和外键',
        'MyISAM' => '支持全文索引',
        'MEMORY' => '内存表，速度快但不持久',
        'ARCHIVE' => '压缩存储，适合历史数据',
        'CSV' => 'CSV格式存储'
    ];

    // 支持的字符集
    public const VALID_CHARSETS = [
        'utf8' => 'UTF-8 Unicode (3字节)',
        'utf8mb4' => 'UTF-8 Unicode (4字节，支持emoji)',
        'latin1' => 'ISO 8859-1 West European',
        'ascii' => 'US ASCII',
        'binary' => '二进制数据'
    ];

    // 默认配置
    protected static $defaultConfig = [
        'engine' => 'InnoDB',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci'
    ];

    // 当前连接配置
    protected static $connection;
    /**
     * 获取所有表信息
     */
    public static function all($connection)
    {
        return [];
    }

    /**
     * 检查表是否存在
     * @param string $tableName 表名
     * @return bool 表是否存在
     */
    public static function exists(string $tableName): bool
    {
        try {
            // 验证表名有效性
            if (!self::isValidTableName($tableName)) {
                return false;
            }
            
            // 转义表名中的单引号
            $escapedTableName = str_replace("'", "''", $tableName);
            
            // 直接拼接表名（因为SHOW TABLES不支持参数化查询）
            // 正确的语法是使用单引号包裹表名
            $result = Rdb::query("SHOW TABLES LIKE '{$escapedTableName}'");
            return !empty($result);
        } catch (\Exception $e) {
            error_log("检查表是否存在失败: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 获取表状态信息
     * @param string $tableName 表名
     * @return array 包含表状态信息的数组
     */
    
    public static function status(string $tableName): array
    {
        try {
            // 使用参数化查询防止SQL注入
            $status = Rdb::query("SHOW TABLE STATUS WHERE Name = ?", [$tableName])[0] ?? [];
            
            // 获取创建时间
            $createTime = isset($status['Create_time']) && $status['Create_time'] 
                ? strtotime($status['Create_time']) 
                : time();
            
            // 获取更新时间，如果为空则尝试使用其他时间字段
            try {
                $updateTime = Rdb::table($tableName)->max('update_time');
                $ctime = Rdb::table($tableName)->max('create_time');
                if ($ctime >= $updateTime) {
                    $updateTime = $ctime;
                }
            }  catch (\Exception $e) {

                $updateTime = isset($status['Create_time']) && $status['Create_time']
                    ? strtotime($status['Create_time'])
                    : time();

            }

                    
            // 更新表统计信息
            if (strtolower($status['Engine'] ?? '') === 'innodb') {
                Rdb::execute("ANALYZE TABLE `{$tableName}`");
                // 重新获取状态信息
                $status = Rdb::query("SHOW TABLE STATUS WHERE Name = ?", [$tableName])[0] ?? [];
            }

            // 计算实际大小（字节）
            $dataSize = $status['Data_length'] ?? 0;
            $indexSize = $status['Index_length'] ?? 0;
            $totalSize = $dataSize + $indexSize;

            // 获取实际行数
            $realRows = Rdb::table($tableName)->count();

            return [
                'createTime' => $createTime,           // 创建时间
                'lastModified' => $updateTime,         // 最后修改时间
                'rows' => $realRows,                   // 实际行数（通过 COUNT(*) 获取）
                'estimatedRows' => $status['Rows'] ?? 0, // 估计行数（来自表状态）
                'size' => $totalSize,                  // 总存储大小（字节）
                'dataSize' => $dataSize,               // 数据大小（字节）
                'indexSize' => $indexSize,             // 索引大小（字节）
                'formattedSize' => formatBytes($totalSize),      // 格式化后的总大小
                'formattedDataSize' =>formatBytes($dataSize),   // 格式化后的数据大小
                'formattedIndexSize' => formatBytes($indexSize), // 格式化后的索引大小
                'engine' => $status['Engine'] ?? 'unknown',            // 存储引擎
                'collation' => $status['Collation'] ?? 'unknown',      // 字符集
                'comment' => $status['Comment'] ?? ''                  // 表注释
            ];
        } catch (\Exception $e) {
            $currentTime = time();
            return [
                'createTime' => $currentTime,    // 添加创建时间
                'lastModified' => $currentTime,  // 最后修改时间
                'rows' => 0,
                'size' => 0,
                'engine' => 'unknown',
                'collation' => 'unknown',
                'comment' => ''
            ];
        }
    }

    /**
     * 备份单个表到指定目录
     * @param string $tableName 表名
     * @param string $backupDir 备份目录
     * @param bool $compress 是否压缩
     * @return array [备份文件路径, 记录数]
     * @throws \Exception
     */
    public static function backupTable(string $tableName, string $backupDir, bool $compress = true): array
    {
        if (!self::exists($tableName)) {
            throw new \Exception("表 {$tableName} 不存在");
        }

        FileUtil::mkdir($backupDir);

        // 获取表结构和数据
        $createTable = Rdb::query("SHOW CREATE TABLE `{$tableName}`")[0]['Create TableUtil'];
        $data = Rdb::table($tableName)->select()->toArray();
        $recordCount = count($data);

        // 生成SQL文件内容
        $content = "-- TableUtil structure for {$tableName}\n";
        $content .= $createTable . ";\n\n";
        $content .= "-- Data for {$tableName}\n";
        foreach ($data as $row) {
            $values = array_map(function ($value) {
                return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
            }, $row);
            $content .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
        }

        // 保存SQL文件
        $timestamp = date('YmdHis');
        $sqlFile = "{$backupDir}{$tableName}_{$timestamp}.sql";
        file_put_contents($sqlFile, $content);

        // 压缩备份文件
        if ($compress) {
            $zipFile = "{$backupDir}{$tableName}_{$timestamp}.zip";
            $zip = new ZipArchive();
            if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
                $zip->addFile($sqlFile, basename($sqlFile));
                $zip->close();
                unlink($sqlFile); // 删除原SQL文件
                return [$zipFile, $recordCount];
            }
            throw new \Exception("创建压缩文件失败");
        }

        return [$sqlFile, $recordCount];
    }

    /**
     * 从备份文件恢复表
     * @param string $tableName 表名
     * @param string $backupFile 备份文件路径
     * @param bool $dropIfExists 是否删除已存在的表
     * @return int 恢复的记录数
     * @throws \Exception
     */
    public static function restoreTable(string $tableName, string $backupFile, bool $dropIfExists = true): int
    {
        if (!file_exists($backupFile)) {
            throw new \Exception("备份文件不存在");
        }

        $tempDir = sys_get_temp_dir() . '/restore_' . md5($backupFile) . '/';
        FileUtil::mkdir($tempDir);

        try {
            // 解压备份文件
            $zip = new ZipArchive();
            if ($zip->open($backupFile) !== true) {
                throw new \Exception("无法打开备份文件");
            }
            $zip->extractTo($tempDir);
            $zip->close();

            // 获取SQL文件
            $sqlFiles = glob($tempDir . '*.sql');
            if (empty($sqlFiles)) {
                throw new \Exception("在备份文件中找不到SQL文件");
            }
            $sqlFile = $sqlFiles[0];

            // 读取并执行SQL
            $queries = self::readSqlFile($sqlFile);
            $recordCount = 0;

            Rdb::startTrans();
            try {
                if ($dropIfExists) {
                    Rdb::execute("DROP TABLE IF EXISTS `{$tableName}`");
                }

                foreach ($queries as $query) {
                    if (!empty(trim($query))) {
                        Rdb::execute($query);
                        if (stripos($query, 'INSERT INTO') === 0) {
                            $recordCount++;
                        }
                    }
                }
                Rdb::commit();
                return $recordCount;
            } catch (\Exception $e) {
                Rdb::rollback();
                throw new \Exception("执行SQL失败: " . $e->getMessage());
            }
        } finally {
            FileUtil::cleanupTempFiles($tempDir);
        }
    }

    /**
     * 批量备份多个表
     * @param array $tableNames 表名数组
     * @param string $backupDir 备份目录
     * @param bool $compress 是否压缩
     * @return array [总记录数, 备份文件列表]
     * @throws \Exception
     */
    public static function backupTables(array $tableNames, string $backupDir, bool $compress = true): array
    {
        $totalRecords = 0;
        $backupFiles = [];

        foreach ($tableNames as $table) {
            try {
                [$file, $count] = self::backupTable($table, $backupDir, $compress);
                $totalRecords += $count;
                $backupFiles[] = $file;
            } catch (\Exception $e) {
                throw new \Exception("备份表 {$table} 失败: " . $e->getMessage());
            }
        }

        return [$totalRecords, $backupFiles];
    }

    /**
     * 验证备份文件完整性
     * @param string $backupFile 备份文件路径
     * @return bool 是否有效
     */
    public static function validateBackup(string $backupFile): bool
    {
        if (!file_exists($backupFile)) {
            return false;
        }

        $tempDir = sys_get_temp_dir() . '/validate_' . md5($backupFile) . '/';
       FileUtil::mkdir($tempDir);

        try {
            $zip = new ZipArchive();
            if ($zip->open($backupFile) !== true) {
                return false;
            }

            $zip->extractTo($tempDir);
            $zip->close();

            $sqlFiles = glob($tempDir . '*.sql');
            return !empty($sqlFiles);
        } catch (\Exception $e) {
            return false;
        } finally {
           FileUtil::cleanupTempFiles($tempDir);
        }
    }

    /**
     * 读取SQL文件生成器
     * @param string $file SQL文件路径
     * @return \Generator
     * @throws \Exception
     */
    private static function readSqlFile(string $file): \Generator
    {
        $handle = fopen($file, 'r');
        if ($handle === false) {
            throw new \Exception("无法打开SQL文件");
        }

        $query = '';
        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '#') === 0) {
                continue;
            }

            $query .= ' ' . $line;

            if (substr(rtrim($query), -1) === ';') {
                yield trim($query);
                $query = '';
            }
        }

        if (!empty(trim($query))) {
            yield trim($query);
        }

        fclose($handle);
    }

    /**
     * 更新数据表的描述信息
     * @param string $tableName 表名
     * @param string $comment 表注释
     * @return bool 是否更新成功
     */
    public static function updateTableComment(string $tableName, string $comment): bool
    {
        try {
            // 基本参数验证
            if (empty($tableName) || !isset($comment)) {
                error_log("缺少必要的参数");
                return false;
            }
            
            // 安全处理表名，验证表名的有效性
            if (!self::isValidTableName($tableName)) {
                error_log("无效的表名: {$tableName}");
                return false;
            }
            
            // 安全处理注释内容
            $comment = self::sanitizeComment($comment);
            // 验证表是否存在
            // 注意：SHOW TABLES 不支持参数化查询，所以使用直接拼接
            // 但我们已经验证了表名的有效性，所以这里使用直接拼接是安全的
            $escapedTableName = str_replace("'", "''", $tableName);
            $tables = Rdb::query("SHOW TABLES LIKE '{$escapedTableName}'");

            if (empty($tables)) {
                error_log("表不存在: {$tableName}");
                return false;
            }
            
            // 获取表的当前信息，以保留其他属性
            // 注意：MySQL不支持在SHOW CREATE TABLE中使用参数化查询
            // 但我们已经验证了表名的有效性，所以这里使用直接拼接是安全的
            $escapedTableName = str_replace('`', '``', $tableName);
            $tableInfo = Rdb::query("SHOW CREATE TABLE `{$escapedTableName}`")[0] ?? [];

            if (empty($tableInfo['Create TableUtil'])) {
                error_log("无法获取表结构: {$tableName}");
                return false;
            }
            
            // 提取并验证表的字符集和引擎信息
            $createTable = $tableInfo['Create TableUtil'];
            [$engine, $charset] = self::extractAndValidateTableProperties($createTable);
            
            // 手动转义注释内容，避免在DDL语句中使用参数化查询
            $escapedComment = addslashes($comment);
            
            // 构建并执行ALTER TABLE语句
            $sql = "ALTER TABLE `{$tableName}` COMMENT = '{$escapedComment}' ENGINE = {$engine} CHARSET = {$charset}";

            Rdb::execute($sql);
            
            // 更新表统计信息
            if (strtolower($engine) === 'innodb') {
                // 使用转义后的表名
                $escapedTableName = str_replace('`', '``', $tableName);
                Rdb::execute("ANALYZE TABLE `{$escapedTableName}`");
            }
            
            return true;
        } catch (\Exception $e) {

            // 记录错误但不中断流程
            error_log("更新表 {$tableName} 的描述信息失败: " . $e->getMessage());
            throw $e;
            return false;

        }
    }

    /**
     * 验证表名是否有效
     * @param string $tableName 表名
     * @return bool 是否有效
     */
    public static function isValidTableName(string $tableName): bool
    {
        // 表名长度限制
        if (strlen($tableName) > 64 || strlen($tableName) < 1) {
            return false;
        }
        
        // 表名只允许包含字母、数字和下划线，且不能以数字开头
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $tableName)) {
            return false;
        }
        
        // 表名不能是MySQL保留关键字
        $reservedWords = [
            'add', 'all', 'alter', 'analyze', 'and', 'as', 'asc', 'asensitive', 
            'before', 'between', 'bigint', 'binary', 'blob', 'both', 'by', 
            'call', 'cascade', 'case', 'change', 'char', 'character', 'check', 
            'collate', 'column', 'condition', 'constraint', 'continue', 'convert', 
            'create', 'cross', 'current_date', 'current_time', 'current_timestamp', 
            'current_user', 'cursor', 'database', 'databases', 'day_hour', 
            'day_microsecond', 'day_minute', 'day_second', 'dec', 'decimal', 
            'declare', 'default', 'delayed', 'delete', 'desc', 'describe', 
            'deterministic', 'distinct', 'distinctrow', 'div', 'double', 'drop', 
            'dual', 'each', 'else', 'elseif', 'enclosed', 'escaped', 'exists', 
            'exit', 'explain', 'false', 'fetch', 'float', 'float4', 'float8', 
            'for', 'force', 'foreign', 'from', 'fulltext', 'grant', 'group', 
            'having', 'high_priority', 'hour_microsecond', 'hour_minute', 
            'hour_second', 'if', 'ignore', 'in', 'index', 'infile', 'inner', 
            'inout', 'insensitive', 'insert', 'int', 'int1', 'int2', 'int3', 
            'int4', 'int8', 'integer', 'interval', 'into', 'is', 'iterate', 
            'join', 'key', 'keys', 'kill', 'leading', 'leave', 'left', 'like', 
            'limit', 'linear', 'lines', 'load', 'localtime', 'localtimestamp', 
            'lock', 'long', 'longblob', 'longtext', 'loop', 'low_priority', 
            'master_ssl_verify_server_cert', 'match', 'mediumblob', 'mediumint', 
            'mediumtext', 'middleint', 'minute_microsecond', 'minute_second', 
            'mod', 'modifies', 'natural', 'not', 'no_write_to_binlog', 'null', 
            'numeric', 'on', 'optimize', 'option', 'optionally', 'or', 'order', 
            'out', 'outer', 'outfile', 'precision', 'primary', 'procedure', 
            'purge', 'range', 'read', 'reads', 'read_write', 'real', 'references', 
            'regexp', 'release', 'rename', 'repeat', 'replace', 'require', 
            'restrict', 'return', 'revoke', 'right', 'rlike', 'schema', 'schemas', 
            'second_microsecond', 'select', 'sensitive', 'separator', 'set', 
            'show', 'smallint', 'spatial', 'specific', 'sql', 'sqlexception', 
            'sqlstate', 'sqlwarning', 'sql_big_result', 'sql_calc_found_rows', 
            'sql_small_result', 'ssl', 'starting', 'straight_join', 'table', 
            'terminated', 'then', 'tinyblob', 'tinyint', 'tinytext', 'to', 
            'trailing', 'trigger', 'true', 'undo', 'union', 'unique', 'unlock', 
            'unsigned', 'update', 'usage', 'use', 'using', 'utc_date', 'utc_time', 
            'utc_timestamp', 'values', 'varbinary', 'varchar', 'varcharacter', 
            'varying', 'when', 'where', 'while', 'with', 'write', 'xor', 'year_month', 
            'zerofill'
        ];
        
        if (in_array(strtolower($tableName), $reservedWords)) {
            return false;
        }
        
        return true;
    }

    /**
     * 清理和验证表注释
     * @param string $comment 表注释
     * @return string 清理后的注释
     */
    public static function sanitizeComment(string $comment): string
    {
        // 移除可能导致SQL注入的字符
        $comment = str_replace(["'", "\"", "\\", "\0", "\n", "\r", "\x1a"], "", $comment);
        
        // 限制注释长度
        if (mb_strlen($comment) > 2048) {
            $comment = mb_substr($comment, 0, 2048);
        }
        
        return $comment;
    }

    /**
     * 从CREATE TABLE语句中提取并验证表属性
     * @param string $createTable CREATE TABLE语句
     * @return array [engine, charset]
     */
    public static function extractAndValidateTableProperties(string $createTable): array
    {
        // 提取引擎
        preg_match('/ENGINE=([a-zA-Z0-9_]+)/i', $createTable, $engineMatch);
        $engine = $engineMatch[1] ?? 'InnoDB';
        
        // 提取字符集
        preg_match('/CHARSET=([a-zA-Z0-9_]+)/i', $createTable, $charsetMatch);
        $charset = $charsetMatch[1] ?? 'utf8mb4';
        
        // 验证引擎
        if (!in_array($engine, self::VALID_ENGINES)) {
            $engine = 'InnoDB'; // 默认使用InnoDB
        }
        
        // 验证字符集
        if (!in_array(strtolower($charset), self::VALID_CHARSETS)) {
            $charset = 'utf8mb4'; // 默认使用utf8mb4
        }
        
        return [$engine, $charset];
    }

    /**
     * 获取表的字段信息
     * @param string $tableName 表名
     * @return array 字段信息数组
     */
    public static function getColumns(string $tableName): array
    {
        try {
            // 验证表名有效性
            if (!self::isValidTableName($tableName)) {
                error_log("无效的表名: {$tableName}");
                return [];
            }
            
            // 转义表名
            $escapedTableName = str_replace('`', '``', $tableName);
            
            // 获取表字段信息
            $columns = Rdb::query("SHOW FULL COLUMNS FROM `{$escapedTableName}`");
            if (empty($columns)) {
                return [];
            }
            
            $result = [];
            foreach ($columns as $column) {
                // 解析类型和长度
                $typeInfo = self::parseColumnType($column['Type']);
                
                $result[] = [
                    'field' => $column['Field'],                  // 字段名
                    'type' => $typeInfo['type'],                  // 数据类型
                    'length' => $typeInfo['length'],              // 长度
                    'precision' => $typeInfo['precision'],        // 精度（小数点位数）
                    'nullable' => $column['Null'] === 'YES',      // 是否允许为NULL
                    'key' => $column['Key'],                      // 键类型（PRI, UNI, MUL等）
                    'default' => $column['Default'],              // 默认值
                    'extra' => $column['Extra'],                  // 额外信息（如auto_increment）
                    'comment' => $column['Comment'] ?? '',        // 注释
                    'collation' => $column['Collation'] ?? null,  // 字符集
                    'privileges' => $column['Privileges'] ?? null  // 权限
                ];
            }
            
            return $result;
        } catch (\Exception $e) {
            error_log("获取表字段信息失败: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * 解析字段类型信息
     * @param string $typeString 类型字符串，如 varchar(255) 或 decimal(10,2)
     * @return array 包含类型、长度和精度的数组
     */
    private static function parseColumnType(string $typeString): array
    {
        $type = $typeString;
        $length = null;
        $precision = null;
        
        // 匹配类型和长度/精度
        if (preg_match('/^([a-z]+)(\(([0-9]+)(,([0-9]+))?\))?/', $typeString, $matches)) {
            $type = $matches[1]; // 基本类型
            
            if (isset($matches[3])) {
                $length = (int)$matches[3]; // 长度
                
                if (isset($matches[5])) {
                    $precision = (int)$matches[5]; // 精度（小数点位数）
                }
            }
        }
        
        // 处理无需长度的类型
        if (in_array(strtolower($type), ['text', 'mediumtext', 'longtext', 'blob', 'mediumblob', 'longblob', 'date', 'time', 'datetime', 'timestamp', 'year'])) {
            $length = null;
        }
        
        return [
            'type' => $type,
            'length' => $length,
            'precision' => $precision
        ];
    }
}
