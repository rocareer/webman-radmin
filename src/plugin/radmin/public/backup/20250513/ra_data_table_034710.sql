-- Table structure for ra_data_table
CREATE TABLE `ra_data_table` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表名',
  `charset` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '字符集',
  `record_count` int DEFAULT NULL COMMENT '记录数',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
  `engine` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '引擎',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='数据表';

-- Data for ra_data_table
INSERT INTO `ra_data_table` VALUES ('1', 'ra_admin', 'utf8mb4', '1', '管理员表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('2', 'ra_admin_group', 'utf8mb4', '4', '管理分组表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('3', 'ra_admin_group_access', 'utf8mb4', '1', '管理分组映射表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('4', 'ra_admin_log', 'utf8mb4', '93', '管理员日志表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('5', 'ra_admin_rule', 'utf8mb4', '89', '菜单和权限规则表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('6', 'ra_area', 'utf8mb4', '0', '省份地区表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('7', 'ra_attachment', 'utf8mb4', '3', '附件表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('8', 'ra_captcha', 'utf8mb4', '1', '验证码表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('9', 'ra_config', 'utf8mb4', '23', '系统配置', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('10', 'ra_crud_log', 'utf8mb4', '8', 'CRUD记录表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('12', 'ra_data_backup_log', 'utf8mb4', '0', '操作日志', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('14', 'ra_log_login_admin', 'utf8mb4', '0', '管理员登录', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('15', 'ra_migrations', 'utf8mb4', '9', '', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('16', 'ra_security_data_recycle', 'utf8mb4', '6', '回收规则表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('17', 'ra_security_data_recycle_log', 'utf8mb4', '6', '数据回收记录表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('18', 'ra_security_sensitive_data', 'utf8mb4', '3', '敏感数据规则表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('19', 'ra_security_sensitive_data_log', 'utf8mb4', '2', '敏感数据修改记录', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('20', 'ra_test_build', 'utf8mb4', '0', '知识库表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('21', 'ra_token', 'utf8mb4', '0', '用户Token表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('22', 'ra_user', 'utf8mb4', '1', '会员表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('23', 'ra_user_group', 'utf8mb4', '3', '会员组表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('24', 'ra_user_money_log', 'utf8mb4', '0', '会员余额变动表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('25', 'ra_user_rule', 'utf8mb4', '6', '会员菜单权限规则表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('26', 'ra_user_score_log', 'utf8mb4', '0', '会员积分变动表', 'InnoDB', '', '1747058645');
INSERT INTO `ra_data_table` VALUES ('27', 'ra_data_backup', 'utf8mb4', '1', '数据备份', 'InnoDB', '', '1747076849');
INSERT INTO `ra_data_table` VALUES ('28', 'ra_data_table', 'utf8mb4', '26', '数据表', 'InnoDB', '', '1747076849');
