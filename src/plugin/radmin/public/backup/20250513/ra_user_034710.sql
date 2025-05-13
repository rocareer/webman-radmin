-- Table structure for ra_user
CREATE TABLE `ra_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int unsigned NOT NULL DEFAULT '0' COMMENT '分组ID',
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `gender` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '性别:0=未知,1=男,2=女',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `money` int unsigned NOT NULL DEFAULT '0' COMMENT '余额',
  `score` int unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `last_login_time` bigint unsigned DEFAULT NULL COMMENT '上次登录时间',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '上次登录IP',
  `login_failure` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '登录失败次数',
  `join_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '加入IP',
  `join_time` bigint unsigned DEFAULT NULL COMMENT '加入时间',
  `motto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '签名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码盐（废弃待删）',
  `status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '状态:enable=启用,disable=禁用',
  `update_time` bigint unsigned DEFAULT NULL COMMENT '更新时间',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='会员表';

-- Data for ra_user
INSERT INTO `ra_user` VALUES ('1', '1', 'user', 'User', '18888888888@qq.com', '18888888888', '/storage/default/20250509/logo43240f82cba37fb6e1b097ecff178023af8e6383.png', '2', '2020-05-09', '0', '0', '1747008081', '127.0.0.1', '0', '', NULL, '', '$2y$10$WbxWagDRbiRUUccesOzqPe/37OJXu2XZs/aR56wup.u/9cSYS3M3m', '', 'enable', '1747008081', '2025');
INSERT INTO `ra_user` VALUES ('2', '3', 'user2', 'user2', '', '', '/storage/default/20250512/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg', '0', NULL, '0', '0', '1747008862', '127.0.0.1', '0', '', NULL, '', '$2y$10$dMN.eVEDwhrxu6KTCtycm.GA.tN0CkxDrXUgTAWcHK5/GWJqnNvK.', '', 'enable', '1747060931', '1970');
