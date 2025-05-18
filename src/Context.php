<?php

namespace Radmin;

/**
 * 成员上下文管理器
 *
 * 用于管理成员实例的生命周期
 */
class Context
{
    protected array $data = [];

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function clear(): void
    {
        $this->data = []; // 清空数据
    }
}