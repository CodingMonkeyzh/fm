-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 09 月 02 日 17:12
-- 服务器版本: 5.1.33-community
-- PHP 版本: 5.3.26

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hnust`
--

-- --------------------------------------------------------

--
-- 表的结构 `administrator`
--

CREATE TABLE IF NOT EXISTS `administrator` (
  `adminName` varchar(20) NOT NULL,
  `name` varchar(10) NOT NULL,
  `nikeName` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `privilege` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adminName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `administrator`
--

INSERT INTO `administrator` (`adminName`, `name`, `nikeName`, `password`, `email`, `privilege`) VALUES
('admin', '', '疯神', '4297f44b13955235245b2497399d7a93', '123123@qq.com', 0),
('zhangwei', '张委', '囚徒', '4297f44b13955235245b2497399d7a93', '505600376@qq.com', 1);

-- --------------------------------------------------------

--
-- 表的结构 `chatrecords`
--

CREATE TABLE IF NOT EXISTS `chatrecords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `speaker` varchar(20) NOT NULL,
  `time` datetime NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `chatrecords`
--

INSERT INTO `chatrecords` (`id`, `speaker`, `time`, `content`) VALUES
(1, '1005030211', '2013-09-01 19:16:41', '111'),
(2, '1005030213', '2013-09-01 19:17:37', '11'),
(3, '1005030211', '2013-09-01 19:17:43', 'sasdf'),
(4, '1005030211', '2013-09-01 19:18:38', '哈哈哈哈哈哈哈哈哈哈哈啊哈哈哈'),
(5, '1005030211', '2013-09-01 19:38:54', '111'),
(6, '1005030213', '2013-09-01 19:39:32', '11'),
(7, '1005030211', '2013-09-01 19:39:37', '撒旦发射点发'),
(8, '1005030211', '2013-09-01 19:41:04', '我错'),
(9, '1005030211', '2013-09-01 19:41:07', '我日'),
(12, '1005030211', '2013-09-01 19:50:19', '我去'),
(13, '1005030211', '2013-09-01 19:54:19', 'woren'),
(14, '1005030211', '2013-09-02 12:27:51', '我日啊'),
(15, '1005030211', '2013-09-02 15:31:23', '我草'),
(16, '1005030211', '2013-09-02 15:31:23', ''),
(17, '1005030213', '2013-09-02 15:32:25', '逗逼'),
(18, '1005030213', '2013-09-02 15:32:39', '搜索'),
(19, '1005030212', '2013-09-02 15:32:46', 'sdf s'),
(20, '1005030212', '2013-09-02 16:00:02', '111'),
(21, '1005030213', '2013-09-02 16:00:08', 'ww'),
(22, '1005030212', '2013-09-02 16:00:48', '11'),
(23, '1005030212', '2013-09-02 16:01:28', '11'),
(24, '1005030213', '2013-09-02 16:01:40', '55'),
(25, '1005030212', '2013-09-02 16:02:19', '111'),
(26, '1005030212', '2013-09-02 16:03:10', '1111'),
(27, '1005030212', '2013-09-02 16:03:54', '111'),
(28, '1005030213', '2013-09-02 16:04:03', 'fg '),
(29, '1005030212', '2013-09-02 16:06:37', '1'),
(30, '1005030212', '2013-09-02 16:11:19', '1'),
(31, '1005030212', '2013-09-02 16:11:51', '546'),
(32, '1005030213', '2013-09-02 16:16:04', '123'),
(33, '1005030212', '2013-09-02 16:16:56', 'hg'),
(35, '1005030211', '2013-09-02 16:54:40', 'saasdasdasd'),
(36, '1005030211', '2013-09-02 17:10:31', '');

-- --------------------------------------------------------

--
-- 表的结构 `ilike`
--

