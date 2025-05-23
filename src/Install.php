<?php
/**
 * File:        Install.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/14 04:32
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace Rocareer\WebmanRadmin;

class Install
{
    const WEBMAN_PLUGIN = true;

    /**
     * @var array
     */
    protected static $pathRelation = array(
        '../config/plugin/rocareer/webman-radmin' => 'config/plugin/rocareer/webman-radmin',
        '../plugin/radmin'                        => 'plugin/radmin',
        '../web'                                  => 'web',
        '.env-example'                            => '.env-example',
        'database'                                => 'database',
    );

    /**
     * Install
     * @return void
     */
    public static function install(): void
    {
        static::installByRelation();
    }

    /**
     * Uninstall
     * @return void
     */
    public static function uninstall(): void
    {
        self::uninstallByRelation();
    }

    /**
     * installByRelation
     * @return void
     */
    /**
     * installByRelation
     * @return void
     */
    public static function installByRelation(): void
    {
        $backup_path = base_path() . "/plugin/radmin/temp/backup";
        foreach (static::$pathRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path() . '/' . substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }
            // 如果是目录，则复制目录
            if (is_dir(__DIR__ . "/$source")) {
                copy_dir(__DIR__ . "/$source", base_path() . "/$dest");
                echo "Create directory $dest\n";
            } elseif (is_file(__DIR__ . "/$source")) {
                // 如果是文件，则移动文件
                $sourceFile = __DIR__ . "/$source";
                $destFile   = base_path() . "/$dest";
                // 如果有 先备份
                if (file_exists($destFile)) {
                    copy($destFile, $backup_path . '/' . $dest);
                    if (copy($destFile, $backup_path . '/' . $dest)) {
                        echo "backup $dest\n";
                    } else {
                        echo "Failed to backup $dest\n";
                    }
                }

                if (copy($sourceFile, $destFile)) {
                    echo "Moved file from $sourceFile to $destFile\n";
                } else {
                    echo "Failed to move file from $sourceFile to $destFile\n";
                }
            }
        }

    }

    /**
     * uninstallByRelation
     * @return void
     */
    public static function uninstallByRelation(): void
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
    public static function modify_config(){
        modify_config('think-cache.php',[ 'default' => 'file']);
    }

}