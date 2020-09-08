/*
 Navicat Premium Data Transfer

 Source Server         : platform
 Source Server Type    : MySQL
 Source Server Version : 50647
 Source Host           : 10.0.1.150:3309
 Source Schema         : platform

 Target Server Type    : MySQL
 Target Server Version : 50647
 File Encoding         : 65001

 Date: 07/09/2020 16:32:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE `admin_logs`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `before_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `after_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员操作日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_logs
-- ----------------------------
INSERT INTO `admin_logs` VALUES (33, '编辑角色', '{\"id\":5,\"role_name\":\"测试角色2\",\"introduce\":\"哇哈哈啊哈\",\"create_time\":1598943191,\"delete_time\":null,\"is_default\":0}', '{\"role_name\":\"测试角色2\",\"introduce\":\"哇哈哈啊哈123\"}', 1598945585, 1);
INSERT INTO `admin_logs` VALUES (34, '登录系统', '[]', '[]', 1598950262, 1);
INSERT INTO `admin_logs` VALUES (35, '角色授权', '[9,8,7,5,4,3,2,1]', '[1,2,5,6,7,8,9,10]', 1598952042, 1);
INSERT INTO `admin_logs` VALUES (36, '删除角色', '{\"id\":5,\"role_name\":\"测试角色2\",\"introduce\":\"哇哈哈啊哈123\",\"create_time\":1598943191,\"delete_time\":1598953009,\"is_default\":0}', '[]', 1598953009, 1);
INSERT INTO `admin_logs` VALUES (37, '新增管理员', '[]', '{\"username\":\"xiaoziyan\",\"password\":\"hs46bCan6jdJF8+9aiCiwbvWzfpI+\\/hRBQPu37kms3a2vJJwUO9VLzXa2qXMXMcb9D7gEer6yDB\\/76nxmuSXR9RFR2xWxSXtyM0fNqxq0g+ZOU0MEY4EFxrzjuAvDpnAHxVScqb1WCCM87QdbJBGNee++8IWFHvachC3z+P8Pa8=\",\"nickname\":\"华尚科技\",\"telephone\":\"13433323221\",\"email\":\"\",\"role_id\":\"4\",\"create_time\":\"2020-09-01 17:48\",\"id\":\"2\"}', 1598953717, 1);
INSERT INTO `admin_logs` VALUES (38, '登录系统', '[]', '[]', 1599209612, 1);
INSERT INTO `admin_logs` VALUES (32, '新增角色', '[]', '{\"role_name\":\"测试角色2\",\"introduce\":\"哇哈哈啊哈\",\"is_default\":0,\"create_time\":\"2020-09-01 14:53\",\"id\":\"5\"}', 1598943191, 1);
INSERT INTO `admin_logs` VALUES (39, '登录系统', '[]', '[]', 1599446258, 1);
INSERT INTO `admin_logs` VALUES (40, '登录系统', '[]', '[]', 1599446297, 1);
INSERT INTO `admin_logs` VALUES (41, '角色授权', '[10,9,8,7,6,5,2,1]', '[1,2,5,6,7,8,9,10]', 1599446346, 1);
INSERT INTO `admin_logs` VALUES (42, '角色授权', '[10,9,8,7,6,5,2,1]', '[1,2,5,6,7,8,9,10]', 1599446353, 1);

-- ----------------------------
-- Table structure for admins
-- ----------------------------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '账号',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '姓名',
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系电话',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系邮箱',
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '状态',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `delete_time` int(11) NULL DEFAULT NULL,
  `is_default` int(11) NOT NULL DEFAULT 0,
  `role_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admins
-- ----------------------------
INSERT INTO `admins` VALUES (1, 'fripside', 'L/v1WQD0dlHmFh78DyotVfu2POao400AevjG2G7uFLQej5pSIHuFRdOAu3eVc+DqrXBba88u1xaOb4JTZdcIjv96nhouUmK0uRns+lTeLgEgGZDtqulZyMUtI5XizaLYvxzIiyzhf5yiZE90PyieR53zpY70b+2Uql6qdNAVyFo=', '梓潼', '1591355123', 'fripside@126.com', 0, 1598926248, NULL, 1, 1);
INSERT INTO `admins` VALUES (2, 'xiaoziyan', 'hs46bCan6jdJF8+9aiCiwbvWzfpI+/hRBQPu37kms3a2vJJwUO9VLzXa2qXMXMcb9D7gEer6yDB/76nxmuSXR9RFR2xWxSXtyM0fNqxq0g+ZOU0MEY4EFxrzjuAvDpnAHxVScqb1WCCM87QdbJBGNee++8IWFHvachC3z+P8Pa8=', '华尚科技', '13433323221', '', 0, 1598953717, NULL, 0, 4);

-- ----------------------------
-- Table structure for config
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `k` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `introduce` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `v_cn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `v_us` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `group_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统配置' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pid` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `is_show` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '菜单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, '系统设置', NULL, 1, NULL, 0, 0, 1);
INSERT INTO `menu` VALUES (2, '权限管理', NULL, 1, NULL, 0, 6, 1);
INSERT INTO `menu` VALUES (3, '基本设置', NULL, 2, 'config/config', 1, 0, 1);
INSERT INTO `menu` VALUES (4, '管理员管理', NULL, 2, 'admin/lists', 2, 0, 1);
INSERT INTO `menu` VALUES (5, '角色管理', NULL, 2, 'role/lists', 2, 1, 1);
INSERT INTO `menu` VALUES (6, '操作日志', NULL, 2, 'log/admin', 2, 2, 1);

-- ----------------------------
-- Table structure for powers
-- ----------------------------
DROP TABLE IF EXISTS `powers`;
CREATE TABLE `powers`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限名称',
  `controller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '控制器',
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '方法',
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '请求方式',
  `menu_id` int(11) NULL DEFAULT NULL COMMENT '关联菜单',
  `is_default` int(11) NULL DEFAULT 1 COMMENT '新增角色的默认权限',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '权限码（菜单ID+操作码）',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 61 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of powers
-- ----------------------------
INSERT INTO `powers` VALUES (1, '查看管理员', 'Admins', 'lists', 'get', 4, 1, '0400');
INSERT INTO `powers` VALUES (2, '添加管理员', 'Admins', 'add', 'post', 4, 1, '0401');
INSERT INTO `powers` VALUES (3, '编辑管理员', 'Admins', 'modify', 'post', 4, 1, '0402');
INSERT INTO `powers` VALUES (4, '删除管理员', 'Admins', 'del', 'post', 4, 1, '0403');
INSERT INTO `powers` VALUES (5, '查看角色', 'Roles', 'lists', 'get', 5, 1, '0500');
INSERT INTO `powers` VALUES (6, '添加角色', 'Roles', 'add', 'post', 5, 1, '0501');
INSERT INTO `powers` VALUES (7, '编辑角色', 'Roles', 'modify', 'post', 5, 1, '0502');
INSERT INTO `powers` VALUES (8, '删除角色', 'Roles', 'del', 'post', 5, 1, '0503');
INSERT INTO `powers` VALUES (9, '角色授权', 'Roles', 'authorization', 'post', 5, 1, '0504');
INSERT INTO `powers` VALUES (10, '查看操作日志', 'Logs', 'admins', 'get', 6, 1, '0600');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `introduce` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `create_time` int(11) NOT NULL,
  `delete_time` int(11) NULL DEFAULT NULL,
  `is_default` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, '超级管理员', '最高管理员', 1598926865, NULL, 1);
INSERT INTO `roles` VALUES (4, '测试角色', '123456', 1598942979, NULL, 0);
INSERT INTO `roles` VALUES (5, '测试角色2', '哇哈哈啊哈123', 1598943191, 1598953009, 0);

-- ----------------------------
-- Table structure for roles_power
-- ----------------------------
DROP TABLE IF EXISTS `roles_power`;
CREATE TABLE `roles_power`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `power_id` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 59 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限分配' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of roles_power
-- ----------------------------
INSERT INTO `roles_power` VALUES (58, 4, 10);
INSERT INTO `roles_power` VALUES (57, 4, 9);
INSERT INTO `roles_power` VALUES (56, 4, 8);
INSERT INTO `roles_power` VALUES (55, 4, 7);
INSERT INTO `roles_power` VALUES (54, 4, 6);
INSERT INTO `roles_power` VALUES (53, 4, 5);
INSERT INTO `roles_power` VALUES (52, 4, 2);
INSERT INTO `roles_power` VALUES (51, 4, 1);
INSERT INTO `roles_power` VALUES (11, 5, 1);
INSERT INTO `roles_power` VALUES (12, 5, 2);
INSERT INTO `roles_power` VALUES (13, 5, 3);
INSERT INTO `roles_power` VALUES (14, 5, 4);
INSERT INTO `roles_power` VALUES (15, 5, 5);
INSERT INTO `roles_power` VALUES (16, 5, 6);
INSERT INTO `roles_power` VALUES (17, 5, 7);
INSERT INTO `roles_power` VALUES (18, 5, 8);
INSERT INTO `roles_power` VALUES (19, 5, 9);
INSERT INTO `roles_power` VALUES (20, 5, 10);

SET FOREIGN_KEY_CHECKS = 1;
