-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-04-28 08:23:27
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

--
-- Database: `weizephp4`
--

-- --------------------------------------------------------

--
-- 表的结构 `w_accesstoken`
--

CREATE TABLE IF NOT EXISTS `w_accesstoken` (
  `aid` char(8) NOT NULL DEFAULT '',
  `expire` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `w_actionlog`
--

CREATE TABLE IF NOT EXISTS `w_actionlog` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL DEFAULT '',
  `logtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `loginfo` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `w_adminlog`
--

CREATE TABLE IF NOT EXISTS `w_adminlog` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL DEFAULT '',
  `logtime` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `loginfo` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `w_article`
--

CREATE TABLE IF NOT EXISTS `w_article` (
  `aid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `w_article_attachment`
--

CREATE TABLE IF NOT EXISTS `w_article_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '附件',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章附件表' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `w_article_category`
--

CREATE TABLE IF NOT EXISTS `w_article_category` (
  `cid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '显示状态',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `displayorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `seotitle` varchar(60) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seokeywords` varchar(100) NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seodescription` text NOT NULL COMMENT 'SEO简介',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='新闻分类表' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `w_article_content`
--

CREATE TABLE IF NOT EXISTS `w_article_content` (
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章内容表';


-- --------------------------------------------------------

--
-- 表的结构 `w_captcha`
--

CREATE TABLE IF NOT EXISTS `w_captcha` (
  `cid` char(8) NOT NULL,
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `code` char(6) NOT NULL DEFAULT '',
  `verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `cid` (`cid`) USING HASH
) ENGINE=MEMORY DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- 表的结构 `w_config`
--

CREATE TABLE IF NOT EXISTS `w_config` (
  `ckey` varchar(255) NOT NULL,
  `cvalue` text NOT NULL,
  PRIMARY KEY (`ckey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `w_config`
--

INSERT INTO `w_config` (`ckey`, `cvalue`) VALUES
('site_name', 'WeizePHP框架系统4.0'),
('site_title', 'WeizePHP框架系统4.00000'),
('site_keywords', 'WeizePHP框架系统4.0'),
('site_description', 'WeizePHP框架系统 - 专业提供建站系统服务'),
('site_icp', '粤ICP备09215389号-2'),
('site_statistical_code', '<script>\r\nvar tt = ''test'';\r\nalert(tt);\r\n</script>'),
('site_footer', '<p>\r\n    专注、专业、诚实\r\n    ┆ <a target="_blank" href="http://weizecms.75hh.com/">WeizeCMS.75hh.com</a>\r\n    ┆ 简单就是好，用了忘不了！\r\n</p>\r\n<P>\r\n    幸福就是：雨天能为你撑起一把小伞；幸福就是：牵你的小手与你共度夕阳；幸福就是：你永远开心快乐！\r\n</P>\r\n<p>Copyright ? 2013 - 2113 75hh.Com All Rights Reserved</p>'),
('site_closed', '0'),
('site_closed_reason', '就是不关闭啦啦啦');

-- --------------------------------------------------------

--
-- 表的结构 `w_loginfailed`
--

CREATE TABLE IF NOT EXISTS `w_loginfailed` (
  `ipusername` char(15) NOT NULL DEFAULT '',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ipusername`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `w_nav`
--

CREATE TABLE IF NOT EXISTS `w_nav` (
  `nid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL COMMENT '导航名称',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0关闭,1启用',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `internal` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '内部链接.0为否,1为是',
  `url` varchar(255) NOT NULL COMMENT '链接',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0本窗口打开,1新窗口打开',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '导航图片',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  PRIMARY KEY (`nid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='导航表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `w_nav`
--

INSERT INTO `w_nav` (`nid`, `pid`, `level`, `name`, `status`, `listorder`, `internal`, `url`, `target`, `pic`, `description`) VALUES
(1, 0, 1, '首 页', 1, 1, 1, './', 0, '', '首页'),
(2, 0, 1, '下 载', 1, 2, 1, 'index.php?m=singlepage&a=view&spid=1', 0, '', '下载'),
(3, 0, 1, '文 档', 1, 3, 1, 'docs.html', 0, '', '文档');

-- --------------------------------------------------------

--
-- 表的结构 `w_role`
--

CREATE TABLE IF NOT EXISTS `w_role` (
  `roleid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rolename` varchar(32) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `w_role`
--

INSERT INTO `w_role` (`roleid`, `status`, `rolename`, `permission`) VALUES
(1, 1, '超级管理员', '');

-- --------------------------------------------------------

--
-- 表的结构 `w_session`
--

CREATE TABLE IF NOT EXISTS `w_session` (
  `sid` char(8) NOT NULL,
  `ip` char(15) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `formtoken` char(6) NOT NULL DEFAULT '',
  UNIQUE KEY `sid` (`sid`) USING HASH
) ENGINE=MEMORY DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- 表的结构 `w_singlepage`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `w_singlepage`
--

INSERT INTO `w_singlepage` (`spid`, `status`, `displayorder`, `title`, `subtitle`, `keywords`, `description`, `pic`, `hits`, `hitstime`, `createtime`, `updatetime`, `jumpurl`, `editor`) VALUES
(1, 1, 10, 'WeizePHP 下载', 'WeizePHP 下载', 'WeizePHP 下载', 'WeizePHP 下载', '', 0, 0, 1492137715, 1492672884, 'WeizePHP 下载', 'admin');

-- --------------------------------------------------------

--
-- 表的结构 `w_singlepage_attachment`
--

CREATE TABLE IF NOT EXISTS `w_singlepage_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `spid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '单页ID',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '附件',
  PRIMARY KEY (`id`),
  KEY `spid` (`spid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='单页附件表' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `w_singlepage_content`
--

CREATE TABLE IF NOT EXISTS `w_singlepage_content` (
  `spid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '单页ID',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`spid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页内容表';

--
-- 转存表中的数据 `w_singlepage_content`
--

INSERT INTO `w_singlepage_content` (`spid`, `content`) VALUES
(1, '<p>WeizePHP 下载</p>');

-- --------------------------------------------------------

--
-- 表的结构 `w_upload`
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='上传表' AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `w_user`
--

CREATE TABLE IF NOT EXISTS `w_user` (
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
  `point` int(10) NOT NULL DEFAULT '0',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `salt` char(6) NOT NULL,
  `realname` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

