-- Table structure for ra_attachment
CREATE TABLE `ra_attachment` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `topic` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '细目',
  `admin_id` int unsigned NOT NULL DEFAULT '0' COMMENT '上传管理员ID',
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '上传用户ID',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '物理路径',
  `width` int unsigned NOT NULL DEFAULT '0' COMMENT '宽度',
  `height` int unsigned NOT NULL DEFAULT '0' COMMENT '高度',
  `name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '原始名称',
  `size` int unsigned NOT NULL DEFAULT '0' COMMENT '大小',
  `mimetype` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'mime类型',
  `quote` int unsigned NOT NULL DEFAULT '0' COMMENT '上传(引用)次数',
  `storage` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储方式',
  `sha1` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'sha1编码',
  `create_time` bigint unsigned DEFAULT NULL COMMENT '创建时间',
  `last_upload_time` bigint unsigned DEFAULT NULL COMMENT '最后上传时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='附件表';

-- Data for ra_attachment
INSERT INTO `ra_attachment` VALUES ('1', 'default', '1', '0', '/storage/default/20250509/logo43240f82cba37fb6e1b097ecff178023af8e6383.png', '864', '823', 'logo.png', '317356', 'image/png', '2', 'local', '43240f82cba37fb6e1b097ecff178023af8e6383', '1746736751', '1746736865');
INSERT INTO `ra_attachment` VALUES ('2', 'default', '1', '0', '/storage/default/20250510/qr834776e09219bcace1886d051f3b28f8d84f0a6a.png', '300', '300', 'qr.png', '33408', 'image/png', '1', 'local', '834776e09219bcace1886d051f3b28f8d84f0a6a', '1746828514', '1746828514');
INSERT INTO `ra_attachment` VALUES ('3', 'default', '1', '0', '/storage/default/20250512/1.png5dd431322c26a2c3d48fa8fe523c22c35e132a54.jpg', '639', '363', '1.png.jpg', '164726', 'image/jpeg', '1', 'local', '5dd431322c26a2c3d48fa8fe523c22c35e132a54', '1746999847', '1746999847');
INSERT INTO `ra_attachment` VALUES ('4', 'default', '1', '0', '/storage/default/20250513/webHelpHI2-all1ffbcafdf6f01c8b3061e8be1978c6b3212b5fae.zip', '0', '0', 'webHelpHI2-all.zip', '1531961', 'application/zip', '1', 'local', '1ffbcafdf6f01c8b3061e8be1978c6b3212b5fae', '1747077584', '1747077584');
