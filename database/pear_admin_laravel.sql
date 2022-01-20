SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for s_debt_detail_log
-- ----------------------------
DROP TABLE IF EXISTS `s_debt_detail_log`;
CREATE TABLE `s_debt_detail_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `debt_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '债务记录ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '资金，单位：元',
  `interest_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '利息，单位：元',
  `total_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总金额，单位：元',
  `plan_back_date` date NOT NULL COMMENT '预计还款日',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 已偿还 0 未偿还',
  `remark` varchar(191) NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COMMENT='负债详细表';

-- ----------------------------
-- Records of s_debt_detail_log
-- ----------------------------
BEGIN;
INSERT INTO `s_debt_detail_log` VALUES (1, 1, 158.16, 0.00, 158.16, '2022-02-05', 0, '', '2021-12-11 21:59:00', '2021-12-11 21:59:02', NULL);
INSERT INTO `s_debt_detail_log` VALUES (2, 1, 158.16, 0.00, 158.16, '2022-03-05', 0, '', '2021-12-11 21:59:00', '2021-12-11 21:59:02', NULL);
INSERT INTO `s_debt_detail_log` VALUES (3, 1, 158.16, 0.00, 158.16, '2022-04-05', 0, '', '2021-12-11 21:59:00', '2021-12-11 21:59:02', NULL);
INSERT INTO `s_debt_detail_log` VALUES (4, 1, 158.16, 0.00, 158.16, '2022-05-05', 0, '', '2021-12-11 21:59:00', '2021-12-11 21:59:02', NULL);
INSERT INTO `s_debt_detail_log` VALUES (5, 1, 158.16, 0.00, 158.16, '2022-06-05', 0, '', '2021-12-11 21:59:00', '2021-12-11 21:59:02', NULL);
INSERT INTO `s_debt_detail_log` VALUES (6, 2, 191.67, 7.59, 199.26, '2022-01-26', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (7, 2, 191.67, 7.59, 199.26, '2022-02-26', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (8, 2, 191.67, 7.59, 199.26, '2022-03-26', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (9, 2, 191.67, 7.59, 199.26, '2022-04-26', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (10, 2, 191.63, 7.59, 199.22, '2022-05-26', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (11, 3, 0.00, 32.03, 32.03, '2022-01-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (12, 3, 0.00, 32.03, 32.03, '2022-02-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (13, 3, 0.00, 28.93, 28.93, '2022-03-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (14, 3, 0.00, 32.03, 32.03, '2022-04-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (15, 3, 0.00, 31.00, 31.00, '2022-05-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (16, 3, 0.00, 32.03, 32.03, '2022-06-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (17, 3, 0.00, 31.00, 31.00, '2022-07-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (18, 3, 0.00, 32.03, 32.03, '2022-08-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (19, 3, 6000.00, 5.17, 6005.17, '2022-08-13', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (20, 4, 0.00, 60.00, 60.00, '2022-02-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (21, 4, 0.00, 28.00, 28.00, '2022-03-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (22, 4, 0.00, 31.00, 31.00, '2022-04-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (23, 4, 0.00, 30.00, 30.00, '2022-05-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (24, 4, 0.00, 31.00, 31.00, '2022-06-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (25, 4, 0.00, 30.00, 30.00, '2022-07-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (26, 4, 0.00, 31.00, 31.00, '2022-08-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (27, 4, 0.00, 31.00, 31.00, '2022-09-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (28, 4, 0.00, 30.00, 30.00, '2022-10-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (29, 4, 0.00, 31.00, 31.00, '2022-11-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (30, 4, 0.00, 30.00, 30.00, '2022-12-08', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (31, 4, 5000.00, 2.00, 5002.00, '2022-12-10', 0, '', '2021-12-11 22:10:40', '2021-12-11 22:10:41', NULL);
INSERT INTO `s_debt_detail_log` VALUES (32, 5, 107.48, 0.00, 107.48, '2022-01-20', 0, '', '2021-12-11 22:44:01', '2021-12-11 22:44:04', NULL);
INSERT INTO `s_debt_detail_log` VALUES (33, 5, 57.66, 0.00, 57.66, '2022-02-20', 0, '', '2021-12-11 22:44:01', '2021-12-11 22:44:04', NULL);
INSERT INTO `s_debt_detail_log` VALUES (34, 5, 57.66, 0.00, 57.66, '2022-03-20', 0, '', '2021-12-11 22:44:01', '2021-12-11 22:44:04', NULL);
COMMIT;

-- ----------------------------
-- Table structure for s_debt_log
-- ----------------------------
DROP TABLE IF EXISTS `s_debt_log`;
CREATE TABLE `s_debt_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `debt_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1 借入债务 2 借出债务',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题',
  `from_user` varchar(20) NOT NULL DEFAULT '' COMMENT '资金来源',
  `to_user` varchar(20) NOT NULL DEFAULT '' COMMENT '资金流向',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '资金，单位：元',
  `interest_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '利息，单位：元',
  `total_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总金额，单位：元',
  `happened_at` date NOT NULL COMMENT '发生日期',
  `is_installment` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否分期：1 分期 0 否',
  `installment_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '分期期数',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1 已完结 0 未还清',
  `remark` varchar(30) NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='债务表';

-- ----------------------------
-- Records of s_debt_log
-- ----------------------------
BEGIN;
INSERT INTO `s_debt_log` VALUES (1, 1, 1, '京东白条-显示器', '京东', '自己', 790.80, 0.00, 790.80, '2021-12-11', 1, 5, 0, '', '2021-12-11 21:57:30', '2021-12-11 21:57:32', NULL);
INSERT INTO `s_debt_log` VALUES (2, 1, 1, '招商银行分期还款', '招商银行', '自己', 958.31, 37.95, 996.26, '2021-12-11', 1, 5, 0, '', '2021-12-11 22:09:57', '2021-12-11 22:09:59', NULL);
INSERT INTO `s_debt_log` VALUES (3, 1, 1, '招商银行借贷分期还款', '招商银行', '自己', 6000.00, 256.25, 6256.25, '2021-12-11', 1, 9, 0, '', '2021-12-11 22:09:57', '2021-12-11 22:09:59', NULL);
INSERT INTO `s_debt_log` VALUES (4, 1, 1, '招商银行借贷分期还款', '招商银行', '自己', 5000.00, 365.00, 5365.00, '2021-12-11', 1, 12, 0, '', '2021-12-11 22:09:57', '2021-12-11 22:09:59', NULL);
INSERT INTO `s_debt_log` VALUES (5, 1, 1, '花呗', '支付宝花呗', '自己', 222.80, 0.00, 222.80, '2021-12-11', 1, 3, 0, '', '2021-12-11 22:09:57', '2021-12-11 22:09:59', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
