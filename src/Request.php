<?php
/**
 * File:        Request.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/12 04:40
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */
namespace Radmin;
use Exception;
use stdClass;

class Request extends \Webman\Http\Request
{
    public string|null $role           = null;
    public string|null $token          = null;
    public object|null $member         = null;
    public array|null  $roles          = null;
    public array|null  $upload         = null;
    /**
     * @var mixed|stdClass
     */
    public mixed  $payload;
    private mixed $filter=null;


    /**
     *
     * @return      string|null
     * @throws      Exception
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 16:55
     */
    public function token(): ?string
    {
        $this->token = getTokenFromRequest($this);
        return $this->token;
    }

    /**
     * @param bool|null $full
     * @return   string|null
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 17:15
     */
    public function controller(?bool $full=false): ?string
    {
        if ($full){
            return $this->controller;
        }
        $controller = explode('\\', $this->controller);
        $controller = array_slice($controller, -2);
        return strtolower(implode('/', $controller));
    }
    /**
     * buildadmin 前端路由适配 强写
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 14:15
     */
    public function path(): string
    {
        $path = (string)parse_url($this->uri(), PHP_URL_PATH);
        // 匹配 /admin/ 开头且包含 . 的路径
        if (preg_match('#^/app/radmin/admin/.*\..+#', $path)) {
            // 替换 . 为 /
            $newPath = str_replace('.', '/', $path);
        }
        $this->data['path'] = $newPath ?? $path;
        return $this->data['path'];
    }


    /**
     * 增加过滤和强制类型转换
     * @param string            $name
     * @param mixed|null        $default
     * @param string|array|null $filter
     * @return   mixed
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 16:45
     */
    public function input(string $name, mixed $default = null, string|array|null $filter = ''): mixed
    {
        $data = null;
        if ('' != $name) {
            // 解析name
            if (str_contains($name, '/')) {
                [$name, $type] = explode('/', $name);
            }

            $data = $this->get($name, $this->post($name, $default));
        }
        return $this->filterData($data, $filter, $name, $default, $type ?? '');
    }

    /**
     * @param $data
     * @param $filter
     * @param $name
     * @param $default
     * @param $type
     * @return   mixed
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/16 16:46
     */
    protected function filterData($data, $filter, $name, $default, $type): mixed
    {
        if (is_null($data)) {
            return $default;
        }

        if (is_object($data)) {
            return $data;
        }

        // 解析过滤器
        $filter = $this->getFilter($filter, $default);

        if (is_array($data)) {
            array_walk_recursive($data, [$this, 'filterValue'], $filter);
        } else {
            $this->filterValue($data, $name, $filter);
        }

        if ($type) {
            // 强制类型转换
            $this->typeCast($data, $type);
        }

        return $data;
    }

    protected function getFilter($filter, $default): array
    {
        if (is_null($filter)) {
            $filter = [];
        } else {
            $filter = $filter ?: $this->filter;
            if (is_string($filter) && !str_contains($filter, '/')) {
                $filter = explode(',', $filter);
            } else {
                $filter = (array)$filter;
            }
        }

        $filter[] = $default;

        return $filter;
    }

    /**
     * 强制类型转换
     * @access protected
     * @param mixed  $data
     * @param string $type
     * @return void
     */
    protected function typeCast(mixed &$data, string $type): void
    {
        $data = match (strtolower($type)) {
            'a' => (array)$data,
            'b' => (bool)$data,
            'd' => (int)$data,
            'f' => (float)$data,
            's' => is_scalar($data) ? (string)$data : throw new \InvalidArgumentException('variable type error：' . gettype($data)),
            default => $data,
        };
    }

    /**
     * 递归过滤给定的值
     * @access public
     * @param mixed $value   键值
     * @param mixed $key     键名
     * @param array $filters 过滤方法+默认值
     * @return mixed
     */
    public function filterValue(mixed &$value, mixed $key, array $filters): mixed
    {
        $default = array_pop($filters);

        foreach ($filters as $filter) {
            if (is_callable($filter)) {
                // 调用函数或者方法过滤
                if (is_null($value)) {
                    continue;
                }

                $value = call_user_func($filter, $value);
            } elseif (is_scalar($value)) {
                if (is_string($filter) && str_contains($filter, '/')) {
                    // 正则过滤
                    if (!preg_match($filter, $value)) {
                        // 匹配不成功返回默认值
                        $value = $default;
                        break;
                    }
                } elseif (!empty($filter)) {
                    // filter函数不存在时, 则使用filter_var进行过滤
                    // filter为非整形值时, 调用filter_id取得过滤id
                    $value = filter_var($value, is_int($filter) ? $filter : filter_id($filter));
                    if (false === $value) {
                        $value = $default;
                        break;
                    }
                }
            }
        }

        return $value;
    }

    /**
     * 设置或获取当前的过滤规则
     * @access public
     * @param mixed|null $filter 过滤规则
     */
    public function filter(mixed $filter = null): mixed
    {
        if (is_null($filter)) {
            return $this->filter;
        }

        $this->filter = $filter;

        return $this;
    }

}
