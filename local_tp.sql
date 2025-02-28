/*
 Navicat Premium Dump SQL

 Source Server         : 本地数据库
 Source Server Type    : MySQL
 Source Server Version : 50726 (5.7.26)
 Source Host           : localhost:3306
 Source Schema         : local_tp

 Target Server Type    : MySQL
 Target Server Version : 50726 (5.7.26)
 File Encoding         : 65001

 Date: 28/02/2025 19:21:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for temp_value
-- ----------------------------
DROP TABLE IF EXISTS `temp_value`;
CREATE TABLE `temp_value`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t_key` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `t_value` varchar(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `expiration_date` datetime NOT NULL,
  `private_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `once` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of temp_value
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
