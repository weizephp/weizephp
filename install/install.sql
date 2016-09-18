-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2016 at 01:59 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

--
-- Database: `weizephp`
--

-- --------------------------------------------------------

--
-- Table structure for table `w_accesstokens`
--

CREATE TABLE IF NOT EXISTS `w_accesstokens` (
  `accesstoken` char(32) NOT NULL DEFAULT '',
  `expires` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`accesstoken`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `w_adminlog`
--

CREATE TABLE IF NOT EXISTS `w_adminlog` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL DEFAULT '',
  `logtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `loginfo` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `w_articles`
--

CREATE TABLE IF NOT EXISTS `w_articles` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `categoryid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '显示状态。0不显示，1显示',
  `displayorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `subtitle` varchar(255) NOT NULL DEFAULT '' COMMENT '副标题',
  `keywords` varchar(80) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` text NOT NULL COMMENT '简介',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `hits` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `hitstime` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点击时间',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `jumpurl` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转网址',
  `source` varchar(32) NOT NULL DEFAULT '' COMMENT '来源',
  `sourceurl` varchar(255) NOT NULL DEFAULT '' COMMENT '来源网址',
  `editor` varchar(32) NOT NULL DEFAULT '' COMMENT '编辑员',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `w_articlesattachments`
--

CREATE TABLE IF NOT EXISTS `w_articlesattachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '附件',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章附件表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `w_articlescategories`
--

CREATE TABLE IF NOT EXISTS `w_articlescategories` (
  `categoryid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `categoryname` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '显示状态',
  `displayorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `seotitle` varchar(60) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seokeywords` varchar(100) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seodescription` text NOT NULL COMMENT 'SEO简介',
  PRIMARY KEY (`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻分类表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `w_articlescontents`
--

CREATE TABLE IF NOT EXISTS `w_articlescontents` (
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章内容表';

-- --------------------------------------------------------

--
-- Table structure for table `w_captchas`
--

CREATE TABLE IF NOT EXISTS `w_captchas` (
  `cid` int(10) NOT NULL AUTO_INCREMENT,
  `code` char(6) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `count` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `w_config`
--

CREATE TABLE IF NOT EXISTS `w_config` (
  `ckey` varchar(255) NOT NULL,
  `cvalue` text NOT NULL,
  PRIMARY KEY (`ckey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `w_config`
--

INSERT INTO `w_config` (`ckey`, `cvalue`) VALUES
('site_name', 'WeizePHP框架系统3.0'),
('site_keywords', 'WeizePHP框架系统3.0'),
('site_description', 'WeizePHP框架系统 - 专业提供建站系统服务'),
('site_icp', '粤ICP备09215389号-2'),
('site_statistical_code', '<script>\r\nvar tt = ''test'';\r\nalert(tt);\r\n</script>'),
('site_footer', '<p>\r\n    专注、专业、诚实\r\n    ┆ <a target="_blank" href="http://weizecms.75hh.com/">WeizeCMS.75hh.com</a>\r\n    ┆ 简单就是好，用了忘不了！\r\n</p>\r\n<P>\r\n    幸福就是：雨天能为你撑起一把小伞；幸福就是：牵你的小手与你共度夕阳；幸福就是：你永远开心快乐！\r\n</P>\r\n<p>Copyright ? 2013 - 2113 75hh.Com All Rights Reserved</p>');

-- --------------------------------------------------------

--
-- Table structure for table `w_loginfailedaccount`
--

CREATE TABLE IF NOT EXISTS `w_loginfailedaccount` (
  `uid` mediumint(8) NOT NULL,
  `email` char(40) NOT NULL DEFAULT '',
  `username` char(15) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `ip` char(15) NOT NULL DEFAULT '',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `w_loginfailedip`
--

CREATE TABLE IF NOT EXISTS `w_loginfailedip` (
  `ip` char(15) NOT NULL DEFAULT '',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `w_roles`
--

CREATE TABLE IF NOT EXISTS `w_roles` (
  `roleid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rolename` varchar(32) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `w_roles`
--

INSERT INTO `w_roles` (`roleid`, `status`, `rolename`, `permissions`) VALUES
(1, 1, '超级管理员', 'generals/ueditor/controller');

-- --------------------------------------------------------

--
-- Table structure for table `w_sessions`
--

CREATE TABLE IF NOT EXISTS `w_sessions` (
  `sid` char(32) NOT NULL,
  `ip` char(15) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `salt` char(6) NOT NULL DEFAULT '',
  `formtoken` char(6) NOT NULL DEFAULT '',
  UNIQUE KEY `sid` (`sid`) USING HASH
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `w_singlepage`
--

CREATE TABLE IF NOT EXISTS `w_singlepage` (
  `spid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '单页ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '显示状态。0不显示，1显示',
  `displayorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `subtitle` varchar(255) NOT NULL DEFAULT '' COMMENT '副标题',
  `keywords` varchar(80) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` text NOT NULL COMMENT '简介',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `hits` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `hitstime` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点击时间',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `jumpurl` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转网址',
  `editor` varchar(32) NOT NULL DEFAULT '' COMMENT '编辑员',
  PRIMARY KEY (`spid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `w_singlepageattachment`
--

CREATE TABLE IF NOT EXISTS `w_singlepageattachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `spid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '单页ID',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '附件',
  PRIMARY KEY (`id`),
  KEY `spid` (`spid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页附件表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `w_singlepagecontent`
--

CREATE TABLE IF NOT EXISTS `w_singlepagecontent` (
  `spid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '单页ID',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`spid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页内容表';

-- --------------------------------------------------------

--
-- Table structure for table `w_upload`
--

CREATE TABLE IF NOT EXISTS `w_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `uploadtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `format` varchar(10) NOT NULL DEFAULT '' COMMENT '文件格式',
  `filename` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `filepath` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '使用状态。0为否1为是。',
  `width` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '图片宽',
  `height` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '图片高',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '文件标题',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '文件描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `w_users`
--

CREATE TABLE IF NOT EXISTS `w_users` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` char(40) NOT NULL,
  `username` char(15) NOT NULL,
  `mobile` char(11) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `roleid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `regtime` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL DEFAULT '',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `lastloginip` char(15) NOT NULL DEFAULT '',
  `logincount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL DEFAULT '0',
  `balances` decimal(10,2) NOT NULL DEFAULT '0.00',
  `salt` char(6) NOT NULL,
  `realname` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
