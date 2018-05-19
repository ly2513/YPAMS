# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.21)
# Database: yp_ams
# Generation Time: 2018-05-10 16:05:10 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table ams_sys_menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ams_sys_menus`;

CREATE TABLE `ams_sys_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `pid` int(10) unsigned NOT NULL COMMENT '菜单父ID',
  `icon` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '菜单层级',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单路由',
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单描述',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单排序',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:菜单 1:接口',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_by` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建人',
  `update_by` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新人',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单表';

LOCK TABLES `ams_sys_menus` WRITE;
/*!40000 ALTER TABLE `ams_sys_menus` DISABLE KEYS */;

INSERT INTO `ams_sys_menus` (`id`, `pid`, `icon`, `name`, `level`, `url`, `description`, `sort`, `type`, `create_time`, `update_time`, `create_by`, `update_by`, `is_delete`)
VALUES
	(1,0,'fa-th-large','权限管理',1,'#','',80,0,1524755039,1525960045,1,1,0),
	(2,1,'fa-group','账号管理',2,'/Admin/User/getUser','',80,0,1524756747,1524756747,1,1,0),
	(3,1,'fa-user','角色管理',2,'/Admin/Role/getRole','',70,0,1524757574,1524757574,1,1,0),
	(4,1,'fa-list','菜单管理',2,'/Admin/Menu/getMenu','',60,0,1524757648,1524757648,1,1,0),
	(22,0,'fa-home','首页',1,'/Admin/Index/index','',100,0,1525959670,1525960017,1,1,0),
	(23,0,'fa-credit-card','H+前端框架',1,'/Admin/Html/index','前端页面',90,0,1525959825,1525960033,1,1,0);

/*!40000 ALTER TABLE `ams_sys_menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ams_sys_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ams_sys_roles`;

CREATE TABLE `ams_sys_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique role ID.',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '角色名称',
  `menus` longblob NOT NULL COMMENT '菜单',
  `permissions` longblob NOT NULL COMMENT '权限',
  `p_id` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '1:正常 2:禁用',
  `create_by` int(11) NOT NULL DEFAULT '0' COMMENT '创建者',
  `update_by` int(11) NOT NULL DEFAULT '0' COMMENT '更新者',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '逻辑删除(0:正常1:删除)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_name` (`name`),
  KEY `i_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='角色表';

LOCK TABLES `ams_sys_roles` WRITE;
/*!40000 ALTER TABLE `ams_sys_roles` DISABLE KEYS */;

INSERT INTO `ams_sys_roles` (`id`, `name`, `menus`, `permissions`, `p_id`, `status`, `create_by`, `update_by`, `create_time`, `update_time`, `is_delete`)
VALUES
	(1,'超级管理员',X'5B32322C32332C315D',X'5B332C342C322C32322C32332C315D',0,1,0,0,0,1525962159,0),
	(2,'前端',X'5B32322C32335D',X'5B32322C32335D',0,1,1,1,1524671404,1525964014,0);

/*!40000 ALTER TABLE `ams_sys_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ams_sys_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ams_sys_user`;

CREATE TABLE `ams_sys_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
  `roles` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色ID',
  `nickname` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `head_img` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Static/images/head.png' COMMENT '头像',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1: 激活2：禁用',
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标识',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `create_by` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建人',
  `update_by` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新人',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0: 正常 1: 已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表';

LOCK TABLES `ams_sys_user` WRITE;
/*!40000 ALTER TABLE `ams_sys_user` DISABLE KEYS */;

INSERT INTO `ams_sys_user` (`id`, `pid`, `name`, `roles`, `nickname`, `phone`, `password`, `head_img`, `status`, `token`, `create_time`, `update_time`, `login_time`, `create_by`, `update_by`, `is_delete`)
VALUES
	(1,0,'admin','[\"1\",\"3\"]','dylan.li','18518178485','$2y$10$w4xL7hzI1dIATcSWfcZlTerFg3wjeomZGfSqHTA/mbvdG/JHqjWsq','Static/images/head.png',1,'',0,1525959021,0,0,1,0),
	(2,0,'张三','[]','ly2513','','$2y$10$dintxct1gqOauJ0trV543OxMLnE3djt5hrwc/XPUeYye5eoopbY9G','Static/images/head.png',2,'',1524418445,1524745819,0,1,1,0);

/*!40000 ALTER TABLE `ams_sys_user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table ams_sys_user_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ams_sys_user_role`;

CREATE TABLE `ams_sys_user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `rid` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户与角色的当前状态  0激活 1冻结',
  `create_by` int(11) DEFAULT '0' COMMENT '创建者',
  `update_by` int(11) NOT NULL DEFAULT '0' COMMENT '更新者',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:正常1：删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='账号与角色关联表';

LOCK TABLES `ams_sys_user_role` WRITE;
/*!40000 ALTER TABLE `ams_sys_user_role` DISABLE KEYS */;

INSERT INTO `ams_sys_user_role` (`id`, `uid`, `rid`, `status`, `create_by`, `update_by`, `create_time`, `update_time`, `is_delete`)
VALUES
	(1,1,1,0,1,1,0,1524500320,1),
	(2,1,1,0,1,1,1524500230,1524500320,1),
	(3,1,3,0,1,1,1524500230,1524500320,1),
	(4,1,1,0,1,1,1524500320,1524500320,0),
	(5,1,3,0,1,1,1524500320,1524500320,0);

/*!40000 ALTER TABLE `ams_sys_user_role` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
