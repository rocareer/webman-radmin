<?php

namespace app\admin\controller;

use Throwable;
use extend\ba\Terminal;
use support\Response;
use extend\ba\TableManager;
use support\think\Db;
use support\Cache;
use Webman\Event\Event;
use app\admin\model\AdminLog;
use app\common\library\upload;
use app\common\controller\Backend;

class Ajax extends Backend
{
    protected array $noNeedPermission = ['*'];

    /**
     * 无需登录的方法
     * terminal 内部自带验权
     */
    protected array $noNeedLogin = ['terminal'];

    public function initialize():void
    {
        parent::initialize();
    }

    public function upload()
    {
        AdminLog::instance()->setTitle(__('upload'));
        $file   = $this->request->file('file');
        $driver = $this->request->input('driver', 'local');
        $topic  = $this->request->input('topic', 'default');
        try {
            $upload     = new Upload();
            $attachment = $upload
                ->setFile($file)
                ->setDriver($driver)
                ->setTopic($topic)
                ->upload(null, $this->request->member->id);
            unset($attachment['create_time'], $attachment['quote']);
        } catch (Throwable $e) {
         return $this->error($e->getMessage());
        }

     return $this->success(__('File uploaded successfully'), [
            'file' => $attachment ?? []
        ]);
    }

    /**
     * 获取省市区数据
     * @throws Throwable
     */
    public function area()
    {
     return $this->success('', get_area());
    }

    public function buildSuffixSvg(): Response
    {
        $suffix     = $this->request->input('suffix', 'file');
        $background = $this->request->input('background');
        $content    = build_suffix_svg((string)$suffix, (string)$background);
        return response($content, 200, ['Content-Length' => strlen($content)])->contentType('image/svg+xml');
    }

    /**
     * 获取已脱敏的数据库连接配置列表
     * @throws Throwable
     */
    public function getDatabaseConnectionList()
    {
        $quickSearch     = $this->request->input("quickSearch", '');
        $connections     = config('think-orm.connections');
        $desensitization = [];
        foreach ($connections as $key => $connection) {
            $connection        = TableManager::getConnectionConfig($key);
            $desensitization[] = [
                'type'     => $connection['type'],
                'database' => substr_replace($connection['database'], '****', 1, strlen($connection['database']) > 4 ? 2 : 1),
                'key'      => $key,
            ];
        }

        if ($quickSearch) {
            $desensitization = array_filter($desensitization, function ($item) use ($quickSearch) {
                return preg_match("/$quickSearch/i", $item['key']);
            });
            $desensitization = array_values($desensitization);
        }

     return $this->success('', [
            'list' => $desensitization,
        ]);
    }

    /**
     * 获取表主键字段
     * @param ?string $table
     * @param ?string $connection
     * @throws Throwable
     */
    public function getTablePk(?string $table = null, ?string $connection = null)
    {
        if (!$table) {
         return $this->error(__('Parameter error'));
        }

        $table = TableManager::tableName($table, true, $connection);
        if (!TableManager::phinxAdapter(false, $connection)->hasTable($table)) {
         return $this->error(__('Data table does not exist'));
        }

        $tablePk = Db::connect(TableManager::getConnection($connection))
            ->table($table)
            ->getPk();
     return $this->success('', ['pk' => $tablePk]);
    }

    /**
     * 获取数据表列表
     * @throws Throwable
     */
    public function getTableList()
    {
        $quickSearch  = $this->request->input("quickSearch", '');
        $connection   = $this->request->input('connection');// 数据库连接配置标识
        $samePrefix   = $this->request->input('samePrefix', true);// 是否仅返回项目数据表（前缀同项目一致的）
        $excludeTable = $this->request->input('excludeTable', []);// 要排除的数据表数组（表名无需带前缀）

        $outTables = [];
        $dbConfig  = TableManager::getConnectionConfig($connection);
        $tables    = TableManager::getTableList($connection);

        if ($quickSearch) {
            $tables = array_filter($tables, function ($comment) use ($quickSearch) {
                return preg_match("/$quickSearch/i", $comment);
            });
        }

        $pattern = '/^' . $dbConfig['prefix'] . '/i';
        foreach ($tables as $table => $comment) {
            if ($samePrefix && !preg_match($pattern, $table)) continue;

            $table = preg_replace($pattern, '', $table);
            if (!in_array($table, $excludeTable)) {
                $outTables[] = [
                    'table'      => $table,
                    'comment'    => $comment,
                    'connection' => $connection,
                    'prefix'     => $dbConfig['prefix'],
                ];
            }
        }

     return $this->success('', [
            'list' => $outTables,
        ]);
    }

    /**
     * 获取数据表字段列表
     * @throws Throwable
     */
    public function getTableFieldList()
    {
        $table      = $this->request->input('table');
        $clean      = $this->request->input('clean', true);
        $connection = $this->request->input('connection');
        if (!$table) {
         return $this->error(__('Parameter error'));
        }

        $connection = TableManager::getConnection($connection);
        $tablePk    = Db::connect($connection)->name($table)->getPk();
     return $this->success('', [
            'pk'        => $tablePk,
            'fieldList' => TableManager::getTableColumns($table, $clean, $connection),
        ]);
    }

    public function changeTerminalConfig()
    {
        AdminLog::instance()->setTitle(__('Change terminal config'));
        if (Terminal::changeTerminalConfig()) {
         return $this->success();
        } else {
         return $this->error(__('Failed to modify the terminal configuration. Please modify the configuration file manually:%s', ['/config/buildadmin.php']));
        }
    }

    public function clearCache()
    {
        AdminLog::instance()->setTitle(__('Clear cache'));
        $type = $this->request->post('type');
        if ($type == 'tp' || $type == 'all') {
            Cache::clear();
        } else {
         return $this->error(__('Parameter error'));
        }
        //todo
        // Event::trigger('cacheClearAfter', $this->app);
     return $this->success(__('Cache cleaned~'));
    }

    /**
     * 终端
     * @throws Throwable
     */
    public function terminal()
    {
        (new Terminal())->exec();
    }
}