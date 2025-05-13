<?php

namespace database\migrations;

use support\think\Db;

class FullMigration
{
    public function up()
    {
        Db::table('ra_aaa')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('status')->notNull()->default('1');
            $table->string('create_time');
        });
        Db::table('ra_aaa')->insert(array (
  0 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  1 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  2 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  3 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  4 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  5 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  6 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  7 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  8 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  9 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  10 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
  11 => 
  array (
    'id' => 1,
    'status' => 0,
    'create_time' => 1746738672,
  ),
));
        Db::table('ra_admin')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('username')->notNull()->default('');
            $table->string('nickname')->notNull()->default('');
            $table->string('avatar')->notNull()->default('');
            $table->string('email')->notNull()->default('');
            $table->string('mobile')->notNull()->default('');
            $table->string('login_failure')->notNull()->default('0');
            $table->string('last_login_time');
            $table->string('last_login_ip')->notNull()->default('');
            $table->string('password')->notNull()->default('');
            $table->string('salt')->notNull()->default('');
            $table->string('motto')->notNull()->default('');
            $table->string('status')->notNull()->default('');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_admin')->insert(array (
  0 => 
  array (
    'id' => 1,
    'username' => 'admin',
    'nickname' => 'Admin',
    'avatar' => '/storage/default/20250509/logo43240f82cba37fb6e1b097ecff178023af8e6383.png',
    'email' => 'admin@buildadmin.com',
    'mobile' => '18888888888',
    'login_failure' => 0,
    'last_login_time' => 1747009552,
    'last_login_ip' => '127.0.0.1',
    'password' => '$2y$12$U7YW4Df9p/T2NsHmUE82tOGaVhpj4/tBBEQjzU7jcEstVdT1vcvH6',
    'salt' => 'aFxSeLNqEodr5cMG',
    'motto' => '1',
    'status' => 'enable',
    'update_time' => 1747009552,
    'create_time' => 1746723960,
  ),
));
        Db::table('ra_admin_group')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('pid')->notNull()->default('0');
            $table->string('name')->notNull()->default('');
            $table->text('rules');
            $table->string('status')->notNull()->default('1');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_admin_group')->insert(array (
  0 => 
  array (
    'id' => 1,
    'pid' => 0,
    'name' => '超级管理组',
    'rules' => '*',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  1 => 
  array (
    'id' => 2,
    'pid' => 1,
    'name' => '一级管理员',
    'rules' => '1,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,77,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,89',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  2 => 
  array (
    'id' => 3,
    'pid' => 2,
    'name' => '二级管理员',
    'rules' => '21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  3 => 
  array (
    'id' => 4,
    'pid' => 3,
    'name' => '三级管理员',
    'rules' => '55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
));
        Db::table('ra_admin_group_access')->create(function ($table) {
            $table->string('uid')->notNull();
            $table->string('group_id')->notNull();
        });
        Db::table('ra_admin_group_access')->insert(array (
  0 => 
  array (
    'uid' => 1,
    'group_id' => 1,
  ),
));
        Db::table('ra_admin_log')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('admin_id')->notNull()->default('0');
            $table->string('username')->notNull()->default('');
            $table->string('url')->notNull()->default('');
            $table->string('title')->notNull()->default('');
            $table->string('data');
            $table->string('ip')->notNull()->default('');
            $table->string('useragent')->notNull()->default('');
            $table->string('create_time');
        });
        Db::table('ra_admin_log')->insert(array (
  0 => 
  array (
    'id' => 209,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"bb23ece4-7f3c-4c2c-840e-e69b993db17e","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746984599,
  ),
  1 => 
  array (
    'id' => 210,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"67bfe750-24a6-4ae0-b22a-ea3ec92cb78e","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746984632,
  ),
  2 => 
  array (
    'id' => 211,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"95ef4880-a964-4832-a23c-027b21d7b864","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746989288,
  ),
  3 => 
  array (
    'id' => 212,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"95ef4880-a964-4832-a23c-027b21d7b864","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746989298,
  ),
  4 => 
  array (
    'id' => 213,
    'admin_id' => 0,
    'username' => 'albert',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"albert","password":"***","keep":true,"captchaId":"95ef4880-a964-4832-a23c-027b21d7b864","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746989306,
  ),
  5 => 
  array (
    'id' => 214,
    'admin_id' => 0,
    'username' => 'albert',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"albert","password":"***","keep":true,"captchaId":"95ef4880-a964-4832-a23c-027b21d7b864","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746989351,
  ),
  6 => 
  array (
    'id' => 215,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"39218285-3c25-47de-8d3b-0d85cb5683bb","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746989388,
  ),
  7 => 
  array (
    'id' => 216,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"iss":"Radmin","driver":"jwt","jwt_algo":"HS256","jwt_secret":"P3nP5cLURe!RQeOTzjNPAVNEs&8a4(2o$Eh(nsT3IAOtyLf9te1tAlyLx45gWLMaN5kxnx1C&wrWcf)xTxi97bGhrBADum0EF$EP_E$F503bT^Srq8vP6Vuh%4&wgHohI8LGX@dksEKnOQW#Gv181_Tp@dwXtO*5HNlv)RdS06k2DGI+V24$jA%i!gL$EGw16%i_szlLDyP!CLyzkr(ygy8mXxLr)aXXurMln5H)W4___EfNYOIoSH7W1HNor&lS","algo":"sha256","secret":"jp9S^mtu^!6)(iGr_Xqwe^PstooaJRyMcPAYgyfo+bDKg%z*$JivrY0vz_waCrV*Arx@0+60zBU8L50tacPG1zTq12mGalZ9qa%tktUPj)%EAv2fjCBsWgSl*Pz&@9!dpR0hXl1e2El*%DwJS#xeOIkyOUv*6G@OI9XCumlyBxtwYn8E^pyVP9IJHTzq^#E8p#SS%tPRNsiF1IE@I$hnCbRSd5AjERg#++^palDcyjav8qKh*!GXUWrtuH@W(4)S","expire_time":10,"keep_time":604800,"allow_keys":[{"key":"iss","value":"\\u7b7e\\u53d1\\u8005"},{"key":"sub","value":"\\u7528\\u6237 ID"},{"key":"exp","value":"\\u8fc7\\u671f\\u65f6\\u95f4"},{"key":"iat","value":"\\u7b7e\\u53d1\\u65f6\\u95f4"},{"key":"jti","value":"\\u552f\\u4e00\\u6807\\u8bc6\\u7b26"},{"key":"roles","value":"\\u7528\\u6237\\u89d2\\u8272"},{"key":"type","value":"TOKEN\\u7c7b\\u578b"},{"key":"role","value":"\\u4e25\\u683c\\u89d2\\u8272"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746989736,
  ),
  8 => 
  array (
    'id' => 217,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"iss":"Radmin","driver":"jwt","jwt_algo":"HS256","jwt_secret":"P3nP5cLURe!RQeOTzjNPAVNEs&8a4(2o$Eh(nsT3IAOtyLf9te1tAlyLx45gWLMaN5kxnx1C&wrWcf)xTxi97bGhrBADum0EF$EP_E$F503bT^Srq8vP6Vuh%4&wgHohI8LGX@dksEKnOQW#Gv181_Tp@dwXtO*5HNlv)RdS06k2DGI+V24$jA%i!gL$EGw16%i_szlLDyP!CLyzkr(ygy8mXxLr)aXXurMln5H)W4___EfNYOIoSH7W1HNor&lS","algo":"sha256","secret":"jp9S^mtu^!6)(iGr_Xqwe^PstooaJRyMcPAYgyfo+bDKg%z*$JivrY0vz_waCrV*Arx@0+60zBU8L50tacPG1zTq12mGalZ9qa%tktUPj)%EAv2fjCBsWgSl*Pz&@9!dpR0hXl1e2El*%DwJS#xeOIkyOUv*6G@OI9XCumlyBxtwYn8E^pyVP9IJHTzq^#E8p#SS%tPRNsiF1IE@I$hnCbRSd5AjERg#++^palDcyjav8qKh*!GXUWrtuH@W(4)S","expire_time":10,"keep_time":604800,"allow_keys":[{"key":"iss","value":"\\u7b7e\\u53d1\\u8005"},{"key":"sub","value":"\\u7528\\u6237 ID"},{"key":"exp","value":"\\u8fc7\\u671f\\u65f6\\u95f4"},{"key":"iat","value":"\\u7b7e\\u53d1\\u65f6\\u95f4"},{"key":"jti","value":"\\u552f\\u4e00\\u6807\\u8bc6\\u7b26"},{"key":"roles","value":"\\u7528\\u6237\\u89d2\\u8272"},{"key":"type","value":"TOKEN\\u7c7b\\u578b"},{"key":"role","value":"\\u4e25\\u683c\\u89d2\\u8272"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746989741,
  ),
  9 => 
  array (
    'id' => 218,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"iss":"Radmin","driver":"jwt","jwt_algo":"HS256","jwt_secret":"P3nP5cLURe!RQeOTzjNPAVNEs&8a4(2o$Eh(nsT3IAOtyLf9te1tAlyLx45gWLMaN5kxnx1C&wrWcf)xTxi97bGhrBADum0EF$EP_E$F503bT^Srq8vP6Vuh%4&wgHohI8LGX@dksEKnOQW#Gv181_Tp@dwXtO*5HNlv)RdS06k2DGI+V24$jA%i!gL$EGw16%i_szlLDyP!CLyzkr(ygy8mXxLr)aXXurMln5H)W4___EfNYOIoSH7W1HNor&lS","algo":"sha256","secret":"jp9S^mtu^!6)(iGr_Xqwe^PstooaJRyMcPAYgyfo+bDKg%z*$JivrY0vz_waCrV*Arx@0+60zBU8L50tacPG1zTq12mGalZ9qa%tktUPj)%EAv2fjCBsWgSl*Pz&@9!dpR0hXl1e2El*%DwJS#xeOIkyOUv*6G@OI9XCumlyBxtwYn8E^pyVP9IJHTzq^#E8p#SS%tPRNsiF1IE@I$hnCbRSd5AjERg#++^palDcyjav8qKh*!GXUWrtuH@W(4)S","expire_time":10,"keep_time":604800,"allow_keys":[{"key":"iss","value":"\\u7b7e\\u53d1\\u8005"},{"key":"sub","value":"\\u7528\\u6237 ID"},{"key":"exp","value":"\\u8fc7\\u671f\\u65f6\\u95f4"},{"key":"iat","value":"\\u7b7e\\u53d1\\u65f6\\u95f4"},{"key":"jti","value":"\\u552f\\u4e00\\u6807\\u8bc6\\u7b26"},{"key":"roles","value":"\\u7528\\u6237\\u89d2\\u8272"},{"key":"type","value":"TOKEN\\u7c7b\\u578b"},{"key":"role","value":"\\u4e25\\u683c\\u89d2\\u8272"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746989742,
  ),
  10 => 
  array (
    'id' => 219,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"iss":"Radmin","driver":"jwt","jwt_algo":"HS256","jwt_secret":"P3nP5cLURe!RQeOTzjNPAVNEs&8a4(2o$Eh(nsT3IAOtyLf9te1tAlyLx45gWLMaN5kxnx1C&wrWcf)xTxi97bGhrBADum0EF$EP_E$F503bT^Srq8vP6Vuh%4&wgHohI8LGX@dksEKnOQW#Gv181_Tp@dwXtO*5HNlv)RdS06k2DGI+V24$jA%i!gL$EGw16%i_szlLDyP!CLyzkr(ygy8mXxLr)aXXurMln5H)W4___EfNYOIoSH7W1HNor&lS","algo":"sha256","secret":"jp9S^mtu^!6)(iGr_Xqwe^PstooaJRyMcPAYgyfo+bDKg%z*$JivrY0vz_waCrV*Arx@0+60zBU8L50tacPG1zTq12mGalZ9qa%tktUPj)%EAv2fjCBsWgSl*Pz&@9!dpR0hXl1e2El*%DwJS#xeOIkyOUv*6G@OI9XCumlyBxtwYn8E^pyVP9IJHTzq^#E8p#SS%tPRNsiF1IE@I$hnCbRSd5AjERg#++^palDcyjav8qKh*!GXUWrtuH@W(4)S","expire_time":20,"keep_time":604800,"allow_keys":[{"key":"iss","value":"\\u7b7e\\u53d1\\u8005"},{"key":"sub","value":"\\u7528\\u6237 ID"},{"key":"exp","value":"\\u8fc7\\u671f\\u65f6\\u95f4"},{"key":"iat","value":"\\u7b7e\\u53d1\\u65f6\\u95f4"},{"key":"jti","value":"\\u552f\\u4e00\\u6807\\u8bc6\\u7b26"},{"key":"roles","value":"\\u7528\\u6237\\u89d2\\u8272"},{"key":"type","value":"TOKEN\\u7c7b\\u578b"},{"key":"role","value":"\\u4e25\\u683c\\u89d2\\u8272"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746989749,
  ),
  11 => 
  array (
    'id' => 220,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"0be25204-f988-4563-be2c-d857bab6cb32","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746990495,
  ),
  12 => 
  array (
    'id' => 221,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"972dd4bb-7906-47d0-9d78-46d5bf094c0d","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746990612,
  ),
  13 => 
  array (
    'id' => 222,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"53e95787-9bb8-46d6-a100-f365166b71f7","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746990868,
  ),
  14 => 
  array (
    'id' => 223,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"d2e42a14-8d94-492a-a754-dc1c012fe277","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746991112,
  ),
  15 => 
  array (
    'id' => 224,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"d2e42a14-8d94-492a-a754-dc1c012fe277","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746991116,
  ),
  16 => 
  array (
    'id' => 225,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"d2e42a14-8d94-492a-a754-dc1c012fe277","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746991721,
  ),
  17 => 
  array (
    'id' => 226,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"6a2f1226-3987-4969-8119-e2736fce02d3","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746991754,
  ),
  18 => 
  array (
    'id' => 227,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"1bf5f9e1-831f-498b-b5e8-3104d0193fb7","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746991874,
  ),
  19 => 
  array (
    'id' => 228,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"25026a01-4687-4bd1-9020-1a16f1029b7b","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746992219,
  ),
  20 => 
  array (
    'id' => 229,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"c408eac2-35a4-47c1-a673-cdb624eca43a","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746992248,
  ),
  21 => 
  array (
    'id' => 230,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"19d51f14-1d59-45d5-9fd3-4e9fda031b00","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746992283,
  ),
  22 => 
  array (
    'id' => 231,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"8c8c773f-bb48-4214-93e3-d5b0aea26004","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746992406,
  ),
  23 => 
  array (
    'id' => 232,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"09fc8de3-3f3b-466a-a387-9de451db405e","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746994167,
  ),
  24 => 
  array (
    'id' => 233,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995756,
  ),
  25 => 
  array (
    'id' => 234,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995766,
  ),
  26 => 
  array (
    'id' => 235,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"\\u5c31","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995770,
  ),
  27 => 
  array (
    'id' => 236,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"5879c9ff-5420-46f7-aab4-3141fb982a40","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995837,
  ),
  28 => 
  array (
    'id' => 237,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995844,
  ),
  29 => 
  array (
    'id' => 238,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995846,
  ),
  30 => 
  array (
    'id' => 239,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"ss","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995852,
  ),
  31 => 
  array (
    'id' => 240,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"ss","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995853,
  ),
  32 => 
  array (
    'id' => 241,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995860,
  ),
  33 => 
  array (
    'id' => 242,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"Basics"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995862,
  ),
  34 => 
  array (
    'id' => 243,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"Mail"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995876,
  ),
  35 => 
  array (
    'id' => 244,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"\\u90ae\\u4ef6\\u914d\\u7f6e"},{"key":"authentication","value":"Authentication"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995890,
  ),
  36 => 
  array (
    'id' => 245,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"\\u90ae\\u4ef6\\u914d\\u7f6e"},{"key":"authentication","value":"\\u9274\\u6743\\u914d\\u7f6e"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746995898,
  ),
  37 => 
  array (
    'id' => 246,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"\\u90ae\\u4ef6\\u914d\\u7f6e"},{"key":"authentication","value":"\\u9274\\u6743\\u914d\\u7f6e"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746995908,
  ),
  38 => 
  array (
    'id' => 247,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"\\u90ae\\u4ef6\\u914d\\u7f6e"},{"key":"authentication","value":"\\u9274\\u6743\\u914d\\u7f6e"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746995917,
  ),
  39 => 
  array (
    'id' => 248,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"\\u90ae\\u4ef6\\u914d\\u7f6e"},{"key":"authentication","value":"\\u9274\\u6743\\u914d\\u7f6e"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746999771,
  ),
  40 => 
  array (
    'id' => 249,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"\\u90ae\\u4ef6\\u914d\\u7f6e"},{"key":"authentication","value":"\\u9274\\u6743\\u914d\\u7f6e"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746999772,
  ),
  41 => 
  array (
    'id' => 250,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/routine/Config/edit',
    'title' => '系统配置-编辑',
    'data' => '{"site_name":"RAdmin","backend_entrance":"\\/admin","record_number":"\\u6e1dICP\\u59078888888\\u53f7-1","version":"v1.0.0","time_zone":"Asia\\/Shanghai","no_access_ip":"","config_group":[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"\\u90ae\\u4ef6\\u914d\\u7f6e"},{"key":"authentication","value":"\\u9274\\u6743\\u914d\\u7f6e"}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746999773,
  ),
  42 => 
  array (
    'id' => 251,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/ajax/upload',
    'title' => '上传文件',
    'data' => '{"uuid":"920b4451-4380-40f5-8877-f1cd6f49ec49"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746999847,
  ),
  43 => 
  array (
    'id' => 252,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/add',
    'title' => '会员管理-添加',
    'data' => '{"gender":0,"money":"0","score":"0","status":"enable","group_id":"1","username":"user2","nickname":"user2","avatar":"\\/storage\\/default\\/20250512\\/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg","password":"***"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746999854,
  ),
  44 => 
  array (
    'id' => 253,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/Group/add',
    'title' => '会员分组管理-添加',
    'data' => '{"status":1,"name":"\\u6d4b\\u8bd5\\u7ec4","rules":[2,3,4,1]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1746999916,
  ),
  45 => 
  array (
    'id' => 254,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"0ad127ba-972c-4108-b8a4-4f7316ad4616","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.4 Safari/605.1.15',
    'create_time' => 1747000164,
  ),
  46 => 
  array (
    'id' => 255,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/Group/edit',
    'title' => '会员分组管理-编辑',
    'data' => '{"id":2,"name":"\\u6d4b\\u8bd5\\u7ec4","rules":[2,3,4,1],"status":"1","update_time":"2025-05-12 05:45:16","create_time":"2025-05-12 05:45:16"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747000643,
  ),
  47 => 
  array (
    'id' => 256,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/Group/add',
    'title' => '会员分组管理-添加',
    'data' => '{"status":1,"name":"\\u6d4b\\u8bd5\\u7ec42","rules":[1,2,3,4,5,6]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747000745,
  ),
  48 => 
  array (
    'id' => 257,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"ctest","connection":"","webViewsDir":"web\\/src\\/views\\/backend\\/ctest","controllerFile":"app\\/admin\\/controller\\/Ctest.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747001134,
  ),
  49 => 
  array (
    'id' => 258,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generate',
    'title' => 'CRUD代码生成-生成',
    'data' => '{"type":"create","table":{"name":"ctest","comment":"ctset","quickSearchField":["id"],"defaultSortField":"id","formFields":["remark","user_group_id"],"columnFields":["id"],"defaultSortType":"desc","generateRelativePath":"ctest","isCommonModel":0,"modelFile":"app\\/admin\\/model\\/Ctest.php","controllerFile":"app\\/admin\\/controller\\/Ctest.php","validateFile":"app\\/admin\\/validate\\/Ctest.php","webViewsDir":"web\\/src\\/views\\/backend\\/ctest","databaseConnection":"","designChange":[],"rebuild":"No"},"fields":[{"title":"\\u4e3b\\u952e","name":"id","comment":"ID","designType":"pk","formBuildExclude":true,"table":{"width":70,"operator":"RANGE","sortable":"custom"},"form":[],"type":"int","length":10,"precision":0,"defaultType":"NONE","null":false,"primaryKey":true,"unsigned":true,"autoIncrement":true},{"title":"\\u5907\\u6ce8","name":"remark","comment":"\\u5907\\u6ce8","designType":"textarea","tableBuildExclude":true,"table":{"operator":"false"},"form":{"validator":[],"validatorMsg":"","rows":3},"type":"varchar","length":255,"precision":0,"defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false},{"title":"\\u8fdc\\u7a0b\\u4e0b\\u62c9\\uff08\\u5173\\u8054\\u8868\\uff09","name":"user_group_id","comment":"\\u8fdc\\u7a0b\\u4e0b\\u62c9","designType":"remoteSelect","tableBuildExclude":true,"table":{"operator":"LIKE"},"form":{"validator":[],"validatorMsg":"","select-multi":false,"remote-pk":"id","remote-field":"name","remote-table":"user_group","remote-controller":"app\\/admin\\/controller\\/user\\/Group.php","remote-model":"app\\/admin\\/model\\/User.php","relation-fields":"name","remote-url":"","remote-primary-table-alias":"","remote-source-config-type":"crud"},"type":"int","length":10,"precision":0,"defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747001135,
  ),
  50 => 
  array (
    'id' => 259,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/logStart',
    'title' => 'CRUD代码生成-从历史记录开始',
    'data' => '{"id":2,"type":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747001401,
  ),
  51 => 
  array (
    'id' => 260,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"ggg","connection":"","webViewsDir":"web\\/src\\/views\\/backend\\/ggg","controllerFile":"app\\/admin\\/controller\\/Ggg.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747002498,
  ),
  52 => 
  array (
    'id' => 261,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generate',
    'title' => 'CRUD代码生成-生成',
    'data' => '{"type":"create","table":{"name":"ggg","comment":"ggg","quickSearchField":["id"],"defaultSortField":"id","formFields":["admin_id"],"columnFields":["id"],"defaultSortType":"desc","generateRelativePath":"ggg","isCommonModel":0,"modelFile":"app\\/admin\\/model\\/Ggg.php","controllerFile":"app\\/admin\\/controller\\/Ggg.php","validateFile":"app\\/admin\\/validate\\/Ggg.php","webViewsDir":"web\\/src\\/views\\/backend\\/ggg","databaseConnection":"","designChange":[],"rebuild":"No"},"fields":[{"title":"\\u4e3b\\u952e","name":"id","comment":"ID","designType":"pk","formBuildExclude":true,"table":{"width":70,"operator":"RANGE","sortable":"custom"},"form":[],"type":"int","length":10,"precision":0,"defaultType":"NONE","null":false,"primaryKey":true,"unsigned":true,"autoIncrement":true},{"title":"\\u8fdc\\u7a0b\\u4e0b\\u62c9\\uff08\\u5173\\u8054\\u8868\\uff09","name":"admin_id","comment":"\\u8fdc\\u7a0b\\u4e0b\\u62c9","designType":"remoteSelect","tableBuildExclude":true,"table":{"operator":"LIKE"},"form":{"validator":[],"validatorMsg":"","select-multi":false,"remote-pk":"id","remote-field":"username","remote-table":"admin","remote-controller":"app\\/admin\\/controller\\/auth\\/Admin.php","remote-model":"app\\/admin\\/model\\/Admin.php","relation-fields":"username","remote-url":"","remote-primary-table-alias":"","remote-source-config-type":"crud"},"type":"int","length":10,"precision":0,"defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747002499,
  ),
  53 => 
  array (
    'id' => 262,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"nnn","connection":"","webViewsDir":"web\\/src\\/views\\/backend\\/nnn","controllerFile":"app\\/admin\\/controller\\/Nnn.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747002687,
  ),
  54 => 
  array (
    'id' => 263,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generate',
    'title' => 'CRUD代码生成-生成',
    'data' => '{"type":"create","table":{"name":"nnn","comment":"","quickSearchField":["id"],"defaultSortField":"id","formFields":["admin_id"],"columnFields":["id"],"defaultSortType":"desc","generateRelativePath":"nnn","isCommonModel":0,"modelFile":"app\\/admin\\/model\\/Nnn.php","controllerFile":"app\\/admin\\/controller\\/Nnn.php","validateFile":"app\\/admin\\/validate\\/Nnn.php","webViewsDir":"web\\/src\\/views\\/backend\\/nnn","databaseConnection":"","designChange":[],"rebuild":"No"},"fields":[{"title":"\\u4e3b\\u952e","name":"id","comment":"ID","designType":"pk","formBuildExclude":true,"table":{"width":70,"operator":"RANGE","sortable":"custom"},"form":[],"type":"int","length":10,"precision":0,"defaultType":"NONE","null":false,"primaryKey":true,"unsigned":true,"autoIncrement":true},{"title":"\\u8fdc\\u7a0b\\u4e0b\\u62c9\\uff08\\u5173\\u8054\\u8868\\uff09","name":"admin_id","comment":"\\u8fdc\\u7a0b\\u4e0b\\u62c9","designType":"remoteSelect","tableBuildExclude":true,"table":{"operator":"LIKE"},"form":{"validator":[],"validatorMsg":"","select-multi":false,"remote-pk":"id","remote-field":"username","remote-table":"admin","remote-controller":"app\\/admin\\/controller\\/auth\\/Admin.php","remote-model":"app\\/admin\\/model\\/Admin.php","relation-fields":"username","remote-url":"","remote-primary-table-alias":"","remote-source-config-type":"crud"},"type":"int","length":10,"precision":0,"defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747002687,
  ),
  55 => 
  array (
    'id' => 264,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"sss","connection":"","webViewsDir":"web\\/src\\/views\\/backend\\/sss","controllerFile":"app\\/admin\\/controller\\/Sss.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747002775,
  ),
  56 => 
  array (
    'id' => 265,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generate',
    'title' => 'CRUD代码生成-生成',
    'data' => '{"type":"create","table":{"name":"sss","comment":"ddd","quickSearchField":["id"],"defaultSortField":"id","formFields":["admin_id"],"columnFields":["id"],"defaultSortType":"desc","generateRelativePath":"sss","isCommonModel":0,"modelFile":"app\\/admin\\/model\\/Sss.php","controllerFile":"app\\/admin\\/controller\\/Sss.php","validateFile":"app\\/admin\\/validate\\/Sss.php","webViewsDir":"web\\/src\\/views\\/backend\\/sss","databaseConnection":"","designChange":[],"rebuild":"No"},"fields":[{"title":"\\u4e3b\\u952e","name":"id","comment":"ID","designType":"pk","formBuildExclude":true,"table":{"width":70,"operator":"RANGE","sortable":"custom"},"form":[],"type":"int","length":10,"precision":0,"defaultType":"NONE","null":false,"primaryKey":true,"unsigned":true,"autoIncrement":true},{"title":"\\u8fdc\\u7a0b\\u4e0b\\u62c9\\uff08\\u5173\\u8054\\u8868\\uff09","name":"admin_id","comment":"\\u8fdc\\u7a0b\\u4e0b\\u62c9","designType":"remoteSelect","tableBuildExclude":true,"table":{"operator":"LIKE"},"form":{"validator":[],"validatorMsg":"","select-multi":false,"remote-pk":"id","remote-field":"username","remote-table":"admin","remote-controller":"app\\/admin\\/controller\\/auth\\/Admin.php","remote-model":"app\\/admin\\/model\\/Admin.php","relation-fields":"username","remote-url":"","remote-primary-table-alias":"","remote-source-config-type":"crud"},"type":"int","length":10,"precision":0,"defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747002775,
  ),
  57 => 
  array (
    'id' => 266,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/delete',
    'title' => 'CRUD代码生成-删除',
    'data' => '{"id":5}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003417,
  ),
  58 => 
  array (
    'id' => 267,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/delete',
    'title' => 'CRUD代码生成-删除',
    'data' => '{"id":4}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003419,
  ),
  59 => 
  array (
    'id' => 268,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/delete',
    'title' => 'CRUD代码生成-删除',
    'data' => '{"id":3}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003421,
  ),
  60 => 
  array (
    'id' => 269,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/delete',
    'title' => 'CRUD代码生成-删除',
    'data' => '{"id":2}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003423,
  ),
  61 => 
  array (
    'id' => 270,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"aaa","connection":"","webViewsDir":"web\\/src\\/views\\/backend\\/aaa","controllerFile":"app\\/admin\\/controller\\/Aaa.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003441,
  ),
  62 => 
  array (
    'id' => 271,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"18970462-4c5c-4767-b8fc-2a858aef962a","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003450,
  ),
  63 => 
  array (
    'id' => 272,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"sss","connection":"","webViewsDir":"web\\/src\\/views\\/backend\\/sss","controllerFile":"app\\/admin\\/controller\\/Sss.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003470,
  ),
  64 => 
  array (
    'id' => 273,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"2c277ecb-fa47-424b-8c4f-a260f01b9a30","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003518,
  ),
  65 => 
  array (
    'id' => 274,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"sss","connection":"","webViewsDir":"web\\/src\\/views\\/backend\\/sss","controllerFile":"app\\/admin\\/controller\\/Sss.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003532,
  ),
  66 => 
  array (
    'id' => 275,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generate',
    'title' => 'CRUD代码生成-生成',
    'data' => '{"type":"create","table":{"name":"sss","comment":"ssss","quickSearchField":["id"],"defaultSortField":"id","formFields":["remark","string"],"columnFields":["id","update_time","string"],"defaultSortType":"desc","generateRelativePath":"sss","isCommonModel":0,"modelFile":"app\\/admin\\/model\\/Sss.php","controllerFile":"app\\/admin\\/controller\\/Sss.php","validateFile":"app\\/admin\\/validate\\/Sss.php","webViewsDir":"web\\/src\\/views\\/backend\\/sss","databaseConnection":"","designChange":[],"rebuild":"No"},"fields":[{"title":"\\u4e3b\\u952e","name":"id","comment":"ID","designType":"pk","formBuildExclude":true,"table":{"width":70,"operator":"RANGE","sortable":"custom"},"form":[],"type":"int","length":10,"precision":0,"defaultType":"NONE","null":false,"primaryKey":true,"unsigned":true,"autoIncrement":true},{"title":"\\u5b57\\u7b26\\u4e32","name":"string","comment":"\\u5b57\\u7b26\\u4e32","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""},"type":"varchar","length":255,"precision":0,"defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false},{"title":"\\u5907\\u6ce8","name":"remark","comment":"\\u5907\\u6ce8","designType":"textarea","tableBuildExclude":true,"table":{"operator":"false"},"form":{"validator":[],"validatorMsg":"","rows":3},"type":"varchar","length":255,"precision":0,"defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false},{"title":"\\u4fee\\u6539\\u65f6\\u95f4","name":"update_time","comment":"\\u4fee\\u6539\\u65f6\\u95f4","designType":"timestamp","formBuildExclude":true,"table":{"render":"datetime","operator":"RANGE","sortable":"custom","width":160,"timeFormat":"yyyy-mm-dd hh:MM:ss"},"form":{"validator":["date"],"validatorMsg":""},"type":"bigint","length":16,"precision":0,"defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003533,
  ),
  67 => 
  array (
    'id' => 276,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/Sss/add',
    'title' => 'Unknown(add)',
    'data' => '{"string":"sss","remark":"sss"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003561,
  ),
  68 => 
  array (
    'id' => 277,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/Sss/add',
    'title' => 'Unknown(add)',
    'data' => '{"string":"aaa","remark":"aaa"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003567,
  ),
  69 => 
  array (
    'id' => 278,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/Sss/add',
    'title' => 'Unknown(add)',
    'data' => '{"string":"ddd","remark":"ddd"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003575,
  ),
  70 => 
  array (
    'id' => 279,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"ddd","connection":"","webViewsDir":"web\\/src\\/views\\/backend\\/ddd","controllerFile":"app\\/admin\\/controller\\/Ddd.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003624,
  ),
  71 => 
  array (
    'id' => 280,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generate',
    'title' => 'CRUD代码生成-生成',
    'data' => '{"type":"create","table":{"name":"ddd","comment":"ddd","quickSearchField":["id"],"defaultSortField":"id","formFields":["sss_id"],"columnFields":["id"],"defaultSortType":"desc","generateRelativePath":"ddd","isCommonModel":0,"modelFile":"app\\/admin\\/model\\/Ddd.php","controllerFile":"app\\/admin\\/controller\\/Ddd.php","validateFile":"app\\/admin\\/validate\\/Ddd.php","webViewsDir":"web\\/src\\/views\\/backend\\/ddd","databaseConnection":"","designChange":[],"rebuild":"No"},"fields":[{"title":"\\u4e3b\\u952e","name":"id","comment":"ID","designType":"pk","formBuildExclude":true,"table":{"width":70,"operator":"RANGE","sortable":"custom"},"form":[],"type":"int","length":10,"precision":0,"defaultType":"NONE","null":false,"primaryKey":true,"unsigned":true,"autoIncrement":true},{"title":"\\u8fdc\\u7a0b\\u4e0b\\u62c9\\uff08\\u5173\\u8054\\u8868\\uff09","name":"sss_id","comment":"\\u8fdc\\u7a0b\\u4e0b\\u62c9","designType":"remoteSelect","tableBuildExclude":true,"table":{"operator":"LIKE"},"form":{"validator":[],"validatorMsg":"","select-multi":false,"remote-pk":"id","remote-field":"string","remote-table":"sss","remote-controller":"app\\/admin\\/controller\\/Sss.php","remote-model":"app\\/admin\\/model\\/Sss.php","relation-fields":"string","remote-url":"","remote-primary-table-alias":"","remote-source-config-type":"crud"},"type":"int","length":10,"precision":0,"defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003624,
  ),
  72 => 
  array (
    'id' => 281,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"ef604f38-c224-4210-97c0-56d6306ab484","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003710,
  ),
  73 => 
  array (
    'id' => 282,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/Ddd/add',
    'title' => 'Unknown(add)',
    'data' => '{"sss_id":"2"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747003768,
  ),
  74 => 
  array (
    'id' => 283,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/add',
    'title' => '会员管理-添加',
    'data' => '{"gender":0,"money":"0","score":"0","status":"enable","group_id":"2","username":"aaa222","nickname":"aa222","password":"***"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747004166,
  ),
  75 => 
  array (
    'id' => 284,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"452b2599-2911-47d8-b0bc-8c4384eec5ae","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747004313,
  ),
  76 => 
  array (
    'id' => 285,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/parseFieldData',
    'title' => 'CRUD代码生成-解析字段数据',
    'data' => '{"type":"db","table":"user","sql":"","connection":"mysql"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747006910,
  ),
  77 => 
  array (
    'id' => 286,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"user","connection":"mysql","webViewsDir":"web\\/src\\/views\\/backend\\/user\\/user","controllerFile":"app\\/admin\\/controller\\/user\\/User.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747007042,
  ),
  78 => 
  array (
    'id' => 287,
    'admin_id' => 0,
    'username' => 'admin',
    'url' => '///admin/Index/login',
    'title' => 'Unknown(login)',
    'data' => '{"username":"admin","password":"***","keep":true,"captchaId":"65309c43-afdb-4b4d-b019-c7cedb8fcfe7","captchaInfo":""}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747007082,
  ),
  79 => 
  array (
    'id' => 288,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/parseFieldData',
    'title' => 'CRUD代码生成-解析字段数据',
    'data' => '{"type":"db","table":"user","sql":"","connection":"mysql"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747007090,
  ),
  80 => 
  array (
    'id' => 289,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generateCheck',
    'title' => 'CRUD代码生成-生成前预检',
    'data' => '{"table":"user","connection":"mysql","webViewsDir":"web\\/src\\/views\\/backend\\/user\\/user","controllerFile":"app\\/admin\\/controller\\/user\\/User.php"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747007093,
  ),
  81 => 
  array (
    'id' => 290,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/generate',
    'title' => 'CRUD代码生成-生成',
    'data' => '{"type":"db","table":{"name":"user","comment":"\\u4f1a\\u5458\\u8868","quickSearchField":["id"],"defaultSortField":"id","formFields":["group_id","username","nickname","email","mobile","avatar","gender","birthday","money","score","last_login_time","last_login_ip","login_failure","join_ip","join_time","motto","password","salt","status"],"columnFields":["id","group_id","username","nickname","email","mobile","avatar","gender","birthday","money","score","last_login_time","last_login_ip","login_failure","join_ip","join_time","motto","password","salt","status","update_time","create_time"],"defaultSortType":"desc","generateRelativePath":"user","isCommonModel":0,"modelFile":"app\\/admin\\/model\\/User.php","controllerFile":"app\\/admin\\/controller\\/user\\/User.php","validateFile":"app\\/admin\\/validate\\/User.php","webViewsDir":"web\\/src\\/views\\/backend\\/user\\/user","databaseConnection":"mysql","designChange":[],"rebuild":"No"},"fields":[{"name":"id","type":"int","dataType":"int","default":"","defaultType":"NONE","null":false,"primaryKey":true,"unsigned":true,"autoIncrement":true,"comment":"ID","designType":"pk","table":{"width":70,"operator":"RANGE","sortable":"custom"},"form":[]},{"name":"group_id","type":"int","dataType":"int","default":"0","defaultType":"INPUT","null":false,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u5206\\u7ec4ID","designType":"remoteSelect","table":{"operator":"LIKE"},"form":{"validator":[],"validatorMsg":"","select-multi":false,"remote-pk":"id","remote-field":"name","remote-table":"","remote-controller":"","remote-model":"","relation-fields":"","remote-url":"","remote-primary-table-alias":"","remote-source-config-type":""}},{"name":"username","type":"varchar","dataType":"varchar(32)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u7528\\u6237\\u540d","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"nickname","type":"varchar","dataType":"varchar(50)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u6635\\u79f0","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"email","type":"varchar","dataType":"varchar(50)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u90ae\\u7bb1","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"mobile","type":"varchar","dataType":"varchar(11)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u624b\\u673a","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"avatar","type":"varchar","dataType":"varchar(255)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u5934\\u50cf","designType":"image","table":{"render":"image","operator":"false"},"form":{"validator":[],"validatorMsg":"","image-multi":false}},{"name":"gender","type":"tinyint","dataType":"tinyint","default":"0","defaultType":"INPUT","null":false,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u6027\\u522b:0=\\u672a\\u77e5,1=\\u7537,2=\\u5973","designType":"number","table":{"render":"none","operator":"RANGE","sortable":"false"},"form":{"validator":["number"],"validatorMsg":"","step":1}},{"name":"birthday","type":"date","dataType":"date","default":"","defaultType":"NULL","null":true,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u751f\\u65e5","designType":"date","table":{"operator":"eq","sortable":"custom"},"form":{"validator":["date"],"validatorMsg":""}},{"name":"money","type":"int","dataType":"int","default":"0","defaultType":"INPUT","null":false,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u4f59\\u989d","designType":"number","table":{"render":"none","operator":"RANGE","sortable":"false"},"form":{"validator":["number"],"validatorMsg":"","step":1}},{"name":"score","type":"int","dataType":"int","default":"0","defaultType":"INPUT","null":false,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u79ef\\u5206","designType":"number","table":{"render":"none","operator":"RANGE","sortable":"false"},"form":{"validator":["number"],"validatorMsg":"","step":1}},{"name":"last_login_time","type":"bigint","dataType":"bigint","default":"","defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u4e0a\\u6b21\\u767b\\u5f55\\u65f6\\u95f4","designType":"number","table":{"render":"none","operator":"RANGE","sortable":"false"},"form":{"validator":["number"],"validatorMsg":"","step":1}},{"name":"last_login_ip","type":"varchar","dataType":"varchar(50)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u4e0a\\u6b21\\u767b\\u5f55IP","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"login_failure","type":"tinyint","dataType":"tinyint","default":"0","defaultType":"INPUT","null":false,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u767b\\u5f55\\u5931\\u8d25\\u6b21\\u6570","designType":"number","table":{"render":"none","operator":"RANGE","sortable":"false"},"form":{"validator":["number"],"validatorMsg":"","step":1}},{"name":"join_ip","type":"varchar","dataType":"varchar(50)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u52a0\\u5165IP","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"join_time","type":"bigint","dataType":"bigint","default":"","defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u52a0\\u5165\\u65f6\\u95f4","designType":"number","table":{"render":"none","operator":"RANGE","sortable":"false"},"form":{"validator":["number"],"validatorMsg":"","step":1}},{"name":"motto","type":"varchar","dataType":"varchar(255)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u7b7e\\u540d","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"password","type":"varchar","dataType":"varchar(255)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u5bc6\\u7801","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"salt","type":"varchar","dataType":"varchar(30)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u5bc6\\u7801\\u76d0\\uff08\\u5e9f\\u5f03\\u5f85\\u5220\\uff09","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"status","type":"varchar","dataType":"varchar(30)","default":"","defaultType":"EMPTY STRING","null":false,"primaryKey":false,"unsigned":false,"autoIncrement":false,"comment":"\\u72b6\\u6001:enable=\\u542f\\u7528,disable=\\u7981\\u7528","designType":"string","table":{"render":"none","operator":"LIKE","sortable":"false"},"form":{"validator":[],"validatorMsg":""}},{"name":"update_time","type":"bigint","dataType":"bigint","default":"","defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u66f4\\u65b0\\u65f6\\u95f4","designType":"timestamp","table":{"render":"datetime","operator":"RANGE","sortable":"custom","width":160,"timeFormat":"yyyy-mm-dd hh:MM:ss"},"form":{"validator":["date"],"validatorMsg":""}},{"name":"create_time","type":"bigint","dataType":"bigint","default":"","defaultType":"NULL","null":true,"primaryKey":false,"unsigned":true,"autoIncrement":false,"comment":"\\u521b\\u5efa\\u65f6\\u95f4","designType":"timestamp","table":{"render":"datetime","operator":"RANGE","sortable":"custom","width":160,"timeFormat":"yyyy-mm-dd hh:MM:ss"},"form":{"validator":["date"],"validatorMsg":""}}]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747007097,
  ),
  82 => 
  array (
    'id' => 291,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/Group/edit',
    'title' => '会员分组管理-编辑',
    'data' => '{"id":2,"name":"\\u6d4b\\u8bd5\\u7ec4","rules":[2,3,1],"status":"1","update_time":"2025-05-12 05:45:16","create_time":"2025-05-12 05:45:16"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747008048,
  ),
  83 => 
  array (
    'id' => 292,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/del',
    'title' => '会员管理-删除',
    'data' => '{"ids":["3"]}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747008056,
  ),
  84 => 
  array (
    'id' => 293,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/edit',
    'title' => '会员管理-编辑',
    'data' => '{"id":2,"group_id":"2","username":"user2","nickname":"user2","email":"","mobile":"","avatar":"\\/storage\\/default\\/20250512\\/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg","gender":0,"birthday":null,"money":"0.00","score":0,"last_login_time":null,"last_login_ip":"","login_failure":0,"join_ip":"","join_time":null,"motto":"","password":"***","salt":"***","status":"enable","update_time":"2025-05-12 05:44:14","create_time":"2025-05-12 05:44:14"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747008063,
  ),
  85 => 
  array (
    'id' => 294,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/edit',
    'title' => '会员管理-编辑',
    'data' => '{"id":2,"group_id":"2","username":"user2","nickname":"user2","email":"","mobile":"","avatar":"\\/storage\\/default\\/20250512\\/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg","gender":0,"birthday":null,"money":"0.00","score":0,"last_login_time":null,"last_login_ip":"","login_failure":0,"join_ip":"","join_time":null,"motto":"","password":"***","salt":"***","status":"enable","update_time":"2025-05-12 08:01:03","create_time":"1970-01-01 08:33:45"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747008072,
  ),
  86 => 
  array (
    'id' => 295,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/delete',
    'title' => 'CRUD代码生成-删除',
    'data' => '{"id":7}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747009170,
  ),
  87 => 
  array (
    'id' => 296,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/crud/crud/delete',
    'title' => 'CRUD代码生成-删除',
    'data' => '{"id":6}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747009175,
  ),
  88 => 
  array (
    'id' => 297,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/edit',
    'title' => '会员管理-编辑',
    'data' => '{"id":2,"group_id":"3","username":"user2","nickname":"user2","email":"","mobile":"","avatar":"\\/storage\\/default\\/20250512\\/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg","gender":0,"birthday":null,"money":"0.00","score":0,"last_login_time":"1747008862","last_login_ip":"127.0.0.1","login_failure":0,"join_ip":"","join_time":null,"motto":"","password":"***","salt":"***","status":"enable","update_time":"2025-05-12 08:14:22","create_time":"1970-01-01 08:32:50"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747009392,
  ),
  89 => 
  array (
    'id' => 298,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/edit',
    'title' => '会员管理-编辑',
    'data' => '{"id":2,"group_id":"3","username":"user2","nickname":"user2","email":"","mobile":"","avatar":"\\/storage\\/default\\/20250512\\/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg","gender":0,"birthday":null,"money":"0.00","score":0,"last_login_time":"1747008862","last_login_ip":"127.0.0.1","login_failure":0,"join_ip":"","join_time":null,"motto":"","password":"***","salt":"***","status":"enable","update_time":"2025-05-12 08:23:12","create_time":"1970-01-01 08:32:50"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747009488,
  ),
  90 => 
  array (
    'id' => 299,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/edit',
    'title' => '会员管理-编辑',
    'data' => '{"id":2,"group_id":"3","username":"user2","nickname":"user2","email":"","mobile":"","avatar":"\\/storage\\/default\\/20250512\\/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg","gender":0,"birthday":null,"money":"0.00","score":0,"last_login_time":"1747008862","last_login_ip":"127.0.0.1","login_failure":0,"join_ip":"","join_time":null,"motto":"","password":"***","salt":"***","status":"enable","update_time":"2025-05-12 08:24:48","create_time":"1970-01-01 08:32:50"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747009497,
  ),
  91 => 
  array (
    'id' => 300,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/edit',
    'title' => '会员管理-编辑',
    'data' => '{"id":2,"group_id":"2","username":"user2","nickname":"user2","email":"","mobile":"","avatar":"\\/storage\\/default\\/20250512\\/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg","gender":0,"birthday":null,"money":"0.00","score":0,"last_login_time":"1747008862","last_login_ip":"127.0.0.1","login_failure":0,"join_ip":"","join_time":null,"motto":"","password":"***","salt":"***","status":"enable","update_time":"2025-05-12 08:24:57","create_time":"1970-01-01 08:32:50"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747009522,
  ),
  92 => 
  array (
    'id' => 301,
    'admin_id' => 1,
    'username' => 'admin',
    'url' => '///admin/user/User/edit',
    'title' => '会员管理-编辑',
    'data' => '{"id":2,"group_id":"3","username":"user2","nickname":"user2","email":"","mobile":"","avatar":"\\/storage\\/default\\/20250512\\/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg","gender":0,"birthday":null,"money":"0.00","score":0,"last_login_time":"1747008862","last_login_ip":"127.0.0.1","login_failure":0,"join_ip":"","join_time":null,"motto":"","password":"***","salt":"***","status":"enable","update_time":"2025-05-12 08:25:22","create_time":"1970-01-01 08:32:50"}',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747009552,
  ),
));
        Db::table('ra_admin_rule')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('pid')->notNull()->default('0');
            $table->string('type')->notNull()->default('menu');
            $table->string('title')->notNull()->default('');
            $table->string('name')->notNull()->default('');
            $table->string('path')->notNull()->default('');
            $table->string('icon')->notNull()->default('');
            $table->string('menu_type');
            $table->string('url')->notNull()->default('');
            $table->string('component')->notNull()->default('');
            $table->string('keepalive')->notNull()->default('0');
            $table->string('extend')->notNull()->default('none');
            $table->string('remark')->notNull()->default('');
            $table->integer('weigh')->notNull()->default('0');
            $table->string('status')->notNull()->default('1');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_admin_rule')->insert(array (
  0 => 
  array (
    'id' => 1,
    'pid' => 0,
    'type' => 'menu',
    'title' => '控制台',
    'name' => 'dashboard',
    'path' => 'dashboard',
    'icon' => 'fa fa-dashboard',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/dashboard.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => 'Remark lang',
    'weigh' => 999,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  1 => 
  array (
    'id' => 2,
    'pid' => 0,
    'type' => 'menu_dir',
    'title' => '权限管理',
    'name' => 'auth',
    'path' => 'auth',
    'icon' => 'fa fa-group',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 100,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  2 => 
  array (
    'id' => 3,
    'pid' => 2,
    'type' => 'menu',
    'title' => '角色组管理',
    'name' => 'auth/group',
    'path' => 'auth/group',
    'icon' => 'fa fa-group',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/auth/group/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => 'Remark lang',
    'weigh' => 99,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  3 => 
  array (
    'id' => 4,
    'pid' => 3,
    'type' => 'button',
    'title' => '查看',
    'name' => 'auth/group/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  4 => 
  array (
    'id' => 5,
    'pid' => 3,
    'type' => 'button',
    'title' => '添加',
    'name' => 'auth/group/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  5 => 
  array (
    'id' => 6,
    'pid' => 3,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'auth/group/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  6 => 
  array (
    'id' => 7,
    'pid' => 3,
    'type' => 'button',
    'title' => '删除',
    'name' => 'auth/group/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  7 => 
  array (
    'id' => 8,
    'pid' => 2,
    'type' => 'menu',
    'title' => '管理员管理',
    'name' => 'auth/admin',
    'path' => 'auth/admin',
    'icon' => 'el-icon-UserFilled',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/auth/admin/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 98,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  8 => 
  array (
    'id' => 9,
    'pid' => 8,
    'type' => 'button',
    'title' => '查看',
    'name' => 'auth/admin/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  9 => 
  array (
    'id' => 10,
    'pid' => 8,
    'type' => 'button',
    'title' => '添加',
    'name' => 'auth/admin/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  10 => 
  array (
    'id' => 11,
    'pid' => 8,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'auth/admin/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  11 => 
  array (
    'id' => 12,
    'pid' => 8,
    'type' => 'button',
    'title' => '删除',
    'name' => 'auth/admin/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  12 => 
  array (
    'id' => 13,
    'pid' => 2,
    'type' => 'menu',
    'title' => '菜单规则管理',
    'name' => 'auth/rule',
    'path' => 'auth/rule',
    'icon' => 'el-icon-Grid',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/auth/rule/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 97,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  13 => 
  array (
    'id' => 14,
    'pid' => 13,
    'type' => 'button',
    'title' => '查看',
    'name' => 'auth/rule/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  14 => 
  array (
    'id' => 15,
    'pid' => 13,
    'type' => 'button',
    'title' => '添加',
    'name' => 'auth/rule/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  15 => 
  array (
    'id' => 16,
    'pid' => 13,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'auth/rule/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  16 => 
  array (
    'id' => 17,
    'pid' => 13,
    'type' => 'button',
    'title' => '删除',
    'name' => 'auth/rule/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  17 => 
  array (
    'id' => 18,
    'pid' => 13,
    'type' => 'button',
    'title' => '快速排序',
    'name' => 'auth/rule/sortable',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  18 => 
  array (
    'id' => 19,
    'pid' => 2,
    'type' => 'menu',
    'title' => '管理员日志管理',
    'name' => 'auth/adminLog',
    'path' => 'auth/adminLog',
    'icon' => 'el-icon-List',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/auth/adminLog/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 96,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  19 => 
  array (
    'id' => 20,
    'pid' => 19,
    'type' => 'button',
    'title' => '查看',
    'name' => 'auth/adminLog/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  20 => 
  array (
    'id' => 21,
    'pid' => 0,
    'type' => 'menu_dir',
    'title' => '会员管理',
    'name' => 'user',
    'path' => 'user',
    'icon' => 'fa fa-drivers-license',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 95,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  21 => 
  array (
    'id' => 22,
    'pid' => 21,
    'type' => 'menu',
    'title' => '会员管理',
    'name' => 'user/user',
    'path' => 'user/user',
    'icon' => 'fa fa-user',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/user/user/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 94,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  22 => 
  array (
    'id' => 23,
    'pid' => 22,
    'type' => 'button',
    'title' => '查看',
    'name' => 'user/user/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  23 => 
  array (
    'id' => 24,
    'pid' => 22,
    'type' => 'button',
    'title' => '添加',
    'name' => 'user/user/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  24 => 
  array (
    'id' => 25,
    'pid' => 22,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'user/user/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  25 => 
  array (
    'id' => 26,
    'pid' => 22,
    'type' => 'button',
    'title' => '删除',
    'name' => 'user/user/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  26 => 
  array (
    'id' => 27,
    'pid' => 21,
    'type' => 'menu',
    'title' => '会员分组管理',
    'name' => 'user/group',
    'path' => 'user/group',
    'icon' => 'fa fa-group',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/user/group/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 93,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  27 => 
  array (
    'id' => 28,
    'pid' => 27,
    'type' => 'button',
    'title' => '查看',
    'name' => 'user/group/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  28 => 
  array (
    'id' => 29,
    'pid' => 27,
    'type' => 'button',
    'title' => '添加',
    'name' => 'user/group/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  29 => 
  array (
    'id' => 30,
    'pid' => 27,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'user/group/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  30 => 
  array (
    'id' => 31,
    'pid' => 27,
    'type' => 'button',
    'title' => '删除',
    'name' => 'user/group/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  31 => 
  array (
    'id' => 32,
    'pid' => 21,
    'type' => 'menu',
    'title' => '会员规则管理',
    'name' => 'user/rule',
    'path' => 'user/rule',
    'icon' => 'fa fa-th-list',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/user/rule/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 92,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  32 => 
  array (
    'id' => 33,
    'pid' => 32,
    'type' => 'button',
    'title' => '查看',
    'name' => 'user/rule/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  33 => 
  array (
    'id' => 34,
    'pid' => 32,
    'type' => 'button',
    'title' => '添加',
    'name' => 'user/rule/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  34 => 
  array (
    'id' => 35,
    'pid' => 32,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'user/rule/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  35 => 
  array (
    'id' => 36,
    'pid' => 32,
    'type' => 'button',
    'title' => '删除',
    'name' => 'user/rule/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  36 => 
  array (
    'id' => 37,
    'pid' => 32,
    'type' => 'button',
    'title' => '快速排序',
    'name' => 'user/rule/sortable',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  37 => 
  array (
    'id' => 38,
    'pid' => 21,
    'type' => 'menu',
    'title' => '会员余额管理',
    'name' => 'user/moneyLog',
    'path' => 'user/moneyLog',
    'icon' => 'el-icon-Money',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/user/moneyLog/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 91,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  38 => 
  array (
    'id' => 39,
    'pid' => 38,
    'type' => 'button',
    'title' => '查看',
    'name' => 'user/moneyLog/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  39 => 
  array (
    'id' => 40,
    'pid' => 38,
    'type' => 'button',
    'title' => '添加',
    'name' => 'user/moneyLog/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  40 => 
  array (
    'id' => 41,
    'pid' => 21,
    'type' => 'menu',
    'title' => '会员积分管理',
    'name' => 'user/scoreLog',
    'path' => 'user/scoreLog',
    'icon' => 'el-icon-Discount',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/user/scoreLog/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 90,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  41 => 
  array (
    'id' => 42,
    'pid' => 41,
    'type' => 'button',
    'title' => '查看',
    'name' => 'user/scoreLog/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  42 => 
  array (
    'id' => 43,
    'pid' => 41,
    'type' => 'button',
    'title' => '添加',
    'name' => 'user/scoreLog/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  43 => 
  array (
    'id' => 44,
    'pid' => 0,
    'type' => 'menu_dir',
    'title' => '常规管理',
    'name' => 'routine',
    'path' => 'routine',
    'icon' => 'fa fa-cogs',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 89,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  44 => 
  array (
    'id' => 45,
    'pid' => 44,
    'type' => 'menu',
    'title' => '系统配置',
    'name' => 'routine/config',
    'path' => 'routine/config',
    'icon' => 'el-icon-Tools',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/routine/config/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 88,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  45 => 
  array (
    'id' => 46,
    'pid' => 45,
    'type' => 'button',
    'title' => '查看',
    'name' => 'routine/config/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  46 => 
  array (
    'id' => 47,
    'pid' => 45,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'routine/config/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  47 => 
  array (
    'id' => 48,
    'pid' => 44,
    'type' => 'menu',
    'title' => '附件管理',
    'name' => 'routine/attachment',
    'path' => 'routine/attachment',
    'icon' => 'fa fa-folder',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/routine/attachment/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => 'Remark lang',
    'weigh' => 87,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  48 => 
  array (
    'id' => 49,
    'pid' => 48,
    'type' => 'button',
    'title' => '查看',
    'name' => 'routine/attachment/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  49 => 
  array (
    'id' => 50,
    'pid' => 48,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'routine/attachment/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  50 => 
  array (
    'id' => 51,
    'pid' => 48,
    'type' => 'button',
    'title' => '删除',
    'name' => 'routine/attachment/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  51 => 
  array (
    'id' => 52,
    'pid' => 44,
    'type' => 'menu',
    'title' => '个人资料',
    'name' => 'routine/adminInfo',
    'path' => 'routine/adminInfo',
    'icon' => 'fa fa-user',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/routine/adminInfo.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 86,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  52 => 
  array (
    'id' => 53,
    'pid' => 52,
    'type' => 'button',
    'title' => '查看',
    'name' => 'routine/adminInfo/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  53 => 
  array (
    'id' => 54,
    'pid' => 52,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'routine/adminInfo/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  54 => 
  array (
    'id' => 55,
    'pid' => 0,
    'type' => 'menu_dir',
    'title' => '数据安全管理',
    'name' => 'security',
    'path' => 'security',
    'icon' => 'fa fa-shield',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 85,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  55 => 
  array (
    'id' => 56,
    'pid' => 55,
    'type' => 'menu',
    'title' => '数据回收站',
    'name' => 'security/dataRecycleLog',
    'path' => 'security/dataRecycleLog',
    'icon' => 'fa fa-database',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/security/dataRecycleLog/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 84,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  56 => 
  array (
    'id' => 57,
    'pid' => 56,
    'type' => 'button',
    'title' => '查看',
    'name' => 'security/dataRecycleLog/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  57 => 
  array (
    'id' => 58,
    'pid' => 56,
    'type' => 'button',
    'title' => '删除',
    'name' => 'security/dataRecycleLog/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  58 => 
  array (
    'id' => 59,
    'pid' => 56,
    'type' => 'button',
    'title' => '还原',
    'name' => 'security/dataRecycleLog/restore',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  59 => 
  array (
    'id' => 60,
    'pid' => 56,
    'type' => 'button',
    'title' => '查看详情',
    'name' => 'security/dataRecycleLog/info',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  60 => 
  array (
    'id' => 61,
    'pid' => 55,
    'type' => 'menu',
    'title' => '敏感数据修改记录',
    'name' => 'security/sensitiveDataLog',
    'path' => 'security/sensitiveDataLog',
    'icon' => 'fa fa-expeditedssl',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/security/sensitiveDataLog/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 83,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  61 => 
  array (
    'id' => 62,
    'pid' => 61,
    'type' => 'button',
    'title' => '查看',
    'name' => 'security/sensitiveDataLog/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  62 => 
  array (
    'id' => 63,
    'pid' => 61,
    'type' => 'button',
    'title' => '删除',
    'name' => 'security/sensitiveDataLog/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  63 => 
  array (
    'id' => 64,
    'pid' => 61,
    'type' => 'button',
    'title' => '回滚',
    'name' => 'security/sensitiveDataLog/rollback',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  64 => 
  array (
    'id' => 65,
    'pid' => 61,
    'type' => 'button',
    'title' => '查看详情',
    'name' => 'security/sensitiveDataLog/info',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  65 => 
  array (
    'id' => 66,
    'pid' => 55,
    'type' => 'menu',
    'title' => '数据回收规则管理',
    'name' => 'security/dataRecycle',
    'path' => 'security/dataRecycle',
    'icon' => 'fa fa-database',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/security/dataRecycle/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => 'Remark lang',
    'weigh' => 82,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  66 => 
  array (
    'id' => 67,
    'pid' => 66,
    'type' => 'button',
    'title' => '查看',
    'name' => 'security/dataRecycle/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  67 => 
  array (
    'id' => 68,
    'pid' => 66,
    'type' => 'button',
    'title' => '添加',
    'name' => 'security/dataRecycle/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  68 => 
  array (
    'id' => 69,
    'pid' => 66,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'security/dataRecycle/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  69 => 
  array (
    'id' => 70,
    'pid' => 66,
    'type' => 'button',
    'title' => '删除',
    'name' => 'security/dataRecycle/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  70 => 
  array (
    'id' => 71,
    'pid' => 55,
    'type' => 'menu',
    'title' => '敏感字段规则管理',
    'name' => 'security/sensitiveData',
    'path' => 'security/sensitiveData',
    'icon' => 'fa fa-expeditedssl',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/security/sensitiveData/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => 'Remark lang',
    'weigh' => 81,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  71 => 
  array (
    'id' => 72,
    'pid' => 71,
    'type' => 'button',
    'title' => '查看',
    'name' => 'security/sensitiveData/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  72 => 
  array (
    'id' => 73,
    'pid' => 71,
    'type' => 'button',
    'title' => '添加',
    'name' => 'security/sensitiveData/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  73 => 
  array (
    'id' => 74,
    'pid' => 71,
    'type' => 'button',
    'title' => '编辑',
    'name' => 'security/sensitiveData/edit',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  74 => 
  array (
    'id' => 75,
    'pid' => 71,
    'type' => 'button',
    'title' => '删除',
    'name' => 'security/sensitiveData/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  75 => 
  array (
    'id' => 76,
    'pid' => 0,
    'type' => 'menu',
    'title' => 'BuildAdmin',
    'name' => 'buildadmin',
    'path' => 'buildadmin',
    'icon' => 'local-logo',
    'menu_type' => 'link',
    'url' => 'https://doc.buildadmin.com',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '0',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  76 => 
  array (
    'id' => 77,
    'pid' => 45,
    'type' => 'button',
    'title' => '添加',
    'name' => 'routine/config/add',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  77 => 
  array (
    'id' => 78,
    'pid' => 0,
    'type' => 'menu',
    'title' => '模块市场',
    'name' => 'moduleStore/moduleStore',
    'path' => 'moduleStore',
    'icon' => 'el-icon-GoodsFilled',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/module/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 86,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  78 => 
  array (
    'id' => 79,
    'pid' => 78,
    'type' => 'button',
    'title' => '查看',
    'name' => 'moduleStore/moduleStore/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  79 => 
  array (
    'id' => 80,
    'pid' => 78,
    'type' => 'button',
    'title' => '安装',
    'name' => 'moduleStore/moduleStore/install',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  80 => 
  array (
    'id' => 81,
    'pid' => 78,
    'type' => 'button',
    'title' => '调整状态',
    'name' => 'moduleStore/moduleStore/changeState',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  81 => 
  array (
    'id' => 82,
    'pid' => 78,
    'type' => 'button',
    'title' => '卸载',
    'name' => 'moduleStore/moduleStore/uninstall',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  82 => 
  array (
    'id' => 83,
    'pid' => 78,
    'type' => 'button',
    'title' => '更新',
    'name' => 'moduleStore/moduleStore/update',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  83 => 
  array (
    'id' => 84,
    'pid' => 0,
    'type' => 'menu',
    'title' => 'CRUD代码生成',
    'name' => 'crud/crud',
    'path' => 'crud/crud',
    'icon' => 'fa fa-code',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/backend/crud/index.vue',
    'keepalive' => 1,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 80,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  84 => 
  array (
    'id' => 85,
    'pid' => 84,
    'type' => 'button',
    'title' => '查看',
    'name' => 'crud/crud/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  85 => 
  array (
    'id' => 86,
    'pid' => 84,
    'type' => 'button',
    'title' => '生成',
    'name' => 'crud/crud/generate',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  86 => 
  array (
    'id' => 87,
    'pid' => 84,
    'type' => 'button',
    'title' => '删除',
    'name' => 'crud/crud/delete',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  87 => 
  array (
    'id' => 88,
    'pid' => 45,
    'type' => 'button',
    'title' => '删除',
    'name' => 'routine/config/del',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  88 => 
  array (
    'id' => 89,
    'pid' => 1,
    'type' => 'button',
    'title' => '查看',
    'name' => 'dashboard/index',
    'path' => '',
    'icon' => '',
    'menu_type' => NULL,
    'url' => '',
    'component' => '',
    'keepalive' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 0,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
));
        Db::table('ra_area')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('pid');
            $table->string('shortname');
            $table->string('name');
            $table->string('mergename');
            $table->string('level');
            $table->string('pinyin');
            $table->string('code');
            $table->string('zip');
            $table->string('first');
            $table->string('lng');
            $table->string('lat');
        });
        Db::table('ra_area')->insert(array (
));
        Db::table('ra_attachment')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('topic')->notNull()->default('');
            $table->string('admin_id')->notNull()->default('0');
            $table->string('user_id')->notNull()->default('0');
            $table->string('url')->notNull()->default('');
            $table->string('width')->notNull()->default('0');
            $table->string('height')->notNull()->default('0');
            $table->string('name')->notNull()->default('');
            $table->string('size')->notNull()->default('0');
            $table->string('mimetype')->notNull()->default('');
            $table->string('quote')->notNull()->default('0');
            $table->string('storage')->notNull()->default('');
            $table->string('sha1')->notNull()->default('');
            $table->string('create_time');
            $table->string('last_upload_time');
        });
        Db::table('ra_attachment')->insert(array (
  0 => 
  array (
    'id' => 1,
    'topic' => 'default',
    'admin_id' => 1,
    'user_id' => 0,
    'url' => '/storage/default/20250509/logo43240f82cba37fb6e1b097ecff178023af8e6383.png',
    'width' => 864,
    'height' => 823,
    'name' => 'logo.png',
    'size' => 317356,
    'mimetype' => 'image/png',
    'quote' => 2,
    'storage' => 'local',
    'sha1' => '43240f82cba37fb6e1b097ecff178023af8e6383',
    'create_time' => 1746736751,
    'last_upload_time' => 1746736865,
  ),
  1 => 
  array (
    'id' => 2,
    'topic' => 'default',
    'admin_id' => 1,
    'user_id' => 0,
    'url' => '/storage/default/20250510/qr834776e09219bcace1886d051f3b28f8d84f0a6a.png',
    'width' => 300,
    'height' => 300,
    'name' => 'qr.png',
    'size' => 33408,
    'mimetype' => 'image/png',
    'quote' => 1,
    'storage' => 'local',
    'sha1' => '834776e09219bcace1886d051f3b28f8d84f0a6a',
    'create_time' => 1746828514,
    'last_upload_time' => 1746828514,
  ),
  2 => 
  array (
    'id' => 3,
    'topic' => 'default',
    'admin_id' => 1,
    'user_id' => 0,
    'url' => '/storage/default/20250512/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg',
    'width' => 639,
    'height' => 363,
    'name' => '1.png.jpg',
    'size' => 164726,
    'mimetype' => 'image/jpeg',
    'quote' => 1,
    'storage' => 'local',
    'sha1' => '5dd431322c26a2c3d48fa8fe523c22c35e132a54',
    'create_time' => 1746999847,
    'last_upload_time' => 1746999847,
  ),
));
        Db::table('ra_captcha')->create(function ($table) {
            $table->string('key')->notNull()->default('');
            $table->string('code')->notNull()->default('');
            $table->text('captcha');
            $table->string('create_time');
            $table->string('expire_time');
        });
        Db::table('ra_captcha')->insert(array (
  0 => 
  array (
    'key' => 'b8949b8955c452a827f6a49046f5f115',
    'code' => '2593cb876b5ca08bb9e6f92260d08fc0',
    'captcha' => '{"text":[{"size":25,"icon":false,"text":"延","width":32,"height":29,"x":178,"y":73},{"size":17,"icon":false,"text":"觉","width":21,"height":19,"x":72,"y":89}],"width":350,"height":200}',
    'create_time' => 1746998644,
    'expire_time' => 1746999244,
  ),
));
        Db::table('ra_config')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('name')->notNull()->default('');
            $table->string('group')->notNull()->default('');
            $table->string('title')->notNull()->default('');
            $table->string('tip')->notNull()->default('');
            $table->string('type')->notNull()->default('');
            $table->string('value');
            $table->string('content');
            $table->string('rule')->notNull()->default('');
            $table->string('extend')->notNull()->default('');
            $table->string('allow_del')->notNull()->default('0');
            $table->integer('weigh')->notNull()->default('0');
        });
        Db::table('ra_config')->insert(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'config_group',
    'group' => 'basics',
    'title' => 'Config group',
    'tip' => '',
    'type' => 'array',
    'value' => '[{"key":"basics","value":"\\u57fa\\u7840\\u914d\\u7f6e"},{"key":"mail","value":"\\u90ae\\u4ef6\\u914d\\u7f6e"},{"key":"authentication","value":"\\u9274\\u6743\\u914d\\u7f6e"}]',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => -1,
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'site_name',
    'group' => 'basics',
    'title' => 'Site Name',
    'tip' => '',
    'type' => 'string',
    'value' => 'RAdmin',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 99,
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'record_number',
    'group' => 'basics',
    'title' => 'Record number',
    'tip' => '域名备案号',
    'type' => 'string',
    'value' => '渝ICP备8888888号-1',
    'content' => NULL,
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 0,
  ),
  3 => 
  array (
    'id' => 4,
    'name' => 'version',
    'group' => 'basics',
    'title' => 'Version number',
    'tip' => '系统版本号',
    'type' => 'string',
    'value' => 'v1.0.0',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 0,
  ),
  4 => 
  array (
    'id' => 5,
    'name' => 'time_zone',
    'group' => 'basics',
    'title' => 'time zone',
    'tip' => '',
    'type' => 'string',
    'value' => 'Asia/Shanghai',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 0,
  ),
  5 => 
  array (
    'id' => 6,
    'name' => 'no_access_ip',
    'group' => 'basics',
    'title' => 'No access ip',
    'tip' => '禁止访问站点的ip列表,一行一个',
    'type' => 'textarea',
    'value' => '',
    'content' => NULL,
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 0,
  ),
  6 => 
  array (
    'id' => 7,
    'name' => 'smtp_server',
    'group' => 'mail',
    'title' => 'smtp server',
    'tip' => '',
    'type' => 'string',
    'value' => 'smtp.qq.com',
    'content' => NULL,
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 9,
  ),
  7 => 
  array (
    'id' => 8,
    'name' => 'smtp_port',
    'group' => 'mail',
    'title' => 'smtp port',
    'tip' => '',
    'type' => 'string',
    'value' => '465',
    'content' => NULL,
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 8,
  ),
  8 => 
  array (
    'id' => 9,
    'name' => 'smtp_user',
    'group' => 'mail',
    'title' => 'smtp user',
    'tip' => '',
    'type' => 'string',
    'value' => NULL,
    'content' => NULL,
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 7,
  ),
  9 => 
  array (
    'id' => 10,
    'name' => 'smtp_pass',
    'group' => 'mail',
    'title' => 'smtp pass',
    'tip' => '',
    'type' => 'string',
    'value' => NULL,
    'content' => NULL,
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 6,
  ),
  10 => 
  array (
    'id' => 11,
    'name' => 'smtp_verification',
    'group' => 'mail',
    'title' => 'smtp verification',
    'tip' => '',
    'type' => 'select',
    'value' => 'SSL',
    'content' => '{"SSL":"SSL","TLS":"TLS"}',
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 5,
  ),
  11 => 
  array (
    'id' => 12,
    'name' => 'smtp_sender_mail',
    'group' => 'mail',
    'title' => 'smtp sender mail',
    'tip' => '',
    'type' => 'string',
    'value' => NULL,
    'content' => NULL,
    'rule' => 'email',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 4,
  ),
  12 => 
  array (
    'id' => 13,
    'name' => 'config_quick_entrance',
    'group' => 'config_quick_entrance',
    'title' => 'Config Quick entrance',
    'tip' => '',
    'type' => 'array',
    'value' => '[{"key":"数据回收规则配置","value":"/admin/security/dataRecycle"},{"key":"敏感数据规则配置","value":"/admin/security/sensitiveData"}]',
    'content' => NULL,
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 0,
  ),
  13 => 
  array (
    'id' => 14,
    'name' => 'backend_entrance',
    'group' => 'basics',
    'title' => 'Backend entrance',
    'tip' => '',
    'type' => 'string',
    'value' => '/admin',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 1,
  ),
  14 => 
  array (
    'id' => 17,
    'name' => 'driver',
    'group' => 'authentication',
    'title' => '驱动类型',
    'tip' => '默认驱动类型',
    'type' => 'radio',
    'value' => 'jwt',
    'content' => '{"jwt":"Jwt","cache":"Cache","mysql":"Mysql","redis":"Redis"}',
    'rule' => '',
    'extend' => '{"blockHelp":"1. \\u63a8\\u8350JWT,\\u4e0d\\u5b58\\u50a8\\u65e0\\u72b6\\u6001,\\u66f4\\u5b89\\u5168   2. Cache\\u65b9\\u5f0f\\u652f\\u6301File,Redis\\u7b49"}',
    'allow_del' => 0,
    'weigh' => 999,
  ),
  15 => 
  array (
    'id' => 18,
    'name' => 'expire_time',
    'group' => 'authentication',
    'title' => 'Token有效期(秒)',
    'tip' => '单位秒,安全起见不要设置太长',
    'type' => 'number',
    'value' => '20',
    'content' => NULL,
    'rule' => 'required,integer',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 965,
  ),
  16 => 
  array (
    'id' => 19,
    'name' => 'keep_time',
    'group' => 'authentication',
    'title' => '保持会话时间(秒)',
    'tip' => '单位秒,默认7天',
    'type' => 'number',
    'value' => '604800',
    'content' => NULL,
    'rule' => 'required,integer',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 960,
  ),
  17 => 
  array (
    'id' => 20,
    'name' => 'algo',
    'group' => 'authentication',
    'title' => '加密方式',
    'tip' => '哈希算法,不推荐MD5',
    'type' => 'radio',
    'value' => 'sha256',
    'content' => '{"md5 ":"MD5","sha256":"SHA-256","whirlpool":"Whirlpool","ripemd256":"RIPEMD-256","gost":"GOST"}',
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 971,
  ),
  18 => 
  array (
    'id' => 29,
    'name' => 'jwt_algo',
    'group' => 'authentication',
    'title' => 'JWT签名算法',
    'tip' => 'JWT签名算法,暂不支持RS非对称加密',
    'type' => 'radio',
    'value' => 'HS256',
    'content' => '{"HS256":"HS256","HS384":"HS384","HS512":"HS512"}',
    'rule' => '',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 980,
  ),
  19 => 
  array (
    'id' => 38,
    'name' => 'secret',
    'group' => 'authentication',
    'title' => '加密密钥',
    'tip' => '密钥字串',
    'type' => 'password',
    'value' => 'jp9S^mtu^!6)(iGr_Xqwe^PstooaJRyMcPAYgyfo+bDKg%z*$JivrY0vz_waCrV*Arx@0+60zBU8L50tacPG1zTq12mGalZ9qa%tktUPj)%EAv2fjCBsWgSl*Pz&@9!dpR0hXl1e2El*%DwJS#xeOIkyOUv*6G@OI9XCumlyBxtwYn8E^pyVP9IJHTzq^#E8p#SS%tPRNsiF1IE@I$hnCbRSd5AjERg#++^palDcyjav8qKh*!GXUWrtuH@W(4)S',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 970,
  ),
  20 => 
  array (
    'id' => 39,
    'name' => 'jwt_secret',
    'group' => 'authentication',
    'title' => 'JWT加密密钥',
    'tip' => 'JWT加密密钥',
    'type' => 'password',
    'value' => 'P3nP5cLURe!RQeOTzjNPAVNEs&8a4(2o$Eh(nsT3IAOtyLf9te1tAlyLx45gWLMaN5kxnx1C&wrWcf)xTxi97bGhrBADum0EF$EP_E$F503bT^Srq8vP6Vuh%4&wgHohI8LGX@dksEKnOQW#Gv181_Tp@dwXtO*5HNlv)RdS06k2DGI+V24$jA%i!gL$EGw16%i_szlLDyP!CLyzkr(ygy8mXxLr)aXXurMln5H)W4___EfNYOIoSH7W1HNor&lS',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 979,
  ),
  21 => 
  array (
    'id' => 41,
    'name' => 'iss',
    'group' => 'authentication',
    'title' => '签发者标识',
    'tip' => '签发者标识',
    'type' => 'string',
    'value' => 'Radmin',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 9999,
  ),
  22 => 
  array (
    'id' => 42,
    'name' => 'allow_keys',
    'group' => 'authentication',
    'title' => '允许的字段',
    'tip' => '允许的字段',
    'type' => 'array',
    'value' => '[{"key":"iss","value":"\\u7b7e\\u53d1\\u8005"},{"key":"sub","value":"\\u7528\\u6237 ID"},{"key":"exp","value":"\\u8fc7\\u671f\\u65f6\\u95f4"},{"key":"iat","value":"\\u7b7e\\u53d1\\u65f6\\u95f4"},{"key":"jti","value":"\\u552f\\u4e00\\u6807\\u8bc6\\u7b26"},{"key":"roles","value":"\\u7528\\u6237\\u89d2\\u8272"},{"key":"type","value":"TOKEN\\u7c7b\\u578b"},{"key":"role","value":"\\u4e25\\u683c\\u89d2\\u8272"}]',
    'content' => NULL,
    'rule' => 'required',
    'extend' => '',
    'allow_del' => 0,
    'weigh' => 955,
  ),
));
        Db::table('ra_crud_log')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('table_name')->notNull()->default('');
            $table->string('comment')->notNull()->default('');
            $table->text('table');
            $table->text('fields');
            $table->string('sync')->notNull()->default('0');
            $table->string('status')->notNull()->default('start');
            $table->string('connection')->notNull()->default('');
            $table->string('create_time');
        });
        Db::table('ra_crud_log')->insert(array (
));
        Db::table('ra_ctest')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('remark')->notNull()->default('');
            $table->string('user_group_id');
        });
        Db::table('ra_ctest')->insert(array (
));
        Db::table('ra_ddd')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('sss_id');
        });
        Db::table('ra_ddd')->insert(array (
  0 => 
  array (
    'id' => 1,
    'sss_id' => 2,
  ),
));
        Db::table('ra_ggg')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('admin_id');
        });
        Db::table('ra_ggg')->insert(array (
));
        Db::table('ra_migrations')->create(function ($table) {
            $table->string('version')->notNull();
            $table->string('migration_name');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('breakpoint')->notNull()->default('0');
        });
        Db::table('ra_migrations')->insert(array (
));
        Db::table('ra_nnn')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('admin_id');
        });
        Db::table('ra_nnn')->insert(array (
));
        Db::table('ra_security_data_recycle')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('name')->notNull()->default('');
            $table->string('controller')->notNull()->default('');
            $table->string('controller_as')->notNull()->default('');
            $table->string('data_table')->notNull()->default('');
            $table->string('connection')->notNull()->default('');
            $table->string('primary_key')->notNull()->default('');
            $table->string('status')->notNull()->default('1');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_security_data_recycle')->insert(array (
  0 => 
  array (
    'id' => 1,
    'name' => '管理员',
    'controller' => 'auth/Admin.php',
    'controller_as' => 'auth/admin',
    'data_table' => 'admin',
    'connection' => '',
    'primary_key' => 'id',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  1 => 
  array (
    'id' => 2,
    'name' => '管理员日志',
    'controller' => 'auth/AdminLog.php',
    'controller_as' => 'auth/adminlog',
    'data_table' => 'admin_log',
    'connection' => '',
    'primary_key' => 'id',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  2 => 
  array (
    'id' => 3,
    'name' => '菜单规则',
    'controller' => 'auth/Menu.php',
    'controller_as' => 'auth/menu',
    'data_table' => 'menu_rule',
    'connection' => '',
    'primary_key' => 'id',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  3 => 
  array (
    'id' => 4,
    'name' => '系统配置项',
    'controller' => 'routine/Config.php',
    'controller_as' => 'routine/config',
    'data_table' => 'config',
    'connection' => '',
    'primary_key' => 'id',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  4 => 
  array (
    'id' => 5,
    'name' => '会员',
    'controller' => 'user/User.php',
    'controller_as' => 'user/user',
    'data_table' => 'user',
    'connection' => 'mysql',
    'primary_key' => 'id',
    'status' => '1',
    'update_time' => 1746738237,
    'create_time' => 1746723960,
  ),
  5 => 
  array (
    'id' => 6,
    'name' => '数据回收规则',
    'controller' => 'security/DataRecycle.php',
    'controller_as' => 'security/datarecycle',
    'data_table' => 'security_data_recycle',
    'connection' => 'mysql',
    'primary_key' => 'id',
    'status' => '1',
    'update_time' => 1746738231,
    'create_time' => 1746723960,
  ),
));
        Db::table('ra_security_data_recycle_log')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('admin_id')->notNull()->default('0');
            $table->string('recycle_id')->notNull()->default('0');
            $table->text('data');
            $table->string('data_table')->notNull()->default('');
            $table->string('connection')->notNull()->default('');
            $table->string('primary_key')->notNull()->default('');
            $table->string('is_restore')->notNull()->default('0');
            $table->string('ip')->notNull()->default('');
            $table->string('useragent')->notNull()->default('');
            $table->string('create_time');
        });
        Db::table('ra_security_data_recycle_log')->insert(array (
  0 => 
  array (
    'id' => 1,
    'admin_id' => 1,
    'recycle_id' => 4,
    'data' => '{"id":26,"name":"aaa","group":"authentication","title":"aaa","tip":"aaa","type":"checkbox","value":null,"content":"{\\"key1\\":\\"value1\\",\\"key2\\":\\"value2\\"}","rule":"","extend":"style: {display: state.form[driver] === jwt ? block : none}","allow_del":1,"weigh":0}',
    'data_table' => 'config',
    'connection' => '',
    'primary_key' => 'id',
    'is_restore' => 0,
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746914650,
  ),
  1 => 
  array (
    'id' => 2,
    'admin_id' => 1,
    'recycle_id' => 4,
    'data' => '{"id":28,"name":"dddd","group":"authentication","title":"dddd","tip":"","type":"radio","value":null,"content":"{\\"key1\\":\\"value1\\",\\"key2\\":\\"value2\\"}","rule":"required","extend":"","allow_del":1,"weigh":0}',
    'data_table' => 'config',
    'connection' => '',
    'primary_key' => 'id',
    'is_restore' => 0,
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746916110,
  ),
  2 => 
  array (
    'id' => 3,
    'admin_id' => 1,
    'recycle_id' => 4,
    'data' => '{"id":27,"name":"sddf","group":"authentication","title":"saf","tip":"","type":"checkbox","value":null,"content":"{\\"key1\\":\\"value1\\",\\"key2\\":\\"value2\\"}","rule":"","extend":"{\\"class\\":\\"dddd\\",\\"style\\":\\"{display: \'none\'}\\"}","allow_del":1,"weigh":0}',
    'data_table' => 'config',
    'connection' => '',
    'primary_key' => 'id',
    'is_restore' => 0,
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746916119,
  ),
  3 => 
  array (
    'id' => 4,
    'admin_id' => 1,
    'recycle_id' => 4,
    'data' => '{"id":37,"name":"aaa","group":"authentication","title":"aaa","tip":"aa","type":"textarea","value":null,"content":null,"rule":"","extend":"{\\"blockHelp\\":\\"1. \\\\u63a8\\\\u8350JWT,\\\\u4e0d\\\\u5b58\\\\u50a8\\\\u65e0\\\\u72b6\\\\u6001,\\\\u66f4\\\\u5b89\\\\u5168   2. Cache\\\\u65b9\\\\u5f0f\\\\u652f\\\\u6301File,Redis\\\\u7b49\\"}","allow_del":1,"weigh":0}',
    'data_table' => 'config',
    'connection' => '',
    'primary_key' => 'id',
    'is_restore' => 0,
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746924614,
  ),
  4 => 
  array (
    'id' => 5,
    'admin_id' => 1,
    'recycle_id' => 4,
    'data' => '{"id":40,"name":"aaaa","group":"authentication","title":"aaa","tip":"","type":"password","value":null,"content":null,"rule":"","extend":"{\\"baInputExtend\\":{\\"size\\":\\"large\\"}}","allow_del":1,"weigh":0}',
    'data_table' => 'config',
    'connection' => '',
    'primary_key' => 'id',
    'is_restore' => 0,
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1746928365,
  ),
  5 => 
  array (
    'id' => 6,
    'admin_id' => 1,
    'recycle_id' => 5,
    'data' => '{"id":3,"group_id":2,"username":"aaa222","nickname":"aa222","email":"","mobile":"","avatar":"","gender":0,"birthday":null,"money":0,"score":0,"last_login_time":null,"last_login_ip":"","login_failure":0,"join_ip":"","join_time":null,"motto":"","password":"$2y$10$jMz5Cp421brpZdn58KtG2eGPF4XV\\/ZtJQxZ8oQItFOIDhSHDT8mq2","salt":"","status":"enable","update_time":1747004166,"create_time":1747004166}',
    'data_table' => 'user',
    'connection' => 'mysql',
    'primary_key' => 'id',
    'is_restore' => 0,
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'create_time' => 1747008056,
  ),
));
        Db::table('ra_security_sensitive_data')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('name')->notNull()->default('');
            $table->string('controller')->notNull()->default('');
            $table->string('controller_as')->notNull()->default('');
            $table->string('data_table')->notNull()->default('');
            $table->string('connection')->notNull()->default('');
            $table->string('primary_key')->notNull()->default('');
            $table->text('data_fields');
            $table->string('status')->notNull()->default('1');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_security_sensitive_data')->insert(array (
  0 => 
  array (
    'id' => 1,
    'name' => '管理员数据',
    'controller' => 'auth/Admin.php',
    'controller_as' => 'auth/admin',
    'data_table' => 'admin',
    'connection' => '',
    'primary_key' => 'id',
    'data_fields' => '{"username":"用户名","mobile":"手机","password":"密码","status":"状态"}',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  1 => 
  array (
    'id' => 2,
    'name' => '会员数据',
    'controller' => 'user/User.php',
    'controller_as' => 'user/user',
    'data_table' => 'user',
    'connection' => '',
    'primary_key' => 'id',
    'data_fields' => '{"username":"用户名","mobile":"手机号","password":"密码","status":"状态","email":"邮箱地址"}',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  2 => 
  array (
    'id' => 3,
    'name' => '管理员权限',
    'controller' => 'auth/Group.php',
    'controller_as' => 'auth/group',
    'data_table' => 'admin_group',
    'connection' => '',
    'primary_key' => 'id',
    'data_fields' => '{"rules":"权限规则ID"}',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
));
        Db::table('ra_security_sensitive_data_log')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('admin_id')->notNull()->default('0');
            $table->string('sensitive_id')->notNull()->default('0');
            $table->string('data_table')->notNull()->default('');
            $table->string('connection')->notNull()->default('');
            $table->string('primary_key')->notNull()->default('');
            $table->string('data_field')->notNull()->default('');
            $table->string('data_comment')->notNull()->default('');
            $table->integer('id_value')->notNull()->default('0');
            $table->text('before');
            $table->text('after');
            $table->string('ip')->notNull()->default('');
            $table->string('useragent')->notNull()->default('');
            $table->string('is_rollback')->notNull()->default('0');
            $table->string('create_time');
        });
        Db::table('ra_security_sensitive_data_log')->insert(array (
  0 => 
  array (
    'id' => 1,
    'admin_id' => 1,
    'sensitive_id' => 2,
    'data_table' => 'user',
    'connection' => '',
    'primary_key' => 'id',
    'data_field' => 'password',
    'data_comment' => '密码',
    'id_value' => 1,
    'before' => '$2y$12$0fXwSgQbpcFvv66X82FwSuFd/HmgA18YJnR.3c6EPx5vss3V43mlG',
    'after' => '******',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'is_rollback' => 0,
    'create_time' => 1746736757,
  ),
  1 => 
  array (
    'id' => 2,
    'admin_id' => 1,
    'sensitive_id' => 2,
    'data_table' => 'user',
    'connection' => '',
    'primary_key' => 'id',
    'data_field' => 'password',
    'data_comment' => '密码',
    'id_value' => 2,
    'before' => '$2y$10$LDd75AvkNsNVbfriot5/MOdvh5ENnfO0vHLB7jWhunDw9YeLnlzVq',
    'after' => '******',
    'ip' => '127.0.0.1',
    'useragent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'is_rollback' => 0,
    'create_time' => 1747008072,
  ),
));
        Db::table('ra_sss')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('string')->notNull()->default('');
            $table->string('remark')->notNull()->default('');
            $table->string('update_time');
        });
        Db::table('ra_sss')->insert(array (
  0 => 
  array (
    'id' => 1,
    'string' => 'sss',
    'remark' => 'sss',
    'update_time' => 1747003561,
  ),
  1 => 
  array (
    'id' => 2,
    'string' => 'aaa',
    'remark' => 'aaa',
    'update_time' => 1747003567,
  ),
  2 => 
  array (
    'id' => 3,
    'string' => 'ddd',
    'remark' => 'ddd',
    'update_time' => 1747003575,
  ),
));
        Db::table('ra_test_build')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('title')->notNull()->default('');
            $table->string('keyword_rows')->notNull()->default('');
            $table->text('content');
            $table->string('views')->notNull()->default('0');
            $table->string('likes')->notNull()->default('0');
            $table->string('dislikes')->notNull()->default('0');
            $table->string('note_textarea')->notNull()->default('');
            $table->string('status')->notNull()->default('1');
            $table->integer('weigh')->notNull()->default('0');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_test_build')->insert(array (
));
        Db::table('ra_token')->create(function ($table) {
            $table->string('token')->notNull()->default('');
            $table->string('type')->notNull()->default('');
            $table->string('user_id')->notNull()->default('0');
            $table->string('create_time');
            $table->string('expire_time');
        });
        Db::table('ra_token')->insert(array (
));
        Db::table('ra_user')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('group_id')->notNull()->default('0');
            $table->string('username')->notNull()->default('');
            $table->string('nickname')->notNull()->default('');
            $table->string('email')->notNull()->default('');
            $table->string('mobile')->notNull()->default('');
            $table->string('avatar')->notNull()->default('');
            $table->string('gender')->notNull()->default('0');
            $table->date('birthday');
            $table->string('money')->notNull()->default('0');
            $table->string('score')->notNull()->default('0');
            $table->string('last_login_time');
            $table->string('last_login_ip')->notNull()->default('');
            $table->string('login_failure')->notNull()->default('0');
            $table->string('join_ip')->notNull()->default('');
            $table->string('join_time');
            $table->string('motto')->notNull()->default('');
            $table->string('password')->notNull()->default('');
            $table->string('salt')->notNull()->default('');
            $table->string('status')->notNull()->default('');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_user')->insert(array (
  0 => 
  array (
    'id' => 1,
    'group_id' => 1,
    'username' => 'user',
    'nickname' => 'User',
    'email' => '18888888888@qq.com',
    'mobile' => '18888888888',
    'avatar' => '/storage/default/20250509/logo43240f82cba37fb6e1b097ecff178023af8e6383.png',
    'gender' => 2,
    'birthday' => '2020-05-09',
    'money' => 0,
    'score' => 0,
    'last_login_time' => 1747008081,
    'last_login_ip' => '127.0.0.1',
    'login_failure' => 0,
    'join_ip' => '',
    'join_time' => NULL,
    'motto' => '',
    'password' => '$2y$10$WbxWagDRbiRUUccesOzqPe/37OJXu2XZs/aR56wup.u/9cSYS3M3m',
    'salt' => '',
    'status' => 'enable',
    'update_time' => 1747008081,
    'create_time' => 2025,
  ),
  1 => 
  array (
    'id' => 2,
    'group_id' => 3,
    'username' => 'user2',
    'nickname' => 'user2',
    'email' => '',
    'mobile' => '',
    'avatar' => '/storage/default/20250512/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg',
    'gender' => 0,
    'birthday' => NULL,
    'money' => 0,
    'score' => 0,
    'last_login_time' => 1747008862,
    'last_login_ip' => '127.0.0.1',
    'login_failure' => 0,
    'join_ip' => '',
    'join_time' => NULL,
    'motto' => '',
    'password' => '$2y$10$dMN.eVEDwhrxu6KTCtycm.GA.tN0CkxDrXUgTAWcHK5/GWJqnNvK.',
    'salt' => '',
    'status' => 'enable',
    'update_time' => 1747009552,
    'create_time' => 1970,
  ),
));
        Db::table('ra_user_group')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('name')->notNull()->default('');
            $table->text('rules');
            $table->string('status')->notNull()->default('1');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_user_group')->insert(array (
  0 => 
  array (
    'id' => 1,
    'name' => '默认分组',
    'rules' => '*',
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  1 => 
  array (
    'id' => 2,
    'name' => '测试组',
    'rules' => '2,3,1',
    'status' => '1',
    'update_time' => 1747008048,
    'create_time' => 1746999916,
  ),
  2 => 
  array (
    'id' => 3,
    'name' => '测试组2',
    'rules' => '*',
    'status' => '1',
    'update_time' => 1747000745,
    'create_time' => 1747000745,
  ),
));
        Db::table('ra_user_money_log')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('user_id')->notNull()->default('0');
            $table->integer('money')->notNull()->default('0');
            $table->integer('before')->notNull()->default('0');
            $table->integer('after')->notNull()->default('0');
            $table->string('memo')->notNull()->default('');
            $table->string('create_time');
        });
        Db::table('ra_user_money_log')->insert(array (
));
        Db::table('ra_user_rule')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('pid')->notNull()->default('0');
            $table->string('type')->notNull()->default('menu');
            $table->string('title')->notNull()->default('');
            $table->string('name')->notNull()->default('');
            $table->string('path')->notNull()->default('');
            $table->string('icon')->notNull()->default('');
            $table->string('menu_type')->notNull()->default('tab');
            $table->string('url')->notNull()->default('');
            $table->string('component')->notNull()->default('');
            $table->string('no_login_valid')->notNull()->default('0');
            $table->string('extend')->notNull()->default('none');
            $table->string('remark')->notNull()->default('');
            $table->integer('weigh')->notNull()->default('0');
            $table->string('status')->notNull()->default('1');
            $table->string('update_time');
            $table->string('create_time');
        });
        Db::table('ra_user_rule')->insert(array (
  0 => 
  array (
    'id' => 1,
    'pid' => 0,
    'type' => 'menu_dir',
    'title' => '我的账户',
    'name' => 'account',
    'path' => 'account',
    'icon' => 'fa fa-user-circle',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '',
    'no_login_valid' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 98,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  1 => 
  array (
    'id' => 2,
    'pid' => 1,
    'type' => 'menu',
    'title' => '账户概览',
    'name' => 'account/overview',
    'path' => 'account/overview',
    'icon' => 'fa fa-home',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/frontend/user/account/overview.vue',
    'no_login_valid' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 99,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  2 => 
  array (
    'id' => 3,
    'pid' => 1,
    'type' => 'menu',
    'title' => '个人资料',
    'name' => 'account/profile',
    'path' => 'account/profile',
    'icon' => 'fa fa-user-circle-o',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/frontend/user/account/profile.vue',
    'no_login_valid' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 98,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  3 => 
  array (
    'id' => 4,
    'pid' => 1,
    'type' => 'menu',
    'title' => '修改密码',
    'name' => 'account/changePassword',
    'path' => 'account/changePassword',
    'icon' => 'fa fa-shield',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/frontend/user/account/changePassword.vue',
    'no_login_valid' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 97,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  4 => 
  array (
    'id' => 5,
    'pid' => 1,
    'type' => 'menu',
    'title' => '积分记录',
    'name' => 'account/integral',
    'path' => 'account/integral',
    'icon' => 'fa fa-tag',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/frontend/user/account/integral.vue',
    'no_login_valid' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 96,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
  5 => 
  array (
    'id' => 6,
    'pid' => 1,
    'type' => 'menu',
    'title' => '余额记录',
    'name' => 'account/balance',
    'path' => 'account/balance',
    'icon' => 'fa fa-money',
    'menu_type' => 'tab',
    'url' => '',
    'component' => '/src/views/frontend/user/account/balance.vue',
    'no_login_valid' => 0,
    'extend' => 'none',
    'remark' => '',
    'weigh' => 95,
    'status' => '1',
    'update_time' => 1746723960,
    'create_time' => 1746723960,
  ),
));
        Db::table('ra_user_score_log')->create(function ($table) {
            $table->string('id')->notNull();
            $table->string('user_id')->notNull()->default('0');
            $table->integer('score')->notNull()->default('0');
            $table->integer('before')->notNull()->default('0');
            $table->integer('after')->notNull()->default('0');
            $table->string('memo')->notNull()->default('');
            $table->string('create_time');
        });
        Db::table('ra_user_score_log')->insert(array (
));
    }

    public function down()
    {
        // 回滚逻辑
        Db::table('ra_aaa')->drop();
        Db::table('ra_admin')->drop();
        Db::table('ra_admin_group')->drop();
        Db::table('ra_admin_group_access')->drop();
        Db::table('ra_admin_log')->drop();
        Db::table('ra_admin_rule')->drop();
        Db::table('ra_area')->drop();
        Db::table('ra_attachment')->drop();
        Db::table('ra_captcha')->drop();
        Db::table('ra_config')->drop();
        Db::table('ra_crud_log')->drop();
        Db::table('ra_ctest')->drop();
        Db::table('ra_ddd')->drop();
        Db::table('ra_ggg')->drop();
        Db::table('ra_migrations')->drop();
        Db::table('ra_nnn')->drop();
        Db::table('ra_security_data_recycle')->drop();
        Db::table('ra_security_data_recycle_log')->drop();
        Db::table('ra_security_sensitive_data')->drop();
        Db::table('ra_security_sensitive_data_log')->drop();
        Db::table('ra_sss')->drop();
        Db::table('ra_test_build')->drop();
        Db::table('ra_token')->drop();
        Db::table('ra_user')->drop();
        Db::table('ra_user_group')->drop();
        Db::table('ra_user_money_log')->drop();
        Db::table('ra_user_rule')->drop();
        Db::table('ra_user_score_log')->drop();
    }
}