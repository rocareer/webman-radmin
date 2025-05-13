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

return [
    // 最大上传
    'max_size'           => '10mb',
    // 文件保存格式化方法
    'save_name'          => '/storage/{topic}/{year}{mon}{day}/{fileName}{fileSha1}{.suffix}',
    // 允许的文件后缀
    'allowed_suffixes'   => 'jpg,png,bmp,jpeg,gif,webp,zip,rar,wav,mp4,mp3',
    // 允许的MIME类型
    'allowed_mime_types' => [],
];
