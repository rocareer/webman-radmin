<?php


namespace plugin\radmin\support;

use plugin\radmin\app\process\Http;


/**
 * Class Response
 * @package support
 */
class Response extends \Webman\Http\Response
{

    protected function notModifiedSince(string $file): bool
    {
        $ifModifiedSince = Http::request()->header('if-modified-since');
        if ($ifModifiedSince === null || !is_file($file) || !($mtime = filemtime($file))) {
            return false;
        }
        return $ifModifiedSince === gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
    }
}