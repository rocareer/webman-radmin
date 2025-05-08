<?php

namespace support\token;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;
use stdClass;

class TokenManager
{
    protected array $drivers       = [];
    public string   $defaultDriver = 'cache';
    protected array $config        = [];
    protected array $configs       = [];
    /**
     * 驱动类命名空间
     * @var string
     */
    protected string $namespace = '\\plugin\\radmin\\support\\token\\driver\\';
    protected mixed  $handler   = null;

    public function __construct(array $config = [])
    {
        $this->configs       = radmin_config('token');
        $this->defaultDriver = $this->configs['default'] ?? $this->defaultDriver;

        $this->config                            = array_merge($config, $this->configs['drivers'][$this->defaultDriver]);
        $this->config['common_expire_time']      = $this->configs['expire_time'];
        $this->config['common_refresh_keep_ttl'] = $this->configs['refresh_keep_ttl'];
    }

    public static function getInstance(?array $config = []): self
    {
        return new self($config);
    }

    /**
     * 获取驱动句柄
     * @param string|null $name
     * @return object
     */
    public function getDriver(?string $name = null): object
    {
        if (!is_null($this->handler)) {
            return $this->handler;
        }
        $name = $name ?: $this->defaultDriver;


        if (is_null($name)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unable to resolve NULL driver for [%s].',
                    static::class
                )
            );
        }

        return $this->createDriver($name);
    }

    /**
     * 获取驱动配置参数
     * @param $name
     * @return array
     */
    protected function resolveParams($name): array
    {
        $config = $this->getDriverConfig($name);
        return [$config];
    }


    /**
     * 获取驱动配置
     * @param string      $store
     * @param string|null $name
     * @param null        $default
     * @return array|string
     */
    protected function getDriverConfig(string $driver, ?string $name = null, $default = null): array|string
    {
        if ($config = $this->config) {

            return Arr::get($config, $name, $default);
        }

        throw new InvalidArgumentException("Driver [$driver] not found.");
    }

    /**
     * 获取驱动类型
     * @param string $name
     * @return string
     */
    protected function resolveType(string $name): string
    {
        return $this->getDriverConfig($name, 'type', 'Mysql');
    }

    /**
     * 创建驱动句柄
     * @param string $name
     * @return object
     */
    protected function createDriver(string $name): object
    {
        $type = $this->resolveType($name);

        $method = 'create' . Str::studly($type) . 'Driver';

        $params = $this->resolveParams($name);

        if (method_exists($this, $method)) {
            return $this->$method(...$params);
        }

        $class = $this->resolveClass($type);

        if (isset($this->instance[$type])) {
            return $this->instance[$type];
        }

        return new $class(...$params);
    }


    /**
     * 默认驱动
     * @return string
     */
    protected function getDefaultDriver(): string
    {
        return $this->config['default'];
    }

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

    public function driver(string $name = null): TokenInterface
    {
        $name = $name ?: $this->defaultDriver;

        if (!isset($this->drivers[$name])) {
            throw new RuntimeException("Token driver [{$name}] not found.");
        }

        $config = $this->config['drivers'][$name] ?? [];

        return $this->drivers[$name]($config);
    }


    public function encode(array $payload = [], bool $keep = false):string
    {
        return $this->getDriver()->encode($payload, $keep);
    }

    /**
     *
     * By albert  2025/05/03 08:54:23
     * @param string $token
     * @return bool|stdClass
     */
    public function verify(string $token):stdClass
    {
        return $this->getDriver()->verify($token);
    }

    public function decode(string $token):stdClass
    {
        return $this->getDriver()->decode($token);
    }

    /**
     *
     * By albert  2025/05/03 08:54:04
     * @param string $token
     * @return bool
     */
    public function destroy(string $token): bool
    {
        return $this->getDriver()->destroy($token);
    }


    public function refresh(string $token): string
    {
        return $this->getDriver()->refresh($token);
    }

    public function expired(string $token): bool
    {
        return $this->getDriver()->expired($token);
    }


}
