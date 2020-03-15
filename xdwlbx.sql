/*
Navicat MySQL Data Transfer

Source Server         : wlbx_mgr
Source Server Version : 50552
Source Host           : wlbx.xidian.edu.cn:3306
Source Database       : xdwlbx

Target Server Type    : MYSQL
Target Server Version : 50552
File Encoding         : 65001

Date: 2020-03-15 13:51:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for feedback
-- ----------------------------
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `identity` text NOT NULL,
  `contact` text NOT NULL,
  `detail` longtext NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for inflist
-- ----------------------------
DROP TABLE IF EXISTS `inflist`;
CREATE TABLE `inflist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repairid` tinytext NOT NULL,
  `status` tinyint(4) NOT NULL,
  `updatetime` datetime NOT NULL,
  `remark` text,
  `exptime` tinytext,
  `processer` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17391 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jsexe
-- ----------------------------
DROP TABLE IF EXISTS `jsexe`;
CREATE TABLE `jsexe` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pagename` tinytext NOT NULL,
  `jstype` tinytext NOT NULL,
  `jscontent` longtext,
  `jslocation` text,
  `enable` tinyint(4) NOT NULL,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jsmgr
-- ----------------------------
DROP TABLE IF EXISTS `jsmgr`;
CREATE TABLE `jsmgr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pagename` tinytext NOT NULL,
  `jstype` tinytext NOT NULL,
  `jscontent` longtext,
  `jslocation` text,
  `enable` tinyint(4) NOT NULL,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for pagemgr
-- ----------------------------
DROP TABLE IF EXISTS `pagemgr`;
CREATE TABLE `pagemgr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pagename` tinytext NOT NULL,
  `pagecontent` longtext,
  `pagelocation` text,
  `enable` tinyint(4) NOT NULL,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for qiangpiao
-- ----------------------------
DROP TABLE IF EXISTS `qiangpiao`;
CREATE TABLE `qiangpiao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET latin1 NOT NULL,
  `name_hash` text CHARACTER SET latin1 NOT NULL,
  `stuid` text CHARACTER SET latin1 NOT NULL,
  `stuid_hash` text CHARACTER SET latin1 NOT NULL,
  `tel` text CHARACTER SET latin1 NOT NULL,
  `tel_hash` text CHARACTER SET latin1 NOT NULL,
  `wechat` text CHARACTER SET latin1 NOT NULL,
  `qp_chang` int(11) NOT NULL,
  `qp_ci` int(11) NOT NULL,
  `qp_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=193 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for qiangpiao_idx
-- ----------------------------
DROP TABLE IF EXISTS `qiangpiao_idx`;
CREATE TABLE `qiangpiao_idx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET latin1 NOT NULL,
  `chang` int(11) NOT NULL,
  `ci` int(11) NOT NULL,
  `begin_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `qp_limit` int(11) NOT NULL,
  `qp_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for queue
-- ----------------------------
DROP TABLE IF EXISTS `queue`;
CREATE TABLE `queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repairid` tinytext NOT NULL,
  `wechatid` tinytext NOT NULL,
  `usrid` int(10) unsigned NOT NULL,
  `freetime` tinytext NOT NULL,
  `buildnum` tinytext NOT NULL,
  `floor` tinytext NOT NULL,
  `section` tinytext NOT NULL,
  `roomnum` tinytext NOT NULL,
  `roomside` tinytext NOT NULL,
  `nowstage` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  `qstatus` tinyint(4) NOT NULL,
  `processer` tinytext,
  `remark` text,
  `finishtime` int(10) unsigned DEFAULT NULL COMMENT '添加时间完成时间戳',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3818 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for routerscan
-- ----------------------------
DROP TABLE IF EXISTS `routerscan`;
CREATE TABLE `routerscan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET latin1 NOT NULL,
  `loss` tinyint(4) NOT NULL,
  `rtt` tinyint(8) NOT NULL,
  `condition` tinyint(1) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=347551 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3771 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for usr
-- ----------------------------
DROP TABLE IF EXISTS `usr`;
CREATE TABLE `usr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realname` text NOT NULL,
  `namehash` tinytext NOT NULL,
  `stuid` text NOT NULL,
  `stuidhash` tinytext NOT NULL,
  `tel` text NOT NULL,
  `telhash` tinytext NOT NULL,
  `wechatid` tinytext NOT NULL,
  `groups` tinytext NOT NULL,
  `enable` tinyint(4) NOT NULL,
  `remark` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2601 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wechatbind
-- ----------------------------
DROP TABLE IF EXISTS `wechatbind`;
CREATE TABLE `wechatbind` (
  `key` tinytext NOT NULL,
  `wechatid` tinytext,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`key`(32))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Event structure for auto_delete_token
-- ----------------------------
DROP EVENT IF EXISTS `auto_delete_token`;
DELIMITER ;;
CREATE DEFINER=`xdwlbx`@`localhost` EVENT `auto_delete_token` ON SCHEDULE EVERY 1 DAY STARTS '2019-03-14 19:14:09' ON COMPLETION NOT PRESERVE ENABLE COMMENT '每天清理前一天的token信息 5 删 3' DO DELETE from token WHERE time<now()-INTERVAL 1 day
;;
DELIMITER ;

-- ----------------------------
-- Event structure for auto_delete_wechatbind_1_month_ago
-- ----------------------------
DROP EVENT IF EXISTS `auto_delete_wechatbind_1_month_ago`;
DELIMITER ;;
CREATE DEFINER=`xdwlbx`@`localhost` EVENT `auto_delete_wechatbind_1_month_ago` ON SCHEDULE EVERY 1 DAY STARTS '2019-03-14 19:18:40' ON COMPLETION NOT PRESERVE ENABLE DO DELETE from wechatbind WHERE time<now()- INTERVAL 1 MONTH
;;
DELIMITER ;
SET FOREIGN_KEY_CHECKS=1;
