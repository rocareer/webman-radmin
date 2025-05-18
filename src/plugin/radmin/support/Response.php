<?php


namespace plugin\radmin\support;


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
    /**
     * Download
     * @param string $file
     * @param string $downloadName
     * @return $this
     */
    public function download(string $file, string $downloadName = ''): Response
    {
        $this->withFile($file);
        if ($downloadName) {
            $this->header('Content-Disposition', "attachment; filename=\"$downloadName\"");
        }
        return $this;
    }

   public static function response(string $body = '', int $status = 200, array $headers = []): Response
    {
        return new Response($status, $headers, $body);
    }
}