<?php

namespace plugin\radmin\support\bootstrap;

use plugin\radmin\support\Container;
use plugin\radmin\support\Event as E;
use plugin\radmin\support\Log;
use Webman\Bootstrap;

class Event implements Bootstrap
{

    /**
     * @var array
     */
    protected static $events = [];

    /**
     * @param $worker
     * @return void
     */
    public static function start($worker): void
    {

        static::getEvents([config()]);
        foreach (static::$events as $name => $events) {
            // 支持排序，1 2 3 ... 9 a b c...z
            ksort($events, SORT_NATURAL);
            foreach ($events as $callbacks) {
                foreach ($callbacks as $callback) {
                    E::on($name, $callback);
                }
            }
        }
    }

    /**
     * @param $callbacks
     * @return array|mixed
     */
    protected static function convertCallable($callbacks)
    {
        if (\is_array($callbacks)) {
            $callback = \array_values($callbacks);
            if (isset($callback[1]) && \is_string($callback[0]) && \class_exists($callback[0])) {
                return [Container::get($callback[0]), $callback[1]];
            }
        }
        return $callbacks;
    }

    /**
     * @param $configs
     * @return void
     */
    protected static function getEvents($configs)
    {

        $config = config('plugin.radmin');
        // var_dump($config);
        if (isset($config['events']) && is_array($config['events'])) {
            foreach ($config['events'] as $event_name => $callbacks) {
                $callbacks = static::convertCallable($callbacks);
                if (is_callable($callbacks)) {
                    static::$events[$event_name][] = [$callbacks];
                    continue;
                }
                if (!is_array($callbacks)) {
                    $msg = "Events: $event_name => " . var_export($callbacks, true) . " is not callable\n";
                    echo $msg;
                    Log::error($msg);
                    continue;
                }
                ksort($callbacks, SORT_NATURAL);
                foreach ($callbacks as $id => $callback) {
                    $callback = static::convertCallable($callback);
                    if (is_callable($callback)) {
                        static::$events[$event_name][$id][] = $callback;
                        continue;
                    }
                    $msg = "Events: $event_name => " . var_export($callback, true) . " is not callable\n";
                    echo $msg;
                    Log::error($msg);
                }
            }
            unset($config['events']);
        }
    }


}
