<?php


namespace Radmin;

use Exception;
use FilesystemIterator;
use PhpZip\ZipFile;
use plugin\radmin\exception\BusinessException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

/**
 * 访问和操作文件系统
 */
class HelperFile
{
    /**
     * 是否是空目录
     */
    public static function dirIsEmpty(string $dir): bool
    {
        if (!file_exists($dir)) return true;
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                closedir($handle);
                return false;
            }
        }
        closedir($handle);
        return true;
    }

    /**
     * 递归删除目录
     * @param string $dir     目录路径
     * @param bool   $delSelf 是否删除传递的目录本身
     * @return bool
     */
    public static function delDir(string $dir, bool $delSelf = true): bool
    {
        if (!is_dir($dir)) {
            return false;
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileInfo) {
            if ($fileInfo->isDir()) {
                self::delDir($fileInfo->getRealPath());
            } else {
                @unlink($fileInfo->getRealPath());
            }
        }
        if ($delSelf) {
            @rmdir($dir);
        }
        return true;
    }

    /**
     * 删除一个路径下的所有相对空文件夹（删除此路径中的所有空文件夹）
     * @param string $path 相对于根目录的文件夹路径 如`c:BuildAdmin/a/b/`
     
     */
    public static function delEmptyDir(string $path)
    {
        $path = str_replace(root_path(), '', rtrim(self::fsFit($path), DIRECTORY_SEPARATOR));
        $path = array_filter(explode(DIRECTORY_SEPARATOR, $path));
        for ($i = count($path) - 1; $i >= 0; $i--) {
            $dirPath = root_path() . implode(DIRECTORY_SEPARATOR, $path);
            if (!is_dir($dirPath)) {
                unset($path[$i]);
                continue;
            }
            if (self::dirIsEmpty($dirPath)) {
                self::delDir($dirPath);
                unset($path[$i]);
            } else {
                break;
            }
        }
    }

    /**
     * 检查目录/文件是否可写
     * @param $path
     * @return bool
     */
    public static function pathIsWritable($path): bool
    {
        if (DIRECTORY_SEPARATOR == '/' && !@ini_get('safe_mode')) {
            return is_writable($path);
        }

        if (is_dir($path)) {
            $path = rtrim($path, '/') . '/' . md5(mt_rand(1, 100) . mt_rand(1, 100));
            if (($fp = @fopen($path, 'ab')) === false) {
                return false;
            }

            fclose($fp);
            @chmod($path, 0777);
            @unlink($path);

            return true;
        } elseif (!is_file($path) || ($fp = @fopen($path, 'ab')) === false) {
            return false;
        }

        fclose($fp);
        return true;
    }

    /**
     * 路径分隔符根据当前系统分隔符适配
     * @param string $path 路径
     * @return string 转换后的路径
     */
    public static function fsFit(string $path): string
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    /**
     * 解压Zip
     * @param string $file ZIP文件路径
     * @param string $dir  解压路径
     * @return string 解压后的路径
     * @throws Throwable
     */
    public static function unzip(string $file, string $dir = ''): string
    {
        if (!file_exists($file)) {
            throw new Exception("Zip file not found");
        }

        $zip = new ZipFile();
        try {
            $zip->openFile($file);
        } catch (Throwable $e) {
            $zip->close();
            throw new Exception('Unable to open the zip file', 0, ['msg' => $e->getMessage()]);
        }

        $dir = $dir ?: substr($file, 0, strripos($file, '.zip'));
        if (!is_dir($dir)) {
            @mkdir($dir, 0755);
        }

        try {
            $zip->extractTo($dir);
        } catch (Throwable $e) {
            throw new Exception('Unable to extract ZIP file', 0, ['msg' => $e->getMessage()]);
        } finally {
            $zip->close();
        }
        return $dir;
    }

    /**
     * 创建ZIP
     * @param array  $files    文件路径列表
     * @param string $fileName ZIP文件名称
     * @return bool
     * @throws Throwable
     */
    public static function zip(array $files, string $fileName): bool
    {
        $zip = new ZipFile();
        try {
            foreach ($files as $v) {
                if (is_array($v) && isset($v['file']) && isset($v['name'])) {
                    $zip->addFile(str_replace(root_path(), '', HelperFile::fsFit($v['file'])), $v['name']);
                } else {
                    $saveFile = str_replace(root_path(), '', HelperFile::fsFit($v));
                    $zip->addFile(root_path() . $saveFile, $saveFile);
                }
            }
            $zip->saveAsFile($fileName);
        } catch (Throwable $e) {
            throw new BusinessException('Unable to package zip file', 0, ['msg' => $e->getMessage(), 'file' => $fileName]);
        } finally {
            $zip->close();
        }
        if (file_exists($fileName)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 递归创建目录
     * @param string $dir 目录路径
     * @return bool 是否创建成功或已存在
     */
    public static function mkdir(string $dir): bool
    {
        if (!is_dir($dir)) {
            return mkdir($dir, 0755, true);
        }
        return true;
    }

    /**
     * 清理临时文件目录
     * @param string $dir 目录路径
     */
    public static function cleanupTempFiles(string $dir): void
    {
        if (is_dir($dir)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
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
     * 获取一个目录内的文件列表
     * @param string $dir    目录路径
     * @param array  $suffix 要获取的文件列表的后缀
     * @return array
     */
    public static function getDirFiles(string $dir, array $suffix = []): array
    {

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::LEAVES_ONLY
        );

        $fileList = [];
        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (!empty($suffix) && !in_array($file->getExtension(), $suffix)) {
                continue;
            }
            $filePath        = $file->getRealPath();
            $name            = str_replace($dir, '', $filePath);
            $name            = str_replace(DIRECTORY_SEPARATOR, "/", $name);
            $fileList[$name] = $name;
        }
        return $fileList;
    }

    /**
     *
     * @param $source
     * @param $destination
     * @return   void
     * @throws Exception
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/18 23:27
     */
   public static function copyDir($source, $destination): void
   {
        // 检查源文件夹是否存在
        if (!is_dir($source)) {
            throw new Exception("Source folder does not exist: $source");
        }

        // 如果目标文件夹不存在，则创建
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        // 打开源文件夹
        $dir = opendir($source);

        // 遍历源文件夹中的每个文件和子文件夹
        while (($file = readdir($dir)) !== false) {
            // 跳过特殊文件夹 . 和 ..
            if ($file == '.' || $file == '..') {
                continue;
            }

            $sourcePath = $source . DIRECTORY_SEPARATOR . $file;
            $destinationPath = $destination . DIRECTORY_SEPARATOR . $file;

            // 如果是文件夹，递归拷贝
            if (is_dir($sourcePath)) {
                self::copyDir($sourcePath, $destinationPath);
            } else {
                // 如果是文件，直接拷贝
                copy($sourcePath, $destinationPath);
            }
        }

        // 关闭文件夹
        closedir($dir);
    }

}
