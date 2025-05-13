-- Table structure for ra_admin_rule
CREATE TABLE `ra_admin_rule` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单',
  `type` enum('menu_dir','menu','button') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menu' COMMENT '类型:menu_dir=菜单目录,menu=菜单项,button=页面按钮',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '规则名称',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路由路径',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `menu_type` enum('tab','link','iframe') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '菜单类型:tab=选项卡,link=链接,iframe=Iframe',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Url',
  `component` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '组件路径',
  `keepalive` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '缓存:0=关闭,1=开启',
  `extend` enum('none','add_rules_only','add_menu_only') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none' COMMENT '扩展属性:none=无,add_rules_only=只添加为路由,add_menu_only=只添加为菜单',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `weigh` int NOT NULL DEFAULT '0' COMMENT '权重',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:0=禁用,1=启用',
  `update_time` bigint unsigned DEFAULT NULL COMMENT '更新时间',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='菜单和权限规则表';

-- Data for ra_admin_rule
INSERT INTO `ra_admin_rule` VALUES ('1', '0', 'menu', '控制台', 'dashboard', 'dashboard', 'fa fa-dashboard', 'tab', '', '/src/views/backend/dashboard.vue', '1', 'none', 'Remark lang', '999', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('2', '0', 'menu_dir', '权限管理', 'auth', 'auth', 'fa fa-group', NULL, '', '', '0', 'none', '', '100', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('3', '2', 'menu', '角色组管理', 'auth/group', 'auth/group', 'fa fa-group', 'tab', '', '/src/views/backend/auth/group/index.vue', '1', 'none', 'Remark lang', '99', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('4', '3', 'button', '查看', 'auth/group/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('5', '3', 'button', '添加', 'auth/group/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('6', '3', 'button', '编辑', 'auth/group/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('7', '3', 'button', '删除', 'auth/group/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('8', '2', 'menu', '管理员管理', 'auth/admin', 'auth/admin', 'el-icon-UserFilled', 'tab', '', '/src/views/backend/auth/admin/index.vue', '1', 'none', '', '98', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('9', '8', 'button', '查看', 'auth/admin/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('10', '8', 'button', '添加', 'auth/admin/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('11', '8', 'button', '编辑', 'auth/admin/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('12', '8', 'button', '删除', 'auth/admin/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('13', '2', 'menu', '菜单规则管理', 'auth/rule', 'auth/rule', 'el-icon-Grid', 'tab', '', '/src/views/backend/auth/rule/index.vue', '1', 'none', '', '97', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('14', '13', 'button', '查看', 'auth/rule/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('15', '13', 'button', '添加', 'auth/rule/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('16', '13', 'button', '编辑', 'auth/rule/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('17', '13', 'button', '删除', 'auth/rule/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('18', '13', 'button', '快速排序', 'auth/rule/sortable', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('19', '2', 'menu', '管理员日志管理', 'auth/adminLog', 'auth/adminLog', 'el-icon-List', 'tab', '', '/src/views/backend/auth/adminLog/index.vue', '1', 'none', '', '96', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('20', '19', 'button', '查看', 'auth/adminLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('21', '0', 'menu_dir', '会员管理', 'user', 'user', 'fa fa-drivers-license', NULL, '', '', '0', 'none', '', '95', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('22', '21', 'menu', '会员管理', 'user/user', 'user/user', 'fa fa-user', 'tab', '', '/src/views/backend/user/user/index.vue', '1', 'none', '', '94', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('23', '22', 'button', '查看', 'user/user/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('24', '22', 'button', '添加', 'user/user/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('25', '22', 'button', '编辑', 'user/user/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('26', '22', 'button', '删除', 'user/user/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('27', '21', 'menu', '会员分组管理', 'user/group', 'user/group', 'fa fa-group', 'tab', '', '/src/views/backend/user/group/index.vue', '1', 'none', '', '93', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('28', '27', 'button', '查看', 'user/group/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('29', '27', 'button', '添加', 'user/group/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('30', '27', 'button', '编辑', 'user/group/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('31', '27', 'button', '删除', 'user/group/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('32', '21', 'menu', '会员规则管理', 'user/rule', 'user/rule', 'fa fa-th-list', 'tab', '', '/src/views/backend/user/rule/index.vue', '1', 'none', '', '92', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('33', '32', 'button', '查看', 'user/rule/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('34', '32', 'button', '添加', 'user/rule/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('35', '32', 'button', '编辑', 'user/rule/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('36', '32', 'button', '删除', 'user/rule/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('37', '32', 'button', '快速排序', 'user/rule/sortable', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('38', '21', 'menu', '会员余额管理', 'user/moneyLog', 'user/moneyLog', 'el-icon-Money', 'tab', '', '/src/views/backend/user/moneyLog/index.vue', '1', 'none', '', '91', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('39', '38', 'button', '查看', 'user/moneyLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('40', '38', 'button', '添加', 'user/moneyLog/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('41', '21', 'menu', '会员积分管理', 'user/scoreLog', 'user/scoreLog', 'el-icon-Discount', 'tab', '', '/src/views/backend/user/scoreLog/index.vue', '1', 'none', '', '90', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('42', '41', 'button', '查看', 'user/scoreLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('43', '41', 'button', '添加', 'user/scoreLog/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('44', '0', 'menu_dir', '常规管理', 'routine', 'routine', 'fa fa-cogs', NULL, '', '', '0', 'none', '', '89', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('45', '44', 'menu', '系统配置', 'routine/config', 'routine/config', 'el-icon-Tools', 'tab', '', '/src/views/backend/routine/config/index.vue', '1', 'none', '', '88', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('46', '45', 'button', '查看', 'routine/config/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('47', '45', 'button', '编辑', 'routine/config/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('48', '44', 'menu', '附件管理', 'routine/attachment', 'routine/attachment', 'fa fa-folder', 'tab', '', '/src/views/backend/routine/attachment/index.vue', '1', 'none', 'Remark lang', '87', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('49', '48', 'button', '查看', 'routine/attachment/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('50', '48', 'button', '编辑', 'routine/attachment/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('51', '48', 'button', '删除', 'routine/attachment/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('52', '44', 'menu', '个人资料', 'routine/adminInfo', 'routine/adminInfo', 'fa fa-user', 'tab', '', '/src/views/backend/routine/adminInfo.vue', '1', 'none', '', '86', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('53', '52', 'button', '查看', 'routine/adminInfo/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('54', '52', 'button', '编辑', 'routine/adminInfo/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('55', '0', 'menu_dir', '数据安全管理', 'security', 'security', 'fa fa-shield', NULL, '', '', '0', 'none', '', '85', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('56', '55', 'menu', '数据回收站', 'security/dataRecycleLog', 'security/dataRecycleLog', 'fa fa-database', 'tab', '', '/src/views/backend/security/dataRecycleLog/index.vue', '1', 'none', '', '84', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('57', '56', 'button', '查看', 'security/dataRecycleLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('58', '56', 'button', '删除', 'security/dataRecycleLog/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('59', '56', 'button', '还原', 'security/dataRecycleLog/restore', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('60', '56', 'button', '查看详情', 'security/dataRecycleLog/info', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('61', '55', 'menu', '敏感数据修改记录', 'security/sensitiveDataLog', 'security/sensitiveDataLog', 'fa fa-expeditedssl', 'tab', '', '/src/views/backend/security/sensitiveDataLog/index.vue', '1', 'none', '', '83', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('62', '61', 'button', '查看', 'security/sensitiveDataLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('63', '61', 'button', '删除', 'security/sensitiveDataLog/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('64', '61', 'button', '回滚', 'security/sensitiveDataLog/rollback', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('65', '61', 'button', '查看详情', 'security/sensitiveDataLog/info', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('66', '55', 'menu', '数据回收规则管理', 'security/dataRecycle', 'security/dataRecycle', 'fa fa-database', 'tab', '', '/src/views/backend/security/dataRecycle/index.vue', '1', 'none', 'Remark lang', '82', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('67', '66', 'button', '查看', 'security/dataRecycle/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('68', '66', 'button', '添加', 'security/dataRecycle/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('69', '66', 'button', '编辑', 'security/dataRecycle/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('70', '66', 'button', '删除', 'security/dataRecycle/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('71', '55', 'menu', '敏感字段规则管理', 'security/sensitiveData', 'security/sensitiveData', 'fa fa-expeditedssl', 'tab', '', '/src/views/backend/security/sensitiveData/index.vue', '1', 'none', 'Remark lang', '81', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('72', '71', 'button', '查看', 'security/sensitiveData/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('73', '71', 'button', '添加', 'security/sensitiveData/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('74', '71', 'button', '编辑', 'security/sensitiveData/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('75', '71', 'button', '删除', 'security/sensitiveData/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('76', '0', 'menu', 'BuildAdmin', 'buildadmin', 'buildadmin', 'local-logo', 'link', 'https://doc.buildadmin.com', '', '0', 'none', '', '0', '0', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('77', '45', 'button', '添加', 'routine/config/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('78', '0', 'menu', '模块市场', 'moduleStore/moduleStore', 'moduleStore', 'el-icon-GoodsFilled', 'tab', '', '/src/views/backend/module/index.vue', '1', 'none', '', '86', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('79', '78', 'button', '查看', 'moduleStore/moduleStore/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('80', '78', 'button', '安装', 'moduleStore/moduleStore/install', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('81', '78', 'button', '调整状态', 'moduleStore/moduleStore/changeState', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('82', '78', 'button', '卸载', 'moduleStore/moduleStore/uninstall', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('83', '78', 'button', '更新', 'moduleStore/moduleStore/update', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('84', '0', 'menu', 'CRUD代码生成', 'crud/crud', 'crud/crud', 'fa fa-code', 'tab', '', '/src/views/backend/crud/index.vue', '1', 'none', '', '80', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('85', '84', 'button', '查看', 'crud/crud/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('86', '84', 'button', '生成', 'crud/crud/generate', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('87', '84', 'button', '删除', 'crud/crud/delete', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('88', '45', 'button', '删除', 'routine/config/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('89', '1', 'button', '查看', 'dashboard/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');
INSERT INTO `ra_admin_rule` VALUES ('132', '55', 'menu_dir', '数据库备份', 'security/backup', 'security/backup', 'fa fa-upload', NULL, '', '', '0', 'none', '', '0', '1', '1747048032', '1747047140');
INSERT INTO `ra_admin_rule` VALUES ('146', '0', 'menu_dir', '数据管理', 'data', '', 'fa fa-database', 'tab', '', '', '0', 'none', '', '0', '1', '1747050863', '1747050813');
INSERT INTO `ra_admin_rule` VALUES ('160', '146', 'menu', '数据备份', 'data/backup', 'data/backup', '', 'tab', '', '/src/views/backend/data/backup/index.vue', '1', 'none', '', '0', '1', '1747063292', '1747051055');
INSERT INTO `ra_admin_rule` VALUES ('161', '160', 'button', '查看', 'data/backup/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747063377', '1747051055');
INSERT INTO `ra_admin_rule` VALUES ('162', '160', 'button', '添加', 'data/backup/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747063359', '1747051055');
INSERT INTO `ra_admin_rule` VALUES ('163', '160', 'button', '编辑', 'data/backup/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747063344', '1747051055');
INSERT INTO `ra_admin_rule` VALUES ('164', '160', 'button', '删除', 'data/backup/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747063328', '1747051055');
INSERT INTO `ra_admin_rule` VALUES ('165', '160', 'button', '快速排序', 'data/backup/sortable', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747063314', '1747051055');
INSERT INTO `ra_admin_rule` VALUES ('166', '0', 'menu_dir', '日志管理', 'log', 'log', 'el-icon-InfoFilled', NULL, '', '', '0', 'none', '', '0', '1', '1747051833', '1747051767');
INSERT INTO `ra_admin_rule` VALUES ('167', '166', 'menu_dir', 'login', 'login', 'login', '', NULL, '', '', '0', 'none', '', '0', '1', '1747051767', '1747051767');
INSERT INTO `ra_admin_rule` VALUES ('168', '167', 'menu', '管理员登录', 'log/login/admin', 'log/login/admin', '', 'tab', '', '/src/views/backend/log/login/admin/index.vue', '1', 'none', '', '0', '1', '1747051767', '1747051767');
INSERT INTO `ra_admin_rule` VALUES ('169', '168', 'button', '查看', 'log/login/admin/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747051767', '1747051767');
INSERT INTO `ra_admin_rule` VALUES ('170', '168', 'button', '添加', 'log/login/admin/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747051767', '1747051767');
INSERT INTO `ra_admin_rule` VALUES ('171', '168', 'button', '编辑', 'log/login/admin/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747051767', '1747051767');
INSERT INTO `ra_admin_rule` VALUES ('172', '168', 'button', '删除', 'log/login/admin/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747051767', '1747051767');
INSERT INTO `ra_admin_rule` VALUES ('173', '168', 'button', '快速排序', 'log/login/admin/sortable', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747051767', '1747051767');
INSERT INTO `ra_admin_rule` VALUES ('175', '146', 'menu', '数据表', 'data/table', 'data/table', '', 'tab', '', '/src/views/backend/data/table/index.vue', '1', 'none', '', '0', '1', '1747062437', '1747053457');
INSERT INTO `ra_admin_rule` VALUES ('176', '175', 'button', '查看', 'data/table/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747062307', '1747053457');
INSERT INTO `ra_admin_rule` VALUES ('177', '175', 'button', '添加', 'data/table/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747062359', '1747053457');
INSERT INTO `ra_admin_rule` VALUES ('178', '175', 'button', '编辑', 'data/table/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747062390', '1747053457');
INSERT INTO `ra_admin_rule` VALUES ('179', '175', 'button', '删除', 'data/table/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747062373', '1747053457');
INSERT INTO `ra_admin_rule` VALUES ('180', '175', 'button', '快速排序', 'data/table/sortable', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747062342', '1747053457');
INSERT INTO `ra_admin_rule` VALUES ('181', '175', 'button', '同步', 'data/table/sync', '', 'fa fa-circle-o', 'tab', '', '', '0', 'none', '', '0', '1', '1747062322', '1747061573');
INSERT INTO `ra_admin_rule` VALUES ('182', '0', 'menu', '搜索', 'teset', 'teset', '', 'tab', '', '/src/views/backend/teset/index.vue', '1', 'none', '', '0', '1', '1747077513', '1747077513');
INSERT INTO `ra_admin_rule` VALUES ('183', '182', 'button', '查看', 'teset/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747077513', '1747077513');
INSERT INTO `ra_admin_rule` VALUES ('184', '182', 'button', '添加', 'teset/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747077513', '1747077513');
INSERT INTO `ra_admin_rule` VALUES ('185', '182', 'button', '编辑', 'teset/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747077513', '1747077513');
INSERT INTO `ra_admin_rule` VALUES ('186', '182', 'button', '删除', 'teset/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747077513', '1747077513');
INSERT INTO `ra_admin_rule` VALUES ('187', '182', 'button', '快速排序', 'teset/sortable', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1747077513', '1747077513');
