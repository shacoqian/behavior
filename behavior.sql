/*
Navicat MySQL Data Transfer

Source Server         : 121.40.130.117
Source Server Version : 50544
Source Host           : 121.40.130.117:3306
Source Database       : behavior

Target Server Type    : MYSQL
Target Server Version : 50544
File Encoding         : 65001
Date: 2016-02-23 10:55:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `behavior`
-- ----------------------------
DROP TABLE IF EXISTS `behavior`;
CREATE TABLE `behavior` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '行为分类id',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `who` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'who do it',
  `year` int(4) NOT NULL DEFAULT '0' COMMENT '年',
  `month` tinyint(2) NOT NULL DEFAULT '0' COMMENT '月',
  `day` tinyint(2) NOT NULL DEFAULT '0' COMMENT '日',
  `ctime` varchar(20) NOT NULL DEFAULT '' COMMENT '时间',
  `money` varchar(10) NOT NULL DEFAULT '0' COMMENT '消费',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2618 DEFAULT CHARSET=utf8 COMMENT='行为表';

-- ----------------------------
-- Records of behavior
-- ----------------------------

-- ----------------------------
-- Table structure for `category`
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '家庭id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES ('1', '衣服鞋袜', '1');
INSERT INTO `category` VALUES ('2', '买菜吃饭', '1');
INSERT INTO `category` VALUES ('3', '水果零食', '1');
INSERT INTO `category` VALUES ('4', '烟酒饮料', '1');
INSERT INTO `category` VALUES ('5', '生活用品', '1');
INSERT INTO `category` VALUES ('7', '房租水电', '1');
INSERT INTO `category` VALUES ('8', '回家旅行', '1');
INSERT INTO `category` VALUES ('9', '话费交通', '1');
INSERT INTO `category` VALUES ('10', '其他支出', '1');

-- ----------------------------
-- Table structure for `group`
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gname` varchar(50) NOT NULL DEFAULT '' COMMENT '家庭组',
  `password` varchar(40) NOT NULL DEFAULT '' COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='家庭表';

-- ----------------------------
-- Records of group
-- ----------------------------


-- ----------------------------
-- Table structure for `income`
-- ----------------------------
DROP TABLE IF EXISTS `income`;
CREATE TABLE `income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(4) NOT NULL DEFAULT '0' COMMENT '人员',
  `money` float NOT NULL DEFAULT '0' COMMENT '收入',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收入表';

-- ----------------------------
-- Records of income
-- ----------------------------

-- ----------------------------
-- Table structure for `personnel`
-- ----------------------------
DROP TABLE IF EXISTS `personnel`;
CREATE TABLE `personnel` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '家庭组id',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `nameabb` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名缩写',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='人员表';

-- ----------------------------
-- Records of personnel
-- ----------------------------


