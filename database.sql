-- phpMyAdmin SQL Dump
-- version 4.3.2
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 產生時間： 2017 年 11 月 30 日 15:31
-- 伺服器版本: 5.5.47-MariaDB
-- PHP 版本： 5.5.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫： `pure`
--

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_admin`
--

CREATE TABLE IF NOT EXISTS `cs_sys_admin` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `parentID` varchar(40) DEFAULT NULL COMMENT '上層',
  `account` varchar(50) NOT NULL COMMENT '密碼',
  `password` varchar(50) NOT NULL COMMENT '帳號',
  `title` varchar(50) DEFAULT NULL COMMENT '聯絡人',
  `domain` varchar(255) DEFAULT NULL COMMENT '專屬網址',
  `google_id` varchar(100) DEFAULT NULL COMMENT 'Google識別碼',
  `facebook_id` varchar(100) DEFAULT NULL COMMENT 'Facebook識別碼',
  `jsonContent` text COMMENT '額外資訊',
  `sortTime` datetime DEFAULT NULL COMMENT '排序時間',
  `sequence` int(11) DEFAULT NULL COMMENT '順序',
  `createTime` datetime DEFAULT NULL COMMENT '建立時間',
  `updateTime` datetime DEFAULT NULL COMMENT '更新時間'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_authority`
--

CREATE TABLE IF NOT EXISTS `cs_sys_authority` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `title` varchar(40) NOT NULL COMMENT '權限名稱',
  `authority_setting` text COMMENT '權限設定',
  `jsonContent` text COMMENT '額外資訊',
  `sortTime` datetime DEFAULT NULL COMMENT '排序時間',
  `sequence` int(11) DEFAULT NULL COMMENT '順序',
  `createTime` datetime DEFAULT NULL COMMENT '建立時間',
  `updateTime` datetime DEFAULT NULL COMMENT '更新時間'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_information`
--

CREATE TABLE IF NOT EXISTS `cs_sys_information` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `title` varchar(50) DEFAULT NULL COMMENT '系統名稱',
  `domain` varchar(10) DEFAULT NULL COMMENT '多網域設定',
  `jsonContent` text COMMENT '額外資訊'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_mail_setting`
--

CREATE TABLE IF NOT EXISTS `cs_sys_mail_setting` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `code` varchar(40) NOT NULL COMMENT '引用代碼',
  `sendMail` varchar(50) DEFAULT NULL COMMENT '發信信箱',
  `replyMail` varchar(50) DEFAULT NULL COMMENT '回覆信箱',
  `sendName` varchar(50) DEFAULT NULL COMMENT '信箱名稱',
  `smtpGate` varchar(1) DEFAULT NULL,
  `smtpHost` varchar(50) DEFAULT NULL,
  `smtpPort` int(11) DEFAULT NULL,
  `smtpUser` varchar(50) DEFAULT NULL,
  `smtpPassword` varchar(50) DEFAULT NULL,
  `smtpVerify` varchar(1) DEFAULT NULL,
  `smtpSecure` varchar(10) DEFAULT NULL,
  `jsonContent` text COMMENT '額外資訊',
  `sortTime` datetime DEFAULT NULL COMMENT '排序時間',
  `sequence` int(11) DEFAULT NULL COMMENT '順序',
  `createTime` datetime DEFAULT NULL COMMENT '建立時間',
  `updateTime` datetime DEFAULT NULL COMMENT '更新時間'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_mail_template`
--

CREATE TABLE IF NOT EXISTS `cs_sys_mail_template` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `code` varchar(40) NOT NULL COMMENT '引用代碼',
  `subject` varchar(100) DEFAULT NULL COMMENT '主旨',
  `content` text,
  `jsonContent` text COMMENT '額外資訊',
  `sortTime` datetime DEFAULT NULL COMMENT '排序時間',
  `sequence` int(11) DEFAULT NULL COMMENT '順序',
  `createTime` datetime DEFAULT NULL COMMENT '建立時間',
  `updateTime` datetime DEFAULT NULL COMMENT '更新時間'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_member`
--

CREATE TABLE IF NOT EXISTS `cs_sys_member` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `title` varchar(20) NOT NULL COMMENT '名稱',
  `code` varchar(30) NOT NULL COMMENT '引用代碼',
  `model` varchar(20) NOT NULL COMMENT '插件模組名稱',
  `data_source` varchar(20) NOT NULL COMMENT '代碼',
  `admin_login` varchar(5) DEFAULT NULL COMMENT '是否可後台登入',
  `authority` varchar(40) DEFAULT NULL COMMENT '控制權限',
  `jsonContent` text COMMENT '分類層數',
  `createTime` datetime DEFAULT NULL COMMENT '建立時間',
  `updateTime` datetime DEFAULT NULL COMMENT '更新時間',
  `sortTime` datetime DEFAULT NULL COMMENT '排序時間',
  `sequence` int(11) DEFAULT NULL COMMENT '順序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小部件資料表';

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_menu`
--

CREATE TABLE IF NOT EXISTS `cs_sys_menu` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `menu` text COMMENT '選單',
  `jsonContent` text COMMENT '額外資訊'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_page`
