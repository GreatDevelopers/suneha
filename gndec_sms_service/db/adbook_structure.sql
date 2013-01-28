-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 28, 2013 at 03:01 PM
-- Server version: 5.5.29
-- PHP Version: 5.3.10-1ubuntu3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `adbook`
--

-- --------------------------------------------------------

--
-- Table structure for table `additionaldata`
--

CREATE TABLE IF NOT EXISTS `additionaldata` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) DEFAULT NULL,
  `value` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `refid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '',
  `line1` varchar(100) DEFAULT NULL,
  `line2` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `country` varchar(3) DEFAULT NULL,
  `phone1` varchar(20) DEFAULT NULL,
  `phone2` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`refid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=804 ;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(40) NOT NULL DEFAULT '',
  `lastname` varchar(80) NOT NULL DEFAULT '',
  `middlename` varchar(40) DEFAULT NULL,
  `primaryAddress` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `nickname` varchar(40) DEFAULT NULL,
  `pictureURL` varchar(255) DEFAULT NULL,
  `notes` text,
  `lastUpdate` datetime DEFAULT NULL,
  `hidden` int(1) NOT NULL DEFAULT '0',
  `whoAdded` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=814 ;

-- --------------------------------------------------------

--
-- Table structure for table `dlr`
--

CREATE TABLE IF NOT EXISTS `dlr` (
  `dlr-id` int(10) NOT NULL AUTO_INCREMENT,
  `smsc` varchar(255) NOT NULL,
  `ts` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `mask` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `boxc` varchar(255) NOT NULL,
  PRIMARY KEY (`dlr-id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4845 ;

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `grouplist`
--

CREATE TABLE IF NOT EXISTS `grouplist` (
  `groupid` int(11) NOT NULL DEFAULT '0',
  `groupname` varchar(60) DEFAULT NULL,
  `whoAdded` varchar(255) NOT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL DEFAULT '0',
  `groupid` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messaging`
--

CREATE TABLE IF NOT EXISTS `messaging` (
  `id` int(11) NOT NULL DEFAULT '0',
  `handle` varchar(30) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `bdayInterval` int(3) NOT NULL DEFAULT '21',
  `bdayDisplay` int(1) NOT NULL DEFAULT '1',
  `displayAsPopup` int(1) NOT NULL DEFAULT '0',
  `useMailScript` int(1) NOT NULL DEFAULT '1',
  `picAlwaysDisplay` int(1) NOT NULL DEFAULT '0',
  `picWidth` int(1) NOT NULL DEFAULT '140',
  `picHeight` int(1) NOT NULL DEFAULT '140',
  `picDupeMode` int(1) NOT NULL DEFAULT '1',
  `picAllowUpload` int(1) NOT NULL DEFAULT '1',
  `modifyTime` varchar(3) NOT NULL DEFAULT '0',
  `msgLogin` text,
  `msgWelcome` varchar(255) DEFAULT NULL,
  `countryDefault` char(3) DEFAULT '0',
  `allowUserReg` int(1) NOT NULL DEFAULT '0',
  `eMailAdmin` int(1) NOT NULL DEFAULT '0',
  `requireLogin` int(1) NOT NULL DEFAULT '1',
  `language` varchar(25) NOT NULL,
  `defaultLetter` char(2) DEFAULT NULL,
  `limitEntries` smallint(3) NOT NULL DEFAULT '0',
  `status_notification_admin` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `otherphone`
--

CREATE TABLE IF NOT EXISTS `otherphone` (
  `id` int(11) NOT NULL DEFAULT '0',
  `phone` varchar(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `msgdata` varchar(255) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `msgid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11333 ;

-- --------------------------------------------------------

--
-- Table structure for table `scratchpad`
--

CREATE TABLE IF NOT EXISTS `scratchpad` (
  `notes` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `send_sms`
--

CREATE TABLE IF NOT EXISTS `send_sms` (
  `sql_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `momt` enum('MO','MT') DEFAULT NULL,
  `sender` varchar(20) DEFAULT NULL,
  `receiver` varchar(20) DEFAULT NULL,
  `udhdata` blob,
  `msgdata` text,
  `time` varchar(255) DEFAULT NULL,
  `smsc_id` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL,
  `id` bigint(20) DEFAULT NULL,
  `sms_type` bigint(20) DEFAULT NULL,
  `mclass` bigint(20) DEFAULT NULL,
  `mwi` bigint(20) DEFAULT NULL,
  `coding` bigint(20) DEFAULT NULL,
  `compress` bigint(20) DEFAULT NULL,
  `validity` bigint(20) DEFAULT NULL,
  `deferred` bigint(20) DEFAULT NULL,
  `dlr_mask` bigint(20) DEFAULT '7',
  `dlr_url` varchar(255) DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `alt_dcs` bigint(20) DEFAULT NULL,
  `rpi` bigint(20) DEFAULT NULL,
  `charset` varchar(255) DEFAULT NULL,
  `boxc_id` varchar(255) DEFAULT NULL,
  `binfo` varchar(255) DEFAULT NULL,
  `meta_data` text,
  PRIMARY KEY (`sql_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11909 ;

-- --------------------------------------------------------

--
-- Table structure for table `sent_sms`
--

CREATE TABLE IF NOT EXISTS `sent_sms` (
  `sql_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `momt` enum('MO','MT','DLR') DEFAULT NULL,
  `sender` varchar(20) DEFAULT NULL,
  `receiver` varchar(20) DEFAULT NULL,
  `udhdata` blob,
  `msgdata` text,
  `time` varchar(255) DEFAULT NULL,
  `smsc_id` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL,
  `id` bigint(20) DEFAULT NULL,
  `sms_type` bigint(20) DEFAULT NULL,
  `mclass` bigint(20) DEFAULT NULL,
  `mwi` bigint(20) DEFAULT NULL,
  `coding` bigint(20) DEFAULT NULL,
  `compress` bigint(20) DEFAULT NULL,
  `validity` bigint(20) DEFAULT NULL,
  `deferred` bigint(20) DEFAULT NULL,
  `dlr_mask` bigint(20) DEFAULT NULL,
  `dlr_url` varchar(255) DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `alt_dcs` bigint(20) DEFAULT NULL,
  `rpi` bigint(20) DEFAULT NULL,
  `charset` varchar(255) DEFAULT NULL,
  `boxc_id` varchar(255) DEFAULT NULL,
  `binfo` varchar(255) DEFAULT NULL,
  `meta_data` text,
  PRIMARY KEY (`sql_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=187022 ;

-- --------------------------------------------------------

--
-- Table structure for table `tdlr`
--

CREATE TABLE IF NOT EXISTS `tdlr` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  `fid` varchar(255) NOT NULL,
  `msgid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4650 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(15) NOT NULL,
  `usertype` enum('admin','user','guest') NOT NULL DEFAULT 'user',
  `password` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `confirm_hash` varchar(50) NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `bdayInterval` int(3) DEFAULT NULL,
  `bdayDisplay` int(1) DEFAULT NULL,
  `displayAsPopup` int(1) DEFAULT NULL,
  `useMailScript` int(1) DEFAULT NULL,
  `language` varchar(25) DEFAULT NULL,
  `defaultLetter` char(2) DEFAULT NULL,
  `limitEntries` smallint(3) DEFAULT NULL,
  `LastLogin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=162 ;

-- --------------------------------------------------------

--
-- Table structure for table `websites`
--

CREATE TABLE IF NOT EXISTS `websites` (
  `id` int(11) NOT NULL DEFAULT '0',
  `webpageURL` varchar(255) DEFAULT NULL,
  `webpageName` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
