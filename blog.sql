/*
Navicat MySQL Data Transfer

Source Server         : echo
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2020-03-30 00:06:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `authorId` int(11) unsigned NOT NULL,
  `categoryId` int(11) unsigned NOT NULL,
  `title` varchar(100) DEFAULT '未命名标题' COMMENT '文章标题',
  `content` longtext DEFAULT NULL COMMENT '文章内容',
  `tags` varchar(100) DEFAULT '"[]"',
  `description` varchar(500) DEFAULT NULL,
  `isOrigin` int(1) DEFAULT 1,
  `isPrivate` int(1) DEFAULT 1,
  `isDraft` int(1) DEFAULT 1,
  `createAt` int(13) DEFAULT NULL,
  `updateAt` int(13) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `authorId` (`authorId`),
  KEY `categoryId` (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `authorId` int(11) unsigned NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `createAt` int(13) DEFAULT NULL,
  `updateAt` int(13) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`authorId`),
  CONSTRAINT `user` FOREIGN KEY (`authorId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(18) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `isBindEmail` int(1) DEFAULT 0,
  `avatar` varchar(500) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT '匿名用户',
  `createAt` int(13) DEFAULT NULL,
  `updateAt` int(13) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `article` FOREIGN KEY (`id`) REFERENCES `article` (`authorId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `nikeName` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL COMMENT '邮件',
  `avatar` varchar(255) DEFAULT 'avatar.png',
  `emailCaptcha` varchar(30) DEFAULT '' COMMENT '邮件验证码',
  `rememberToken` varchar(255) DEFAULT '' COMMENT '登录token',
  `createAt` timestamp NULL DEFAULT NULL COMMENT '注册时间',
  `updateAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT '更新时间',
  `deleteTime` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
