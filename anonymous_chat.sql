-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 2018-08-16 12:31:45
-- 服务器版本： 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anonymous_chat`
--

-- --------------------------------------------------------

--
-- 表的结构 `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `msg` text CHARACTER SET utf8mb4,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf32;

--
-- 转存表中的数据 `chat`
--

INSERT INTO `chat` (`id`, `room_id`, `msg`, `time`) VALUES
(1, '34234234', '12321', '2018-03-30 09:34:36');

-- --------------------------------------------------------

--
-- 表的结构 `headshot`
--

DROP TABLE IF EXISTS `headshot`;
CREATE TABLE IF NOT EXISTS `headshot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `girl` varchar(255) NOT NULL COMMENT '女生头像，文件名',
  `boy` varchar(255) NOT NULL COMMENT '男生头像，文件名',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='情侣头像';

--
-- 转存表中的数据 `headshot`
--

INSERT INTO `headshot` (`id`, `girl`, `boy`, `time`) VALUES
(1, '5abdc7db07c59mengbi.jpg', '5abdc7db07c54mengbi.jpg', '2018-03-30 05:15:07');

-- --------------------------------------------------------

--
-- 表的结构 `matching`
--

DROP TABLE IF EXISTS `matching`;
CREATE TABLE IF NOT EXISTS `matching` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) DEFAULT NULL COMMENT '连接的IP地址',
  `gender` enum('boy','girl') DEFAULT NULL COMMENT '性别',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf32;

--
-- 转存表中的数据 `matching`
--

INSERT INTO `matching` (`id`, `ip`, `gender`, `time`) VALUES
(1, '127.0.0.1', 'boy', '2018-03-30 09:33:56'),
(2, '127.0.0.1', 'boy', '2018-03-30 09:34:27'),
(3, '127.0.0.1', 'girl', '2018-03-30 09:34:29'),
(4, '127.0.0.1', 'boy', '2018-03-30 09:37:22');

-- --------------------------------------------------------

--
-- 表的结构 `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` varchar(100) NOT NULL COMMENT '房间ID',
  `girl` varchar(255) NOT NULL COMMENT 'ip',
  `boy` varchar(255) NOT NULL COMMENT 'ip',
  `girl_avatar` varchar(255) NULL COMMENT '女生头像',
  `boy_avatar` varchar(255) NULL COMMENT '男生头像',
  `topic` varchar(255) NULL COMMENT '聊天主题',
  `timelimit` int(11) DEFAULT NULL COMMENT '时间限制',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '房间创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf32;

--
-- 转存表中的数据 `room`
--

INSERT INTO `room` (`id`, `room_id`, `girl`, `boy`, `timelimit`, `time`) VALUES
(1, 9, '127.0.0.1', '127.0.0.1', 107, '2018-03-30 09:34:30');

-- --------------------------------------------------------

--
-- 表的结构 `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boy` int(11) NOT NULL,
  `girl` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `status`
--

INSERT INTO `status` (`id`, `boy`, `girl`, `total`) VALUES
(1, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `topic`
--

DROP TABLE IF EXISTS `topic`;
CREATE TABLE IF NOT EXISTS `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `topic`
--

INSERT INTO `topic` (`id`, `topic`) VALUES
(1, '假如你们买彩票中奖10万了，你们会拿这些钱买点什么？'),
(2, '假如今天是1周年纪念日，你们会有什么安排？'),
(3, '假如你们昨天吵架了，你们今天怎样才能和好？\r\n'),
(4, '假如今天是你们第一天交往，你们会做什么？');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
