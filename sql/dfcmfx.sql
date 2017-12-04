/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : dfcmfx

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-12-04 10:26:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `df_auth_permission`
-- ----------------------------
DROP TABLE IF EXISTS `df_auth_permission`;
CREATE TABLE `df_auth_permission` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文名称',
  `pre_code` int(3) unsigned DEFAULT '0' COMMENT '权限三位前置码',
  `suf_code` int(3) unsigned DEFAULT '0' COMMENT '后缀权限代码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：为0正常，为1禁用',
  `condition` char(100) NOT NULL DEFAULT '' COMMENT '规则表达式，为空表示存在就验证，不为空表示按照条件验证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pre_suf_code` (`pre_code`,`suf_code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Table structure for `df_auth_role`
-- ----------------------------
DROP TABLE IF EXISTS `df_auth_role`;
CREATE TABLE `df_auth_role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` char(100) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：为0正常，为1禁用',
  `rules` char(80) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id， 多个规则","隔开',
  `description` char(80) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ind_id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='用户角色表';


-- ----------------------------
-- Table structure for `df_auth_role_access`
-- ----------------------------
DROP TABLE IF EXISTS `df_auth_role_access`;
CREATE TABLE `df_auth_role_access` (
  `aid` mediumint(8) unsigned NOT NULL COMMENT '用户id',
  `role_id` mediumint(8) unsigned NOT NULL COMMENT '用户角色id',
  UNIQUE KEY `ind_aid_role_id` (`aid`,`role_id`) USING BTREE,
  KEY `ind_aid` (`aid`) USING BTREE,
  KEY `ind_role_id` (`role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色\r\n明细表';


-- ----------------------------
-- Table structure for `df_a_logs`
-- ----------------------------
DROP TABLE IF EXISTS `df_a_logs`;
CREATE TABLE `df_a_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '日志类型 1：登录日志 2：操作日志',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '内容',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `client_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '客户端ip',
  `create_at` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ind_id` (`id`) USING BTREE,
  KEY `ind_user_name` (`user_name`) USING BTREE,
  KEY `ind_create_at` (`create_at`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志表';


-- ----------------------------
-- Table structure for `df_a_users`
-- ----------------------------
DROP TABLE IF EXISTS `df_a_users`;
CREATE TABLE `df_a_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `user_pwd` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  `mobile` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `create_at` int(10) NOT NULL COMMENT '创建时间',
  `modiffied_at` int(10) DEFAULT NULL COMMENT '修改时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '账号状态 ： 0 启用 1 禁用',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ind_id` (`id`) USING BTREE,
  UNIQUE KEY `ind_user_name` (`user_name`) USING BTREE,
  UNIQUE KEY `ind_mobile` (`mobile`) USING BTREE,
  UNIQUE KEY `ind_email` (`email`) USING BTREE,
  KEY `ind_create_at` (`create_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT COMMENT='后台用户表';

-- ----------------------------
-- Records of df_a_users
-- ----------------------------
INSERT INTO `df_a_users` VALUES ('1', 'admin@bropeak.com', '$2y$10$zVSNEmYdtoksJF7QGN985.2TaE2kSsQvx4cqtAZ53VFJOgSA6pd9G', null, null, '1511410294', null, '0');

-- ----------------------------
-- Table structure for `df_goods`
-- ----------------------------
DROP TABLE IF EXISTS `df_goods`;
CREATE TABLE `df_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `catgory_id` int(5) NOT NULL COMMENT '所属商品分类',
  `shop_catgory_id` int(3) unsigned NOT NULL COMMENT '所属店铺分类',
  `goods_z_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '商品主名称',
  `goods_f_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品副名称',
  `market_price` decimal(15,3) DEFAULT NULL COMMENT '市场价',
  `present_price` decimal(15,3) NOT NULL COMMENT '现价',
  `shop_id` int(10) NOT NULL DEFAULT '1' COMMENT '店铺id： 1： 官方内测店 ',
  `num_sold` int(10) NOT NULL DEFAULT '0' COMMENT '售出总量',
  `num_evaluation` int(10) NOT NULL DEFAULT '0' COMMENT '评价数量',
  `num_follow` int(10) NOT NULL DEFAULT '0' COMMENT '关注数量/浏览量',
  `num_collection` int(10) NOT NULL DEFAULT '0' COMMENT '收藏数量',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '上下架状态',
  `create_at` int(10) NOT NULL COMMENT '创建时间',
  `modiffied_at` int(10) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ind_id` (`id`) USING BTREE,
  UNIQUE KEY `ind_catgory_id` (`catgory_id`) USING BTREE,
  UNIQUE KEY `ind_shop_id_cat_id` (`shop_id`,`shop_catgory_id`) USING BTREE,
  KEY `ind_goods_z_name` (`goods_z_name`) USING BTREE,
  KEY `ind_present_price` (`present_price`) USING BTREE,
  KEY `ind_shop_id` (`shop_id`) USING BTREE,
  KEY `ind_create_at` (`create_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商品主表';

-- ----------------------------
-- Records of df_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `df_infos`
-- ----------------------------
DROP TABLE IF EXISTS `df_infos`;
CREATE TABLE `df_infos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` int(10) NOT NULL COMMENT '用户id',
  `u_type` int(1) unsigned NOT NULL COMMENT '用户类型：0：admin 1: 前台用户 2：店铺用户',
  `nick` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '昵称',
  `real_name` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '真实姓名',
  `sex` tinyint(1) DEFAULT '0' COMMENT '性别 0：女 1：男',
  `mobile` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `qq` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'QQ号',
  `country_code` int(6) DEFAULT NULL COMMENT '国家',
  `provence_code` int(6) DEFAULT NULL COMMENT '省',
  `city_code` int(6) DEFAULT NULL COMMENT '市',
  `area_code` int(6) DEFAULT NULL COMMENT '县',
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地址',
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户简介',
  `create_at` int(10) NOT NULL COMMENT '创建时间',
  `modified_at` int(10) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ind_id` (`id`) USING BTREE,
  UNIQUE KEY `ind_uid_u_type` (`uid`,`u_type`) USING BTREE,
  KEY `ind_create_at` (`create_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户资料表';

-- ----------------------------
-- Table structure for `df_u_users`
-- ----------------------------
DROP TABLE IF EXISTS `df_u_users`;
CREATE TABLE `df_u_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `user_pwd` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  `mobile` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `qq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'QQ互联',
  `weibo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '微博互联',
  `create_at` int(10) NOT NULL COMMENT '创建时间',
  `modiffied_at` int(10) DEFAULT NULL COMMENT '修改时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '账号状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ind_id` (`id`) USING BTREE,
  UNIQUE KEY `ind_user_name` (`user_name`) USING BTREE,
  UNIQUE KEY `ind_mobile` (`mobile`) USING BTREE,
  UNIQUE KEY `ind_email` (`email`) USING BTREE,
  KEY `ind_create_at` (`create_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT COMMENT='前台用户表';