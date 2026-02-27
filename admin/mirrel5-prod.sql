-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.36-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.2.0.5684
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table transasia.account
CREATE TABLE IF NOT EXISTS `account` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `superadmin` int(3) NOT NULL DEFAULT '0',
  `name` varchar(225) NOT NULL DEFAULT '',
  `email` varchar(225) NOT NULL DEFAULT '',
  `password` varchar(225) NOT NULL DEFAULT '',
  `last_login` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `status` int(10) NOT NULL DEFAULT '1',
  `presence` int(5) NOT NULL DEFAULT '1',
  `ilock` int(5) NOT NULL DEFAULT '0',
  `token` varchar(250) NOT NULL DEFAULT '',
  `input_date` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `input_by` varchar(250) NOT NULL DEFAULT 'mysql',
  `update_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `update_by` varchar(250) NOT NULL DEFAULT 'mysql',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table transasia.account: ~2 rows (approximately)
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
INSERT INTO `account` (`id`, `superadmin`, `name`, `email`, `password`, `last_login`, `status`, `presence`, `ilock`, `token`, `input_date`, `input_by`, `update_date`, `update_by`) VALUES
	(1, 0, 'Admin', 'admin@admin.com', '25f9e794323b453885f5181f1b624d0b', '2017-01-01 10:00:00', 1, 1, 1, '45933c0655bb93e70fe7280734509dbc', '2017-01-01 00:00:00', 'mysql', '2017-01-01 00:00:00', 'mysql'),
	(2, 0, 'Admin', 'admin@mirrel.com', '25f9e794323b453885f5181f1b624d0b', '2017-01-01 10:00:00', 1, 1, 1, '0f78d16fb5cd8a722b0a3a2a5f37ef76', '2017-01-01 00:00:00', 'mysql', '2017-01-01 00:00:00', 'mysql');
/*!40000 ALTER TABLE `account` ENABLE KEYS */;

-- Dumping structure for table transasia.cms_content
CREATE TABLE IF NOT EXISTS `cms_content` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `id_pages` int(6) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `h1` text NOT NULL,
  `h2` text NOT NULL,
  `h3` text NOT NULL,
  `content` text NOT NULL,
  `embed` text NOT NULL,
  `publish_date` datetime NOT NULL DEFAULT '2019-01-01 00:00:00',
  `img` varchar(250) NOT NULL DEFAULT '',
  `metadata_description` varchar(250) NOT NULL DEFAULT '',
  `metadata_keywords` varchar(250) NOT NULL DEFAULT '',
  `status` int(2) NOT NULL DEFAULT '1',
  `sorting` int(3) NOT NULL DEFAULT '999',
  `presence` int(1) NOT NULL DEFAULT '1',
  `created_by` varchar(250) NOT NULL DEFAULT 'admin',
  `input_date` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `update_date` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3030 DEFAULT CHARSET=latin1;

-- Dumping data for table transasia.cms_content: ~78 rows (approximately)
/*!40000 ALTER TABLE `cms_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_content` ENABLE KEYS */;

-- Dumping structure for table transasia.cms_label
CREATE TABLE IF NOT EXISTS `cms_label` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `href` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=latin1;

-- Dumping data for table transasia.cms_label: ~41 rows (approximately)
/*!40000 ALTER TABLE `cms_label` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_label` ENABLE KEYS */;

-- Dumping structure for table transasia.cms_pages
CREATE TABLE IF NOT EXISTS `cms_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pages` int(6) NOT NULL DEFAULT '0',
  `ilock` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '1',
  `post` int(6) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `themes` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `metadata_description` varchar(250) NOT NULL DEFAULT '',
  `metadata_keywords` varchar(250) NOT NULL DEFAULT '',
  `pages_note1` text NOT NULL,
  `pages_note2` text NOT NULL,
  `pages_note3` text NOT NULL,
  `sorting` int(3) NOT NULL DEFAULT '999',
  `idefault` int(1) NOT NULL DEFAULT '0',
  `href` varchar(250) NOT NULL DEFAULT '',
  `href_target_blank` int(1) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  `img` varchar(250) NOT NULL DEFAULT '',
  `background` varchar(250) NOT NULL DEFAULT '',
  `data_hidden` text NOT NULL,
  `input_date` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `update_date` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=565 DEFAULT CHARSET=latin1;

-- Dumping data for table transasia.cms_pages: ~59 rows (approximately)
/*!40000 ALTER TABLE `cms_pages` DISABLE KEYS */;
INSERT INTO `cms_pages` (`id`, `id_pages`, `ilock`, `status`, `post`, `name`, `url`, `themes`, `title`, `metadata_description`, `metadata_keywords`, `pages_note1`, `pages_note2`, `pages_note3`, `sorting`, `idefault`, `href`, `href_target_blank`, `presence`, `img`, `background`, `data_hidden`, `input_date`, `update_date`) VALUES
	(1, 0, 1, 1, 0, 'Home', 'home', 'home', 'home', 'home', 'home', '', '', '', 0, 1, '', 0, 1, '', '', '', '2000-00-00 00:00:00', '2019-10-29 14:28:17');
/*!40000 ALTER TABLE `cms_pages` ENABLE KEYS */;

-- Dumping structure for table transasia.cms_widget
CREATE TABLE IF NOT EXISTS `cms_widget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(250) NOT NULL DEFAULT '',
  `href` text NOT NULL,
  `h1` text NOT NULL,
  `h2` text NOT NULL,
  `h3` text NOT NULL,
  `h4` text NOT NULL,
  `img` text NOT NULL,
  `content` text NOT NULL,
  `sorting` int(5) NOT NULL DEFAULT '999',
  `status` int(1) NOT NULL DEFAULT '1',
  `input_date` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  `update_date` datetime NOT NULL DEFAULT '2017-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=latin1;

-- Dumping data for table transasia.cms_widget: ~118 rows (approximately)
/*!40000 ALTER TABLE `cms_widget` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_widget` ENABLE KEYS */;

-- Dumping structure for table transasia.global_setting
CREATE TABLE IF NOT EXISTS `global_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=latin1;

-- Dumping data for table transasia.global_setting: ~18 rows (approximately)
/*!40000 ALTER TABLE `global_setting` DISABLE KEYS */;
INSERT INTO `global_setting` (`id`, `name`, `value`) VALUES
	(1, 'embed_code', ''),
	(2, 'header_code', ''),
	(10, 'footer', 'Mirrel.com'),
	(101, 'smtp_host', 'mail.mirrel.com'),
	(102, 'smtp_port', '25'),
	(103, 'smtp_user', 'admin@mirrel.com'),
	(104, 'smtp_pass', ''),
	(105, 'smtp_to', 'felix@cuvox.de'),
	(106, 'smtp_name', 'Sales Mirrel'),
	(110, 'subject', 'Email from contact Us CMS 5'),
	(112, 'smtp_timeout', '10'),
	(170, 'user', 'adminbiz'),
	(177, 'key', '12ul3Xz8WI-hHeh06cD1Z-Wo6TouEHuh-VIhcIbTEV4-LJkrGOm2Pq-0OUMS6bSDJ-dqv5GRR9Iy-44djYFZREp-Ys7egftRwL-YzfwnDlYqR'),
	(178, 'token', '(0cd!kd#*^lvM.-2=+'),
	(200, 'ecatalog', '0'),
	(201, 'catalogue', 'cat'),
	(202, 'product', 'product'),
	(203, 'cart', 'cart');
/*!40000 ALTER TABLE `global_setting` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
