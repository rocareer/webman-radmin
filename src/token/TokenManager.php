<?php
/**
 * File:        TokenManager.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 11:20
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

namespace Radmin\token;

use InvalidArgumentException;
use RuntimeException;
use stdClass;
use think\helper\Arr;
use think\helper\Str;

class TokenManager
{
    protected array  $drivers       = [];
    protected string $defaultDriver = 'jwt';
    protected array  $config        = [];
    protected string $namespace     = '\\Radmin\\token\\driver\\';
    protected mixed  $handler       = null;

    /**
     * @throws \Throwable
     */
    public function __construct(array $config = [])
    {
        $tokenConfig         = SystemUtil::get_sys_config('', 'authentication');
        $this->defaultDriver = $tokenConfig['driver'] ?? $this->defaultDriver;
        $this->config        = array_merge($config, $tokenConfig);
    }

    /**
     * 获取实例
     * @param array|null $config
     * @return   self
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:21
     */
    public static function getInstance(?array $config = []): self
    {
        return new self($config);
    }

    /**
     * 获取驱动
     * @param string|null $name
     * @return object
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:21
     */
    public function getDriver(?string $name = null): object
    {
        if ($this->handler !== null) {
            return $this->handler;
        }

        $name = $name ?: $this->defaultDriver;

        if ($name === null) {
            throw new InvalidArgumentException(sprintf('Unable to resolve NULL driver for [%s].', static::class));
        }

        return $this->createDriver($name);
    }

    /**
     * 解析参数
     * @param string $name
     * @return   array[]|string[]
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:21
     */
    protected function resolveParams(string $name): array
    {
        $config = $this->getDriverConfig($name);
        return [$config];
    }

    /**
     * 获取驱动配置
     * @param string      $driver
     * @param string|null $name
     * @param             $default
     * @return   array|string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:21
     */
    protected function getDriverConfig(string $driver, ?string $name = null, $default = null): array|string
    {
        if ($config = $this->config) {
            return Arr::get($config, $name, $default);
        }

        throw new InvalidArgumentException("Driver [$driver] not found.");
    }

    /**
     * 通过类型获取驱动
     * @param string $name
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:22
     */
    protected function resolveType(string $name): string
    {
        return $this->getDriverConfig($name, 'type', 'jwt');
    }

    /**
     * 创建驱动
     * @param string $name
     * @return object
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:22
     */
    protected function createDriver(string $name): object
    {
        $type   = $this->resolveType($name);
        $method = 'create' . Str::studly($type) . 'Driver';
        $params = $this->resolveParams($name);

        if (method_exists($this, $method)) {
            return $this->$method(...$params);
        }

        $class = $this->resolveClass($type);
        return new $class(...$params);
    }

    /**
     * 解析类名
     * @param string $type
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:22
     */
    protected function resolveClass(string $type): string
    {
        if ($this->namespace || str_contains($type, '\\')) {
            $class = str_contains($type, '\\') ? $type : $this->namespace . Str::studly($type);

            if (class_exists($class)) {
                return $class;
            }
        }

        throw new InvalidArgumentException("Driver [$type] not supported.");
    }

    /**
     * 通过类型创建驱动
     * @param string|null $name
     * @return   TokenInterface
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:23
     */
    public function driver(?string $name = null): TokenInterface
    {
        $name = $name ?: $this->defaultDriver;

        if (!isset($this->drivers[$name])) {
            throw new RuntimeException("Token driver [{$name}] not found.");
        }

        $config = $this->config['drivers'][$name] ?? [];
        return $this->drivers[$name]($config);
    }

    /**
     * 编码
     * @param array $payload
     * @param bool  $keep
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:23
     */
    public function encode(array $payload = []): string
    {
        return $this->getDriver()->encode($payload);
    }

    /**
     * 验证
     * @param string $token
     * @return   stdClass
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:23
     */
    public function verify(string $token): stdClass
    {
        return $this->getDriver()->verify($token);
    }

    /**
     * 解码
     * @param string $token
     * @return   stdClass
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:24
     */
    public function decode(string $token): stdClass
    {
        return $this->getDriver()->decode($token);
    }

    /**
     * 废弃
     * @param string $token
     * @return   bool
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:24
     */
    public function destroy(string $token): bool
    {
        return $this->getDriver()->destroy($token);
    }

    /**
     * 刷新
     * @param string $token
     * @return   string
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:24
     */
    public function refresh(string $token): string
    {
        return $this->getDriver()->refresh($token);
    }

    /**
     * 判断过期
     * @param string $token
     * @return   bool
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 11:24
     */
    public function expired(string $token): bool
    {
        return $this->getDriver()->expired($token);
    }

    /**
     * @param string $token
     * @return   mixed
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 22:30
     */
    public function ttl(string $token)
    {
        return $this->getDriver()->ttl($token);
    }

    /**
     * 获取加密 Token
     *
     * @return string
     */
    public function getEncryptedToken(): string
    {
        return getEncryptedToken(uniqid($this->config['iss'], true), $this->config['algo'], $this->config['secret']);
    }
}
