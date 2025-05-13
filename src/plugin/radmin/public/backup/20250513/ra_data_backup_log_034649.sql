-- Table structure for ra_data_backup_log
CREATE TABLE `ra_data_backup_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `string` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '字符串',
  `backup_id` int unsigned DEFAULT NULL COMMENT '远程下拉',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='操作日志';

-- Data for ra_data_backup_log
