-- Table structure for ra_admin_group_access
CREATE TABLE `ra_admin_group_access` (
  `uid` int unsigned NOT NULL COMMENT '管理员ID',
  `group_id` int unsigned NOT NULL COMMENT '分组ID',
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理分组映射表';

-- Data for ra_admin_group_access
INSERT INTO `ra_admin_group_access` VALUES ('1', '1');
