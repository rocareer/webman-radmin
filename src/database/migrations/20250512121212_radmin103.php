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
use plugin\radmin\support\Log;
use Phinx\Migration\AbstractMigration;

class Radmin103 extends AbstractMigration
{
    /**
     * @throws Throwable
     */
    public function up()
    {
        $this->addAuthentication();
        $this->insertData();
        $this->insertHost();
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
                if (isset($item['key']) && $item['key'] === 'data') {
                    $keyExists = true;
                    break;
                }
            }

            // 只有在不存在相同 key 的情况下才追加新数据
            if (!$keyExists) {
                // 追加新数据
                $value[] = [
                    'key'   => 'terminal',
                    'value' => '自定义命令'
                ];
                $value[] = [
                    'key'   => 'system',
                    'value' => '系统配置'
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

    }

    public function insertHost()
    {
        $authentication = Config::where([
            'name'  => 'host',
            'group' => 'system',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'host',
                'group'     => 'system',
                'title'     => '后端主机地址',
                'tip'       => '不能为空,否则命令行文件生成等功能异常',
                'type'      => 'string',
                'value'     => 'http://localhost:9696',
                'content'   => '',
                'rule'      => '',
                'extend'    => '{"baInputExtend":{"placeholder":"\u4e0d\u80fd\u4e3a\u7a7a,\u5426\u5219\u547d\u4ee4\u884c\u6587\u4ef6\u751f\u6210\u7b49\u529f\u80fd\u5f02\u5e38"}}',
                'allow_del' => 0,
                'weigh'     => 0
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }
    }

    public function insertData()
    {
        $config = Config::where([
            'name'  => 'data',
            'group' => 'terminal',
        ])->find();
        if (!$config) {
            // 插入新记录到 ra_config 表
            $data[] = [
                'name'      => 'backup_path',
                'group'     => 'system',
                'title'     => '备份路径',
                'tip'       => '备份所在相对路径',
                'type'      => 'string',
                'value'     => '/backup/',
                'content'   => '',
                'rule'      => 'required',
                'extend'    => '{"baInputExtend":{"placeholder":"\u9ed8\u8ba4\u662f\u5728runtime\/backup"}}',
                'allow_del' => 0,
                'weigh'     => 0
            ];
            // 使用 Db 类插入新数据
            Config::insertAll($data);
        }
    }
}
