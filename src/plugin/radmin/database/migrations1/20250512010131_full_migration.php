<?php
/** @noinspection ALL */

namespace database\migrations;

use upport\think\Db;

class FullMigration
{
    public function up()
    {
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_admin` (
            `id` int unsigned NOT NULL,
            `username` varchar(20) NOT NULL DEFAULT '',
            `nickname` varchar(50) NOT NULL DEFAULT '',
            `avatar` varchar(255) NOT NULL DEFAULT '',
            `email` varchar(50) NOT NULL DEFAULT '',
            `mobile` varchar(11) NOT NULL DEFAULT '',
            `login_failure` tinyint unsigned NOT NULL DEFAULT '0',
            `last_login_time` bigint unsigned,
            `last_login_ip` varchar(50) NOT NULL DEFAULT '',
            `password` varchar(255) NOT NULL DEFAULT '',
            `salt` varchar(30) NOT NULL DEFAULT '',
            `motto` varchar(255) NOT NULL DEFAULT '',
            `status` varchar(30) NOT NULL DEFAULT '',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_admin` VALUES ('1', 'admin', 'Admin', '/storage/default/20250509/logo43240f82cba37fb6e1b097ecff178023af8e6383.png', 'admin@buildadmin.com', '18888888888', '0', NULL, '', '$2y$12$U7YW4Df9p/T2NsHmUE82tOGaVhpj4/tBBEQjzU7jcEstVdT1vcvH6', 'aFxSeLNqEodr5cMG', '1', 'enable', '1746827330', '1746723960');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_admin_group` (
            `id` int unsigned NOT NULL,
            `pid` int unsigned NOT NULL DEFAULT '0',
            `name` varchar(100) NOT NULL DEFAULT '',
            `rules` text,
            `status` enum('0','1') NOT NULL DEFAULT '1',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_admin_group` VALUES ('1', '0', '超级管理组', '*', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_group` VALUES ('2', '1', '一级管理员', '1,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,77,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,89', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_group` VALUES ('3', '2', '二级管理员', '21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_group` VALUES ('4', '3', '三级管理员', '55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75', '1', '1746723960', '1746723960');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_admin_group_access` (
            `uid` int unsigned NOT NULL,
            `group_id` int unsigned NOT NULL
        );" );
        Db::execute("INSERT INTO `ra_admin_group_access` VALUES ('1', '1');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_admin_log` (
            `id` int unsigned NOT NULL,
            `admin_id` int unsigned NOT NULL DEFAULT '0',
            `username` varchar(20) NOT NULL DEFAULT '',
            `url` varchar(1500) NOT NULL DEFAULT '',
            `title` varchar(100) NOT NULL DEFAULT '',
            `data` longtext,
            `ip` varchar(50) NOT NULL DEFAULT '',
            `useragent` varchar(255) NOT NULL DEFAULT '',
            `create_time` bigint unsigned
        );" );
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_admin_rule` (
            `id` int unsigned NOT NULL,
            `pid` int unsigned NOT NULL DEFAULT '0',
            `type` enum('menu_dir','menu','button') NOT NULL DEFAULT 'menu',
            `title` varchar(50) NOT NULL DEFAULT '',
            `name` varchar(50) NOT NULL DEFAULT '',
            `path` varchar(100) NOT NULL DEFAULT '',
            `icon` varchar(50) NOT NULL DEFAULT '',
            `menu_type` enum('tab','link','iframe'),
            `url` varchar(255) NOT NULL DEFAULT '',
            `component` varchar(100) NOT NULL DEFAULT '',
            `keepalive` tinyint unsigned NOT NULL DEFAULT '0',
            `extend` enum('none','add_rules_only','add_menu_only') NOT NULL DEFAULT 'none',
            `remark` varchar(255) NOT NULL DEFAULT '',
            `weigh` int NOT NULL DEFAULT '0',
            `status` enum('0','1') NOT NULL DEFAULT '1',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('1', '0', 'menu', '控制台', 'dashboard', 'dashboard', 'fa fa-dashboard', 'tab', '', '/src/views/backend/dashboard.vue', '1', 'none', 'Remark lang', '999', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('2', '0', 'menu_dir', '权限管理', 'auth', 'auth', 'fa fa-group', NULL, '', '', '0', 'none', '', '100', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('3', '2', 'menu', '角色组管理', 'auth/group', 'auth/group', 'fa fa-group', 'tab', '', '/src/views/backend/auth/group/index.vue', '1', 'none', 'Remark lang', '99', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('4', '3', 'button', '查看', 'auth/group/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('5', '3', 'button', '添加', 'auth/group/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('6', '3', 'button', '编辑', 'auth/group/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('7', '3', 'button', '删除', 'auth/group/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('8', '2', 'menu', '管理员管理', 'auth/admin', 'auth/admin', 'el-icon-UserFilled', 'tab', '', '/src/views/backend/auth/admin/index.vue', '1', 'none', '', '98', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('9', '8', 'button', '查看', 'auth/admin/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('10', '8', 'button', '添加', 'auth/admin/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('11', '8', 'button', '编辑', 'auth/admin/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('12', '8', 'button', '删除', 'auth/admin/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('13', '2', 'menu', '菜单规则管理', 'auth/rule', 'auth/rule', 'el-icon-Grid', 'tab', '', '/src/views/backend/auth/rule/index.vue', '1', 'none', '', '97', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('14', '13', 'button', '查看', 'auth/rule/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('15', '13', 'button', '添加', 'auth/rule/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('16', '13', 'button', '编辑', 'auth/rule/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('17', '13', 'button', '删除', 'auth/rule/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('18', '13', 'button', '快速排序', 'auth/rule/sortable', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('19', '2', 'menu', '管理员日志管理', 'auth/adminLog', 'auth/adminLog', 'el-icon-List', 'tab', '', '/src/views/backend/auth/adminLog/index.vue', '1', 'none', '', '96', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('20', '19', 'button', '查看', 'auth/adminLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('21', '0', 'menu_dir', '会员管理', 'user', 'user', 'fa fa-drivers-license', NULL, '', '', '0', 'none', '', '95', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('22', '21', 'menu', '会员管理', 'user/user', 'user/user', 'fa fa-user', 'tab', '', '/src/views/backend/user/user/index.vue', '1', 'none', '', '94', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('23', '22', 'button', '查看', 'user/user/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('24', '22', 'button', '添加', 'user/user/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('25', '22', 'button', '编辑', 'user/user/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('26', '22', 'button', '删除', 'user/user/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('27', '21', 'menu', '会员分组管理', 'user/group', 'user/group', 'fa fa-group', 'tab', '', '/src/views/backend/user/group/index.vue', '1', 'none', '', '93', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('28', '27', 'button', '查看', 'user/group/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('29', '27', 'button', '添加', 'user/group/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('30', '27', 'button', '编辑', 'user/group/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('31', '27', 'button', '删除', 'user/group/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('32', '21', 'menu', '会员规则管理', 'user/rule', 'user/rule', 'fa fa-th-list', 'tab', '', '/src/views/backend/user/rule/index.vue', '1', 'none', '', '92', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('33', '32', 'button', '查看', 'user/rule/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('34', '32', 'button', '添加', 'user/rule/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('35', '32', 'button', '编辑', 'user/rule/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('36', '32', 'button', '删除', 'user/rule/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('37', '32', 'button', '快速排序', 'user/rule/sortable', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('38', '21', 'menu', '会员余额管理', 'user/moneyLog', 'user/moneyLog', 'el-icon-Money', 'tab', '', '/src/views/backend/user/moneyLog/index.vue', '1', 'none', '', '91', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('39', '38', 'button', '查看', 'user/moneyLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('40', '38', 'button', '添加', 'user/moneyLog/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('41', '21', 'menu', '会员积分管理', 'user/scoreLog', 'user/scoreLog', 'el-icon-Discount', 'tab', '', '/src/views/backend/user/scoreLog/index.vue', '1', 'none', '', '90', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('42', '41', 'button', '查看', 'user/scoreLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('43', '41', 'button', '添加', 'user/scoreLog/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('44', '0', 'menu_dir', '常规管理', 'routine', 'routine', 'fa fa-cogs', NULL, '', '', '0', 'none', '', '89', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('45', '44', 'menu', '系统配置', 'routine/config', 'routine/config', 'el-icon-Tools', 'tab', '', '/src/views/backend/routine/config/index.vue', '1', 'none', '', '88', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('46', '45', 'button', '查看', 'routine/config/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('47', '45', 'button', '编辑', 'routine/config/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('48', '44', 'menu', '附件管理', 'routine/attachment', 'routine/attachment', 'fa fa-folder', 'tab', '', '/src/views/backend/routine/attachment/index.vue', '1', 'none', 'Remark lang', '87', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('49', '48', 'button', '查看', 'routine/attachment/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('50', '48', 'button', '编辑', 'routine/attachment/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('51', '48', 'button', '删除', 'routine/attachment/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('52', '44', 'menu', '个人资料', 'routine/adminInfo', 'routine/adminInfo', 'fa fa-user', 'tab', '', '/src/views/backend/routine/adminInfo.vue', '1', 'none', '', '86', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('53', '52', 'button', '查看', 'routine/adminInfo/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('54', '52', 'button', '编辑', 'routine/adminInfo/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('55', '0', 'menu_dir', '数据安全管理', 'security', 'security', 'fa fa-shield', NULL, '', '', '0', 'none', '', '85', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('56', '55', 'menu', '数据回收站', 'security/dataRecycleLog', 'security/dataRecycleLog', 'fa fa-database', 'tab', '', '/src/views/backend/security/dataRecycleLog/index.vue', '1', 'none', '', '84', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('57', '56', 'button', '查看', 'security/dataRecycleLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('58', '56', 'button', '删除', 'security/dataRecycleLog/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('59', '56', 'button', '还原', 'security/dataRecycleLog/restore', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('60', '56', 'button', '查看详情', 'security/dataRecycleLog/info', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('61', '55', 'menu', '敏感数据修改记录', 'security/sensitiveDataLog', 'security/sensitiveDataLog', 'fa fa-expeditedssl', 'tab', '', '/src/views/backend/security/sensitiveDataLog/index.vue', '1', 'none', '', '83', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('62', '61', 'button', '查看', 'security/sensitiveDataLog/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('63', '61', 'button', '删除', 'security/sensitiveDataLog/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('64', '61', 'button', '回滚', 'security/sensitiveDataLog/rollback', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('65', '61', 'button', '查看详情', 'security/sensitiveDataLog/info', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('66', '55', 'menu', '数据回收规则管理', 'security/dataRecycle', 'security/dataRecycle', 'fa fa-database', 'tab', '', '/src/views/backend/security/dataRecycle/index.vue', '1', 'none', 'Remark lang', '82', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('67', '66', 'button', '查看', 'security/dataRecycle/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('68', '66', 'button', '添加', 'security/dataRecycle/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('69', '66', 'button', '编辑', 'security/dataRecycle/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('70', '66', 'button', '删除', 'security/dataRecycle/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('71', '55', 'menu', '敏感字段规则管理', 'security/sensitiveData', 'security/sensitiveData', 'fa fa-expeditedssl', 'tab', '', '/src/views/backend/security/sensitiveData/index.vue', '1', 'none', 'Remark lang', '81', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('72', '71', 'button', '查看', 'security/sensitiveData/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('73', '71', 'button', '添加', 'security/sensitiveData/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('74', '71', 'button', '编辑', 'security/sensitiveData/edit', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('75', '71', 'button', '删除', 'security/sensitiveData/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('76', '0', 'menu', 'BuildAdmin', 'buildadmin', 'buildadmin', 'local-logo', 'link', 'https://doc.buildadmin.com', '', '0', 'none', '', '0', '0', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('77', '45', 'button', '添加', 'routine/config/add', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('78', '0', 'menu', '模块市场', 'moduleStore/moduleStore', 'moduleStore', 'el-icon-GoodsFilled', 'tab', '', '/src/views/backend/module/index.vue', '1', 'none', '', '86', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('79', '78', 'button', '查看', 'moduleStore/moduleStore/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('80', '78', 'button', '安装', 'moduleStore/moduleStore/install', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('81', '78', 'button', '调整状态', 'moduleStore/moduleStore/changeState', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('82', '78', 'button', '卸载', 'moduleStore/moduleStore/uninstall', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('83', '78', 'button', '更新', 'moduleStore/moduleStore/update', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('84', '0', 'menu', 'CRUD代码生成', 'crud/crud', 'crud/crud', 'fa fa-code', 'tab', '', '/src/views/backend/crud/index.vue', '1', 'none', '', '80', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('85', '84', 'button', '查看', 'crud/crud/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('86', '84', 'button', '生成', 'crud/crud/generate', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('87', '84', 'button', '删除', 'crud/crud/delete', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('88', '45', 'button', '删除', 'routine/config/del', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_admin_rule` VALUES ('89', '1', 'button', '查看', 'dashboard/index', '', '', NULL, '', '', '0', 'none', '', '0', '1', '1746723960', '1746723960');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_area` (
            `id` int unsigned NOT NULL,
            `pid` int unsigned,
            `shortname` varchar(100),
            `name` varchar(100),
            `mergename` varchar(255),
            `level` tinyint unsigned,
            `pinyin` varchar(100),
            `code` varchar(100),
            `zip` varchar(100),
            `first` varchar(50),
            `lng` varchar(50),
            `lat` varchar(50)
        );" );
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_attachment` (
            `id` int unsigned NOT NULL,
            `topic` varchar(20) NOT NULL DEFAULT '',
            `admin_id` int unsigned NOT NULL DEFAULT '0',
            `user_id` int unsigned NOT NULL DEFAULT '0',
            `url` varchar(255) NOT NULL DEFAULT '',
            `width` int unsigned NOT NULL DEFAULT '0',
            `height` int unsigned NOT NULL DEFAULT '0',
            `name` varchar(120) NOT NULL DEFAULT '',
            `size` int unsigned NOT NULL DEFAULT '0',
            `mimetype` varchar(100) NOT NULL DEFAULT '',
            `quote` int unsigned NOT NULL DEFAULT '0',
            `storage` varchar(50) NOT NULL DEFAULT '',
            `sha1` varchar(40) NOT NULL DEFAULT '',
            `create_time` bigint unsigned,
            `last_upload_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_attachment` VALUES ('1', 'default', '1', '0', '/storage/default/20250509/logo43240f82cba37fb6e1b097ecff178023af8e6383.png', '864', '823', 'logo.png', '317356', 'image/png', '2', 'local', '43240f82cba37fb6e1b097ecff178023af8e6383', '1746736751', '1746736865');");
        Db::execute("INSERT INTO `ra_attachment` VALUES ('2', 'default', '1', '0', '/storage/default/20250510/qr834776e09219bcace1886d051f3b28f8d84f0a6a.png', '300', '300', 'qr.png', '33408', 'image/png', '1', 'local', '834776e09219bcace1886d051f3b28f8d84f0a6a', '1746828514', '1746828514');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_captcha` (
            `key` varchar(32) NOT NULL DEFAULT '',
            `code` varchar(32) NOT NULL DEFAULT '',
            `captcha` text,
            `create_time` bigint unsigned,
            `expire_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_captcha` VALUES ('92744a7a4417c4942c0da6a6dc90ee3d', '64ff6accae97b3234917df70e382a02d', '{\"text\":[{\"size\":21,\"icon\":true,\"name\":\"bomb\",\"text\":\"<炸弹>\",\"width\":32,\"height\":32,\"x\":225,\"y\":85},{\"size\":17,\"icon\":false,\"name\":\"bomb\",\"text\":\"解\",\"width\":22,\"height\":20,\"x\":204,\"y\":59}],\"width\":350,\"height\":200}', '1746783228', '1746783828');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_config` (
            `id` int unsigned NOT NULL,
            `name` varchar(30) NOT NULL DEFAULT '',
            `group` varchar(30) NOT NULL DEFAULT '',
            `title` varchar(50) NOT NULL DEFAULT '',
            `tip` varchar(100) NOT NULL DEFAULT '',
            `type` varchar(30) NOT NULL DEFAULT '',
            `value` longtext,
            `content` longtext,
            `rule` varchar(100) NOT NULL DEFAULT '',
            `extend` varchar(255) NOT NULL DEFAULT '',
            `allow_del` tinyint unsigned NOT NULL DEFAULT '0',
            `weigh` int NOT NULL DEFAULT '0'
        );" );
        Db::execute("INSERT INTO `ra_config` VALUES ('1', 'config_group', 'basics', 'Config group', '', 'array', '[{\"key\":\"basics\",\"value\":\"Basics\"},{\"key\":\"mail\",\"value\":\"Mail\"},{\"key\":\"authentication\",\"value\":\"Authentication\"}]', NULL, 'required', '', '0', '-1');");
        Db::execute("INSERT INTO `ra_config` VALUES ('2', 'site_name', 'basics', 'Site Name', '', 'string', 'RAdmin', NULL, 'required', '', '0', '99');");
        Db::execute("INSERT INTO `ra_config` VALUES ('3', 'record_number', 'basics', 'Record number', '域名备案号', 'string', '渝ICP备8888888号-1', NULL, '', '', '0', '0');");
        Db::execute("INSERT INTO `ra_config` VALUES ('4', 'version', 'basics', 'Version number', '系统版本号', 'string', 'v1.0.0', NULL, 'required', '', '0', '0');");
        Db::execute("INSERT INTO `ra_config` VALUES ('5', 'time_zone', 'basics', 'time zone', '', 'string', 'Asia/Shanghai', NULL, 'required', '', '0', '0');");
        Db::execute("INSERT INTO `ra_config` VALUES ('6', 'no_access_ip', 'basics', 'No access ip', '禁止访问站点的ip列表,一行一个', 'textarea', '', NULL, '', '', '0', '0');");
        Db::execute("INSERT INTO `ra_config` VALUES ('7', 'smtp_server', 'mail', 'smtp server', '', 'string', 'smtp.qq.com', NULL, '', '', '0', '9');");
        Db::execute("INSERT INTO `ra_config` VALUES ('8', 'smtp_port', 'mail', 'smtp port', '', 'string', '465', NULL, '', '', '0', '8');");
        Db::execute("INSERT INTO `ra_config` VALUES ('9', 'smtp_user', 'mail', 'smtp user', '', 'string', NULL, NULL, '', '', '0', '7');");
        Db::execute("INSERT INTO `ra_config` VALUES ('10', 'smtp_pass', 'mail', 'smtp pass', '', 'string', NULL, NULL, '', '', '0', '6');");
        Db::execute("INSERT INTO `ra_config` VALUES ('11', 'smtp_verification', 'mail', 'smtp verification', '', 'select', 'SSL', '{\"SSL\":\"SSL\",\"TLS\":\"TLS\"}', '', '', '0', '5');");
        Db::execute("INSERT INTO `ra_config` VALUES ('12', 'smtp_sender_mail', 'mail', 'smtp sender mail', '', 'string', NULL, NULL, 'email', '', '0', '4');");
        Db::execute("INSERT INTO `ra_config` VALUES ('13', 'config_quick_entrance', 'config_quick_entrance', 'Config Quick entrance', '', 'array', '[{\"key\":\"数据回收规则配置\",\"value\":\"/admin/security/dataRecycle\"},{\"key\":\"敏感数据规则配置\",\"value\":\"/admin/security/sensitiveData\"}]', NULL, '', '', '0', '0');");
        Db::execute("INSERT INTO `ra_config` VALUES ('14', 'backend_entrance', 'basics', 'Backend entrance', '', 'string', '/admin', NULL, 'required', '', '0', '1');");
        Db::execute("INSERT INTO `ra_config` VALUES ('17', 'driver', 'authentication', '驱动类型', '默认驱动类型', 'radio', 'jwt', '{\"jwt\":\"Jwt\",\"cache\":\"Cache\",\"mysql\":\"Mysql\",\"redis\":\"Redis\"}', '', '{\"blockHelp\":\"1. \\u63a8\\u8350JWT,\\u4e0d\\u5b58\\u50a8\\u65e0\\u72b6\\u6001,\\u66f4\\u5b89\\u5168   2. Cache\\u65b9\\u5f0f\\u652f\\u6301File,Redis\\u7b49\"}', '0', '999');");
        Db::execute("INSERT INTO `ra_config` VALUES ('18', 'expire_time', 'authentication', 'Token有效期(秒)', '单位秒,安全起见不要设置太长', 'number', '10', NULL, 'required,integer', '', '0', '965');");
        Db::execute("INSERT INTO `ra_config` VALUES ('19', 'keep_time', 'authentication', '保持会话时间(秒)', '单位秒,默认7天', 'number', '604800', NULL, 'required,integer', '', '0', '960');");
        Db::execute("INSERT INTO `ra_config` VALUES ('20', 'algo', 'authentication', '加密方式', '哈希算法,不推荐MD5', 'radio', 'sha256', '{\"md5 \":\"MD5\",\"sha256\":\"SHA-256\",\"whirlpool\":\"Whirlpool\",\"ripemd256\":\"RIPEMD-256\",\"gost\":\"GOST\"}', '', '', '0', '971');");
        Db::execute("INSERT INTO `ra_config` VALUES ('29', 'jwt_algo', 'authentication', 'JWT签名算法', 'JWT签名算法,暂不支持RS非对称加密', 'radio', 'HS256', '{\"HS256\":\"HS256\",\"HS384\":\"HS384\",\"HS512\":\"HS512\"}', '', '', '0', '980');");
        Db::execute("INSERT INTO `ra_config` VALUES ('38', 'secret', 'authentication', '加密密钥', '密钥字串', 'password', 'jp9S^mtu^!6)(iGr_Xqwe^PstooaJRyMcPAYgyfo+bDKg%z*$JivrY0vz_waCrV*Arx@0+60zBU8L50tacPG1zTq12mGalZ9qa%tktUPj)%EAv2fjCBsWgSl*Pz&@9!dpR0hXl1e2El*%DwJS#xeOIkyOUv*6G@OI9XCumlyBxtwYn8E^pyVP9IJHTzq^#E8p#SS%tPRNsiF1IE@I$hnCbRSd5AjERg#++^palDcyjav8qKh*!GXUWrtuH@W(4)S', NULL, 'required', '', '0', '970');");
        Db::execute("INSERT INTO `ra_config` VALUES ('39', 'jwt_secret', 'authentication', 'JWT加密密钥', 'JWT加密密钥', 'password', 'P3nP5cLURe!RQeOTzjNPAVNEs&8a4(2o$Eh(nsT3IAOtyLf9te1tAlyLx45gWLMaN5kxnx1C&wrWcf)xTxi97bGhrBADum0EF$EP_E$F503bT^Srq8vP6Vuh%4&wgHohI8LGX@dksEKnOQW#Gv181_Tp@dwXtO*5HNlv)RdS06k2DGI+V24$jA%i!gL$EGw16%i_szlLDyP!CLyzkr(ygy8mXxLr)aXXurMln5H)W4___EfNYOIoSH7W1HNor&lS', NULL, 'required', '', '0', '979');");
        Db::execute("INSERT INTO `ra_config` VALUES ('41', 'iss', 'authentication', '签发者标识', '签发者标识', 'string', 'Radmin', NULL, 'required', '', '0', '9999');");
        Db::execute("INSERT INTO `ra_config` VALUES ('42', 'allow_keys', 'authentication', '允许的字段', '允许的字段', 'array', '[{\"key\":\"iss\",\"value\":\"\\u7b7e\\u53d1\\u8005\"},{\"key\":\"sub\",\"value\":\"\\u7528\\u6237 ID\"},{\"key\":\"exp\",\"value\":\"\\u8fc7\\u671f\\u65f6\\u95f4\"},{\"key\":\"iat\",\"value\":\"\\u7b7e\\u53d1\\u65f6\\u95f4\"},{\"key\":\"jti\",\"value\":\"\\u552f\\u4e00\\u6807\\u8bc6\\u7b26\"},{\"key\":\"roles\",\"value\":\"\\u7528\\u6237\\u89d2\\u8272\"},{\"key\":\"type\",\"value\":\"TOKEN\\u7c7b\\u578b\"},{\"key\":\"role\",\"value\":\"\\u4e25\\u683c\\u89d2\\u8272\"}]', NULL, 'required', '', '0', '955');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_crud_log` (
            `id` int unsigned NOT NULL,
            `table_name` varchar(200) NOT NULL DEFAULT '',
            `comment` varchar(255) NOT NULL DEFAULT '',
            `table` text,
            `fields` text,
            `sync` int unsigned NOT NULL DEFAULT '0',
            `status` enum('delete','success','error','start') NOT NULL DEFAULT 'start',
            `connection` varchar(100) NOT NULL DEFAULT '',
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_crud_log` VALUES ('1', 'aaa', 'aaa', '{\"name\":\"aaa\",\"comment\":\"aaa\",\"quickSearchField\":[\"id\"],\"defaultSortField\":\"id\",\"formFields\":[\"status\"],\"columnFields\":[\"id\",\"status\",\"create_time\"],\"defaultSortType\":\"desc\",\"generateRelativePath\":\"aaa\",\"isCommonModel\":0,\"modelFile\":\"app\\/admin\\/model\\/Aaa.php\",\"controllerFile\":\"app\\/admin\\/controller\\/Aaa.php\",\"validateFile\":\"app\\/admin\\/validate\\/Aaa.php\",\"webViewsDir\":\"web\\/src\\/views\\/backend\\/aaa\",\"databaseConnection\":\"\",\"designChange\":[],\"rebuild\":\"No\"}', '[{\"title\":\"主键\",\"name\":\"id\",\"comment\":\"ID\",\"designType\":\"pk\",\"formBuildExclude\":true,\"table\":{\"width\":70,\"operator\":\"RANGE\",\"sortable\":\"custom\"},\"form\":[],\"type\":\"int\",\"length\":10,\"precision\":0,\"defaultType\":\"NONE\",\"null\":false,\"primaryKey\":true,\"unsigned\":true,\"autoIncrement\":true},{\"title\":\"状态\",\"name\":\"status\",\"comment\":\"状态:0=禁用,1=启用\",\"designType\":\"switch\",\"table\":{\"render\":\"switch\",\"operator\":\"eq\",\"sortable\":\"false\"},\"form\":{\"validator\":[],\"validatorMsg\":\"\"},\"type\":\"tinyint\",\"length\":1,\"precision\":0,\"default\":\"1\",\"defaultType\":\"INPUT\",\"null\":false,\"primaryKey\":false,\"unsigned\":true,\"autoIncrement\":false},{\"title\":\"创建时间\",\"name\":\"create_time\",\"comment\":\"创建时间\",\"designType\":\"timestamp\",\"formBuildExclude\":true,\"table\":{\"render\":\"datetime\",\"operator\":\"RANGE\",\"sortable\":\"custom\",\"width\":160,\"timeFormat\":\"yyyy-mm-dd hh:MM:ss\"},\"form\":{\"validator\":[\"date\"],\"validatorMsg\":\"\"},\"type\":\"bigint\",\"length\":16,\"precision\":0,\"defaultType\":\"NULL\",\"null\":true,\"primaryKey\":false,\"unsigned\":true,\"autoIncrement\":false}]', '0', 'delete', 'mysql', '1746738657');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_migrations` (
            `version` bigint NOT NULL,
            `migration_name` varchar(100),
            `start_time` timestamp,
            `end_time` timestamp,
            `breakpoint` tinyint(1) NOT NULL DEFAULT '0'
        );" );
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20230620180908', 'Install', '2025-05-09 01:06:00', '2025-05-09 01:06:00', '0');");
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20230620180916', 'InstallData', '2025-05-09 01:06:00', '2025-05-09 01:06:00', '0');");
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20230622221507', 'Version200', '2025-05-09 01:06:00', '2025-05-09 01:06:00', '0');");
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20230719211338', 'Version201', '2025-05-09 01:06:00', '2025-05-09 01:06:00', '0');");
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20230905060702', 'Version202', '2025-05-09 01:06:00', '2025-05-09 01:06:00', '0');");
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20231112093414', 'Version205', '2025-05-09 01:06:00', '2025-05-09 01:06:00', '0');");
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20231229043002', 'Version206', '2025-05-09 01:06:00', '2025-05-09 01:06:00', '0');");
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20250412134127', 'Version222', '2025-05-11 04:38:45', '2025-05-11 04:38:45', '0');");
        Db::execute("INSERT INTO `ra_migrations` VALUES ('20250510121212', 'Radmin101', '2025-05-11 04:55:43', '2025-05-11 04:55:44', '0');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_security_data_recycle` (
            `id` int unsigned NOT NULL,
            `name` varchar(50) NOT NULL DEFAULT '',
            `controller` varchar(100) NOT NULL DEFAULT '',
            `controller_as` varchar(100) NOT NULL DEFAULT '',
            `data_table` varchar(100) NOT NULL DEFAULT '',
            `connection` varchar(100) NOT NULL DEFAULT '',
            `primary_key` varchar(50) NOT NULL DEFAULT '',
            `status` enum('0','1') NOT NULL DEFAULT '1',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_security_data_recycle` VALUES ('1', '管理员', 'auth/Admin.php', 'auth/admin', 'admin', '', 'id', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_security_data_recycle` VALUES ('2', '管理员日志', 'auth/AdminLog.php', 'auth/adminlog', 'admin_log', '', 'id', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_security_data_recycle` VALUES ('3', '菜单规则', 'auth/Menu.php', 'auth/menu', 'menu_rule', '', 'id', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_security_data_recycle` VALUES ('4', '系统配置项', 'routine/Config.php', 'routine/config', 'config', '', 'id', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_security_data_recycle` VALUES ('5', '会员', 'user/User.php', 'user/user', 'user', 'mysql', 'id', '1', '1746738237', '1746723960');");
        Db::execute("INSERT INTO `ra_security_data_recycle` VALUES ('6', '数据回收规则', 'security/DataRecycle.php', 'security/datarecycle', 'security_data_recycle', 'mysql', 'id', '1', '1746738231', '1746723960');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_security_data_recycle_log` (
            `id` int unsigned NOT NULL,
            `admin_id` int unsigned NOT NULL DEFAULT '0',
            `recycle_id` int unsigned NOT NULL DEFAULT '0',
            `data` text,
            `data_table` varchar(100) NOT NULL DEFAULT '',
            `connection` varchar(100) NOT NULL DEFAULT '',
            `primary_key` varchar(50) NOT NULL DEFAULT '',
            `is_restore` tinyint unsigned NOT NULL DEFAULT '0',
            `ip` varchar(50) NOT NULL DEFAULT '',
            `useragent` varchar(255) NOT NULL DEFAULT '',
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_security_data_recycle_log` VALUES ('1', '1', '4', '{\"id\":26,\"name\":\"aaa\",\"group\":\"authentication\",\"title\":\"aaa\",\"tip\":\"aaa\",\"type\":\"checkbox\",\"value\":null,\"content\":\"{\\\"key1\\\":\\\"value1\\\",\\\"key2\\\":\\\"value2\\\"}\",\"rule\":\"\",\"extend\":\"style: {display: state.form[driver] === jwt ? block : none}\",\"allow_del\":1,\"weigh\":0}', 'config', '', 'id', '0', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '1746914650');");
        Db::execute("INSERT INTO `ra_security_data_recycle_log` VALUES ('2', '1', '4', '{\"id\":28,\"name\":\"dddd\",\"group\":\"authentication\",\"title\":\"dddd\",\"tip\":\"\",\"type\":\"radio\",\"value\":null,\"content\":\"{\\\"key1\\\":\\\"value1\\\",\\\"key2\\\":\\\"value2\\\"}\",\"rule\":\"required\",\"extend\":\"\",\"allow_del\":1,\"weigh\":0}', 'config', '', 'id', '0', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '1746916110');");
        Db::execute("INSERT INTO `ra_security_data_recycle_log` VALUES ('3', '1', '4', '{\"id\":27,\"name\":\"sddf\",\"group\":\"authentication\",\"title\":\"saf\",\"tip\":\"\",\"type\":\"checkbox\",\"value\":null,\"content\":\"{\\\"key1\\\":\\\"value1\\\",\\\"key2\\\":\\\"value2\\\"}\",\"rule\":\"\",\"extend\":\"{\\\"class\\\":\\\"dddd\\\",\\\"style\\\":\\\"{display: \'none\'}\\\"}\",\"allow_del\":1,\"weigh\":0}', 'config', '', 'id', '0', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '1746916119');");
        Db::execute("INSERT INTO `ra_security_data_recycle_log` VALUES ('4', '1', '4', '{\"id\":37,\"name\":\"aaa\",\"group\":\"authentication\",\"title\":\"aaa\",\"tip\":\"aa\",\"type\":\"textarea\",\"value\":null,\"content\":null,\"rule\":\"\",\"extend\":\"{\\\"blockHelp\\\":\\\"1. \\\\u63a8\\\\u8350JWT,\\\\u4e0d\\\\u5b58\\\\u50a8\\\\u65e0\\\\u72b6\\\\u6001,\\\\u66f4\\\\u5b89\\\\u5168   2. Cache\\\\u65b9\\\\u5f0f\\\\u652f\\\\u6301File,Redis\\\\u7b49\\\"}\",\"allow_del\":1,\"weigh\":0}', 'config', '', 'id', '0', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '1746924614');");
        Db::execute("INSERT INTO `ra_security_data_recycle_log` VALUES ('5', '1', '4', '{\"id\":40,\"name\":\"aaaa\",\"group\":\"authentication\",\"title\":\"aaa\",\"tip\":\"\",\"type\":\"password\",\"value\":null,\"content\":null,\"rule\":\"\",\"extend\":\"{\\\"baInputExtend\\\":{\\\"size\\\":\\\"large\\\"}}\",\"allow_del\":1,\"weigh\":0}', 'config', '', 'id', '0', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '1746928365');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_security_sensitive_data` (
            `id` int unsigned NOT NULL,
            `name` varchar(50) NOT NULL DEFAULT '',
            `controller` varchar(100) NOT NULL DEFAULT '',
            `controller_as` varchar(100) NOT NULL DEFAULT '',
            `data_table` varchar(100) NOT NULL DEFAULT '',
            `connection` varchar(100) NOT NULL DEFAULT '',
            `primary_key` varchar(50) NOT NULL DEFAULT '',
            `data_fields` text,
            `status` enum('0','1') NOT NULL DEFAULT '1',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_security_sensitive_data` VALUES ('1', '管理员数据', 'auth/Admin.php', 'auth/admin', 'admin', '', 'id', '{\"username\":\"用户名\",\"mobile\":\"手机\",\"password\":\"密码\",\"status\":\"状态\"}', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_security_sensitive_data` VALUES ('2', '会员数据', 'user/User.php', 'user/user', 'user', '', 'id', '{\"username\":\"用户名\",\"mobile\":\"手机号\",\"password\":\"密码\",\"status\":\"状态\",\"email\":\"邮箱地址\"}', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_security_sensitive_data` VALUES ('3', '管理员权限', 'auth/Group.php', 'auth/group', 'admin_group', '', 'id', '{\"rules\":\"权限规则ID\"}', '1', '1746723960', '1746723960');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_security_sensitive_data_log` (
            `id` int unsigned NOT NULL,
            `admin_id` int unsigned NOT NULL DEFAULT '0',
            `sensitive_id` int unsigned NOT NULL DEFAULT '0',
            `data_table` varchar(100) NOT NULL DEFAULT '',
            `connection` varchar(100) NOT NULL DEFAULT '',
            `primary_key` varchar(50) NOT NULL DEFAULT '',
            `data_field` varchar(50) NOT NULL DEFAULT '',
            `data_comment` varchar(50) NOT NULL DEFAULT '',
            `id_value` int NOT NULL DEFAULT '0',
            `before` text,
            `after` text,
            `ip` varchar(50) NOT NULL DEFAULT '',
            `useragent` varchar(255) NOT NULL DEFAULT '',
            `is_rollback` tinyint unsigned NOT NULL DEFAULT '0',
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_security_sensitive_data_log` VALUES ('1', '1', '2', 'user', '', 'id', 'password', '密码', '1', '$2y$12$0fXwSgQbpcFvv66X82FwSuFd/HmgA18YJnR.3c6EPx5vss3V43mlG', '******', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', '0', '1746736757');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_test_build` (
            `id` int unsigned NOT NULL,
            `title` varchar(100) NOT NULL DEFAULT '',
            `keyword_rows` varchar(100) NOT NULL DEFAULT '',
            `content` text,
            `views` int unsigned NOT NULL DEFAULT '0',
            `likes` int unsigned NOT NULL DEFAULT '0',
            `dislikes` int unsigned NOT NULL DEFAULT '0',
            `note_textarea` varchar(100) NOT NULL DEFAULT '',
            `status` enum('0','1') NOT NULL DEFAULT '1',
            `weigh` int NOT NULL DEFAULT '0',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_token` (
            `token` varchar(50) NOT NULL DEFAULT '',
            `type` varchar(15) NOT NULL DEFAULT '',
            `user_id` int unsigned NOT NULL DEFAULT '0',
            `create_time` bigint unsigned,
            `expire_time` bigint unsigned
        );" );
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_user` (
            `id` int unsigned NOT NULL,
            `group_id` int unsigned NOT NULL DEFAULT '0',
            `username` varchar(32) NOT NULL DEFAULT '',
            `nickname` varchar(50) NOT NULL DEFAULT '',
            `email` varchar(50) NOT NULL DEFAULT '',
            `mobile` varchar(11) NOT NULL DEFAULT '',
            `avatar` varchar(255) NOT NULL DEFAULT '',
            `gender` tinyint unsigned NOT NULL DEFAULT '0',
            `birthday` date,
            `money` int unsigned NOT NULL DEFAULT '0',
            `score` int unsigned NOT NULL DEFAULT '0',
            `last_login_time` bigint unsigned,
            `last_login_ip` varchar(50) NOT NULL DEFAULT '',
            `login_failure` tinyint unsigned NOT NULL DEFAULT '0',
            `join_ip` varchar(50) NOT NULL DEFAULT '',
            `join_time` bigint unsigned,
            `motto` varchar(255) NOT NULL DEFAULT '',
            `password` varchar(255) NOT NULL DEFAULT '',
            `salt` varchar(30) NOT NULL DEFAULT '',
            `status` varchar(30) NOT NULL DEFAULT '',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_user` VALUES ('1', '1', 'user', 'User', '18888888888@qq.com', '18888888888', '/storage/default/20250509/logo43240f82cba37fb6e1b097ecff178023af8e6383.png', '2', '2025-05-09', '0', '0', NULL, '', '0', '', NULL, '', '$2y$12$TBkdurW0NeZrRKWRCQM0NueEQLwK6JK3c2V9BZCOoE.T4LlWoDUWW', '', 'enable', '1746736758', '2025');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_user_group` (
            `id` int unsigned NOT NULL,
            `name` varchar(50) NOT NULL DEFAULT '',
            `rules` text,
            `status` enum('0','1') NOT NULL DEFAULT '1',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_user_group` VALUES ('1', '默认分组', '*', '1', '1746723960', '1746723960');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_user_money_log` (
            `id` int unsigned NOT NULL,
            `user_id` int unsigned NOT NULL DEFAULT '0',
            `money` int NOT NULL DEFAULT '0',
            `before` int NOT NULL DEFAULT '0',
            `after` int NOT NULL DEFAULT '0',
            `memo` varchar(255) NOT NULL DEFAULT '',
            `create_time` bigint unsigned
        );" );
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_user_rule` (
            `id` int unsigned NOT NULL,
            `pid` int unsigned NOT NULL DEFAULT '0',
            `type` enum('route','menu_dir','menu','nav_user_menu','nav','button') NOT NULL DEFAULT 'menu',
            `title` varchar(50) NOT NULL DEFAULT '',
            `name` varchar(50) NOT NULL DEFAULT '',
            `path` varchar(100) NOT NULL DEFAULT '',
            `icon` varchar(50) NOT NULL DEFAULT '',
            `menu_type` enum('tab','link','iframe') NOT NULL DEFAULT 'tab',
            `url` varchar(255) NOT NULL DEFAULT '',
            `component` varchar(100) NOT NULL DEFAULT '',
            `no_login_valid` tinyint unsigned NOT NULL DEFAULT '0',
            `extend` enum('none','add_rules_only','add_menu_only') NOT NULL DEFAULT 'none',
            `remark` varchar(255) NOT NULL DEFAULT '',
            `weigh` int NOT NULL DEFAULT '0',
            `status` enum('0','1') NOT NULL DEFAULT '1',
            `update_time` bigint unsigned,
            `create_time` bigint unsigned
        );" );
        Db::execute("INSERT INTO `ra_user_rule` VALUES ('1', '0', 'menu_dir', '我的账户', 'account', 'account', 'fa fa-user-circle', 'tab', '', '', '0', 'none', '', '98', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_user_rule` VALUES ('2', '1', 'menu', '账户概览', 'account/overview', 'account/overview', 'fa fa-home', 'tab', '', '/src/views/frontend/user/account/overview.vue', '0', 'none', '', '99', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_user_rule` VALUES ('3', '1', 'menu', '个人资料', 'account/profile', 'account/profile', 'fa fa-user-circle-o', 'tab', '', '/src/views/frontend/user/account/profile.vue', '0', 'none', '', '98', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_user_rule` VALUES ('4', '1', 'menu', '修改密码', 'account/changePassword', 'account/changePassword', 'fa fa-shield', 'tab', '', '/src/views/frontend/user/account/changePassword.vue', '0', 'none', '', '97', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_user_rule` VALUES ('5', '1', 'menu', '积分记录', 'account/integral', 'account/integral', 'fa fa-tag', 'tab', '', '/src/views/frontend/user/account/integral.vue', '0', 'none', '', '96', '1', '1746723960', '1746723960');");
        Db::execute("INSERT INTO `ra_user_rule` VALUES ('6', '1', 'menu', '余额记录', 'account/balance', 'account/balance', 'fa fa-money', 'tab', '', '/src/views/frontend/user/account/balance.vue', '0', 'none', '', '95', '1', '1746723960', '1746723960');");
        Db::execute("CREATE TABLE IF NOT EXISTS `ra_user_score_log` (
            `id` int unsigned NOT NULL,
            `user_id` int unsigned NOT NULL DEFAULT '0',
            `score` int NOT NULL DEFAULT '0',
            `before` int NOT NULL DEFAULT '0',
            `after` int NOT NULL DEFAULT '0',
            `memo` varchar(255) NOT NULL DEFAULT '',
            `create_time` bigint unsigned
        );" );
    }

    public function down()
    {
        // 可选：实现回滚逻辑
    }
}