/*
Navicat MySQL Data Transfer

Source Server         : kaoqin_webuildu
Source Server Version : 50719
Source Host           : 127.0.0.1:3306
Source Database       : kaoqin_webuildu

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-08-27 14:18:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for attendance
-- ----------------------------
DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `time_day` varchar(32) DEFAULT NULL COMMENT '考勤日期',
  `morning_time` varchar(32) DEFAULT NULL,
  `afternoon_time` varchar(32) DEFAULT NULL,
  `is_morning_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否迟到 1:正常 2:迟到 3:缺卡',
  `morning_remarks` text COMMENT '备注',
  `morning_address` varchar(255) DEFAULT NULL COMMENT '早上打卡地点',
  `afternoon_address` varchar(255) DEFAULT NULL,
  `is_afternoon_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否迟到 1:正常 2:早退 3:缺卡',
  `afternoon_remarks` text COMMENT '早退备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for miscellaneous
-- ----------------------------
DROP TABLE IF EXISTS `miscellaneous`;
CREATE TABLE `miscellaneous` (
  `id` int(11) NOT NULL COMMENT '主键id',
  `to_work` varchar(32) DEFAULT NULL COMMENT '上班时间',
  `out_work` varchar(32) DEFAULT NULL COMMENT '下班时间',
  `is_legal_holidays` tinyint(1) DEFAULT NULL COMMENT '是否法定假日 1：不是 2：是',
  `company_name` varchar(32) DEFAULT NULL COMMENT '上班地点',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `name` varchar(32) NOT NULL COMMENT '用户名',
  `nickname` varchar(255) DEFAULT NULL COMMENT '描述',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别:',
  `phone` char(12) DEFAULT NULL COMMENT '电话号码',
  `identifier` char(12) DEFAULT NULL,
  `openid` varchar(64) DEFAULT NULL,
  `created_at` varchar(128) DEFAULT NULL COMMENT '创建时间',
  `updated_at` varchar(128) DEFAULT NULL COMMENT '修改时间',
  `headimgurl` varchar(255) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=292 DEFAULT CHARSET=utf8mb4;
