-- Table structure for ra_user_group
CREATE TABLE `ra_user_group` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '权限节点',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:0=禁用,1=启用',
  `update_time` bigint unsigned DEFAULT NULL COMMENT '更新时间',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='会员组表';

-- Data for ra_user_group
INSERT INTO `ra_user_group` VALUES ('1', '默认分组', '*', '1', '1746723960', '1746723960');
INSERT INTO `ra_user_group` VALUES ('2', '测试组', '2,3,1', '1', '1747008048', '1746999916');
INSERT INTO `ra_user_group` VALUES ('3', '测试组2', '*', '1', '1747000745', '1747000745');
