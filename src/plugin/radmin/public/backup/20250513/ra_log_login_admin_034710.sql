-- Table structure for ra_log_login_admin
CREATE TABLE `ra_log_login_admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `log` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '日志',
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `inout` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '类型:0=未知,1=login,2=logout',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态:0=失败,1=成功',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理员登录';

-- Data for ra_log_login_admin
