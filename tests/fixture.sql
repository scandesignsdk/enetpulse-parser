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

-- Dumping structure for table enetpulse.bettingoffer
CREATE TABLE IF NOT EXISTS `bettingoffer` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `outcomeFK` bigint(20) unsigned NOT NULL,
  `odds_providerFK` int(10) unsigned NOT NULL DEFAULT 0,
  `odds` double NOT NULL,
  `odds_old` double NOT NULL,
  `active` enum('no','yes') CHARACTER SET latin1 NOT NULL,
  `is_back` enum('no','yes') NOT NULL DEFAULT 'no',
  `is_single` enum('no','yes') NOT NULL DEFAULT 'yes',
  `is_live` enum('yes','no') NOT NULL DEFAULT 'no',
  `volume` int(11) NOT NULL,
  `currency` enum('DKK','USD','EUR','AUD','GBP','none') NOT NULL,
  `couponKey` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `del` enum('no','yes') CHARACTER SET latin1 NOT NULL DEFAULT 'no',
  `n` int(10) unsigned NOT NULL,
  `ut` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `outcome_provider` (`outcomeFK`,`odds_providerFK`) USING BTREE,
  KEY `ut_idx` (`ut`),
  KEY `del_idx` (`del`)
) ENGINE=InnoDB AUTO_INCREMENT=3221624514 DEFAULT CHARSET=utf8;

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
-- Dumping structure for table enetpulse.event
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID of event',
  `name` varchar(150) NOT NULL COMMENT 'Name of the event',
  `tournament_stageFK` int(10) unsigned NOT NULL DEFAULT 0,
  `startdate` datetime DEFAULT NULL,
  `eventstatusFK` int(10) unsigned NOT NULL DEFAULT 0,
  `status_type` enum('notstarted','inprogress','finished','cancelled','interrupted','unknown','deleted') NOT NULL DEFAULT 'unknown',
  `status_descFK` int(10) unsigned NOT NULL DEFAULT 0,
  `enetID` int(10) NOT NULL DEFAULT 0,
  `enetSportID` varchar(2) DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Number of changes',
  `ut` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  `locked` enum('yes','no','none') NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`),
  KEY `idx_tournament_stageFK` (`tournament_stageFK`),
  KEY `idx_start_date` (`startdate`),
  KEY `idx_enet_select` (`enetID`,`enetSportID`),
  KEY `gen_ut_idx` (`ut`),
  KEY `idx_status_descFK` (`status_descFK`),
  KEY `del` (`del`)
) ENGINE=InnoDB AUTO_INCREMENT=2726123 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.event_participants
CREATE TABLE IF NOT EXISTS `event_participants` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `number` int(10) unsigned NOT NULL DEFAULT 0,
  `participantFK` int(10) unsigned NOT NULL DEFAULT 0,
  `eventFK` int(10) unsigned NOT NULL DEFAULT 0,
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `ut` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `idx_eventFK` (`eventFK`),
  KEY `idx_participant_exists_in_event` (`number`,`participantFK`,`eventFK`),
  KEY `idx_event_number_unique` (`number`,`eventFK`),
  KEY `gen_ut_idx` (`ut`),
  KEY `idx_participant_event` (`participantFK`,`eventFK`)
) ENGINE=InnoDB AUTO_INCREMENT=9298944 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.odds_provider
CREATE TABLE IF NOT EXISTS `odds_provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `url` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `countryFK` int(10) unsigned NOT NULL,
  `bookmaker` enum('no','yes') CHARACTER SET latin1 NOT NULL,
  `preferred` enum('no','yes') NOT NULL DEFAULT 'no',
  `betex` enum('no','yes') CHARACTER SET latin1 NOT NULL,
  `active` enum('no','yes') CHARACTER SET latin1 NOT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  `ut` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ut` (`ut`)
) ENGINE=InnoDB AUTO_INCREMENT=484 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.outcome
CREATE TABLE IF NOT EXISTS `outcome` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object` enum('event','tournament_stage','tournament','tournament_template','standing','sport') CHARACTER SET latin1 NOT NULL,
  `objectFK` int(10) unsigned NOT NULL,
  `type` enum('1x2','12','ou','ah','oe','cs','win','1x2_hc','dc','gs','ht_ft','ng','smg','nc','tng','dnb','bts','wtn','ew','c_ah','g_ah','s_ah','p_ou') NOT NULL DEFAULT '1x2',
  `event_participant_number` int(11) NOT NULL COMMENT 'To be deprecated',
  `scope` enum('ord','ot','fe','1h','2h','1p','2p','3p','1q','2q','3q','4q','1s','2s','3s','4s','5s','1r','2r','3r','4r','5r','6r','1i','2i','3i','4i','5i','6i','7i','8i','9i','f5i','1m','2m','3m','4m','5m','6m','7m') NOT NULL,
  `subtype` enum('win','draw','over','under','score','odd','even','win_draw','next','anytime','last','from','from_to','to','yes','no','none','place','other') NOT NULL,
  `iparam` int(11) NOT NULL,
  `iparam2` int(11) NOT NULL,
  `dparam` double NOT NULL DEFAULT 0,
  `dparam2` double NOT NULL DEFAULT 0,
  `sparam` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  `n` int(10) unsigned NOT NULL,
  `ut` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `obj_fk_type` (`object`,`objectFK`,`type`),
  KEY `ut` (`ut`),
  KEY `type_idx` (`type`),
  KEY `scope_idx` (`scope`)
) ENGINE=InnoDB AUTO_INCREMENT=420031427 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.participant
CREATE TABLE IF NOT EXISTS `participant` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `gender` enum('undefined','male','female','mixed') NOT NULL DEFAULT 'undefined',
  `type` enum('team','official','undefined','coach','athlete','organization') NOT NULL DEFAULT 'undefined' COMMENT 'Type of participant',
  `countryFK` int(10) unsigned NOT NULL DEFAULT 652 COMMENT 'Default must be undefined',
  `enetID` int(10) unsigned NOT NULL DEFAULT 0,
  `enetSportID` varchar(2) DEFAULT NULL,
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `ut` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `idx_sel_participant_by_enet` (`enetID`,`type`,`enetSportID`),
  KEY `gen_ut_idx` (`ut`)
) ENGINE=InnoDB AUTO_INCREMENT=924447 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.result
CREATE TABLE IF NOT EXISTS `result` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `event_participantsFK` int(10) unsigned NOT NULL DEFAULT 0,
  `result_typeFK` int(10) unsigned NOT NULL DEFAULT 0,
  `result_code` enum('undefined','ordinarytime','extratime','finalresult','period1','period2','period3','penaltyshootout','rank','duration','points','comment','set1','set2','set3','set4','set5','setswon','quarter1','quarter2','quarter3','quarter4','halftime','strokes_r1','strokes_r2','strokes_r3','strokes_r4','strokes_r5','par','position','made_cut','laps','laps_behind','distance','inning1','inning2','inning3','inning4','inning5','inning6','inning7','inning8','inning9','extra_inning','hits','pitstops','errors','horseracingodds','runningscore','mpscore','misses','tiebreak1','tiebreak2','tiebreak3','tiebreak4','tiebreak5','medal','set6','set7','weight','tries','gamescore','startnumber','4s','6s','overs','extra','wickets','missed_shots','additional_shots','secondpoints','secondovers','secondextra','secondwickets') NOT NULL DEFAULT 'undefined',
  `value` varchar(255) NOT NULL DEFAULT '',
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `ut` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `idx_event_participantsFK` (`event_participantsFK`),
  KEY `idx_ep_type` (`event_participantsFK`,`result_typeFK`,`del`),
  KEY `gen_ut_idx` (`ut`),
  KEY `idx_ep_ut` (`event_participantsFK`,`ut`),
  KEY `del` (`del`)
) ENGINE=InnoDB AUTO_INCREMENT=30906245 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table enetpulse.result_type
CREATE TABLE IF NOT EXISTS `result_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `n` int(10) unsigned NOT NULL DEFAULT 0,
  `ut` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `del` enum('no','yes') NOT NULL DEFAULT 'no',
  `code` varchar(20) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`),
  KEY `ut_idx` (`ut`)
) ENGINE=InnoDB AUTO_INCREMENT=514 DEFAULT CHARSET=utf8;

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

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

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
INSERT INTO `country` (`id`, `name`, `enetID`, `n`, `ut`, `del`) VALUES (11, 'International', 0, 0, '2017-05-31 20:29:03', 'no');
