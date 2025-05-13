-- Table structure for ra_security_data_recycle
CREATE TABLE `ra_security_data_recycle` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `controller` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '控制器',
  `controller_as` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '控制器别名',
  `data_table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '对应数据表',
  `connection` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '数据库连接配置标识',
  `primary_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '数据表主键',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:0=禁用,1=启用',
  `update_time` bigint unsigned DEFAULT NULL COMMENT '更新时间',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='回收规则表';

-- Data for ra_security_data_recycle
INSERT INTO `ra_security_data_recycle` VALUES ('1', '管理员', 'auth/Admin.php', 'auth/admin', 'admin', '', 'id', '1', '1746723960', '1746723960');
INSERT INTO `ra_security_data_recycle` VALUES ('2', '管理员日志', 'auth/AdminLog.php', 'auth/adminlog', 'admin_log', '', 'id', '1', '1746723960', '1746723960');
INSERT INTO `ra_security_data_recycle` VALUES ('3', '菜单规则', 'auth/Menu.php', 'auth/menu', 'menu_rule', '', 'id', '1', '1746723960', '1746723960');
INSERT INTO `ra_security_data_recycle` VALUES ('4', '系统配置项', 'routine/Config.php', 'routine/config', 'config', '', 'id', '1', '1746723960', '1746723960');
INSERT INTO `ra_security_data_recycle` VALUES ('5', '会员', 'user/User.php', 'user/user', 'user', 'mysql', 'id', '1', '1746738237', '1746723960');
INSERT INTO `ra_security_data_recycle` VALUES ('6', '数据回收规则', 'security/DataRecycle.php', 'security/datarecycle', 'security_data_recycle', 'mysql', 'id', '1', '1746738231', '1746723960');
