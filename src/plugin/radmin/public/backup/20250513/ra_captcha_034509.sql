-- Table structure for ra_captcha
CREATE TABLE `ra_captcha` (
  `key` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证码Key',
  `code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '验证码(加密后)',
  `captcha` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '验证码数据',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  `expire_time` bigint unsigned DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='验证码表';

-- Data for ra_captcha
INSERT INTO `ra_captcha` VALUES ('b8949b8955c452a827f6a49046f5f115', '2593cb876b5ca08bb9e6f92260d08fc0', '{\"text\":[{\"size\":25,\"icon\":false,\"text\":\"延\",\"width\":32,\"height\":29,\"x\":178,\"y\":73},{\"size\":17,\"icon\":false,\"text\":\"觉\",\"width\":21,\"height\":19,\"x\":72,\"y\":89}],\"width\":350,\"height\":200}', '1746998644', '1746999244');