--

CREATE TABLE IF NOT EXISTS `cs_sys_page` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `parentID` varchar(40) NOT NULL COMMENT '分類識別碼',
  `widgetID` varchar(40) NOT NULL COMMENT '插件代碼',
  `hide` varchar(1) NOT NULL COMMENT '是否顯示',
  `desktop` tinyint(4) NOT NULL COMMENT '桌面寬度比',
  `pad` tinyint(4) NOT NULL COMMENT '平板寬度比',
  `phone` tinyint(4) NOT NULL COMMENT '手機寬度比',
  `createTime` datetime DEFAULT NULL COMMENT '建置時間',
  `updateTime` datetime DEFAULT NULL COMMENT '更新時間',
  `sortTime` datetime DEFAULT NULL COMMENT '排序時間',
  `sequence` int(11) DEFAULT NULL COMMENT '順序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='頁面設定';

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_page_class`
--

CREATE TABLE IF NOT EXISTS `cs_sys_page_class` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `parentID` varchar(40) DEFAULT NULL COMMENT '父分類識別碼',
  `title` varchar(50) NOT NULL COMMENT '頁面名稱',
  `landing` varchar(10) DEFAULT NULL COMMENT '登入頁',
  `logout` varchar(10) DEFAULT NULL COMMENT '登出頁',
  `sequence` int(11) NOT NULL COMMENT '排序',
  `jsonContent` text COMMENT '額外資訊'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `cs_sys_widget`
--

CREATE TABLE IF NOT EXISTS `cs_sys_widget` (
  `id` varchar(40) NOT NULL COMMENT '識別碼',
  `title` varchar(20) NOT NULL COMMENT '名稱',
  `code` varchar(30) NOT NULL COMMENT '引用代碼',
  `model` varchar(20) NOT NULL COMMENT '插件模組名稱',
  `data_source` varchar(20) NOT NULL COMMENT '代碼',
  `jsonContent` text COMMENT '分類層數',
  `createTime` datetime DEFAULT NULL COMMENT '建立時間',
  `updateTime` datetime DEFAULT NULL COMMENT '更新時間',
  `sortTime` datetime DEFAULT NULL COMMENT '排序時間',
  `sequence` int(11) DEFAULT NULL COMMENT '順序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小部件資料表';

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `cs_sys_admin`
--
ALTER TABLE `cs_sys_admin`
  ADD PRIMARY KEY (`id`), ADD KEY `google_id_index` (`google_id`), ADD KEY `facebook_id_index` (`facebook_id`), ADD KEY `domain_index` (`domain`), ADD KEY `parentID_index` (`parentID`);

--
-- 資料表索引 `cs_sys_authority`
--
ALTER TABLE `cs_sys_authority`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `cs_sys_information`
--
ALTER TABLE `cs_sys_information`
  ADD PRIMARY KEY (`id`), ADD KEY `domain_index` (`domain`);

--
-- 資料表索引 `cs_sys_mail_setting`
--
ALTER TABLE `cs_sys_mail_setting`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `cs_sys_mail_template`
--
ALTER TABLE `cs_sys_mail_template`
  ADD PRIMARY KEY (`id`), ADD KEY `code_index` (`code`);

--
-- 資料表索引 `cs_sys_member`
--
ALTER TABLE `cs_sys_member`
  ADD PRIMARY KEY (`id`), ADD KEY `code_index` (`code`), ADD KEY `admin_login_index` (`admin_login`);

--
-- 資料表索引 `cs_sys_menu`
--
ALTER TABLE `cs_sys_menu`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `cs_sys_page`
--
ALTER TABLE `cs_sys_page`
  ADD PRIMARY KEY (`id`), ADD KEY `parentID_index` (`parentID`);

--
-- 資料表索引 `cs_sys_page_class`
--
ALTER TABLE `cs_sys_page_class`
  ADD PRIMARY KEY (`id`), ADD KEY `sequence_index` (`sequence`), ADD KEY `parentID_index` (`parentID`);

--
-- 資料表索引 `cs_sys_widget`
--
ALTER TABLE `cs_sys_widget`
  ADD PRIMARY KEY (`id`), ADD KEY `code_index` (`code`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
