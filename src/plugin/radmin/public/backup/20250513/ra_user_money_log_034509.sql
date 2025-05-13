-- Table structure for ra_user_money_log
CREATE TABLE `ra_user_money_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `money` int NOT NULL DEFAULT '0' COMMENT '变更余额',
  `before` int NOT NULL DEFAULT '0' COMMENT '变更前余额',
  `after` int NOT NULL DEFAULT '0' COMMENT '变更后余额',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='会员余额变动表';

-- Data for ra_user_money_log