CREATE TABLE IF NOT EXISTS `ilike` (
  `userNumber` int(11) NOT NULL,
  `collection` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `music`
--

CREATE TABLE IF NOT EXISTS `music` (
  `musicID` int(10) NOT NULL AUTO_INCREMENT,
  `musicName` varchar(30) CHARACTER SET utf8 NOT NULL,
  `musicSinger` varchar(20) CHARACTER SET utf8 NOT NULL,
  `musicAlbum` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `musicClass` int(2) NOT NULL,
  `musicPub` date DEFAULT NULL,
  `musicCover` varchar(20) NOT NULL,
  `URL` varchar(50) NOT NULL DEFAULT 'hnust/Public/Songs/',
  PRIMARY KEY (`musicID`)
) ENGINE=InnoDB  DEFAULT CHARSET=gbk AUTO_INCREMENT=44 ;

--
-- 转存表中的数据 `music`
--

INSERT INTO `music` (`musicID`, `musicName`, `musicSinger`, `musicAlbum`, `musicClass`, `musicPub`, `musicCover`, `URL`) VALUES
(27, '一个人的城市', '金志文', '未知', 1, '2013-08-09', '27', '/Public/Songs/'),
(28, 'Lean on me', 'Bill', 'Unknow', 2, '2012-01-01', '28', '/Public/Songs/'),
(29, '十年', '陈奕迅', 'The Best Moment', 3, '2008-08-10', '29', '/Public/Songs/'),
(30, '存在', '汪峰', '生无所求', 4, '2008-08-08', '30', '/Public/Songs/'),
(31, '小鳄鱼之歌', '儿歌', 'Unknow', 5, '2011-08-04', '31', '/Public/Songs/'),
(32, '甘心情愿', '郭峰', 'Unknow', 6, '2011-08-04', '32', '/Public/Songs/'),
(33, '海阔天空', 'Beyond', 'Unknow', 10, '2011-08-04', '33', '/Public/Songs/'),
(34, '别在伤口上撒盐', '张惠妹', 'Unknow', 4, '2011-08-04', '34', '/Public/Songs/'),
(35, '春天里', '汪峰', '信仰在空中飘扬', 1, '2013-08-20', '35.jpg', '/Public/Songs/'),
(36, '趁早', '李琦', '中国好声音第二季 第一期', 1, '0000-00-00', '36.jpg', '/Public/Songs/'),
(37, '把悲伤留给自己', '陈升', '魔鬼A春天', 1, '0000-00-00', '37.jpg', '/Public/Songs/'),
(38, '相见恨晚', '彭佳慧', 'K情歌', 1, '0000-00-00', '38.jpg', '/Public/Songs/'),
(39, '趁早', '张惠妹', '不顾一切', 1, '2013-08-20', '39.jpg', '/Public/Songs/'),
(40, 'Heaven', '滨崎步', 'A Complete ~All Singles~', 12, '0000-00-00', '40.jpg', '/Public/Songs/'),
(41, '在水一方', '邓丽君', '花样年华', 13, '0000-00-00', '41.jpg', '/Public/Songs/'),
(42, '甜甜的', '周杰伦', '牛仔很忙', 15, '0000-00-00', '42.jpg', '/Public/Songs/'),
(43, '容易受伤的女人', '王菲', 'Coming home', 16, '0000-00-00', '43.jpg', '/Public/Songs/');

-- --------------------------------------------------------

--
-- 表的结构 `musicclass`
--

CREATE TABLE IF NOT EXISTS `musicclass` (
  `classID` int(11) NOT NULL AUTO_INCREMENT,
  `className` varchar(10) NOT NULL,
  PRIMARY KEY (`classID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `musicclass`
--

INSERT INTO `musicclass` (`classID`, `className`) VALUES
(1, '华　语'),
(2, '欧　美'),
(3, '粤　语'),
(4, '流　行'),
(5, '摇　滚'),
(6, '古　典'),
(9, '未分类'),
(10, '轻音乐'),
(12, '日　韩'),
(13, '怀　旧'),
(15, '甜　蜜'),
(16, '伤　感'),
(17, '红　歌');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userNumber` int(10) NOT NULL,
  `userName` varchar(20) CHARACTER SET utf8 NOT NULL,
  `userPaswd` varchar(40) CHARACTER SET utf8 NOT NULL,
  `userEmail` varchar(50) CHARACTER SET utf8 NOT NULL,
  `userSex` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `userBirthday` date NOT NULL,
  `userExplanation` text NOT NULL,
  `userPhoto` varchar(10) NOT NULL DEFAULT 'default',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `ban` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=gbk;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`userNumber`, `userName`, `userPaswd`, `userEmail`, `userSex`, `userBirthday`, `userExplanation`, `userPhoto`, `status`, `ban`) VALUES
(1005030210, '苏勇', '4297f44b13955235245b2497399d7a93', '', NULL, '0000-00-00', '', 'default', 0, 0),
(1005030211, '周权', '4297f44b13955235245b2497399d7a93', '496115864@qq.com', '男', '1998-08-08', '为梦想而生', '1005030211', 0, 0),
(1005030212, '谢廷辉', '4297f44b13955235245b2497399d7a93', '', NULL, '0000-00-00', '', 'default', 0, 0),
(1005030213, '张委', '4297f44b13955235245b2497399d7a93', '505600376@qq.com', '男', '1991-11-20', '大叔是逗逼', '1005030213', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
