<?php

declare (strict_types = 1);

namespace plugin\radmin\support\orm;

use MongoDB\Driver\Command;
use think\db\BaseQuery;
use think\db\ConnectionInterface;
use think\db\Query;
use think\DbManager;
use Throwable;
use Webman\Context;
use Workerman\Coroutine\Pool;

/**
 * Class DbManager.
 *
 * @mixin BaseQuery
 * @mixin Query
 */
class Rdbm extends DbManager
{

    /**
     * @var Pool[]
     */
    protected static array $pools = [];

    /**
     * Get instance of connection.
     *
     * @param string|null $name
     * @param bool $force
     * @return ConnectionInterface
     * @throws Throwable
     */
    protected function instance(?string $name = null, bool $force = false): ConnectionInterface
    {
        if (empty($name)) {
            $name = $this->getConfig('default', 'mysql');
        }
        $key = "plugin.radmin.think-orm.connections.$name";
        $connection = Context::get($key);
        if (!$connection) {
            if (!isset(static::$pools[$name])) {
                $poolConfig = $this->config['connections'][$name]['pool'] ?? [];
                $pool = new Pool($poolConfig['max_connections'] ?? 10, $poolConfig);
                $pool->setConnectionCreator(function () use ($name) {
                    return $this->createConnection($name);
                });
                $pool->setConnectionCloser(function ($connection) {
                    $this->closeConnection($connection);
                });
                $pool->setHeartbeatChecker(function ($connection) {
                    if ($connection->getConfig('type') === 'mongo') {
                        $command = new Command(['ping' => 1]);
                        $connection->command($command);
                        return;
                    }
                    $connection->query('select 1');
                });
                static::$pools[$name] = $pool;
            }
            try {
                $connection = static::$pools[$name]->get();
                Context::set($key, $connection);
            } finally {
                Context::onDestroy(function () use ($connection, $name) {
                    try {
                        $connection && static::$pools[$name]->put($connection);
                    } catch (Throwable) {
                        // ignore
                    }
                });
            }
        }

        return $connection;
    }

    /**
     * Close connection.
     *
     * @param ConnectionInterface $connection
     * @return void
     */
    protected function closeConnection(ConnectionInterface $connection)
    {
        $connection->close();
        $clearProperties = function () {
            $this->db = null;
            $this->cache = null;
            $this->builder = null;
        };
        $clearProperties->call($connection);
    }
    public function setConfig($config): void
    {
        // $config=config('plugin.radmin.think-orm.connections.mysql');
        $this->config = $config;
    }
}
