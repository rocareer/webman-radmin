<?php


namespace plugin\radmin\app\api\controller;

use Throwable;
use plugin\radmin\support\Response;
use plugin\radmin\app\common\library\upload;
use plugin\radmin\app\common\controller\Frontend;

class Ajax extends Frontend
{
    protected array $noNeedLogin = ['area', 'buildSuffixSvg'];

    protected array $noNeedPermission = ['upload'];

    public function initialize():void
    {
        parent::initialize();
    }

    public function upload()
    {
        $file   = $this->request->file('file');
        $driver = $this->request->input('driver', 'local');
        $topic  = $this->request->input('topic', 'default');
        try {
            $upload     = new Upload();
            $attachment = $upload
                ->setFile($file)
                ->setDriver($driver)
                ->setTopic($topic)
                ->upload(null, 0, $this->request->member->id);
            unset($attachment['create_time'], $attachment['quote']);
        } catch (Throwable $e) {
            return $this->error($e->getMessage());
        }

        return $this->success(__('File uploaded successfully'), [
            'file' => $attachment ?? []
        ]);
    }

    /**
     * 省份地区数据
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
}