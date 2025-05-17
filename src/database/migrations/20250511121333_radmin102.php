<?php
/**
 * File:        20250511121333_radmin102.php
 * Author:      albert <albert@rocareer.com>
 * Created:     2025/5/11 22:52
 * Description:
 *
 * Copyright [2014-2026] [https://rocareer.com]
 * Licensed under the Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 */

use Phinx\Migration\AbstractMigration;
use plugin\radmin\app\admin\model\Config;

class Radmin102 extends AbstractMigration
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

        $authentication = Config::where([
            'name'  => 'expire_time',
            'group' => 'authentication',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'expire_time',
                'group'     => 'authentication',
                'title'     => 'Token有效期(秒)',
                'tip'       => '单位秒,安全起见不要设置太长',
                'type'      => 'number',
                'value'     => '3600',
                'content'   => '',
                'rule'      => 'required,integer',
                'extend'    => '',
                'allow_del' => 0,
                'weigh'     => 965
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }

        $authentication = Config::where([
            'name'  => 'keep_time',
            'group' => 'authentication',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'keep_time',
                'group'     => 'authentication',
                'title'     => '保持会话时间(秒)',
                'tip'       => '单位秒,默认7天',
                'type'      => 'number',
                'value'     => '604800',
                'content'   => '',
                'rule'      => 'required,integer',
                'extend'    => '',
                'allow_del' => 0,
                'weigh'     => 960
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }

        $authentication = Config::where([
            'name'  => 'algo',
            'group' => 'authentication',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'algo',
                'group'     => 'authentication',
                'title'     => '加密方式',
                'tip'       => '哈希算法,不推荐MD5',
                'type'      => 'radio',
                'value'     => 'sha256',
                'content'   => '{"md5 ":"MD5","sha256":"SHA-256","whirlpool":"Whirlpool","ripemd256":"RIPEMD-256","gost":"GOST"}',
                'rule'      => 'required,integer',
                'extend'    => '',
                'allow_del' => 0,
                'weigh'     => 971
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }


        $authentication = Config::where([
            'name'  => 'jwt_algo',
            'group' => 'authentication',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'jwt_algo',
                'group'     => 'authentication',
                'title'     => 'JWT签名算法',
                'tip'       => 'JWT签名算法,暂不支持RS非对称加密',
                'type'      => 'radio',
                'value'     => 'HS256',
                'content'   => '{"HS256":"HS256","HS384":"HS384","HS512":"HS512"}',
                'rule'      => 'required,integer',
                'extend'    => '',
                'allow_del' => 0,
                'weigh'     => 980
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }


        $authentication = Config::where([
            'name'  => 'secret',
            'group' => 'authentication',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'secret',
                'group'     => 'authentication',
                'title'     => '加密密钥',
                'tip'       => '密钥字串',
                'type'      => 'password',
                'value'     => 'jp9S^mtu^!6)(iGr_Xqwe^PstooaJRyMcPAYgyfo+bDKg%z*$JivrY0vz_waCrV*Arx@0+60zBU8L50tacPG1zTq12mGalZ9qa%tktUPj)%EAv2fjCBsWgSl*Pz&@9!dpR0hXl1e2El*%DwJS#xeOIkyOUv*6G@OI9XCumlyBxtwYn8E^pyVP9IJHTzq^#E8p#SS%tPRNsiF1IE@I$hnCbRSd5AjERg#++^palDcyjav8qKh*!GXUWrtuH@W(4)S',
                'content'   => '',
                'rule'      => 'required',
                'extend'    => '',
                'allow_del' => 0,
                'weigh'     => 970
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }

        $authentication = Config::where([
            'name'  => 'jwt_secret',
            'group' => 'authentication',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'jwt_secret',
                'group'     => 'authentication',
                'title'     => 'JWT加密密钥',
                'tip'       => 'JWT加密密钥',
                'type'      => 'password',
                'value'     => 'P3nP5cLURe!RQeOTzjNPAVNEs&8a4(2o$Eh(nsT3IAOtyLf9te1tAlyLx45gWLMaN5kxnx1C&wrWcf)xTxi97bGhrBADum0EF$EP_E$F503bT^Srq8vP6Vuh%4&wgHohI8LGX@dksEKnOQW#Gv181_Tp@dwXtO*5HNlv)RdS06k2DGI+V24$jA%i!gL$EGw16%i_szlLDyP!CLyzkr(ygy8mXxLr)aXXurMln5H)W4___EfNYOIoSH7W1HNor&lS',
                'content'   => '',
                'rule'      => 'required',
                'extend'    => '',
                'allow_del' => 0,
                'weigh'     => 979
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }


        $authentication = Config::where([
            'name'  => 'iss',
            'group' => 'authentication',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'iss',
                'group'     => 'authentication',
                'title'     => '签发者标识',
                'tip'       => '签发者标识',
                'type'      => 'string',
                'value'     => 'Radmin',
                'content'   => '',
                'rule'      => 'required',
                'extend'    => '',
                'allow_del' => 0,
                'weigh'     => 9999
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }

        $authentication = Config::where([
            'name'  => 'allow_keys',
            'group' => 'authentication',
        ])->find();
        if (!$authentication) {
            // 插入新记录到 ra_config 表
            $data = [
                'name'      => 'allow_keys',
                'group'     => 'authentication',
                'title'     => '允许的字段',
                'tip'       => '允许的字段',
                'type'      => 'array',
                'value'     => '[{"key":"iss","value":"\u7b7e\u53d1\u8005"},{"key":"sub","value":"\u7528\u6237 ID"},{"key":"exp","value":"\u8fc7\u671f\u65f6\u95f4"},{"key":"iat","value":"\u7b7e\u53d1\u65f6\u95f4"},{"key":"jti","value":"\u552f\u4e00\u6807\u8bc6\u7b26"},{"key":"roles","value":"\u7528\u6237\u89d2\u8272"},{"key":"type","value":"TOKEN\u7c7b\u578b"},{"key":"role","value":"\u4e25\u683c\u89d2\u8272"}]',
                'content'   => '',
                'rule'      => 'required',
                'extend'    => '',
                'allow_del' => 0,
                'weigh'     => 9999
            ];
            // 使用 Db 类插入新数据
            Config::insert($data);
        }

    }
}
