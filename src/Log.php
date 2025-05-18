<?php
/**
 * File:        Log.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/16 22:05
 * Description: 适配 radmin 应用 修改自webman-log
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace Radmin;


use Monolog\Logger;

/**
 * Class Log
 * @package support
 *
 * @method static void log($level, $message, array $context = [])
 * @method static void debug($message, array $context = [])
 * @method static void info($message, array $context = [])
 * @method static void notice($message, array $context = [])
 * @method static void warning($message, array $context = [])
 * @method static void error($message, array $context = [])
 * @method static void critical($message, array $context = [])
 * @method static void alert($message, array $context = [])
 * @method static void emergency($message, array $context = [])
 *
 */
class Log extends \support\Log
{
    /**
     * @var array
     */
    protected static $instance = [];
    /**
     * Channel.
     * @param string $name
     * @return Logger
     */
    public static function channel(string $name = 'default'): Logger
    {
        if (!isset(static::$instance[$name])) {
            $config                  = include base_path() . '/plugin/radmin/config/log.php';
            $config                  = $config[$name];
            $handlers                = self::handlers($config);
            $processors              = self::processors($config);
            static::$instance[$name] = new Logger($name, $handlers, $processors);
        }
        return static::$instance[$name];
    }


    public function data($log,$event)
    {
        if ($event=='log.data.backup.success'){

        }
        if ($event=='log.data.restore.faile'){
        }
    }
}

