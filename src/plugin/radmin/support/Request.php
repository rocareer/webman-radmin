<?php

namespace plugin\radmin\support;

use InvalidArgumentException;

/**
 * File:        Request.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 04:40
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */
class Request extends \Webman\Http\Request
{
    public string|null $controllerName = null;
    public string|null $role           = null;
    public string|null $token          = null;
    public object|null $member         = null;
    public array|null  $roles          = null;
    public array|null  $upload         = null;



    public function __construct(protected string $buffer)
    {
        parent::__construct($buffer);
        $this->adminRoute();
    }

    /**
     * buildadmin 前端路由适配 强写
     * @return   void
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/12 04:39
     */
    protected function adminRoute(): void
    {
        $path = $this->path();

        // 匹配 /admin/ 开头且包含 . 的路径
        if (preg_match('#^/app/radmin/admin/.*\..+#', $path)) {
            // 替换 . 为 /
            $newPath = str_replace('.', '/', $path);

            // 保留查询参数
            $query  = $this->queryString();
            $newUri = $newPath . ($query ? "?$query" : '');

            // 重写请求URI
            $this->data['uri'] = $newUri;

            // 强制重新解析路径（重要！）
            unset($this->data['path']);
        }
    }
}
