-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.2.14-MariaDB-10.2.14+maria~artful-log - mariadb.org binary distribution
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.country
CREATE TABLE IF NOT EXISTS `country` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `enetID` int(10) unsigned NOT NULL DEFAULT 0,
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `ut` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `gen_ut_idx` (`ut`)
) ENGINE=InnoDB AUTO_INCREMENT=681 DEFAULT CHARSET=utf8;


-- Data exporting was unselected.
-- Dumping structure for table enetpulse.sport
CREATE TABLE IF NOT EXISTS `sport` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `enetSportCode` varchar(2) DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `ut` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `ut_idx` (`ut`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.tournament
CREATE TABLE IF NOT EXISTS `tournament` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `tournament_templateFK` int(10) unsigned NOT NULL DEFAULT 0,
  `enetSeasonID` int(10) unsigned NOT NULL DEFAULT 0,
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `locked` enum('yes','no','none') DEFAULT 'none',
  `ut` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `idx_template` (`tournament_templateFK`),
  KEY `gen_ut_idx` (`ut`)
) ENGINE=InnoDB AUTO_INCREMENT=12533 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.tournament_stage
CREATE TABLE IF NOT EXISTS `tournament_stage` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tournamentFK` int(10) unsigned NOT NULL DEFAULT 0,
  `gender` enum('undefined','male','female','mixed') NOT NULL DEFAULT 'undefined',
  `countryFK` int(10) NOT NULL DEFAULT 652 COMMENT 'Default must be undefined',
  `enetID` int(10) unsigned NOT NULL DEFAULT 0,
  `startdate` datetime DEFAULT '0000-00-00 00:00:00',
  `enddate` datetime DEFAULT '0000-00-00 00:00:00',
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `locked` enum('yes','no','none') DEFAULT 'none',
  `ut` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `std` (`del`,`tournamentFK`,`enetID`,`gender`),
  KEY `gen_ut_idx` (`ut`),
  KEY `idx_tournamentFK` (`tournamentFK`)
) ENGINE=InnoDB AUTO_INCREMENT=854365 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.tournament_template
CREATE TABLE IF NOT EXISTS `tournament_template` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `sportFK` int(10) unsigned NOT NULL DEFAULT 0,
  `gender` enum('undefined','male','female','mixed') NOT NULL DEFAULT 'undefined',
  `enetID` int(10) unsigned NOT NULL DEFAULT 0,
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `ut` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `fk_sport` (`sportFK`),
  KEY `ut_idx` (`ut`)
) ENGINE=InnoDB AUTO_INCREMENT=9741 DEFAULT CHARSET=utf8;

INSERT INTO `sport` (`id`, `name`, `enetSportCode`, `n`, `ut`, `del`) VALUES (1, 'Soccer', NULL, 1, '2017-01-20 07:36:27', 'no');
INSERT INTO `sport` (`id`, `name`, `enetSportCode`, `n`, `ut`, `del`) VALUES (23, 'Basketball', NULL, 0, '2017-01-20 07:36:24', 'no');

INSERT INTO `tournament_template` (`id`, `name`, `sportFK`, `gender`, `enetID`, `n`, `ut`, `del`) VALUES (42, 'Champions League', 1, 'male', 0, 0, '2017-06-21 00:31:41', 'no');
INSERT INTO `tournament_template` (`id`, `name`, `sportFK`, `gender`, `enetID`, `n`, `ut`, `del`) VALUES (46, 'Denmark 1', 1, 'male', 0, 0, '2017-08-21 14:27:08', 'no');

INSERT INTO `tournament` (`id`, `name`, `tournament_templateFK`, `enetSeasonID`, `n`, `locked`, `ut`, `del`) VALUES (10392, '2016/2017', 46, 0, 0, 'none', '2016-06-06 23:45:46', 'no');
INSERT INTO `tournament` (`id`, `name`, `tournament_templateFK`, `enetSeasonID`, `n`, `locked`, `ut`, `del`) VALUES (10436, '2016/2017', 42, 0, 0, 'none', '2016-06-18 18:28:28', 'no');
INSERT INTO `tournament` (`id`, `name`, `tournament_templateFK`, `enetSeasonID`, `n`, `locked`, `ut`, `del`) VALUES (11488, '2017/2018', 46, 0, 0, 'none', '2017-06-07 23:22:24', 'no');
INSERT INTO `tournament` (`id`, `name`, `tournament_templateFK`, `enetSeasonID`, `n`, `locked`, `ut`, `del`) VALUES (11542, '2017/2018', 42, 0, 0, 'none', '2017-06-17 18:29:27', 'no');

INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (844593, 'Superligaen', 10392, 'male', 1, 0, '2016-07-15 00:00:00', '2017-03-19 17:00:00', 2, 'none', '2017-06-20 23:59:30', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (848834, 'Superligaen Championship Playoff', 10392, 'male', 1, 0, '2017-03-31 20:15:00', '2017-05-28 20:00:00', 3, 'none', '2017-06-20 23:59:32', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (848835, 'Superligaen Relegation Grp. 1', 10392, 'male', 1, 0, '2017-03-31 18:00:00', '2017-05-08 21:00:00', 3, 'none', '2017-06-20 23:59:34', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (848836, 'Superligaen Relegation Grp. 2', 10392, 'male', 1, 0, '2017-04-01 16:00:00', '2017-05-07 20:00:00', 3, 'none', '2017-06-20 23:59:34', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (849608, 'Superligaen Europa League Playoff', 10392, 'male', 1, 0, '2017-05-12 00:00:00', '2017-06-01 22:00:00', 1, 'none', '2017-06-20 23:59:32', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (849609, 'Superligaen Relegation Playoff', 10392, 'male', 1, 0, '2017-05-12 00:00:00', '2017-05-28 21:00:00', 3, 'none', '2017-06-20 23:59:34', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (844755, 'Champions League Qualification', 10436, 'male', 11, 0, '2016-06-28 00:00:00', '2016-08-24 00:00:00', 0, 'none', '2017-08-17 18:41:39', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (846089, 'Champions League Grp. A', 10436, 'male', 11, 0, '2016-09-13 00:00:00', '2016-12-06 22:45:00', 4, 'none', '2017-08-22 14:09:31', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (846090, 'Champions League Grp. B', 10436, 'male', 11, 0, '2016-09-13 00:00:00', '2016-12-06 22:45:00', 4, 'none', '2017-09-19 21:38:13', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (846091, 'Champions League Grp. C', 10436, 'male', 11, 0, '2016-09-13 00:00:00', '2016-12-06 22:45:00', 4, 'none', '2017-08-22 14:09:36', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (846092, 'Champions League Grp. D', 10436, 'male', 11, 0, '2016-09-13 00:00:00', '2016-12-06 22:45:00', 4, 'none', '2017-08-22 14:09:39', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (846093, 'Champions League Grp. E', 10436, 'male', 11, 0, '2016-09-14 00:00:00', '2016-12-07 22:45:00', 4, 'none', '2017-08-22 14:09:41', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (846094, 'Champions League Grp. F', 10436, 'male', 11, 0, '2016-09-13 00:00:00', '2016-12-07 22:45:00', 4, 'none', '2017-08-22 14:09:43', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (846095, 'Champions League Grp. G', 10436, 'male', 11, 0, '2016-09-13 00:00:00', '2016-12-07 22:45:00', 4, 'none', '2017-08-22 14:09:46', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (846096, 'Champions League Grp. H', 10436, 'male', 11, 0, '2016-08-25 00:00:00', '2016-12-07 22:45:00', 8, 'none', '2017-08-22 14:09:49', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (847181, 'Champions League Final Stage', 10436, 'male', 11, 0, '2017-02-14 00:00:00', '2017-06-03 23:59:00', 2, 'none', '2017-08-17 18:47:18', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (849851, 'Superligaen', 11488, 'male', 1, 0, '2017-07-14 18:00:00', '2018-03-18 17:00:00', 2, 'none', '2017-07-14 12:46:49', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (850150, 'Champions League Qualification', 11542, 'male', 11, 0, '2017-06-27 00:00:00', '2017-08-23 23:59:00', 0, 'none', '2017-08-17 18:41:39', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (851343, 'Champions League Grp. A', 11542, 'male', 11, 0, '2017-09-12 00:00:00', '2017-12-05 23:59:59', 1, 'none', '2017-09-19 21:31:41', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (851344, 'Champions League Grp. B', 11542, 'male', 11, 0, '2017-09-12 00:00:00', '2017-12-05 23:59:59', 1, 'none', '2017-09-19 21:38:13', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (851345, 'Champions League Grp. D', 11542, 'male', 11, 0, '2017-09-12 00:00:00', '2017-12-05 23:59:59', 1, 'none', '2017-09-19 21:38:17', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (851346, 'Champions League Grp. C', 11542, 'male', 11, 0, '2017-09-12 00:00:00', '2017-12-05 23:59:59', 1, 'none', '2017-09-19 21:38:14', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (851347, 'Champions League Grp. G', 11542, 'male', 11, 0, '2017-09-13 00:00:00', '2017-12-06 23:59:59', 2, 'none', '2017-09-19 21:38:25', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (851348, 'Champions League Grp. F', 11542, 'male', 11, 0, '2017-09-13 00:00:00', '2017-12-06 23:59:59', 2, 'none', '2017-09-19 21:38:22', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (851349, 'Champions League Grp. H', 11542, 'male', 11, 0, '2017-09-13 00:00:00', '2017-12-06 23:59:59', 2, 'none', '2017-09-19 21:38:27', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (851350, 'Champions League Grp. E', 11542, 'male', 11, 0, '2017-09-13 00:00:00', '2017-12-06 23:59:59', 2, 'none', '2017-09-19 21:38:20', 'no');
INSERT INTO `tournament_stage` (`id`, `name`, `tournamentFK`, `gender`, `countryFK`, `enetID`, `startdate`, `enddate`, `n`, `locked`, `ut`, `del`) VALUES (852199, 'Champions League Final Stage', 11542, 'male', 11, 0, '2018-02-13 00:00:00', '2018-05-26 23:59:59', 0, 'none', '2017-11-05 18:50:55', 'no');

INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (1, 'Denmark', 0, 0, '2017-05-09 10:54:20', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (2, 'England', 0, 5, '2017-06-02 23:51:12', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (3, 'Germany', 0, 0, '2017-06-20 23:21:26', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (4, 'Italy', 0, 0, '2017-06-28 01:51:55', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (5, 'France', 0, 0, '2017-05-09 10:56:02', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (6, 'Sweden', 0, 0, '2017-05-09 11:26:52', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (7, 'Norway', 0, 0, '2017-05-09 11:05:43', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (8, 'Spain', 0, 0, '2017-05-09 11:17:37', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (9, 'Netherlands', 0, 0, '2017-07-01 02:57:00', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (11, 'International', 0, 0, '2017-05-31 20:29:03', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (12, 'Portugal', 0, 0, '2017-05-09 11:06:39', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (13, 'Turkey', 0, 0, '2017-06-20 23:23:45', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (14, 'Belgium', 0, 0, '2017-06-28 01:51:46', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (15, 'Scotland', 0, 0, '2017-06-20 22:55:06', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (16, 'USA', 0, 0, '2017-06-20 22:57:37', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (17, 'Slovenia', 0, 0, '2017-05-09 11:09:25', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (19, 'Czech Republic', 0, 0, '2017-06-28 01:52:33', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (20, 'Serbia', 0, 0, '2017-06-28 01:53:39', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (21, 'Romania', 0, 0, '2017-06-28 01:53:32', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (22, 'Russia', 0, 0, '2017-05-09 11:07:39', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (23, 'Canada', 0, 0, '2017-06-20 23:20:12', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (24, 'Finland', 0, 0, '2017-05-09 10:55:54', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (25, 'Japan', 0, 0, '2017-05-09 10:59:51', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (26, 'Hungary', 0, 0, '2017-05-09 11:19:06', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (28, 'South Africa', 0, 0, '2017-06-20 22:58:37', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (29, 'Tunisia', 0, 0, '2017-06-28 01:53:49', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (30, 'Nigeria', 0, 0, '2017-05-09 11:05:24', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (31, 'Egypt', 0, 0, '2017-05-09 10:55:08', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (32, 'Cameroon', 0, 0, '2017-06-20 23:19:30', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (33, 'Greece', 0, 0, '2017-05-09 10:57:49', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (34, 'Austria', 0, 0, '2017-06-20 23:24:17', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (35, 'U.A.E.', 0, 0, '2017-08-21 16:47:41', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (37, 'Switzerland', 0, 0, '2017-06-20 23:23:31', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (38, 'Israel', 0, 0, '2017-06-20 22:55:39', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (39, 'Australia', 0, 0, '2017-06-28 01:52:13', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (41, 'Luxembourg', 0, 0, '2017-05-09 11:01:58', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (42, 'N. Ireland', 0, 0, '2016-12-23 02:34:24', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (44, 'Croatia', 0, 0, '2017-06-28 01:52:30', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (45, 'Ireland', 0, 0, '2017-05-09 10:59:01', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (47, 'Poland', 0, 0, '2017-05-09 11:06:36', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (48, 'Argentina', 0, 0, '2017-06-28 01:52:03', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (50, 'Thailand', 0, 0, '2017-05-09 11:10:32', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (51, 'Brazil', 0, 0, '2017-06-28 01:52:19', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (52, 'Morocco', 0, 0, '2017-05-09 11:04:27', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (53, 'Ukraine', 0, 0, '2017-06-28 01:53:57', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (54, 'Malta', 0, 0, '2017-05-09 11:03:13', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (55, 'Georgia', 0, 0, '2017-06-28 01:52:48', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (56, 'Bulgaria', 0, 0, '2017-05-03 19:00:44', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (57, 'Belarus', 0, 0, '2017-05-03 18:59:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (58, 'Wales', 0, 0, '2016-05-27 18:08:06', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (59, 'Cyprus', 0, 0, '2017-06-20 22:57:13', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (60, 'Estonia', 0, 0, '2017-05-09 10:55:31', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (61, 'Latvia', 0, 0, '2017-06-20 23:22:25', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (62, 'Slovakia', 0, 0, '2017-05-09 11:08:45', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (63, 'Saudi Arabia', 0, 0, '2017-06-28 01:53:36', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (64, 'Azerbaijan', 0, 0, '2017-05-03 18:57:27', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (65, 'Moldova', 0, 0, '2017-05-09 11:03:50', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (66, 'Lithuania', 0, 0, '2017-05-09 11:01:53', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (67, 'Faroe Islands', 0, 0, '2016-12-23 01:31:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (68, 'Macedonia', 0, 1, '2017-06-28 01:53:09', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (69, 'Iceland', 0, 0, '2017-05-09 10:58:45', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (70, 'Bosnia and Herzegovina', 0, 1, '2017-11-21 19:17:05', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (71, 'Albania', 0, 0, '2017-06-28 01:51:37', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (72, 'Armenia', 0, 0, '2017-05-03 18:56:52', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (73, 'San Marino', 0, 0, '2017-05-09 11:08:02', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (74, 'Liechtenstein', 0, 0, '2017-05-09 11:01:50', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (76, 'Andorra', 0, 0, '2017-05-03 18:55:12', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (77, 'Chile', 0, 0, '2017-05-03 19:03:47', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (78, 'Colombia', 0, 0, '2017-06-20 23:21:03', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (79, 'Uruguay', 0, 0, '2017-05-09 11:12:18', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (80, 'Ecuador', 0, 0, '2017-05-09 10:55:04', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (81, 'Bolivia', 0, 0, '2017-05-03 18:59:17', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (82, 'Paraguay', 0, 0, '2017-05-09 11:06:21', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (83, 'Peru', 0, 0, '2017-05-09 11:06:24', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (84, 'Venezuela', 0, 0, '2017-05-09 11:12:31', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (85, 'Algeria', 0, 0, '2017-05-03 18:54:58', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (86, 'Greenland', 0, 0, '2016-12-23 02:31:47', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (87, 'Kuwait', 0, 0, '2017-05-09 11:01:05', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (88, 'South Korea', 0, 0, '2017-06-20 22:58:45', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (89, 'Malaysia', 0, 0, '2017-06-28 01:53:15', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (90, 'Singapore', 0, 0, '2017-05-09 11:08:38', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (91, 'Qatar', 0, 0, '2017-05-09 11:06:44', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (92, 'Zimbabwe', 0, 0, '2017-05-09 11:13:03', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (93, 'New Zealand', 0, 0, '2017-05-09 11:05:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (94, 'Fiji', 0, 0, '2017-05-09 10:55:52', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (95, 'Taiwan', 0, 0, '2017-08-16 21:36:45', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (96, 'Myanmar', 0, 1, '2017-05-09 11:04:40', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (98, 'Libya', 0, 0, '2017-05-09 11:01:48', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (99, 'Togo', 0, 0, '2017-05-09 11:10:35', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (100, 'Zambia', 0, 0, '2017-05-09 11:13:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (101, 'Sudan', 0, 0, '2017-05-09 11:09:53', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (102, 'Ghana', 0, 0, '2017-05-09 10:57:36', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (103, 'Liberia', 0, 0, '2017-05-09 11:01:36', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (104, 'Sierra Leone', 0, 0, '2017-05-09 11:08:34', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (105, 'Namibia', 0, 0, '2017-05-09 11:04:44', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (106, 'Senegal', 0, 0, '2017-05-09 11:08:19', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (107, 'Congo', 0, 0, '2017-05-03 19:01:51', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (108, 'DR Congo', 0, 0, '2017-05-09 10:54:52', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (109, 'Malawi', 0, 0, '2017-05-09 11:02:29', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (110, 'Burkina Faso', 0, 0, '2017-05-03 19:00:51', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (111, 'Ivory Coast', 0, 0, '2017-05-09 10:59:41', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (112, 'Jamaica', 0, 0, '2017-05-09 10:59:46', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (113, 'Trinidad and Tobago', 0, 0, '2017-05-09 11:10:48', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (114, 'Mexico', 0, 0, '2017-06-20 23:22:41', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (115, 'Nicaragua', 0, 0, '2017-05-09 11:05:13', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (116, 'El Salvador', 0, 0, '2017-05-09 10:55:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (117, 'Costa Rica', 0, 0, '2017-05-03 19:04:18', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (118, 'Honduras', 0, 0, '2017-05-09 10:58:26', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (119, 'Angola', 0, 0, '2017-05-03 18:56:20', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (120, 'Madagascar', 0, 0, '2017-05-09 11:02:19', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (121, 'North Korea', 0, 0, '2017-05-09 11:07:17', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (122, 'Iran', 0, 0, '2016-02-12 00:39:21', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (123, 'Iraq', 0, 0, '2017-05-31 20:26:35', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (125, 'China', 0, 0, '2017-06-20 22:56:46', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (126, 'Mali', 0, 0, '2017-05-09 11:03:09', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (127, 'Jordan', 0, 0, '2017-05-09 10:59:57', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (128, 'Hong Kong', 0, 0, '2017-08-16 21:32:34', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (129, 'Kazakhstan', 0, 0, '2017-05-09 10:59:59', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (130, 'Uzbekistan', 0, 0, '2017-05-09 11:12:54', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (131, 'Benin', 0, 0, '2017-05-03 18:58:46', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (133, 'India', 0, 0, '2017-05-31 20:26:55', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (134, 'Oman', 0, 0, '2017-05-09 11:05:46', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (135, 'Laos', 0, 0, '2017-05-09 11:00:52', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (136, 'Guatemala', 0, 0, '2017-05-09 10:58:07', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (137, 'Cuba', 0, 0, '2017-05-09 02:39:46', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (138, 'Martinique', 0, 0, '2016-02-12 00:44:50', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (139, 'Haiti', 0, 0, '2017-05-09 10:58:22', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (140, 'Bahamas', 0, 0, '2017-05-03 18:57:56', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (141, 'Indonesia', 0, 0, '2017-06-28 01:52:55', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (142, 'Puerto Rico', 0, 0, '2017-05-09 11:06:42', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (143, 'Panama', 0, 0, '2017-05-09 11:06:00', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (144, 'Bahrain', 0, 0, '2017-05-03 18:59:34', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (145, 'Rwanda', 0, 0, '2017-05-09 11:07:44', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (146, 'Guinea', 0, 0, '2017-05-09 10:58:13', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (147, 'Kenya', 0, 0, '2017-08-16 21:33:13', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (148, 'Yemen', 0, 0, '2017-05-09 11:13:21', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (149, 'Barbados', 0, 0, '2017-05-03 18:58:14', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (150, 'Kyrgyzstan', 0, 0, '2017-06-28 01:53:04', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (151, 'Botswana', 0, 0, '2017-05-03 18:59:22', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (152, 'Gabon', 0, 0, '2017-05-09 10:56:39', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (153, 'Cape Verde', 0, 0, '2017-05-03 19:03:31', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (154, 'Uganda', 0, 0, '2017-05-09 11:12:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (155, 'Vietnam', 0, 0, '2017-05-09 11:12:47', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (156, 'Sri Lanka', 0, 0, '2017-05-09 11:09:45', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (157, 'Palestine', 0, 0, '2017-05-09 11:06:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (158, 'Syria', 0, 0, '2017-05-09 11:10:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (159, 'Tajikistan', 0, 0, '2017-05-09 11:10:27', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (160, 'Lebanon', 0, 0, '2017-05-09 11:01:28', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (161, 'Maldives', 0, 0, '2017-05-09 11:03:05', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (162, 'Turkmenistan', 0, 0, '2017-05-09 11:11:00', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (163, 'Pakistan', 0, 0, '2017-05-09 11:05:49', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (164, 'Dominican Rep.', 0, 0, '2017-05-09 10:54:38', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (165, 'St. Kitts and Nevis', 0, 0, '2017-05-09 11:09:50', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (166, 'Saint Vincent and The Grenadines', 0, 0, '2017-05-09 11:29:40', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (167, 'Vanuatu', 0, 0, '2017-05-09 11:12:28', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (168, 'French Polynesia', 0, 1, '2017-02-18 00:05:37', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (169, 'Solomon Islands', 0, 0, '2017-05-09 11:09:15', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (170, 'Great Britain', 0, 0, '2017-06-28 01:51:54', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (171, 'Tonga', 0, 0, '2017-05-09 11:10:39', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (172, 'Philippines', 0, 0, '2017-05-09 11:06:34', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (173, 'Mongolia', 0, 0, '2017-06-28 01:53:18', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (174, 'Afghanistan', 0, 0, '2017-05-03 18:54:25', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (175, 'Lesotho', 0, 0, '2017-05-09 11:01:30', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (176, 'Nepal', 0, 0, '2017-05-09 11:04:51', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (177, 'Ethiopia', 0, 0, '2017-06-28 01:52:41', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (178, 'Mauritius', 0, 0, '2017-05-09 11:03:37', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (179, 'Seychelles', 0, 0, '2017-05-09 11:08:27', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (180, 'Brunei', 0, 0, '2017-05-03 18:59:44', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (181, 'U.S. Virgin Islands', 0, 1, '2017-05-09 11:31:20', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (182, 'Micronesia', 0, 0, '2017-05-09 10:56:24', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (183, 'Bermuda', 0, 0, '2017-05-03 18:58:56', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (184, 'Nauru', 0, 0, '2017-05-09 11:04:47', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (185, 'Central African Rep.', 0, 0, '2017-05-03 19:03:35', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (186, 'Guam', 0, 0, '2017-05-09 11:18:47', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (187, 'Netherlands Antilles', 0, 0, '2016-02-12 00:47:09', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (188, 'Bangladesh', 0, 0, '2017-05-03 18:58:10', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (189, 'Bhutan', 0, 0, '2017-05-03 18:59:00', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (190, 'Monaco', 0, 0, '2017-05-09 11:03:55', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (192, 'Antigua and Barbuda', 0, 0, '2017-05-03 18:56:45', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (193, 'American Samoa', 0, 0, '2017-05-03 18:56:00', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (194, 'Belize', 0, 0, '2017-05-03 18:59:07', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (195, 'Aruba', 0, 0, '2017-05-03 18:57:02', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (196, 'Burundi', 0, 0, '2017-05-03 18:58:25', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (197, 'Cayman Islands', 0, 0, '2017-05-03 19:01:38', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (198, 'Djibouti', 0, 0, '2017-05-09 10:54:29', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (199, 'Dominica', 0, 0, '2017-05-09 10:54:35', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (200, 'Equatorial Guinea', 0, 1, '2017-05-09 10:57:15', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (201, 'Eritrea', 0, 0, '2017-05-09 10:55:27', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (202, 'Cook Islands', 0, 0, '2017-05-03 19:04:13', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (203, 'Cambodia', 0, 0, '2017-05-03 19:01:27', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (204, 'French Guiana', 0, 1, '2017-03-27 22:55:12', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (205, 'Gambia', 0, 0, '2017-05-09 10:56:42', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (206, 'Saint Lucia', 0, 0, '2017-05-09 11:21:28', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (207, 'Niger', 0, 0, '2017-05-09 11:05:22', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (208, 'Papua New Guinea', 0, 0, '2017-05-09 11:06:04', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (209, 'Chad', 0, 0, '2017-05-03 19:03:42', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (210, 'Swaziland', 0, 0, '2017-05-09 11:10:06', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (211, 'Comoros', 0, 0, '2017-05-03 19:04:07', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (212, 'Kiribati', 0, 0, '2017-05-09 11:00:22', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (213, 'Grenada', 0, 1, '2017-05-09 10:58:01', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (214, 'British Virgin Islands', 0, 0, '2017-06-20 22:56:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (215, 'Mozambique', 0, 0, '2017-05-09 11:04:31', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (216, 'Guyana', 0, 0, '2017-05-09 10:58:18', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (217, 'Mauritania', 0, 1, '2017-06-28 01:51:58', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (218, 'Tanzania', 0, 0, '2017-05-09 11:10:30', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (219, 'Somalia', 0, 0, '2017-05-09 11:09:32', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (220, 'Suriname', 0, 1, '2017-05-09 11:09:54', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (221, 'Sao Tome and Principe', 0, 0, '2017-05-09 11:08:05', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (222, 'Former East Timor', 0, 0, '2016-12-22 23:08:23', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (223, 'Palau', 0, 0, '2017-05-09 11:06:13', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (224, 'Samoa', 0, 0, '2017-05-09 11:24:59', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (225, 'Europe', 0, 0, '2014-04-30 19:28:15', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (226, 'GB/Ireland', 0, 0, '2016-02-12 00:35:39', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (227, 'Asia', 0, 0, '2015-10-20 23:08:01', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (228, 'Macao', 0, 0, '2017-08-16 21:33:29', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (229, 'Guadeloupe', 0, 0, '2016-12-22 23:22:28', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (230, 'Montenegro', 0, 0, '2017-05-09 11:04:11', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (465, 'Unknown', 0, 0, '2014-04-30 19:30:02', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (652, 'Undefined', 0, 0, '2017-01-19 23:03:47', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (653, 'East Timor', 0, 1, '2017-05-09 11:27:23', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (654, 'New Caledonia', 0, 0, '2016-12-23 00:51:48', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (655, 'Gibraltar', 0, 0, '2016-12-22 23:22:18', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (656, 'Guinea-Bissau', 0, 0, '2017-05-09 11:18:12', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (657, 'Marshall Islands', 0, 0, '2017-05-09 11:03:19', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (658, 'Tuvalu', 0, 0, '2017-05-09 11:11:08', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (659, 'Jersey', 0, 0, '2016-12-23 01:40:33', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (660, 'Christmas Island', 0, 0, '2016-12-23 01:26:22', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (661, 'Anguilla', 0, 0, '2016-02-12 00:04:50', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (662, 'Montserrat', 0, 0, '2016-02-12 00:45:58', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (663, 'Turks and Caicos Islands', 0, 0, '2016-12-22 23:38:50', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (664, 'Curacao', 0, 0, '2016-12-23 01:25:52', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (665, 'Zanzibar', 0, 0, '2016-03-30 05:22:12', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (666, 'South Sudan', 0, 0, '2017-05-09 11:09:39', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (667, 'Northern Mariana Islands', 0, 0, '2016-12-23 00:47:58', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (668, 'Kosovo', 0, 0, '2017-05-09 11:00:25', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (669, 'Yugoslavia', 0, 1, '2017-06-28 01:54:09', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (670, 'West Germany', 0, 0, '2017-06-06 22:33:18', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (671, 'East Germany', 0, 0, '2017-06-06 22:33:08', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (672, 'Soviet Union', 0, 0, '2017-06-06 22:33:57', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (673, 'Czechoslovakia', 0, 0, '2017-06-06 22:32:30', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (674, 'Dutch East Indies', 0, 0, '2016-02-12 01:23:54', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (675, 'Zaire', 0, 0, '2017-06-28 01:54:05', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (676, 'Crimea', 0, 0, '2017-08-16 21:32:00', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (677, 'Niue', 0, 0, '2017-08-16 21:35:53', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (678, 'Sint Maarten', 0, 0, '2017-08-16 21:36:30', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (679, 'Isle of Man', 0, 0, '2017-08-16 21:32:57', 'no');
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (680, 'Guernsey', 0, 0, '2017-11-14 18:01:34', 'no');
