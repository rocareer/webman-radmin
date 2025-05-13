-- Table structure for ra_config
CREATE TABLE `ra_config` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量输入组件类型',
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '变量值',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '字典数据',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '扩展属性',
  `allow_del` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '允许删除:0=否,1=是',
  `weigh` int NOT NULL DEFAULT '0' COMMENT '权重',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='系统配置';

-- Data for ra_config
INSERT INTO `ra_config` VALUES ('1', 'config_group', 'basics', 'Config group', '', 'array', '[{\"key\":\"basics\",\"value\":\"\\u57fa\\u7840\\u914d\\u7f6e\"},{\"key\":\"mail\",\"value\":\"\\u90ae\\u4ef6\\u914d\\u7f6e\"},{\"key\":\"authentication\",\"value\":\"\\u9274\\u6743\\u914d\\u7f6e\"},{\"key\":\"data\",\"value\":\"\\u6570\\u636e\\u5b89\\u5168\"},{\"key\":\"system\",\"value\":\"\\u7cfb\\u7edf\\u914d\\u7f6e\"},{\"key\":\"terminal\",\"value\":\"\\u7ec8\\u7aef\\u547d\\u4ee4\"}]', NULL, 'required', '', '0', '-1');
INSERT INTO `ra_config` VALUES ('2', 'site_name', 'basics', 'Site Name', '', 'string', 'RAdmin', NULL, 'required', '', '0', '99');
INSERT INTO `ra_config` VALUES ('3', 'record_number', 'basics', 'Record number', '域名备案号', 'string', '渝ICP备8888888号-1', NULL, '', '', '0', '0');
INSERT INTO `ra_config` VALUES ('4', 'version', 'basics', 'Version number', '系统版本号', 'string', 'v1.0.0', NULL, 'required', '', '0', '0');
INSERT INTO `ra_config` VALUES ('5', 'time_zone', 'basics', 'time zone', '', 'string', 'Asia/Shanghai', NULL, 'required', '', '0', '0');
INSERT INTO `ra_config` VALUES ('6', 'no_access_ip', 'basics', 'No access ip', '禁止访问站点的ip列表,一行一个', 'textarea', '', NULL, '', '', '0', '0');
INSERT INTO `ra_config` VALUES ('7', 'smtp_server', 'mail', 'smtp server', '', 'string', 'smtp.qq.com', NULL, '', '', '0', '9');
INSERT INTO `ra_config` VALUES ('8', 'smtp_port', 'mail', 'smtp port', '', 'string', '465', NULL, '', '', '0', '8');
INSERT INTO `ra_config` VALUES ('9', 'smtp_user', 'mail', 'smtp user', '', 'string', NULL, NULL, '', '', '0', '7');
INSERT INTO `ra_config` VALUES ('10', 'smtp_pass', 'mail', 'smtp pass', '', 'string', NULL, NULL, '', '', '0', '6');
INSERT INTO `ra_config` VALUES ('11', 'smtp_verification', 'mail', 'smtp verification', '', 'select', 'SSL', '{\"SSL\":\"SSL\",\"TLS\":\"TLS\"}', '', '', '0', '5');
INSERT INTO `ra_config` VALUES ('12', 'smtp_sender_mail', 'mail', 'smtp sender mail', '', 'string', NULL, NULL, 'email', '', '0', '4');
INSERT INTO `ra_config` VALUES ('13', 'config_quick_entrance', 'config_quick_entrance', 'Config Quick entrance', '', 'array', '[{\"key\":\"数据回收规则配置\",\"value\":\"/admin/security/dataRecycle\"},{\"key\":\"敏感数据规则配置\",\"value\":\"/admin/security/sensitiveData\"}]', NULL, '', '', '0', '0');
INSERT INTO `ra_config` VALUES ('14', 'backend_entrance', 'basics', 'Backend entrance', '', 'string', '/admin', NULL, 'required', '', '0', '1');
INSERT INTO `ra_config` VALUES ('17', 'driver', 'authentication', '驱动类型', '默认驱动类型', 'radio', 'jwt', '{\"jwt\":\"Jwt\",\"cache\":\"Cache\",\"mysql\":\"Mysql\",\"redis\":\"Redis\"}', '', '{\"blockHelp\":\"1. \\u63a8\\u8350JWT,\\u4e0d\\u5b58\\u50a8\\u65e0\\u72b6\\u6001,\\u66f4\\u5b89\\u5168   2. Cache\\u65b9\\u5f0f\\u652f\\u6301File,Redis\\u7b49\"}', '0', '999');
INSERT INTO `ra_config` VALUES ('18', 'expire_time', 'authentication', 'Token有效期(秒)', '单位秒,安全起见不要设置太长', 'number', '1000', NULL, 'required,integer', '', '0', '965');
INSERT INTO `ra_config` VALUES ('19', 'keep_time', 'authentication', '保持会话时间(秒)', '单位秒,默认7天', 'number', '604800', NULL, 'required,integer', '', '0', '960');
INSERT INTO `ra_config` VALUES ('20', 'algo', 'authentication', '加密方式', '哈希算法,不推荐MD5', 'radio', 'ripemd256', '{\"md5 \":\"MD5\",\"sha256\":\"SHA-256\",\"whirlpool\":\"Whirlpool\",\"ripemd256\":\"RIPEMD-256\",\"gost\":\"GOST\"}', '', '', '0', '971');
INSERT INTO `ra_config` VALUES ('29', 'jwt_algo', 'authentication', 'JWT签名算法', 'JWT签名算法,暂不支持RS非对称加密', 'radio', 'HS256', '{\"HS256\":\"HS256\",\"HS384\":\"HS384\",\"HS512\":\"HS512\"}', '', '', '0', '980');
INSERT INTO `ra_config` VALUES ('38', 'secret', 'authentication', '加密密钥', '密钥字串', 'password', '17z$!aZIDVF37Yz&4MQH+^Dxe3kmwOV^N0d^bbH6p^q2bKh61HV^ojhh*)xcsNS)&jB1)Y(t&yep4HDX3z2yMv0d7lkZiUD9N@87+Xxbr^T2iNDj0JuOo75dKvzSzoZrhzRYl2Y9RbAIRK2J!Mlu*s)_ZKGyfc0Ki@q7sx0Gut#cPi9KGg6AcC)4!j)QeQW4aAs*XoZOirOYyf2)kkh(qem3E#N#hlPLQ#m5g9Y1$5Z6UvT2fbONZqExDYe8XDGl', NULL, 'required', '', '0', '970');
INSERT INTO `ra_config` VALUES ('39', 'jwt_secret', 'authentication', 'JWT加密密钥', 'JWT加密密钥', 'password', 'jxP*YIHZpPSb4Sae(x^HUQjQc04g4oCWhEJ_p$7UUUm@toF1ssunj3e5UctAQ@ic2MZUVQxrGj_+po6MTnyGGM^41nb61g#PVf!RAy&SQ7nB#RSF)nO%PJKI#G&Vqi0UYrQ+ICatKqdkTEuKlq)OCFXfqyOpU_aMwZT!Lw+%!#yTI)hxc#+24gHF9kIyaPOugJ5piBvSt7X%BiKRL@$MU)M7bkrHy9fSFa+hxYIEcuGZ^nxAUbn+5VS5Zv50DZ(h', NULL, 'required', '', '0', '979');
INSERT INTO `ra_config` VALUES ('41', 'iss', 'authentication', '签发者标识', '签发者标识', 'string', 'Radmin', NULL, 'required', '', '0', '9999');
INSERT INTO `ra_config` VALUES ('42', 'allow_keys', 'authentication', '允许的字段', '允许的字段', 'array', '[{\"key\":\"iss\",\"value\":\"\\u7b7e\\u53d1\\u8005\"},{\"key\":\"sub\",\"value\":\"\\u7528\\u6237 ID\"},{\"key\":\"exp\",\"value\":\"\\u8fc7\\u671f\\u65f6\\u95f4\"},{\"key\":\"iat\",\"value\":\"\\u7b7e\\u53d1\\u65f6\\u95f4\"},{\"key\":\"jti\",\"value\":\"\\u552f\\u4e00\\u6807\\u8bc6\\u7b26\"},{\"key\":\"roles\",\"value\":\"\\u7528\\u6237\\u89d2\\u8272\"},{\"key\":\"type\",\"value\":\"TOKEN\\u7c7b\\u578b\"},{\"key\":\"role\",\"value\":\"\\u4e25\\u683c\\u89d2\\u8272\"}]', NULL, 'required', '', '0', '955');
INSERT INTO `ra_config` VALUES ('43', 'backup_path', 'data', '备份路径', '备份所在相对路径', 'string', '/backup/', NULL, 'required', '{\"baInputExtend\":{\"placeholder\":\"\\u9ed8\\u8ba4\\u662f\\u5728runtime\\/backup\"}}', '1', '0');
INSERT INTO `ra_config` VALUES ('45', 'data', 'terminal', '数据管理', '', 'array', '[{\"key\":\"backup\",\"value\":\"php webman data:backup -a -z\"}]', NULL, '', '', '1', '0');
INSERT INTO `ra_config` VALUES ('46', 'host', 'system', '后端主机地址', '不能为空,否则命令行文件生成等功能异常', 'string', 'http://localhost:8787', NULL, '', '{\"baInputExtend\":{\"placeholder\":\"\\u4e0d\\u80fd\\u4e3a\\u7a7a,\\u5426\\u5219\\u547d\\u4ee4\\u884c\\u6587\\u4ef6\\u751f\\u6210\\u7b49\\u529f\\u80fd\\u5f02\\u5e38\"}}', '1', '0');
