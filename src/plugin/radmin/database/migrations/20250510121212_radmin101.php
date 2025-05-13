<?php
/**
 * File:        20250510121212_radmin101.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 05:00
 * Description: v101 数据升级
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

use plugin\radmin\app\admin\model\Config;
use support\Log;
use Phinx\Migration\AbstractMigration;

class Radmin101 extends AbstractMigration
{
    /**
     * @throws Throwable
     */
    public function up()
    {
        $this->addAuthentication();
    }

    /**
     * 添加鉴权配置项
     * @return   void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Author:   albert <albert@rocareer.com>
     * Time:     2025/5/11 04:59
     */
    public function addAuthentication()
    {
        $config = Config::where('name', 'config_group')->find();
        // 确保 $config 存在
        if ($config) {
            // 检查 $config->value 是否是字符串
            if (is_string($config->value)) {
                // 解码 JSON 字符串为数组
                $value = json_decode($config->value, true);

                // 检查解码结果
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // 处理 JSON 解码错误
                    throw new \Exception('JSON decode error: ' . json_last_error_msg());
                }
            } else {
                // 如果不是字符串，直接使用它
                $value = $config->value; // 假设它已经是一个数组
            }

            // 确保 $value 是数组
            if (!is_array($value)) {
                $value = []; // 如果不是数组，则初始化为一个空数组
            }

            // 检查是否已经存在相同的 key
            $keyExists = false;
            foreach ($value as $item) {
                if (isset($item['key']) && $item['key'] === 'authentication') {
                    $keyExists = true;
                    break;
                }
            }

            // 只有在不存在相同 key 的情况下才追加新数据
            if (!$keyExists) {
                // 追加新数据
                $value[] = [
                    'key' => 'authentication',
                    'value' => 'Authentication'
                ];
            }

            // 将数组编码为 JSON 字符串
            $value = json_encode($value);

            // 更新数据库
            Config::where('name', 'config_group')->update(
                [
                    'value' => $value,
                ]
            );

            Log::info('migrate', ['value' => $value]); // 记录更新后的值
        } else {
            Log::error('Config not found for "config_group"');
        }

        $authentication=Config::where([
            'name' => 'driver',
            'group' => 'authentication',
        ])->find();
        if (!$authentication){
            // 插入新记录到 ra_config 表
            $data = [
                'name' => 'driver',
                'group' => 'authentication',
                'title' => '驱动类型',
                'tip' => '默认驱动类型',
                'type' => 'radio',
                'value' => 'jwt',
                'content' => json_encode([
                    "jwt" => "Jwt",
                    "cache" => "Cache",
                    "mysql" => "Mysql",
                    "redis" => "Redis"
                ]),
                'rule' => '',
                'extend' => '',
                'allow_del' => 1,
                'weigh' => 0
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }
    }
}
