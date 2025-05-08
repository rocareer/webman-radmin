<?php
/*
 *
 *  * // +----------------------------------------------------------------------
 *  * // | Rocareer [ ROC YOUR CAREER ]
 *  * // +----------------------------------------------------------------------
 *  * // | Copyright (c) 2014~2025 Albert@rocareer.com All rights reserved.
 *  * // +----------------------------------------------------------------------
 *  * // | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 *  * // +----------------------------------------------------------------------
 *  * // | Author: albert <Albert@rocareer.com>
 *  * // +----------------------------------------------------------------------
 *
 */

namespace Rocareer\Radmin;

use Exception;

class Install
{
    const WEBMAN_PLUGIN = true;

    /**
     * @var array
     */
    protected static $pathRelation = array(
        '../config/plugin/rocareer/radmin' => 'config/plugin/rocareer/radmin',
        '../plugin/radmin' => 'plugin/radmin',
        '../database' => 'database',
        '../web' => 'web',
        '../support' => 'support',

    );

    /**
     * Install
     * @return void
     */
    public static function install()
    {
        static::installByRelation();
    }

    /**
     * Uninstall
     * @return void
     */
    public static function uninstall()
    {
        self::uninstallByRelation();
    }

    /**
     * installByRelation
     * @return void
     */
    public static function installByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path() . '/' . substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }
            //symlink(__DIR__ . "/$source", base_path()."/$dest");
            copy_dir(__DIR__ . "/$source", base_path() . "/$dest");
            echo "Create $dest" . PHP_EOL;
        }



        copy(__DIR__ . "/../config/think-orm.php", base_path() . "/config/think-orm.php");

        echo "Create config/think-orm.php file" . PHP_EOL;
	    
	    
	    copy(__DIR__ . "/../config/log.php", base_path() . "/config/log.php");
	    
	    echo "Create config/log.php file" . PHP_EOL;

        copy(__DIR__ . "/../config/filesystem.php", base_path() . "/config/filesystem.php");

        echo "Create config/filesystem.php file" . PHP_EOL;

        copy(__DIR__ . "/../config/process.php", base_path() . "/config/process.php");

        echo "Create config/process.php file" . PHP_EOL;

        copy(__DIR__ . "/../config/cache.php", base_path() . "/config/cache.php");

        echo "Create config/cache.php file" . PHP_EOL;

        copy(__DIR__ . "/../support/StatusCode.php", base_path() . "/support/StatusCode.php");

        echo "Create support/Request.php file" . PHP_EOL;

        copy(__DIR__ . "/../support/Request.php", base_path() . "/support/Request.php");

        echo "Create support/Request.php file" . PHP_EOL;


        //删除 event.config
        if (file_exists(base_path().'/plugin/radmin/config/event.php')) {
            unlink(base_path().'/plugin/radmin/config/event.php');
        }
        echo "Remove event.php file" . PHP_EOL;

        // 安装 Env 文件
        copy(__DIR__ . "/.env-example", base_path() . "/.env-example");

        echo "Create .env-example file" . PHP_EOL;
		
       copy(__DIR__ . "/.env-example", base_path()."/.env");

       echo "Create .env file".PHP_EOL;


    }

    /**
     * uninstallByRelation
     * @return void
     */
    public static function uninstallByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            $path = base_path() . "/$dest";
            if (!is_dir($path) && !is_file($path)) {
                continue;
            }
            echo "Remove $dest
";
            if (is_file($path) || is_link($path)) {
                unlink($path);
                continue;
            }
            remove_dir($path);
        }
    }


}