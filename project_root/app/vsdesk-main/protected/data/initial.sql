-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------
-- -------------------------------------------
-- TABLE `CUsers`
-- -------------------------------------------
DROP TABLE IF EXISTS `CUsers`;
CREATE TABLE IF NOT EXISTS `CUsers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Phone` varchar(50) NOT NULL,
  `push_id` varchar(50) NOT NULL,
  `intphone` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `company` varchar(50) DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `umanager` varchar(100) DEFAULT NULL,
  `birth` varchar(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `sendmail` int(1) DEFAULT '1',
  `sendsms` int(1) DEFAULT '0',
  `lang` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `YiiLog`
-- -------------------------------------------
DROP TABLE IF EXISTS `YiiLog`;
CREATE TABLE IF NOT EXISTS `YiiLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(500) DEFAULT NULL,
  `category` varchar(128) DEFAULT NULL,
  `logtime` varchar(128) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `YiiSession`
-- -------------------------------------------
DROP TABLE IF EXISTS `YiiSession`;
CREATE TABLE IF NOT EXISTS `YiiSession` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `ahistory`
-- -------------------------------------------
DROP TABLE IF EXISTS `ahistory`;
CREATE TABLE IF NOT EXISTS `ahistory` (
  `id` int(10) NOT NULL,
  `aid` int(10) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `action` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `alerts`
-- -------------------------------------------
DROP TABLE IF EXISTS `alerts`;
CREATE TABLE IF NOT EXISTS `alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(70) DEFAULT NULL,
  `name` varchar(70) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL,
  `shown` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `areport`
-- -------------------------------------------
DROP TABLE IF EXISTS `areport`;
CREATE TABLE IF NOT EXISTS `areport` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(50) DEFAULT NULL,
  `assetname` varchar(50) DEFAULT NULL,
  `assettype` varchar(50) DEFAULT NULL,
  `status` varchar(70) DEFAULT NULL,
  `slabel` varchar(70) DEFAULT NULL,
  `stnew` int(10) DEFAULT NULL,
  `stopen` int(10) DEFAULT NULL,
  `stclosed` int(10) DEFAULT NULL,
  `reactissue` int(10) DEFAULT NULL,
  `solveissue` int(10) DEFAULT NULL,
  `canceled` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- -------------------------------------------
-- TABLE `asset`
-- -------------------------------------------
DROP TABLE IF EXISTS `asset`;
CREATE TABLE IF NOT EXISTS `asset` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `inventory` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `slabel` varchar(400) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  `asset_attrib_id` int(10) DEFAULT NULL,
  `asset_attrib_name` varchar(50) DEFAULT NULL,
  `cusers_id` int(10) DEFAULT NULL,
  `cusers_name` varchar(50) DEFAULT NULL,
  `cusers_fullname` varchar(50) DEFAULT NULL,
  `cusers_dept` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_asset_asset_attrib` (`asset_attrib_id`),
  KEY `cusers_id` (`cusers_id`),
  CONSTRAINT `FK_asset_asset_attrib` FOREIGN KEY (`asset_attrib_id`) REFERENCES `asset_attrib` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_asset_CUsers` FOREIGN KEY (`cusers_id`) REFERENCES `CUsers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `asset_attrib`
-- -------------------------------------------
DROP TABLE IF EXISTS `asset_attrib`;
CREATE TABLE IF NOT EXISTS `asset_attrib` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `asset_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `asset_attrib_value`
-- -------------------------------------------
DROP TABLE IF EXISTS `asset_attrib_value`;
CREATE TABLE IF NOT EXISTS `asset_attrib_value` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) DEFAULT NULL,
  `asset_attrib_id` int(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `asset_attrib_id` (`asset_attrib_id`),
  CONSTRAINT `FK_asset_attrib_value_asset_attrib` FOREIGN KEY (`asset_attrib_id`) REFERENCES `asset_attrib` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `asset_values`
-- -------------------------------------------
DROP TABLE IF EXISTS `asset_values`;
CREATE TABLE IF NOT EXISTS `asset_values` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) DEFAULT '0',
  `asset_attrib_id` int(10) DEFAULT '0',
  `asset_attrib_name` varchar(50) DEFAULT '0',
  `value` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_asset_values_asset` (`asset_id`),
  CONSTRAINT `FK_asset_values_asset` FOREIGN KEY (`asset_id`) REFERENCES `asset` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `astatus`
-- -------------------------------------------
DROP TABLE IF EXISTS `astatus`;
CREATE TABLE IF NOT EXISTS `astatus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `label` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- -------------------------------------------
-- TABLE `bcats`
-- -------------------------------------------
DROP TABLE IF EXISTS `bcats`;
CREATE TABLE IF NOT EXISTS `bcats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `access` varchar(700) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `brecords`
-- -------------------------------------------
DROP TABLE IF EXISTS `brecords`;
CREATE TABLE IF NOT EXISTS `brecords` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT '0',
  `bcat_name` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `content` text,
  `author` varchar(50) DEFAULT NULL,
  `created` varchar(50) DEFAULT NULL,
  `image` varchar(500) DEFAULT NULL,
  `access` varchar(700) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `comments`
-- -------------------------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `show` int(1) unsigned NOT NULL DEFAULT '0',
  `files` text NOT NULL,
  `recipients` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`),
  CONSTRAINT `FK_comments_request` FOREIGN KEY (`rid`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `companies`
-- -------------------------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `director` varchar(50) DEFAULT NULL,
  `uraddress` varchar(100) DEFAULT NULL,
  `faddress` varchar(100) DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `add1` varchar(200) DEFAULT NULL,
  `add2` varchar(200) DEFAULT NULL,
  `manager` varchar(50) DEFAULT NULL,
  `inn` varchar(20) DEFAULT NULL,
  `kpp` varchar(20) DEFAULT NULL,
  `ogrn` varchar(20) DEFAULT NULL,
  `bik` varchar(20) DEFAULT NULL,
  `korschet` varchar(50) DEFAULT NULL,
  `schet` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `contractors`
-- -------------------------------------------
DROP TABLE IF EXISTS `contractors`;
CREATE TABLE IF NOT EXISTS `contractors` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `director` varchar(50) DEFAULT NULL,
  `uraddress` varchar(100) DEFAULT NULL,
  `faddress` varchar(100) DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `add1` varchar(200) DEFAULT NULL,
  `add2` varchar(200) DEFAULT NULL,
  `manager` varchar(50) DEFAULT NULL,
  `inn` varchar(20) DEFAULT NULL,
  `kpp` varchar(20) DEFAULT NULL,
  `ogrn` varchar(20) DEFAULT NULL,
  `bik` varchar(20) DEFAULT NULL,
  `korschet` varchar(50) DEFAULT NULL,
  `schet` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `cron`
-- -------------------------------------------
DROP TABLE IF EXISTS `cron`;
CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `job_id` varchar(50) DEFAULT NULL,
  `job` varchar(500) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


-- -------------------------------------------
-- TABLE `cron_req`
-- -------------------------------------------
CREATE TABLE `cron_req` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `CUsers_id` varchar(32) NOT NULL,
  `Status` varchar(32) NOT NULL,
  `ZayavCategory_id` varchar(32) NOT NULL,
  `Priority` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Content` text NOT NULL,
  `watchers` varchar(500) DEFAULT NULL,
  `cunits` varchar(500) DEFAULT NULL,
  `Date` datetime NOT NULL,
  `repeats` int(1) DEFAULT '0',
  `enabled` int(1) DEFAULT '0',
  `color` varchar(50) DEFAULT NULL,
  `fields` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -------------------------------------------
-- TABLE `cunit_types`
-- -------------------------------------------
DROP TABLE IF EXISTS `cunit_types`;
CREATE TABLE IF NOT EXISTS `cunit_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `cunits`
-- -------------------------------------------
DROP TABLE IF EXISTS `cunits`;
CREATE TABLE IF NOT EXISTS `cunits` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `slabel` varchar(400) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `fullname` varchar(70) DEFAULT NULL,
  `dept` varchar(100) DEFAULT NULL,
  `inventory` varchar(50) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `datein` varchar(50) DEFAULT NULL,
  `dateout` varchar(50) DEFAULT NULL,
  `company` varchar(70) DEFAULT NULL,
  `assets` varchar(2000) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `depart`
-- -------------------------------------------
DROP TABLE IF EXISTS `depart`;
CREATE TABLE IF NOT EXISTS `depart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT 'Название',
  `company` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `fieldsets`
-- -------------------------------------------
DROP TABLE IF EXISTS `fieldsets`;
CREATE TABLE IF NOT EXISTS `fieldsets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `fieldsets_fields`
-- -------------------------------------------
DROP TABLE IF EXISTS `fieldsets_fields`;
CREATE TABLE IF NOT EXISTS `fieldsets_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `req` tinyint(1) NOT NULL DEFAULT '0',
  `value` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`),
  CONSTRAINT `FK_fieldsets_fields_fieldsets` FOREIGN KEY (`fid`) REFERENCES `fieldsets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `groups`
-- -------------------------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `users` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `history`
-- -------------------------------------------
DROP TABLE IF EXISTS `history`;
CREATE TABLE IF NOT EXISTS `history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `zid` int(10) NOT NULL DEFAULT '0',
  `cusers_id` varchar(50) NOT NULL DEFAULT '0',
  `datetime` varchar(50) NOT NULL DEFAULT '0',
  `action` text,
  PRIMARY KEY (`id`),
  KEY `FK_history_Zayavki` (`zid`),
  CONSTRAINT `FK_history_request` FOREIGN KEY (`zid`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=485 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `influence`
-- -------------------------------------------
DROP TABLE IF EXISTS `influence`;
CREATE TABLE IF NOT EXISTS `influence` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `messages`
-- -------------------------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `news`
-- -------------------------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `author` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `content` text,
  `date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `phistory`
-- -------------------------------------------
DROP TABLE IF EXISTS `phistory`;
CREATE TABLE IF NOT EXISTS `phistory` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0',
  `date` varchar(50) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `action` text,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  CONSTRAINT `FK_phistory_problems` FOREIGN KEY (`pid`) REFERENCES `problems` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `problem_cats`
-- -------------------------------------------
DROP TABLE IF EXISTS `problem_cats`;
CREATE TABLE IF NOT EXISTS `problem_cats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `problems`
-- -------------------------------------------
DROP TABLE IF EXISTS `problems`;
CREATE TABLE IF NOT EXISTS `problems` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(50) DEFAULT NULL,
  `enddate` varchar(50) DEFAULT NULL,
  `manager` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `status` varchar(70) DEFAULT NULL,
  `slabel` varchar(500) DEFAULT NULL,
  `incidents` varchar(200) DEFAULT NULL,
  `workaround` text,
  `decision` text,
  `knowledge` int(10) DEFAULT NULL,
  `knowledge_trigger` int(1) DEFAULT '0',
  `description` text,
  `service` varchar(50) DEFAULT NULL,
  `priority` varchar(50) DEFAULT NULL,
  `downtime` varchar(50) DEFAULT '00:00',
  `influence` varchar(50) DEFAULT NULL,
  `assets` varchar(50) DEFAULT NULL,
  `assets_names` varchar(200) DEFAULT NULL,
  `users` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `psreport`
-- -------------------------------------------
DROP TABLE IF EXISTS `psreport`;
CREATE TABLE IF NOT EXISTS `psreport` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(50) DEFAULT NULL,
  `year` varchar(50) DEFAULT NULL,
  `servicename` varchar(50) DEFAULT NULL,
  `stnew` int(10) DEFAULT NULL,
  `stworkaround` int(10) DEFAULT NULL,
  `stsolved` int(10) DEFAULT NULL,
  `downtime` varchar(50) DEFAULT NULL,
  `availability` varchar(50) DEFAULT NULL,
  `pavailability` varchar(50) DEFAULT NULL,
  `sdate` varchar(50) DEFAULT NULL,
  `edate` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- -------------------------------------------
-- TABLE `pstatus`
-- -------------------------------------------
DROP TABLE IF EXISTS `pstatus`;
CREATE TABLE IF NOT EXISTS `pstatus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `label` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `pureport`
-- -------------------------------------------
DROP TABLE IF EXISTS `pureport`;
CREATE TABLE IF NOT EXISTS `pureport` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(50) DEFAULT NULL,
  `assetname` varchar(50) DEFAULT NULL,
  `assettype` varchar(50) DEFAULT NULL,
  `status` varchar(70) DEFAULT NULL,
  `slabel` varchar(70) DEFAULT NULL,
  `stnew` int(10) DEFAULT NULL,
  `stworkaround` int(10) DEFAULT NULL,
  `stsolved` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- -------------------------------------------
-- TABLE `reply_templates`
-- -------------------------------------------
DROP TABLE IF EXISTS `reply_templates`;
CREATE TABLE IF NOT EXISTS `reply_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `request`
-- -------------------------------------------
DROP TABLE IF EXISTS `request`;
CREATE TABLE IF NOT EXISTS `request` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `ZayavCategory_id` varchar(50) DEFAULT NULL,
  `Date` varchar(50) DEFAULT NULL,
  `StartTime` varchar(50) DEFAULT NULL,
  `fStartTime` varchar(50) DEFAULT NULL,
  `EndTime` varchar(50) DEFAULT NULL,
  `fEndTime` varchar(50) DEFAULT NULL,
  `Status` varchar(100) DEFAULT NULL,
  `slabel` varchar(400) DEFAULT NULL,
  `Priority` varchar(50) DEFAULT NULL,
  `Managers_id` varchar(50) DEFAULT NULL,
  `CUsers_id` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `Content` text,
  `Comment` text,
  `cunits` varchar(500) DEFAULT NULL,
  `closed` varchar(50) DEFAULT NULL,
  `service_id` int(10) DEFAULT NULL,
  `service_name` varchar(50) DEFAULT NULL,
  `image` varchar(250) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `timestampStart` datetime DEFAULT NULL,
  `timestampfStart` datetime DEFAULT NULL,
  `timestampEnd` datetime DEFAULT NULL,
  `timestampfEnd` datetime DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `mfullname` varchar(50) DEFAULT NULL,
  `gfullname` varchar(50) DEFAULT NULL,
  `depart` varchar(50) DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `watchers` varchar(500) DEFAULT NULL,
  `matching` varchar(50) DEFAULT NULL,
  `update_by` varchar(50) DEFAULT NULL,
  `correct_timestamp` varchar(50) DEFAULT NULL,
  `rating` int(1) DEFAULT NULL,
  `lead_time` time DEFAULT NULL,
  `leaving` int(1) unsigned DEFAULT '0',
  `contractors_id` int(11) DEFAULT NULL,
  `re_leaving` int(1) unsigned DEFAULT '0',
  `groups_id` int(10) unsigned DEFAULT NULL,
  `fields_history` varchar(1024) NOT NULL,
  `key` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `CUsers_id` (`CUsers_id`),
  KEY `FK_Zayavki_service` (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- -------------------------------------------
-- TABLE `request_fields`
-- -------------------------------------------
DROP TABLE IF EXISTS `request_fields`;
CREATE TABLE IF NOT EXISTS `request_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`),
  CONSTRAINT `FK_request_fields_request` FOREIGN KEY (`rid`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `roles`
-- -------------------------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `roles_rights`
-- -------------------------------------------
DROP TABLE IF EXISTS `roles_rights`;
CREATE TABLE IF NOT EXISTS `roles_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `rname` varchar(70) DEFAULT NULL,
  `name` varchar(70) DEFAULT NULL,
  `description` varchar(70) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `category` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`),
  CONSTRAINT `FK_roles_rights_roles` FOREIGN KEY (`rid`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=530 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `service`
-- -------------------------------------------
DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `sla` varchar(50) DEFAULT NULL,
  `priority` varchar(50) DEFAULT NULL,
  `manager` varchar(50) DEFAULT NULL,
  `manager_name` varchar(50) DEFAULT NULL,
  `availability` int(3) DEFAULT NULL,
  `group` varchar(50) DEFAULT NULL,
  `gtype` varchar(50) DEFAULT NULL,
  `fieldset` int(10) DEFAULT NULL,
  `company_id` int(10) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `watcher` varchar(50) DEFAULT NULL,
  `matching` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `sla`
-- -------------------------------------------
DROP TABLE IF EXISTS `sla`;
CREATE TABLE IF NOT EXISTS `sla` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `retimeh` varchar(3) DEFAULT NULL,
  `retimem` varchar(2) DEFAULT NULL,
  `sltimeh` varchar(3) DEFAULT NULL,
  `sltimem` varchar(2) DEFAULT NULL,
  `rhours` varchar(50) DEFAULT NULL,
  `shours` varchar(50) DEFAULT NULL,
  `taxes` varchar(500) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  `wstime` varchar(50) DEFAULT NULL,
  `wetime` varchar(50) DEFAULT NULL,
  `round_hours` int(1) NOT NULL DEFAULT '0',
  `round_days` int(1) NOT NULL DEFAULT '0',
  `ntretimeh` varchar(3) DEFAULT NULL,
  `ntretimem` varchar(2) DEFAULT NULL,
  `ntsltimeh` varchar(3) DEFAULT NULL,
  `ntsltimem` varchar(2) DEFAULT NULL,
  `nrhours` varchar(50) DEFAULT NULL,
  `nshours` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `sms`
-- -------------------------------------------
DROP TABLE IF EXISTS `sms`;
CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `content` varchar(140) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `sreport`
-- -------------------------------------------
DROP TABLE IF EXISTS `sreport`;
CREATE TABLE IF NOT EXISTS `sreport` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` varchar(50) DEFAULT NULL,
  `servicename` varchar(50) DEFAULT NULL,
  `stnew` int(10) DEFAULT NULL,
  `stopen` int(10) DEFAULT NULL,
  `stclosed` int(10) DEFAULT NULL,
  `reactissue` int(10) DEFAULT NULL,
  `solveissue` int(10) DEFAULT NULL,
  `canceled` int(10) DEFAULT NULL,
  `sdate` varchar(50) DEFAULT NULL,
  `edate` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- -------------------------------------------
-- TABLE `sureport`
-- -------------------------------------------
DROP TABLE IF EXISTS `sureport`;
CREATE TABLE IF NOT EXISTS `sureport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `count` int(10) NOT NULL,
  `summary` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `tbl_columns`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_columns`;
CREATE TABLE IF NOT EXISTS `tbl_columns` (
  `id` varchar(100) NOT NULL,
  `data` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `tbl_migration`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_migration`;
CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `uhistory`
-- -------------------------------------------
DROP TABLE IF EXISTS `uhistory`;
CREATE TABLE IF NOT EXISTS `uhistory` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `action` text,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  CONSTRAINT `FK_uhistory_cunits` FOREIGN KEY (`uid`) REFERENCES `cunits` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `unit_templates`
-- -------------------------------------------
DROP TABLE IF EXISTS `unit_templates`;
CREATE TABLE IF NOT EXISTS `unit_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `format` varchar(1) DEFAULT NULL,
  `type` int(1) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `ustatus`
-- -------------------------------------------
DROP TABLE IF EXISTS `ustatus`;
CREATE TABLE IF NOT EXISTS `ustatus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `label` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- -------------------------------------------
-- TABLE `zcategory`
-- -------------------------------------------
DROP TABLE IF EXISTS `zcategory`;
CREATE TABLE IF NOT EXISTS `zcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Название',
  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Активно',
  `incident` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Инцидент',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `zpriority`
-- -------------------------------------------
DROP TABLE IF EXISTS `zpriority`;
CREATE TABLE IF NOT EXISTS `zpriority` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  `rcost` varchar(50) DEFAULT NULL,
  `scost` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `zstatus`
-- -------------------------------------------
DROP TABLE IF EXISTS `zstatus`;
CREATE TABLE IF NOT EXISTS `zstatus` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `label` varchar(400) NOT NULL,
  `tag` varchar(100) NOT NULL,
  `close` tinyint(1) NOT NULL DEFAULT '1',
  `notify_user` tinyint(1) NOT NULL DEFAULT '1',
  `notify_user_sms` tinyint(1) NOT NULL DEFAULT '0',
  `notify_manager` tinyint(1) NOT NULL DEFAULT '1',
  `notify_manager_sms` tinyint(1) NOT NULL DEFAULT '0',
  `notify_group` tinyint(1) NOT NULL DEFAULT '0',
  `notify_matching` tinyint(1) NOT NULL DEFAULT '0',
  `notify_matching_sms` tinyint(1) NOT NULL DEFAULT '0',
  `sms` varchar(50) NOT NULL,
  `message` varchar(50) NOT NULL,
  `msms` varchar(50) NOT NULL,
  `mmessage` varchar(50) NOT NULL,
  `gmessage` varchar(50) NOT NULL,
  `matching_message` varchar(50) DEFAULT NULL,
  `matching_sms` varchar(50) DEFAULT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `freeze` tinyint(1) NOT NULL DEFAULT '0',
  `show` tinyint(1) NOT NULL DEFAULT '0',
  `mwsms` varchar(50) NOT NULL,
  `mwmessage` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE `zstatus_to_roles`
-- -------------------------------------------
DROP TABLE IF EXISTS `zstatus_to_roles`;
CREATE TABLE IF NOT EXISTS `zstatus_to_roles` (
  `zstatus_id` int(10) NOT NULL,
  `roles_id` int(10) NOT NULL,
  UNIQUE KEY `ztor` (`zstatus_id`,`roles_id`),
  KEY `zstatus_id` (`zstatus_id`),
  KEY `roles_id` (`roles_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------
-- TABLE DATA CUsers
-- -------------------------------------------
INSERT INTO `CUsers` (`id`,`Username`,`fullname`,`Password`,`Email`,`Phone`,`push_id`,`intphone`,`role`,`role_name`,`company`,`room`,`department`,`umanager`,`birth`,`position`,`sendmail`,`sendsms`,`lang`) VALUES
('1','admin','Администратор','b147fdcfe0a8120780ce85a8a6d27596','admin@email.com','+79003113133','','2222','univefadmin','Администратор','Users Company','','ИТ отдел','','','','1','0','ru');
INSERT INTO `CUsers` (`id`,`Username`,`fullname`,`Password`,`Email`,`Phone`,`push_id`,`intphone`,`role`,`role_name`,`company`,`room`,`department`,`umanager`,`birth`,`position`,`sendmail`,`sendsms`,`lang`) VALUES
('2','manager','Васин В.В.','9a88fba853317e9496ca33824bda8ba8','manager@email.com','+79005556667','','','univefmanager','Исполнитель','Managers company','','ИТ отдел','','','Инженер','1','0','ru');
INSERT INTO `CUsers` (`id`,`Username`,`fullname`,`Password`,`Email`,`Phone`,`push_id`,`intphone`,`role`,`role_name`,`company`,`room`,`department`,`umanager`,`birth`,`position`,`sendmail`,`sendsms`,`lang`) VALUES
('3','user','Кузнецов А.С.','4417e18f1155c1f595e1006aed7d2e27','user@email.com','+79000345567','','','univefuser','Пользователь','Users Company','205','Отдел продаж','','','Менеджер','1','0','ru');


-- -------------------------------------------
-- TABLE DATA YiiSession
-- -------------------------------------------
INSERT INTO `YiiSession` (`id`,`expire`,`data`) VALUES
('goe90f086qo6c4r4p43gkqp050','1473453516','77d576ad5d11d27f438011df0cc92271__id|s:1:\"1\";77d576ad5d11d27f438011df0cc92271__name|s:5:\"admin\";77d576ad5d11d27f438011df0cc92271__states|a:0:{}');



-- -------------------------------------------
-- TABLE DATA asset
-- -------------------------------------------
INSERT INTO `asset` (`id`,`uid`,`date`,`name`,`location`,`inventory`,`status`,`slabel`,`cost`,`asset_attrib_id`,`asset_attrib_name`,`cusers_id`,`cusers_name`,`cusers_fullname`,`cusers_dept`) VALUES
('1','1','27.12.2014 14:03','ПК Кузнецова','','PC-125987','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>','22500','6','Системный блок','0','user','Кузнецов А.С.','Отдел продаж');
INSERT INTO `asset` (`id`,`uid`,`date`,`name`,`location`,`inventory`,`status`,`slabel`,`cost`,`asset_attrib_id`,`asset_attrib_name`,`cusers_id`,`cusers_name`,`cusers_fullname`,`cusers_dept`) VALUES
('2','1','27.12.2014 14:04','Монитор Кузнецова','','MON-124598','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>','7500','17','Монитор','0','user','Кузнецов А.С.','Отдел продаж');
INSERT INTO `asset` (`id`,`uid`,`date`,`name`,`location`,`inventory`,`status`,`slabel`,`cost`,`asset_attrib_id`,`asset_attrib_name`,`cusers_id`,`cusers_name`,`cusers_fullname`,`cusers_dept`) VALUES
('3','1','27.12.2014 14:06','Logitech Black Keyboard','','KB-125798','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>','850','16','Клавиатура','0','user','Кузнецов А.С.','Отдел продаж');
INSERT INTO `asset` (`id`,`uid`,`date`,`name`,`location`,`inventory`,`status`,`slabel`,`cost`,`asset_attrib_id`,`asset_attrib_name`,`cusers_id`,`cusers_name`,`cusers_fullname`,`cusers_dept`) VALUES
('4','1','27.12.2014 14:07','Logitech Black Mouse','','MOU-156798','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>','450','19','Мышь','0','user','Кузнецов А.С.','Отдел продаж');
INSERT INTO `asset` (`id`,`uid`,`date`,`name`,`location`,`inventory`,`status`,`slabel`,`cost`,`asset_attrib_id`,`asset_attrib_name`,`cusers_id`,`cusers_name`,`cusers_fullname`,`cusers_dept`) VALUES
('5','1','27.12.2014 14:08','Windows 8.1 Pro','','','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>','8500','18','Операционная система','0','user','Кузнецов А.С.','Отдел продаж');



-- -------------------------------------------
-- TABLE DATA asset_attrib
-- -------------------------------------------
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('6','Системный блок','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('7','Принтер','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('9','Маршрутизатор','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('13','МФУ','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('14','Сервер','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('15','Картридж','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('16','Клавиатура','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('17','Монитор','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('18','Операционная система','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('19','Мышь','0');
INSERT INTO `asset_attrib` (`id`,`name`,`asset_id`) VALUES
('20','Программное обеспечение','0');



-- -------------------------------------------
-- TABLE DATA asset_attrib_value
-- -------------------------------------------
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('9','6','6','Производитель');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('11','7','7','Модель принтера');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('12','7','7','Ресурс принтера');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('13','7','7','Отпечатанных листов');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('14','7','7','Модель картриджа');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('17','9','9','Инвентарный номер');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('18','9','9','Местонахождение');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('21','13','13','Производитель');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('22','13','13','Куплено');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('24','13','13','Цена при покупке');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('25','14','14','Производитель');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('26','14','14','Бизнес задача');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('27','14','14','Инвентарный номер');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('28','15','15','Дата заправки');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('29','6','6','Материнская плата');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('30','6','6','Процессор');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('31','6','6','Оперативная память');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('32','6','6','Объем HDD');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('33','6','6','Видеокарта');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('34','6','6','Сетевая карта');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('39','17','17','Производитель');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('40','17','17','Модель');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('41','17','17','Диагональ (дюймы)');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('42','17','17','Максимальное разрешение');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('43','18','18','Производитель');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('44','18','18','Версия');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('45','18','18','Разрядность');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('46','18','18','Серийный номер');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('47','20','20','Производитель');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('48','20','20','Версия ПО');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('49','20','20','Тип лицензии');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('50','20','20','Серийный номер');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('51','6','6','Доменное имя');
INSERT INTO `asset_attrib_value` (`id`,`asset_id`,`asset_attrib_id`,`name`) VALUES
('52','6','6','IP адрес');



-- -------------------------------------------
-- TABLE DATA asset_values
-- -------------------------------------------
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('218','1','6','Производитель','ASUS');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('219','1','6','Материнская плата','ASUS P8iX-LE');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('220','1','6','Процессор','Intel Core i5 3,2GHz');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('221','1','6','Оперативная память','4GB');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('222','1','6','Объем HDD','500GB');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('223','1','6','Видеокарта','ASUS GeForce GTX620');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('224','1','6','Сетевая карта','Realtek 1000 LAN');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('225','1','6','Доменное имя','KUZNETSOV-PC');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('226','1','6','IP адрес','192.168.0.101');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('227','2','17','Производитель','SAMSUNG');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('228','2','17','Модель','P2770HD');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('229','2','17','Диагональ (дюймы)','27');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('230','2','17','Максимальное разрешение','1920x1080');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('231','5','18','Производитель','Microsoft');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('232','5','18','Версия','8.1 PRO');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('233','5','18','Разрядность','x64');
INSERT INTO `asset_values` (`id`,`asset_id`,`asset_attrib_id`,`asset_attrib_name`,`value`) VALUES
('234','5','18','Серийный номер','XKG2B-CGJ22-XKW9I-CDF5G-HGDKJ');



-- -------------------------------------------
-- TABLE DATA astatus
-- -------------------------------------------
INSERT INTO `astatus` (`id`,`name`,`label`) VALUES
('1','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>');
INSERT INTO `astatus` (`id`,`name`,`label`) VALUES
('2','Сломан','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #eb5f69\">Сломан</span>');
INSERT INTO `astatus` (`id`,`name`,`label`) VALUES
('3','В ремонте','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #fcb117\">В ремонте</span>');
INSERT INTO `astatus` (`id`,`name`,`label`) VALUES
('4','В резерве','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #a39a44\">В резерве</span>');
INSERT INTO `astatus` (`id`,`name`,`label`) VALUES
('5','На складе','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #58595b\">На складе</span>');



-- -------------------------------------------
-- TABLE DATA bcats
-- -------------------------------------------
INSERT INTO `bcats` (`id`,`name`,`access`) VALUES
('1','Проблемы','Пользователь,Исполнитель');
INSERT INTO `bcats` (`id`,`name`,`access`) VALUES
('2','Инциденты','Пользователь,Исполнитель');
INSERT INTO `bcats` (`id`,`name`,`access`) VALUES
('3','Регламенты','Исполнитель');



-- -------------------------------------------
-- TABLE DATA brecords
-- -------------------------------------------
INSERT INTO `brecords` (`id`,`parent_id`,`bcat_name`,`name`,`content`,`author`,`created`,`image`,`access`) VALUES
('1','1','Проблемы','312','<p>

	 1231231232

</p>','Администратор','26.04.2016 21:26','','Пользователь,Исполнитель');



-- -------------------------------------------
-- TABLE DATA companies
-- -------------------------------------------
INSERT INTO `companies` (`id`,`name`,`director`,`uraddress`,`faddress`,`contact_name`,`phone`,`email`,`add1`,`add2`,`manager`,`inn`,`kpp`,`ogrn`,`bik`,`korschet`,`schet`) VALUES
('1','Users Company','Пупкин И.С.','Москва, ул. Пупырина, д.6, кв.777','Москва, ул. Пупырина, д.6, кв.777','','','','','','manager','123456789','123456789','123456789','123456789','123456789','123456789');
INSERT INTO `companies` (`id`,`name`,`director`,`uraddress`,`faddress`,`contact_name`,`phone`,`email`,`add1`,`add2`,`manager`,`inn`,`kpp`,`ogrn`,`bik`,`korschet`,`schet`) VALUES
('2','Managers company','Сатья Наделла','Москва','Москва','','','','','','','','','','','','');



-- -------------------------------------------
-- TABLE DATA cron
-- -------------------------------------------
INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES
('1','Автоматическая обработка статусов заявок по расписанию','1','php /var/www/univefservicedesk.debian7.standard.local/protected/cron.php getstatus >/dev/null 2>&1','*/5 * * * *');
INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES
('2','Автоматическая проверка IMAP ящика для создания заявок','2','php /var/www/univefservicedesk.debian7.standard.local/protected/cron.php getmail >/dev/null 2>&1 ','*/5 * * * *');
INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES
('3','Автоматическое резервное копирование БД','3','php /var/www/univefservicedesk.debian7.standard.local/protected/cron.php backup >/dev/null 2>&1 ','30 22 * * 5');
INSERT INTO `cron` (`id`,`name`,`job_id`,`job`,`time`) VALUES
('5','Автоматический импорт пользователей из Active Directory','5','php /var/www/univefservicedesk.debian7.standard.local/protected/cron.php syncusers >/dev/null 2>&1 ','0 */1 * * *');



-- -------------------------------------------
-- TABLE DATA cunit_types
-- -------------------------------------------
INSERT INTO `cunit_types` (`id`,`name`) VALUES
('1','Рабочая станция');
INSERT INTO `cunit_types` (`id`,`name`) VALUES
('2','Мобильное рабочее место');
INSERT INTO `cunit_types` (`id`,`name`) VALUES
('3','Станция печати');



-- -------------------------------------------
-- TABLE DATA cunits
-- -------------------------------------------
INSERT INTO `cunits` (`id`,`name`,`type`,`status`,`slabel`,`cost`,`user`,`fullname`,`dept`,`inventory`,`date`,`datein`,`dateout`,`company`,`assets`,`location`) VALUES
('1','Рабочее место Кузнецова','Рабочая станция','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>','39800','user','Кузнецов А.С.','Отдел продаж','WS-156798','27.12.2014 14:05','27.12.2014','','Users Company','3,4,5,1,2','');



-- -------------------------------------------
-- TABLE DATA depart
-- -------------------------------------------
INSERT INTO `depart` (`id`,`name`,`company`) VALUES
('1','Отдел продаж','Users Company');
INSERT INTO `depart` (`id`,`name`,`company`) VALUES
('2','ИТ отдел','Users Company');
INSERT INTO `depart` (`id`,`name`,`company`) VALUES
('3','Руководство','Users Company');



-- -------------------------------------------
-- TABLE DATA fieldsets
-- -------------------------------------------
INSERT INTO `fieldsets` (`id`,`name`) VALUES
('1','Выездное обслуживание');
INSERT INTO `fieldsets` (`id`,`name`) VALUES
('2','Электронная почта');



-- -------------------------------------------
-- TABLE DATA fieldsets_fields
-- -------------------------------------------
INSERT INTO `fieldsets_fields` (`id`,`fid`,`name`,`type`,`req`,`value`) VALUES
('1','1','Требуется выезд','toggle','0','');
INSERT INTO `fieldsets_fields` (`id`,`fid`,`name`,`type`,`req`,`value`) VALUES
('2','1','Дата выезда','date','0','');
INSERT INTO `fieldsets_fields` (`id`,`fid`,`name`,`type`,`req`,`value`) VALUES
('4','2','Адрес электронной почты','textFieldRow','0','');



-- -------------------------------------------
-- TABLE DATA groups
-- -------------------------------------------
INSERT INTO `groups` (`id`,`name`,`users`) VALUES
('1','Первая линия поддержки','2');



-- -------------------------------------------
-- TABLE DATA history
-- -------------------------------------------
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('472','1','Кузнецов А.С.','07.05.2016 11:17','Заявка создана');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('473','1','Кузнецов А.С.','07.05.2016 11:17','Начало работ (план) установлено в: <b>09.05.2016 09:30</b>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('474','1','Кузнецов А.С.','07.05.2016 11:17','Окончание работ (план) установлено в: <b>09.05.2016 10:30</b>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('475','1','Кузнецов А.С.','07.05.2016 11:17','Изменен статус: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Открыта</span>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('476','1','system','09.09.2016 21:19','Изменен статус: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #756994\">Просрочено исполнение</span>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('477','1','Администратор','09.09.2016 21:32','Изменен статус: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Открыта</span>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('478','1','Администратор','09.09.2016 21:32','Изменен сервис: <b>Обслуживание внутренних клиентов</b>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('479','1','Администратор','09.09.2016 21:32','Изменен исполнитель: <b>Васин В.В.</b>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('480','1','Администратор','09.09.2016 21:32','Начало работ (план) установлено в: <b>09.05.2016 09:30</b>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('481','1','Администратор','09.09.2016 21:32','Окончание работ (план) установлено в: <b>09.05.2016 10:30</b>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('482','1','Администратор','09.09.2016 21:32','Изменено описание: <b>Прощу помочь решить задачку:


	 Сколько будет 2+2=?</b>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('483','1','Администратор','09.09.2016 21:32','Добавлен файл: <b>logo.jpg</b>');
INSERT INTO `history` (`id`,`zid`,`cusers_id`,`datetime`,`action`) VALUES
('484','1','system','09.09.2016 21:35','Изменен статус: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #756994\">Просрочено исполнение</span>');



-- -------------------------------------------
-- TABLE DATA influence
-- -------------------------------------------
INSERT INTO `influence` (`id`,`name`,`cost`) VALUES
('1','Незначительное влияние','');
INSERT INTO `influence` (`id`,`name`,`cost`) VALUES
('2','Частичная неработоспособность','');
INSERT INTO `influence` (`id`,`name`,`cost`) VALUES
('3','Полная неработоспособность','');



-- -------------------------------------------
-- TABLE DATA messages
-- -------------------------------------------
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('1','default','[Ticket #{id}] {name}','<h3>Заявка № {id} \"{name}\"</h3>
<table>
<tbody>
<tr>
 <th>
     Статус
 </th>
 <td>
     {status}
 </td>
</tr>
<tr>
  <th>
     Заказчик
 </th>
 <td>
     {fullname}
 </td>
</tr>
<tr>
  <th>
     Исполнитель
  </th>
 <td>
     {manager_name}
 </td>
</tr>
<tr>
  <th>
     Телефон исполнителя
  </th>
 <td>
     {manager_phone}
  </td>
</tr>
<tr>
  <th>
     Email исполнителя
  </th>
 <td>
     {manager_email}
  </td>
</tr>
<tr>
  <th>
     Название
 </th>
 <td>
     {name}
 </td>
</tr>
<tr>
  <th>
     Категория
  </th>
 <td>
     {category}
 </td>
</tr>
<tr>
  <th>
     Приоритет
  </th>
 <td>
     {priority}
 </td>
</tr>
<tr>
  <th>
     Создано
  </th>
 <td>
     {created}
  </td>
</tr>
<tr>
  <th>
     Начало работ (план)
  </th>
 <td>
     {StartTime}
  </td>
</tr>
<tr>
  <th>
     Начало работ (факт)
  </th>
 <td>
     {fStartTime}
 </td>
</tr>
<tr>
  <th>
     Окончание работ (план)
 </th>
 <td>
     {EndTime}
  </td>
</tr>
<tr>
  <th>
     Окончание работ (факт)
 </th>
 <td>
     {fEndTime}
 </td>
</tr>
<tr>
  <th>
     Сервис
 </th>
 <td>
     {service_name}
 </td>
</tr>
<tr>
  <th>
     Адрес
  </th>
 <td>
     {address}
  </td>
</tr>
<tr>
  <th>
     Компания
 </th>
 <td>
     {company}
  </td>
</tr>
<tr>
  <th>
     Актив
  </th>
 <td>
     {unit}
 </td>
</tr>
<tr>
  <th>
     Комментарий
  </th>
 <td>
     {comment}
  </td>
</tr>
<tr>
  <th>
     Содержание
 </th>
 <td>
     {content}
  </td>
</tr>
</tbody>
</table>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('3','Заявка в работе заказчик','[Ticket #{id}] {name}','<h3>Принята в работу заявка № {id} \"{name}\"</h3>

<p>

	    Заявка была принята в работу Исполнителем <strong>{manager_name}, </strong>вы можете связаться с Исполнителем по телефону <strong>{manager_phone}</strong>

</p>

<p>

	    Срок исполнения до: <strong>{EndTime}</strong>

</p>

<hr>

<p>

	 <strong>Содержание заявки:</strong>

</p>

<p>

	   {content}

</p>

<hr id=\"horizontalrule\">

<p>

	<strong>{comment_message}</strong>

</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('4','Заявка завершена','[Ticket #{id}] {name}','<h3>Заявка #{id} \"{name}\" была успешно завершена</h3>
<hr>
<p>
 Исполнитель <strong>{manager_name} </strong>исполнил заявку <strong>{fEndTime}</strong>, если Вы оказались недовольны качеством работ, можете обратиться к исполнителю по телефону <strong>{manager_phone} </strong>или по E-Mail <strong>{manager_email}</strong>
</p>
<h3> </h3>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('5','Заявка в работе исполнитель','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold;\">Вы приняли в работу заявка № {id} \"{name}\"</span></h3>
<p>
 Вам необходимо завершить заявку до: <strong>{EndTime}</strong>
</p>
<hr>
<p>
  <strong>Содержание заявки:</strong>
</p>
<p>
  {content}
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('6','Просрочена заявка исполнитель','[Ticket #{id}] {name}','<h3>Вы просрочили исполнение<span style=\"color: rgb(0, 0, 0); font-weight: bold;\"> заявки № {id} \"{name}\"</span></h3>
<p>
   Назначенная Вам заявка была просрочена  <strong>{EndTime}</strong>
</p>
<p>
   Срочно исполните заявку!
</p>
<hr>
<p>
   <strong>Содержание заявки:</strong>
</p>
<p>
  {content}
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('7','Просрочена реакция исполнитель','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold;\">Вы просрочили реакцию на заявку № {id} \"{name}\"</span></h3>
<p>
 У назначенной Вам заявки был просрочен срок реакции <strong></strong><strong>{StartTime}</strong>
</p>
<p>
  Срочно начните работу над заявкой!
</p>
<hr>
<p>
 <strong>Содержание заявки:</strong>
</p>
<p>
  {content}
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('8','Просрочена заявка заказчик','[Ticket #{id}] {name}','<h3>Исполнитель просрочил<span style=\"color: rgb(0, 0, 0); font-weight: bold;\"> исполнение заявки № {id} \"{name}\"</span></h3>
<p>
 Созданная Вами заявка была просрочена <strong>{EndTime}</strong>
</p>
<p>
 Свяжитесь с исполнителем.
</p>
<hr>
<p>
  <strong>Содержание заявки:</strong>
</p>
<p>
  {content}
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('9','Заявка отменена','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold;\">Исполнитель отменил исполнение заявки № {id} \"{name}\"</span></h3>
<p>
 Свяжитесь с исполнителем.
</p>
<hr>
<p>
  <strong>Причина отмены:</strong>
</p>
<p>
 {comment}
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('10','Новая заказчик','[Ticket #{id}] {name}','<h3>Вы успешно зарегистрировали заявку № {id} {created}</h3>
<hr>
<p>
 Название заявки: <strong>{name}</strong>
</p>
<p>
 Заявке назначен исполнитель: <strong>{manager_name}</strong>
</p>
<p>
 Телефон исполнителя: <strong>{manager_phone}</strong>
</p>
<p>
  E-mail исполнителя: <strong>{manager_email}</strong>
</p>
<p>
 Ваша заявка должна быть исполнена до <strong>{EndTime}</strong>
</p>
<hr>
<p>
  Содержание:
</p>
<p>
  {content}
</p>
<p>
  <strong><br>
  </strong>
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('11','Новая исполнитель','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold;\">Вам была назначена новая заявка № {id} {created}</span></h3>
<hr>
<p>
    Название заявки: <strong>{name}</strong>
</p>
<p>
   Заказчик: <strong>{fullname}</strong>
</p>
<p>
    Вы должны приступить к работе до <strong>{StartTime}</strong>
</p>
<p>
    Заявка должна быть исполнена до <strong>{EndTime}</strong>
</p>
<hr>
<p>
   Содержание:
</p>
<p>
    {content}
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('12','Уведомление наблюдателя','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold; background-color: initial;\">Вы назначены наблюдателем заявки № {id} \"{name}\"</span></h3><p>Произошли изменения в заявке, созданной <strong>{fullname}</strong></p><p>Имя исполнителя: <b style=\"background-color: initial;\">{manager_name}</b></p><p>Статус: <strong>{status}</strong></p><p><span style=\"background-color: initial;\">Начало работ (план): </span><strong style=\"background-color: initial;\">{StartTime}</strong></p><p>Срок исполнения до: <strong>{EndTime}</strong></p><p><span style=\"background-color: initial;\">Начало работ (факт): </span><strong style=\"background-color: initial;\">{fStartTime}</strong></p><p>Окончание работ (факт): <b style=\"background-color: initial;\">{fEndTime}</b></p><hr><p><strong>Содержание заявки:</strong></p><p>{content}</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('13','Скоро просрочена реакция','[Ticket #{id}] {name}','<h3><span style=\\\"background-color: initial;\\\">Истекает время</span><span style=\\\"color: rgb(0, 0, 0); font-weight: bold; background-color: initial;\\\"> реакции на заявку № {id} \\\"{name}\\\"</span></h3>
<p>
   У назначенной Вам заявки скоро истекает срок реакции <strong>{StartTime}</strong>
</p>
<p>
   Срочно начните работу над заявкой!
</p>
<hr>
<p>
   <strong>Содержание заявки:</strong>
</p>
<p>
   {content}
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('14','Скоро просрочено решение','[Ticket #{id}] {name}','<h3>Истекает время исполнения заявки № {id} \\\"{name}\\\"</h3>
<p>
   У назначенной Вам заявки скоро истекает срок исполнения <strong>{EndTime}</strong>
</p>
<p>
   Срочно начните работу над заявкой!
</p>
<hr>
<p>
   <strong>Содержание заявки:</strong>
</p>
<p>
   <span style=\\\"background-color: initial;\\\">{content}</span>
</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('15','Заявка требует согласования','[Ticket #{id}] {name} Требуется согласование','<h3><strong>Вам необходимо согласовать заявку</strong> № {id} \"{name}\"</h3>

<p>

	 Имя заказчика: <strong>{fullname}</strong>

</p>

<p>

	 Имя исполнителя: <strong>{manager_name}</strong>

</p>

<p>

	 Статус: <strong>{status}</strong>

</p>

<p>

	 Начало работ (план): <strong>{StartTime}</strong>

</p>

<p>

	 Срок исполнения до: <strong>{EndTime}</strong>

</p>

<p>

	 Начало работ (факт): <strong>{fStartTime}</strong>

</p>

<p>

	 Окончание работ (факт): <strong>{fEndTime}</strong>

</p>

<hr>

<p>

	 <strong>Содержание заявки:</strong>

</p>

<p>

	 {content}

</p>');
INSERT INTO `messages` (`id`,`name`,`subject`,`content`) VALUES
('16','Заявка согласована','[Ticket #{id}] {name} Заявка согласаована','<p>

	 <strong style=\"color: rgb(0, 0, 0); font-size: 24px;\">Ваша заявка согласована</strong><span style=\"color: rgb(0, 0, 0); font-size: 24px; font-weight: bold;\"> № {id} \"{name}\"</span>

</p>

<p>

	 Имя заказчика: <strong>{fullname}</strong>

</p>

<p>

	 Имя исполнителя: <strong>{manager_name}</strong>

</p>

<p>

	 Статус: <strong>{status}</strong>

</p>

<p>

	 Начало работ (план): <strong>{StartTime}</strong>

</p>

<p>

	 Срок исполнения до: <strong>{EndTime}</strong>

</p>

<p>

	 Начало работ (факт): <strong>{fStartTime}</strong>

</p>

<p>

	 Окончание работ (факт): <strong>{fEndTime}</strong>

</p>

<hr>

<p>

	 <strong>Содержание заявки:</strong>

</p>

<p>

	 {content}

</p>');



-- -------------------------------------------
-- TABLE DATA news
-- -------------------------------------------
INSERT INTO `news` (`id`,`author`,`name`,`content`,`date`) VALUES
('1','Администратор','Отключение электропитания c 15-00 до 18-00!','<p>
  <strong>Уважаемые пользователи,  обращаем ваше внимание на то, что 25 декабря будет отключено электропитание с 15-00 по 18-00, будет недоступны все сетевые сервисы!</strong>
</p>','07.12.2013 12:47');



-- -------------------------------------------
-- TABLE DATA phistory
-- -------------------------------------------
INSERT INTO `phistory` (`id`,`pid`,`date`,`user`,`action`) VALUES
('1','1','06.01.2015 13:59','Администратор','Проблема зарегистрирована!');
INSERT INTO `phistory` (`id`,`pid`,`date`,`user`,`action`) VALUES
('2','1','26.04.2016 21:11','Администратор','Добавлен файл: <b>Заявки по заявителям_01.04.2016-30.04.2016.xls</b>');
INSERT INTO `phistory` (`id`,`pid`,`date`,`user`,`action`) VALUES
('3','1','26.04.2016 21:12','Администратор','Удален файл: <b>Заявки по заявителям_01.04.2016-30.04.2016.xls</b>');
INSERT INTO `phistory` (`id`,`pid`,`date`,`user`,`action`) VALUES
('4','1','26.04.2016 21:14','Администратор','Добавлен файл: <b>Заявки по заявителям_01.04.2016-30.04.2016.xls</b>');



-- -------------------------------------------
-- TABLE DATA problem_cats
-- -------------------------------------------
INSERT INTO `problem_cats` (`id`,`name`) VALUES
('1','Известные проблемы');
INSERT INTO `problem_cats` (`id`,`name`) VALUES
('2','Новые проблемы');



-- -------------------------------------------
-- TABLE DATA problems
-- -------------------------------------------
INSERT INTO `problems` (`id`,`date`,`enddate`,`manager`,`category`,`status`,`slabel`,`incidents`,`workaround`,`decision`,`knowledge`,`knowledge_trigger`,`description`,`service`,`priority`,`downtime`,`influence`,`assets`,`assets_names`,`users`,`image`,`creator`,`timestamp`) VALUES
('1','06.01.2015 13:59','','Васин В.В.','Новые проблемы','Зарегистрирована','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Зарегистрирована</span>','','','','0','0','Проблема с почтовым сервером','Электронная почта','Низкий','00:10','Частичная неработоспособность','Рабочее место Кузнецова','Рабочее место Кузнецова','Кузнецов А.С.','Заявки по заявителям_01.04.2016-30.04.2016.xls','Администратор','2015-01-06 13:59:52');



-- -------------------------------------------
-- TABLE DATA psreport
-- -------------------------------------------
INSERT INTO `psreport` (`id`,`date`,`year`,`servicename`,`stnew`,`stworkaround`,`stsolved`,`downtime`,`availability`,`pavailability`,`sdate`,`edate`) VALUES
('50','05.05.2016 13:11','','Электронная почта','0','0','0','00:00','100','90','','');
INSERT INTO `psreport` (`id`,`date`,`year`,`servicename`,`stnew`,`stworkaround`,`stsolved`,`downtime`,`availability`,`pavailability`,`sdate`,`edate`) VALUES
('51','05.05.2016 13:11','','Обслуживание сторонних клиентов','0','0','0','00:00','100','50','','');
INSERT INTO `psreport` (`id`,`date`,`year`,`servicename`,`stnew`,`stworkaround`,`stsolved`,`downtime`,`availability`,`pavailability`,`sdate`,`edate`) VALUES
('52','05.05.2016 13:11','','Обслуживание внутренних клиентов','0','0','0','00:00','100','90','','');



-- -------------------------------------------
-- TABLE DATA pstatus
-- -------------------------------------------
INSERT INTO `pstatus` (`id`,`name`,`label`) VALUES
('1','Зарегистрирована','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Зарегистрирована</span>');
INSERT INTO `pstatus` (`id`,`name`,`label`) VALUES
('2','Обходное решение','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #5692bb\">Обходное решение</span>');
INSERT INTO `pstatus` (`id`,`name`,`label`) VALUES
('3','Решена','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #58595b\">Решена</span>');



-- -------------------------------------------
-- TABLE DATA pureport
-- -------------------------------------------
INSERT INTO `pureport` (`id`,`date`,`assetname`,`assettype`,`status`,`slabel`,`stnew`,`stworkaround`,`stsolved`) VALUES
('1','04.05.2016 11:29','Рабочее место Кузнецова','Рабочая станция','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.84','1','0','0');



-- -------------------------------------------
-- TABLE DATA reply_templates
-- -------------------------------------------
INSERT INTO `reply_templates` (`id`,`name`,`content`) VALUES
('1','Недостаточно информации для выполнения заявки','<p>

	 <strong>Уважаемый {fullname}, для выполнения вашей заявки №{id} недостаточно информации, уточните пожалуйста следующее:</strong>

</p>');



-- -------------------------------------------
-- TABLE DATA request
-- -------------------------------------------
INSERT INTO `request` (`id`,`pid`,`Name`,`Type`,`ZayavCategory_id`,`Date`,`StartTime`,`fStartTime`,`EndTime`,`fEndTime`,`Status`,`slabel`,`Priority`,`Managers_id`,`CUsers_id`,`phone`,`room`,`Address`,`company`,`Content`,`Comment`,`cunits`,`closed`,`service_id`,`service_name`,`image`,`timestamp`,`timestampStart`,`timestampfStart`,`timestampEnd`,`timestampfEnd`,`fullname`,`mfullname`,`gfullname`,`depart`,`creator`,`watchers`,`matching`,`update_by`,`correct_timestamp`,`rating`,`lead_time`,`leaving`,`contractors_id`,`re_leaving`,`groups_id`,`fields_history`,`key`) VALUES
('1','0','Тестовая заявка','','Заявка на обслуживание','07.05.2016 11:17','09.05.2016 09:30','','09.05.2016 10:30','','Просрочено исполнение','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #756994\">Просрочено исполнение</span>','Низкий','manager','user','+79267022200','205','Москва, ул. Пупырина, д.6, кв.777','Users Company','<p>
	 Прощу помочь решить задачку:
</p>
<p>
	 Сколько будет 2+2=?
</p>','','','9','3','Обслуживание внутренних клиентов','logo.jpg','2016-05-07 11:17:18','2016-05-09 09:30:00','0000-00-00 00:00:00','2016-05-09 10:30:00','0000-00-00 00:00:00','Кузнецов А.С.','Васин В.В.','','','Кузнецов А.С.','','','','1:20:43:0','0','15:10:02','0','0','0','0','','');



-- -------------------------------------------
-- TABLE DATA roles
-- -------------------------------------------
INSERT INTO `roles` (`id`,`name`,`value`) VALUES
('1','Администратор','univefadmin');
INSERT INTO `roles` (`id`,`name`,`value`) VALUES
('2','Пользователь','univefuser');
INSERT INTO `roles` (`id`,`name`,`value`) VALUES
('3','Исполнитель','univefmanager');



-- -------------------------------------------
-- TABLE DATA roles_rights
-- -------------------------------------------
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('1','1','Администратор','systemUser','Системная роль Пользователь','0','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('2','1','Администратор','systemManager','Системная роль Исполнитель','0','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('3','1','Администратор','systemAdmin','Системная роль Администратор','1','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('4','1','Администратор','createRequest','Cоздавать заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('5','1','Администратор','updateRequest','Редактировать заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('6','1','Администратор','viewRequest','Просмотр заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('7','1','Администратор','listRequest','Отображать список заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('8','1','Администратор','deleteRequest','Удаление заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('9','1','Администратор','batchUpdateRequest','Массовое закрытие заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('10','1','Администратор','batchDeleteRequest','Массовое удаление заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('11','1','Администратор','uploadFilesRequest','Пользователь может прикреплять файлы к заявке','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('12','1','Администратор','viewMyselfRequest','Пользователь видит только свои заявки','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('14','1','Администратор','updateDatesRequest','Пользователь может редактировать сроки дедлайнов заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('15','1','Администратор','canAssignRequest','Исполнитель может назначать заявку другому исполнителю','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('16','1','Администратор','viewHistoryRequest','Пользователь может видеть историю заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('17','1','Администратор','canSetUnitRequest','Пользователь может выбирать КЕ в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('18','1','Администратор','canSetObserversRequest','Пользователь может выбирать наблюдателей в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('19','1','Администратор','canSetFieldsRequest','Пользователь может заполнять наборы полей в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('20','1','Администратор','createProblem','Создавать проблемы','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('21','1','Администратор','viewProblem','Просмотр проблем','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('22','1','Администратор','listProblem','Отображать список проблем','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('23','1','Администратор','updateProblem','Редактировать проблемы','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('24','1','Администратор','deleteProblem','Удалять проблемы','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('25','1','Администратор','canAssignProblem','Исполнитель может назначать проблему другому исполнителю','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('26','1','Администратор','uploadFilesProblem','Пользователь может прикреплять файлы к проблеме','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('27','1','Администратор','batchUpdateProblem','Массовое закрытие проблем','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('28','1','Администратор','batchDeleteProblem','Массовое удаление проблем','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('29','1','Администратор','viewHistoryProblem','Пользователь может видеть историю проблемы','1','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('30','1','Администратор','createService','Создавать сервисы','1','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('31','1','Администратор','viewService','Просмотр сервисов','1','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('32','1','Администратор','listService','Отображать список сервисов','1','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('33','1','Администратор','updateService','Редактировать сервисы','1','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('34','1','Администратор','deleteService','Удалять сервисы','1','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('35','1','Администратор','createSla','Создавать уровни сервиса','1','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('36','1','Администратор','viewSla','Просмотр уровней сервиса','1','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('37','1','Администратор','listSla','Отображать список уровней сервисов','1','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('38','1','Администратор','updateSla','Редактировать уровни сервисов','1','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('39','1','Администратор','deleteSla','Удалять уровни сервиса','1','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('40','1','Администратор','createAsset','Создавать активы','1','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('41','1','Администратор','viewAsset','Просматривать активы','1','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('42','1','Администратор','listAsset','Отображать список активов','1','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('43','1','Администратор','updateAsset','Редактировать активы','1','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('44','1','Администратор','deleteAsset','Удалить активы','1','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('45','1','Администратор','exportAsset','Экспортировать список активов','1','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('46','1','Администратор','printAsset','Распечатывать карточку актива','1','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('47','1','Администратор','createAssetType','Создавать типы активов','1','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('48','1','Администратор','listAssetType','Отображать список типов актива','1','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('49','1','Администратор','updateAssetType','Редактировать типы актива','1','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('50','1','Администратор','deleteAssetType','Удалить типы актива','1','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('51','1','Администратор','createUnit','Создавать КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('52','1','Администратор','viewUnit','Просматривать КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('53','1','Администратор','listUnit','Отображать список КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('54','1','Администратор','updateUnit','Редактировать КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('55','1','Администратор','deleteUnit','Удалять КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('56','1','Администратор','exportUnit','Экспортировать список КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('57','1','Администратор','printUnit','Печать карточки КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('58','1','Администратор','viewMyselfUnit','Пользователь видит только свои КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('59','1','Администратор','createUnitType','Создавать типы КЕ','1','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('60','1','Администратор','listUnitType','Отображать список типов КЕ','1','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('61','1','Администратор','updateUnitType','Редактировать типы КЕ','1','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('62','1','Администратор','deleteUnitType','Удалять типы КЕ','1','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('63','1','Администратор','createKB','Создавать записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('64','1','Администратор','viewKB','Просматривать записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('65','1','Администратор','listKB','Отображать список Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('66','1','Администратор','updateKB','Редактировать записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('67','1','Администратор','deleteKB','Удалять записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('68','1','Администратор','uploadFilesKB','Пользователь может прикреплять файлы к записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('69','1','Администратор','createKBCat','Создавать категории Базы знаний','1','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('70','1','Администратор','listKBCat','Отображать список категорий Базы знаний','1','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('71','1','Администратор','updateKBCat','Редактировать категории Базы знаний','1','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('72','1','Администратор','deleteKBCat','Удалять категории Базы знаний','1','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('73','1','Администратор','createNews','Создать новость','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('74','1','Администратор','viewNews','Просматривать новости','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('75','1','Администратор','listNews','Отображать список новостей','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('76','1','Администратор','updateNews','Редактировать новости','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('77','1','Администратор','deleteNews','Удалять новости','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('78','1','Администратор','createUser','Создавать пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('79','1','Администратор','viewUser','Просматривать пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('80','1','Администратор','listUser','Отображать список пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('81','1','Администратор','updateUser','Редактировать пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('82','1','Администратор','deleteUser','Удалить пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('83','1','Администратор','exportUser','Экспортировать список пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('84','1','Администратор','createCompany','Создавать компании','1','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('85','1','Администратор','viewCompany','Просматривать компании','1','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('86','1','Администратор','listCompany','Отображать список компаний','1','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('87','1','Администратор','updateCompany','Редактировать компании','1','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('88','1','Администратор','deleteCompany','Удалять компании','1','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('89','1','Администратор','createDepart','Создавать подразделения','1','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('90','1','Администратор','listDepart','Отображать список подразделений','1','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('91','1','Администратор','updateDepart','Редактировать подразделения','1','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('92','1','Администратор','deleteDepart','Удалять подразделения','1','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('93','1','Администратор','createGroup','Создавать группы','1','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('94','1','Администратор','listGroup','Отображать список групп','1','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('95','1','Администратор','updateGroup','Редактировать группы','1','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('96','1','Администратор','deleteGroup','Удалять группы','1','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('97','1','Администратор','createPriority','Создавать приоритеты','1','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('98','1','Администратор','listPriority','Отображать список приоритетов','1','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('99','1','Администратор','updatePriority','Редактировать приоритеты','1','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('100','1','Администратор','deletePriority','Удалять приоритеты','1','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('101','1','Администратор','createStatus','Создавать статусы','1','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('102','1','Администратор','listStatus','Отображать список статусов','1','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('103','1','Администратор','updateStatus','Редактировать статусы','1','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('104','1','Администратор','deleteStatus','Удалять статусы','1','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('105','1','Администратор','createCategory','Создать категории заявок','1','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('106','1','Администратор','listCategory','Отображать список категорий заявок','1','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('107','1','Администратор','updateCategory','Редактировать категории заявок','1','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('108','1','Администратор','deleteCategory','Удаление категорий заявок','1','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('109','1','Администратор','createETemplate','Создать E-mail шаблон','1','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('110','1','Администратор','viewETemplate','Просматривать Email шаблоны','1','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('111','1','Администратор','listETemplate','Отображать список Email шаблонов','1','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('112','1','Администратор','updateETemplate','Редактировать Email шаблоны','1','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('113','1','Администратор','deleteETemplate','Удалять Email шаблоны','1','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('114','1','Администратор','createSTemplate','Создать SMS шаблон','1','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('115','1','Администратор','viewSTemplate','Просматривать SMS шаблоны','1','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('116','1','Администратор','listSTemplate','Отображать список SMS шаблонов','1','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('117','1','Администратор','updateSTemplate','Редактировать SMS шаблоны','1','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('118','1','Администратор','deleteSTemplate','Удалять SMS шаблоны','1','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('119','1','Администратор','createFieldsets','Создавать наборы полей','1','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('120','1','Администратор','listFieldsets','Отображать наборы полей','1','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('121','1','Администратор','updateFieldsets','Редактировать наборы полей','1','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('122','1','Администратор','deleteFieldsets','Удалять наборы полей','1','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('123','1','Администратор','usersReport','Доступ к отчету Заявки по заявителям','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('124','1','Администратор','companiesReport','Доступ к отчету Заявки по компаниям','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('125','1','Администратор','managersReport','Доступ к отчету Заявки по менеджерам','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('126','1','Администратор','serviceReport','Доступ к отчету Заявки по сервисам','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('127','1','Администратор','assetReport','Доступ к отчету Заявки по КЕ','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('128','1','Администратор','unitProblemReport','Доступ к отчету Проблемы по КЕ','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('129','1','Администратор','monthServiceProblemReport','Доступ к отчету Проблемы по сервисам за месяц','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('130','1','Администратор','serviceProblemReport','Доступ к отчету Проблемы по сервисам','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('131','1','Администратор','unitSProblemReport','Доступ к отчету Сводный отчет по КЕ','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('132','1','Администратор','rolesSettings','Доступ к управлению ролями','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('133','1','Администратор','mainSettings','Доступ к основным настройкам','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('134','1','Администратор','mailParserSettings','Доступ к настройкам парсера почты','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('135','1','Администратор','adSettings','Доступ к настройкам интеграции с AD','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('136','1','Администратор','smsSettings','Доступ к настройкам SMS шлюза','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('137','1','Администратор','ticketSettings','Доступ к настройкам заявки по-умолчанию','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('138','1','Администратор','attachSettings','Доступ к настройкам вложений','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('139','1','Администратор','appearSettings','Доступ к настройкам внешнего вида','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('140','1','Администратор','shedulerSettings','Доступ к настройкам планировщика задач','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('141','1','Администратор','logSettings','Доступ к анализатору лога','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('142','1','Администратор','backupSettings','Доступ к резервному копированию','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('143','1','Администратор','importSettings','Импорт из CSV','1','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('144','1','Администратор','showTicketGraph','Отображать график заявок на главной панели','1','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('145','1','Администратор','showProblemGraph','Отображать график проблем на главной панели','1','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('146','1','Администратор','showlastNews','Отображать список последних новостей на главной панели','1','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('147','1','Администратор','showlastKB','Отображать список последних записей Базы знаний на главной панели','1','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('148','1','Администратор','showSearchKB','Отображать строку поиска по Базе знаний','0','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('149','2','Пользователь','systemUser','Системная роль Пользователь','1','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('150','2','Пользователь','systemManager','Системная роль Исполнитель','0','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('151','2','Пользователь','systemAdmin','Системная роль Администратор','0','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('152','2','Пользователь','createRequest','Cоздавать заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('153','2','Пользователь','updateRequest','Редактировать заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('154','2','Пользователь','viewRequest','Просмотр заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('155','2','Пользователь','listRequest','Отображать список заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('156','2','Пользователь','deleteRequest','Удаление заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('157','2','Пользователь','batchUpdateRequest','Массовое закрытие заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('158','2','Пользователь','batchDeleteRequest','Массовое удаление заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('159','2','Пользователь','uploadFilesRequest','Пользователь может прикреплять файлы к заявке','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('160','2','Пользователь','viewMyselfRequest','Пользователь видит только свои заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('162','2','Пользователь','updateDatesRequest','Пользователь может редактировать сроки дедлайнов заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('163','2','Пользователь','canAssignRequest','Исполнитель может назначать заявку другому исполнителю','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('164','2','Пользователь','viewHistoryRequest','Пользователь может видеть историю заявки','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('165','2','Пользователь','canSetUnitRequest','Пользователь может выбирать КЕ в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('166','2','Пользователь','canSetObserversRequest','Пользователь может выбирать наблюдателей в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('167','2','Пользователь','canSetFieldsRequest','Пользователь может заполнять наборы полей в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('168','2','Пользователь','createProblem','Создавать проблемы','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('169','2','Пользователь','viewProblem','Просмотр проблем','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('170','2','Пользователь','listProblem','Отображать список проблем','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('171','2','Пользователь','updateProblem','Редактировать проблемы','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('172','2','Пользователь','deleteProblem','Удалять проблемы','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('173','2','Пользователь','canAssignProblem','Исполнитель может назначать проблему другому исполнителю','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('174','2','Пользователь','uploadFilesProblem','Пользователь может прикреплять файлы к проблеме','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('175','2','Пользователь','batchUpdateProblem','Массовое закрытие проблем','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('176','2','Пользователь','batchDeleteProblem','Массовое удаление проблем','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('177','2','Пользователь','viewHistoryProblem','Пользователь может видеть историю проблемы','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('178','2','Пользователь','createService','Создавать сервисы','0','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('179','2','Пользователь','viewService','Просмотр сервисов','0','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('180','2','Пользователь','listService','Отображать список сервисов','0','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('181','2','Пользователь','updateService','Редактировать сервисы','0','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('182','2','Пользователь','deleteService','Удалять сервисы','0','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('183','2','Пользователь','createSla','Создавать уровни сервиса','0','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('184','2','Пользователь','viewSla','Просмотр уровней сервиса','0','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('185','2','Пользователь','listSla','Отображать список уровней сервисов','0','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('186','2','Пользователь','updateSla','Редактировать уровни сервисов','0','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('187','2','Пользователь','deleteSla','Удалять уровни сервиса','0','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('188','2','Пользователь','createAsset','Создавать активы','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('189','2','Пользователь','viewAsset','Просматривать активы','1','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('190','2','Пользователь','listAsset','Отображать список активов','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('191','2','Пользователь','updateAsset','Редактировать активы','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('192','2','Пользователь','deleteAsset','Удалить активы','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('193','2','Пользователь','exportAsset','Экспортировать список активов','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('194','2','Пользователь','printAsset','Распечатывать карточку актива','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('195','2','Пользователь','createAssetType','Создавать типы активов','0','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('196','2','Пользователь','listAssetType','Отображать список типов актива','0','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('197','2','Пользователь','updateAssetType','Редактировать типы актива','0','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('198','2','Пользователь','deleteAssetType','Удалить типы актива','0','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('199','2','Пользователь','createUnit','Создавать КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('200','2','Пользователь','viewUnit','Просматривать КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('201','2','Пользователь','listUnit','Отображать список КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('202','2','Пользователь','updateUnit','Редактировать КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('203','2','Пользователь','deleteUnit','Удалять КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('204','2','Пользователь','exportUnit','Экспортировать список КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('205','2','Пользователь','printUnit','Печать карточки КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('206','2','Пользователь','viewMyselfUnit','Пользователь видит только свои КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('207','2','Пользователь','createUnitType','Создавать типы КЕ','0','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('208','2','Пользователь','listUnitType','Отображать список типов КЕ','0','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('209','2','Пользователь','updateUnitType','Редактировать типы КЕ','0','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('210','2','Пользователь','deleteUnitType','Удалять типы КЕ','0','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('211','2','Пользователь','createKB','Создавать записи Базы знаний','0','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('212','2','Пользователь','viewKB','Просматривать записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('213','2','Пользователь','listKB','Отображать список Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('214','2','Пользователь','updateKB','Редактировать записи Базы знаний','0','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('215','2','Пользователь','deleteKB','Удалять записи Базы знаний','0','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('216','2','Пользователь','uploadFilesKB','Пользователь может прикреплять файлы к записи Базы знаний','0','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('217','2','Пользователь','createKBCat','Создавать категории Базы знаний','0','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('218','2','Пользователь','listKBCat','Отображать список категорий Базы знаний','0','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('219','2','Пользователь','updateKBCat','Редактировать категории Базы знаний','0','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('220','2','Пользователь','deleteKBCat','Удалять категории Базы знаний','0','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('221','2','Пользователь','createNews','Создать новость','0','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('222','2','Пользователь','viewNews','Просматривать новости','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('223','2','Пользователь','listNews','Отображать список новостей','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('224','2','Пользователь','updateNews','Редактировать новости','0','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('225','2','Пользователь','deleteNews','Удалять новости','0','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('226','2','Пользователь','createUser','Создавать пользователей','0','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('227','2','Пользователь','viewUser','Просматривать пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('228','2','Пользователь','listUser','Отображать список пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('229','2','Пользователь','updateUser','Редактировать пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('230','2','Пользователь','deleteUser','Удалить пользователей','0','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('231','2','Пользователь','exportUser','Экспортировать список пользователей','0','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('232','2','Пользователь','createCompany','Создавать компании','0','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('233','2','Пользователь','viewCompany','Просматривать компании','0','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('234','2','Пользователь','listCompany','Отображать список компаний','0','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('235','2','Пользователь','updateCompany','Редактировать компании','0','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('236','2','Пользователь','deleteCompany','Удалять компании','0','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('237','2','Пользователь','createDepart','Создавать подразделения','0','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('238','2','Пользователь','listDepart','Отображать список подразделений','0','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('239','2','Пользователь','updateDepart','Редактировать подразделения','0','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('240','2','Пользователь','deleteDepart','Удалять подразделения','0','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('241','2','Пользователь','createGroup','Создавать группы','0','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('242','2','Пользователь','listGroup','Отображать список групп','0','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('243','2','Пользователь','updateGroup','Редактировать группы','0','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('244','2','Пользователь','deleteGroup','Удалять группы','0','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('245','2','Пользователь','createPriority','Создавать приоритеты','0','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('246','2','Пользователь','listPriority','Отображать список приоритетов','0','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('247','2','Пользователь','updatePriority','Редактировать приоритеты','0','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('248','2','Пользователь','deletePriority','Удалять приоритеты','0','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('249','2','Пользователь','createStatus','Создавать статусы','0','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('250','2','Пользователь','listStatus','Отображать список статусов','0','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('251','2','Пользователь','updateStatus','Редактировать статусы','0','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('252','2','Пользователь','deleteStatus','Удалять статусы','0','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('253','2','Пользователь','createCategory','Создать категории заявок','0','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('254','2','Пользователь','listCategory','Отображать список категорий заявок','0','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('255','2','Пользователь','updateCategory','Редактировать категории заявок','0','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('256','2','Пользователь','deleteCategory','Удаление категорий заявок','0','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('257','2','Пользователь','createETemplate','Создать E-mail шаблон','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('258','2','Пользователь','viewETemplate','Просматривать Email шаблоны','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('259','2','Пользователь','listETemplate','Отображать список Email шаблонов','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('260','2','Пользователь','updateETemplate','Редактировать Email шаблоны','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('261','2','Пользователь','deleteETemplate','Удалять Email шаблоны','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('262','2','Пользователь','createSTemplate','Создать SMS шаблон','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('263','2','Пользователь','viewSTemplate','Просматривать SMS шаблоны','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('264','2','Пользователь','listSTemplate','Отображать список SMS шаблонов','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('265','2','Пользователь','updateSTemplate','Редактировать SMS шаблоны','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('266','2','Пользователь','deleteSTemplate','Удалять SMS шаблоны','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('267','2','Пользователь','createFieldsets','Создавать наборы полей','0','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('268','2','Пользователь','listFieldsets','Отображать наборы полей','0','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('269','2','Пользователь','updateFieldsets','Редактировать наборы полей','0','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('270','2','Пользователь','deleteFieldsets','Удалять наборы полей','0','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('271','2','Пользователь','usersReport','Доступ к отчету Заявки по заявителям','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('272','2','Пользователь','companiesReport','Доступ к отчету Заявки по компаниям','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('273','2','Пользователь','managersReport','Доступ к отчету Заявки по менеджерам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('274','2','Пользователь','serviceReport','Доступ к отчету Заявки по сервисам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('275','2','Пользователь','assetReport','Доступ к отчету Заявки по КЕ','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('276','2','Пользователь','unitProblemReport','Доступ к отчету Проблемы по КЕ','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('277','2','Пользователь','monthServiceProblemReport','Доступ к отчету Проблемы по сервисам за месяц','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('278','2','Пользователь','serviceProblemReport','Доступ к отчету Проблемы по сервисам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('279','2','Пользователь','unitSProblemReport','Доступ к отчету Сводный отчет по КЕ','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('280','2','Пользователь','rolesSettings','Доступ к управлению ролями','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('281','2','Пользователь','mainSettings','Доступ к основным настройкам','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('282','2','Пользователь','mailParserSettings','Доступ к настройкам парсера почты','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('283','2','Пользователь','adSettings','Доступ к настройкам интеграции с AD','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('284','2','Пользователь','smsSettings','Доступ к настройкам SMS шлюза','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('285','2','Пользователь','ticketSettings','Доступ к настройкам заявки по-умолчанию','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('286','2','Пользователь','attachSettings','Доступ к настройкам вложений','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('287','2','Пользователь','appearSettings','Доступ к настройкам внешнего вида','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('288','2','Пользователь','shedulerSettings','Доступ к настройкам планировщика задач','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('289','2','Пользователь','logSettings','Доступ к анализатору лога','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('290','2','Пользователь','backupSettings','Доступ к резервному копированию','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('291','2','Пользователь','importSettings','Импорт из CSV','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('292','2','Пользователь','showTicketGraph','Отображать график заявок на главной панели','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('293','2','Пользователь','showProblemGraph','Отображать график проблем на главной панели','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('294','2','Пользователь','showlastNews','Отображать список последних новостей на главной панели','1','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('295','2','Пользователь','showlastKB','Отображать список последних записей Базы знаний на главной панели','1','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('296','2','Пользователь','showSearchKB','Отображать строку поиска по Базе знаний','1','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('297','3','Исполнитель','systemUser','Системная роль Пользователь','0','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('298','3','Исполнитель','systemManager','Системная роль Исполнитель','1','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('299','3','Исполнитель','systemAdmin','Системная роль Администратор','0','Системная роль');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('300','3','Исполнитель','createRequest','Cоздавать заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('301','3','Исполнитель','updateRequest','Редактировать заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('302','3','Исполнитель','viewRequest','Просмотр заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('303','3','Исполнитель','listRequest','Отображать список заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('304','3','Исполнитель','deleteRequest','Удаление заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('305','3','Исполнитель','batchUpdateRequest','Массовое закрытие заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('306','3','Исполнитель','batchDeleteRequest','Массовое удаление заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('307','3','Исполнитель','uploadFilesRequest','Пользователь может прикреплять файлы к заявке','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('308','3','Исполнитель','viewMyselfRequest','Пользователь видит только свои заявки','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('310','3','Исполнитель','updateDatesRequest','Пользователь может редактировать сроки дедлайнов заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('311','3','Исполнитель','canAssignRequest','Исполнитель может назначать заявку другому исполнителю','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('312','3','Исполнитель','viewHistoryRequest','Пользователь может видеть историю заявки','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('313','3','Исполнитель','canSetUnitRequest','Пользователь может выбирать КЕ в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('314','3','Исполнитель','canSetObserversRequest','Пользователь может выбирать наблюдателей в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('315','3','Исполнитель','canSetFieldsRequest','Пользователь может заполнять наборы полей в форме заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('316','3','Исполнитель','createProblem','Создавать проблемы','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('317','3','Исполнитель','viewProblem','Просмотр проблем','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('318','3','Исполнитель','listProblem','Отображать список проблем','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('319','3','Исполнитель','updateProblem','Редактировать проблемы','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('320','3','Исполнитель','deleteProblem','Удалять проблемы','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('321','3','Исполнитель','canAssignProblem','Исполнитель может назначать проблему другому исполнителю','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('322','3','Исполнитель','uploadFilesProblem','Пользователь может прикреплять файлы к проблеме','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('323','3','Исполнитель','batchUpdateProblem','Массовое закрытие проблем','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('324','3','Исполнитель','batchDeleteProblem','Массовое удаление проблем','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('325','3','Исполнитель','viewHistoryProblem','Пользователь может видеть историю проблемы','0','Проблема');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('326','3','Исполнитель','createService','Создавать сервисы','0','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('327','3','Исполнитель','viewService','Просмотр сервисов','1','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('328','3','Исполнитель','listService','Отображать список сервисов','1','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('329','3','Исполнитель','updateService','Редактировать сервисы','0','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('330','3','Исполнитель','deleteService','Удалять сервисы','0','Сервис');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('331','3','Исполнитель','createSla','Создавать уровни сервиса','0','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('332','3','Исполнитель','viewSla','Просмотр уровней сервиса','1','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('333','3','Исполнитель','listSla','Отображать список уровней сервисов','1','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('334','3','Исполнитель','updateSla','Редактировать уровни сервисов','0','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('335','3','Исполнитель','deleteSla','Удалять уровни сервиса','0','Sla');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('336','3','Исполнитель','createAsset','Создавать активы','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('337','3','Исполнитель','viewAsset','Просматривать активы','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('338','3','Исполнитель','listAsset','Отображать список активов','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('339','3','Исполнитель','updateAsset','Редактировать активы','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('340','3','Исполнитель','deleteAsset','Удалить активы','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('341','3','Исполнитель','exportAsset','Экспортировать список активов','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('342','3','Исполнитель','printAsset','Распечатывать карточку актива','0','Актив');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('343','3','Исполнитель','createAssetType','Создавать типы активов','0','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('344','3','Исполнитель','listAssetType','Отображать список типов актива','0','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('345','3','Исполнитель','updateAssetType','Редактировать типы актива','0','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('346','3','Исполнитель','deleteAssetType','Удалить типы актива','0','Тип актива');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('347','3','Исполнитель','createUnit','Создавать КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('348','3','Исполнитель','viewUnit','Просматривать КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('349','3','Исполнитель','listUnit','Отображать список КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('350','3','Исполнитель','updateUnit','Редактировать КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('351','3','Исполнитель','deleteUnit','Удалять КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('352','3','Исполнитель','exportUnit','Экспортировать список КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('353','3','Исполнитель','printUnit','Печать карточки КЕ','1','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('354','3','Исполнитель','viewMyselfUnit','Пользователь видит только свои КЕ','0','КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('355','3','Исполнитель','createUnitType','Создавать типы КЕ','0','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('356','3','Исполнитель','listUnitType','Отображать список типов КЕ','0','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('357','3','Исполнитель','updateUnitType','Редактировать типы КЕ','0','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('358','3','Исполнитель','deleteUnitType','Удалять типы КЕ','0','Типы КЕ');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('359','3','Исполнитель','createKB','Создавать записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('360','3','Исполнитель','viewKB','Просматривать записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('361','3','Исполнитель','listKB','Отображать список Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('362','3','Исполнитель','updateKB','Редактировать записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('363','3','Исполнитель','deleteKB','Удалять записи Базы знаний','0','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('364','3','Исполнитель','uploadFilesKB','Пользователь может прикреплять файлы к записи Базы знаний','1','База знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('365','3','Исполнитель','createKBCat','Создавать категории Базы знаний','0','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('366','3','Исполнитель','listKBCat','Отображать список категорий Базы знаний','0','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('367','3','Исполнитель','updateKBCat','Редактировать категории Базы знаний','0','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('368','3','Исполнитель','deleteKBCat','Удалять категории Базы знаний','0','Категории базы знаний');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('369','3','Исполнитель','createNews','Создать новость','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('370','3','Исполнитель','viewNews','Просматривать новости','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('371','3','Исполнитель','listNews','Отображать список новостей','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('372','3','Исполнитель','updateNews','Редактировать новости','1','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('373','3','Исполнитель','deleteNews','Удалять новости','0','Новости');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('374','3','Исполнитель','createUser','Создавать пользователей','0','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('375','3','Исполнитель','viewUser','Просматривать пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('376','3','Исполнитель','listUser','Отображать список пользователей','1','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('377','3','Исполнитель','updateUser','Редактировать пользователей','0','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('378','3','Исполнитель','deleteUser','Удалить пользователей','0','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('379','3','Исполнитель','exportUser','Экспортировать список пользователей','0','Пользователь');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('380','3','Исполнитель','createCompany','Создавать компании','0','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('381','3','Исполнитель','viewCompany','Просматривать компании','1','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('382','3','Исполнитель','listCompany','Отображать список компаний','1','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('383','3','Исполнитель','updateCompany','Редактировать компании','0','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('384','3','Исполнитель','deleteCompany','Удалять компании','0','Компания');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('385','3','Исполнитель','createDepart','Создавать подразделения','0','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('386','3','Исполнитель','listDepart','Отображать список подразделений','1','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('387','3','Исполнитель','updateDepart','Редактировать подразделения','0','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('388','3','Исполнитель','deleteDepart','Удалять подразделения','0','Подразделение');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('389','3','Исполнитель','createGroup','Создавать группы','0','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('390','3','Исполнитель','listGroup','Отображать список групп','0','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('391','3','Исполнитель','updateGroup','Редактировать группы','0','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('392','3','Исполнитель','deleteGroup','Удалять группы','0','Группа исполнителей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('393','3','Исполнитель','createPriority','Создавать приоритеты','0','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('394','3','Исполнитель','listPriority','Отображать список приоритетов','1','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('395','3','Исполнитель','updatePriority','Редактировать приоритеты','0','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('396','3','Исполнитель','deletePriority','Удалять приоритеты','0','Приоритет');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('397','3','Исполнитель','createStatus','Создавать статусы','0','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('398','3','Исполнитель','listStatus','Отображать список статусов','0','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('399','3','Исполнитель','updateStatus','Редактировать статусы','0','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('400','3','Исполнитель','deleteStatus','Удалять статусы','0','Статус');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('401','3','Исполнитель','createCategory','Создать категории заявок','0','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('402','3','Исполнитель','listCategory','Отображать список категорий заявок','0','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('403','3','Исполнитель','updateCategory','Редактировать категории заявок','0','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('404','3','Исполнитель','deleteCategory','Удаление категорий заявок','0','Категория');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('405','3','Исполнитель','createETemplate','Создать E-mail шаблон','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('406','3','Исполнитель','viewETemplate','Просматривать Email шаблоны','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('407','3','Исполнитель','listETemplate','Отображать список Email шаблонов','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('408','3','Исполнитель','updateETemplate','Редактировать Email шаблоны','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('409','3','Исполнитель','deleteETemplate','Удалять Email шаблоны','0','Шаблоны E-mail уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('410','3','Исполнитель','createSTemplate','Создать SMS шаблон','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('411','3','Исполнитель','viewSTemplate','Просматривать SMS шаблоны','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('412','3','Исполнитель','listSTemplate','Отображать список SMS шаблонов','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('413','3','Исполнитель','updateSTemplate','Редактировать SMS шаблоны','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('414','3','Исполнитель','deleteSTemplate','Удалять SMS шаблоны','0','Шаблоны SMS уведомлений');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('415','3','Исполнитель','createFieldsets','Создавать наборы полей','0','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('416','3','Исполнитель','listFieldsets','Отображать наборы полей','0','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('417','3','Исполнитель','updateFieldsets','Редактировать наборы полей','0','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('418','3','Исполнитель','deleteFieldsets','Удалять наборы полей','0','Наборы полей');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('419','3','Исполнитель','usersReport','Доступ к отчету Заявки по заявителям','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('420','3','Исполнитель','companiesReport','Доступ к отчету Заявки по компаниям','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('421','3','Исполнитель','managersReport','Доступ к отчету Заявки по менеджерам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('422','3','Исполнитель','serviceReport','Доступ к отчету Заявки по сервисам','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('423','3','Исполнитель','assetReport','Доступ к отчету Заявки по КЕ','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('424','3','Исполнитель','unitProblemReport','Доступ к отчету Проблемы по КЕ','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('425','3','Исполнитель','monthServiceProblemReport','Доступ к отчету Проблемы по сервисам за месяц','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('426','3','Исполнитель','serviceProblemReport','Доступ к отчету Проблемы по сервисам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('427','3','Исполнитель','unitSProblemReport','Доступ к отчету Сводный отчет по КЕ','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('428','3','Исполнитель','rolesSettings','Доступ к управлению ролями','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('429','3','Исполнитель','mainSettings','Доступ к основным настройкам','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('430','3','Исполнитель','mailParserSettings','Доступ к настройкам парсера почты','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('431','3','Исполнитель','adSettings','Доступ к настройкам интеграции с AD','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('432','3','Исполнитель','smsSettings','Доступ к настройкам SMS шлюза','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('433','3','Исполнитель','ticketSettings','Доступ к настройкам заявки по-умолчанию','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('434','3','Исполнитель','attachSettings','Доступ к настройкам вложений','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('435','3','Исполнитель','appearSettings','Доступ к настройкам внешнего вида','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('436','3','Исполнитель','shedulerSettings','Доступ к настройкам планировщика задач','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('437','3','Исполнитель','logSettings','Доступ к анализатору лога','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('438','3','Исполнитель','backupSettings','Доступ к резервному копированию','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('439','3','Исполнитель','importSettings','Импорт из CSV','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('440','3','Исполнитель','showTicketGraph','Отображать график заявок на главной панели','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('441','3','Исполнитель','showProblemGraph','Отображать график проблем на главной панели','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('442','3','Исполнитель','showlastNews','Отображать список последних новостей на главной панели','1','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('443','3','Исполнитель','showlastKB','Отображать список последних записей Базы знаний на главной панели','1','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('444','3','Исполнитель','showSearchKB','Отображать строку поиска по Базе знаний','1','Интерфейс');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('445','1','Администратор','viewCompanyRequest','Менеджер видит только заявки его компаний','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('446','2','Пользователь','viewCompanyRequest','Менеджер видит только заявки его компаний','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('447','3','Исполнитель','viewCompanyRequest','Менеджер видит только заявки его компаний','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('448','1','Администратор','requestSReport','Доступ к отчету Сводный отчет по заявкам','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('449','2','Пользователь','requestSReport','Доступ к отчету Сводный отчет по заявкам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('450','3','Исполнитель','requestSReport','Доступ к отчету Сводный отчет по заявкам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('451','1','Администратор','monthServiceRequestsReport','Доступ к отчету Заявки по сервисам за месяц','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('452','2','Пользователь','monthServiceRequestsReport','Доступ к отчету Заявки по сервисам за месяц','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('453','3','Исполнитель','monthServiceRequestsReport','Доступ к отчету Заявки по сервисам за месяц','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('454','1','Администратор','contractorsReport','Доступ к отчету Заявки по подрядчикам','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('455','2','Пользователь','contractorsReport','Доступ к отчету Заявки по подрядчикам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('456','3','Исполнитель','contractorsReport','Доступ к отчету Заявки по подрядчикам','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('457','1','Администратор','mainGraphAllGroupsManagers','Отображать график по группам исполнитилей','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('458','1','Администратор','viewMyCompanyRequest','Пользователь видит все заявки своей компании','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('459','1','Администратор','canViewFieldsRequest','Пользователь видит наборы полей в окне просмотра заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('460','1','Администратор','requestSReport','Доступ у сводному отчету по заявкам','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('461','1','Администратор','ticketSettings','Доступ к настройкам заявки по умолчанию','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('462','1','Администратор','createTemplates','Создавать шаблоны ответа','1','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('463','1','Администратор','listTemplates','Отображать список шаблонов ответа','1','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('464','1','Администратор','updateTemplates','Редактировать шаблоны ответа','1','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('465','1','Администратор','deleteTemplates','Удалять шаблоны ответа','1','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('466','1','Администратор','mainGraphAllGroupsManagers','Отображать график групп исполнителей','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('467','1','Администратор','mainGraphAllUsers','Отображать график по заявителям','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('468','1','Администратор','mainGraphManagers','Отображать график по исполнителям','1','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('469','1','Администратор','mainGraphAllCompanys','Отображать график по компаниям','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('470','1','Администратор','mainGraphCurentUserStatus','График заявок текущего пользователя','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('471','1','Администратор','mainGraphCompanyCurentUserStatus','График заявок по компании текущего пользователя','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('472','2','Пользователь','viewMyCompanyRequest','Пользователь видит все заявки своей компании','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('473','2','Пользователь','canViewFieldsRequest','Пользователь видит наборы полей в окне просмотра заявки','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('474','2','Пользователь','requestSReport','Доступ у сводному отчету по заявкам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('475','2','Пользователь','ticketSettings','Доступ к настройкам заявки по умолчанию','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('476','2','Пользователь','createTemplates','Создавать шаблоны ответа','0','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('477','2','Пользователь','listTemplates','Отображать список шаблонов ответа','0','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('478','2','Пользователь','updateTemplates','Редактировать шаблоны ответа','0','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('479','2','Пользователь','deleteTemplates','Удалять шаблоны ответа','0','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('480','2','Пользователь','mainGraphAllGroupsManagers','Отображать график групп исполнителей','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('481','2','Пользователь','mainGraphAllUsers','Отображать график по заявителям','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('482','2','Пользователь','mainGraphManagers','Отображать график по исполнителям','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('483','2','Пользователь','mainGraphAllCompanys','Отображать график по компаниям','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('484','2','Пользователь','mainGraphCurentUserStatus','График заявок текущего пользователя','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('485','2','Пользователь','mainGraphCompanyCurentUserStatus','График заявок по компании текущего пользователя','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('486','3','Исполнитель','viewMyCompanyRequest','Пользователь видит все заявки своей компании','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('487','3','Исполнитель','canViewFieldsRequest','Пользователь видит наборы полей в окне просмотра заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('488','3','Исполнитель','requestSReport','Доступ у сводному отчету по заявкам','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('489','3','Исполнитель','ticketSettings','Доступ к настройкам заявки по умолчанию','0','Настройки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('490','3','Исполнитель','createTemplates','Создавать шаблоны ответа','0','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('491','3','Исполнитель','listTemplates','Отображать список шаблонов ответа','0','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('492','3','Исполнитель','updateTemplates','Редактировать шаблоны ответа','0','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('493','3','Исполнитель','deleteTemplates','Удалять шаблоны ответа','0','Шаблоны ответа');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('494','3','Исполнитель','mainGraphAllGroupsManagers','Отображать график групп исполнителей','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('495','3','Исполнитель','mainGraphAllUsers','Отображать график по заявителям','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('496','3','Исполнитель','mainGraphManagers','Отображать график по исполнителям','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('497','3','Исполнитель','mainGraphAllCompanys','Отображать график по компаниям','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('498','3','Исполнитель','mainGraphCurentUserStatus','График заявок текущего пользователя','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('499','3','Исполнитель','mainGraphCompanyCurentUserStatus','График заявок по компании текущего пользователя','0','Графики');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('500','1','Администратор','createUnitTemplates','Создавать шаблоны печатной формы','1','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('501','1','Администратор','listUnitTemplates','Просматривать список шаблонов печтаных форм','1','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('502','1','Администратор','updateUnitTemplates','Редактировать шаблоны печатных форм','1','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('503','1','Администратор','deleteUnitTemplates','Удалять шаблоны печтаных форм','1','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('504','2','Пользователь','createUnitTemplates','Создавать шаблоны печатной формы','0','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('505','2','Пользователь','listUnitTemplates','Просматривать список шаблонов печтаных форм','0','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('506','2','Пользователь','updateUnitTemplates','Редактировать шаблоны печатных форм','0','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('507','2','Пользователь','deleteUnitTemplates','Удалять шаблоны печтаных форм','0','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('508','3','Исполнитель','createUnitTemplates','Создавать шаблоны печатной формы','0','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('509','3','Исполнитель','listUnitTemplates','Просматривать список шаблонов печтаных форм','0','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('510','3','Исполнитель','updateUnitTemplates','Редактировать шаблоны печатных форм','0','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('511','3','Исполнитель','deleteUnitTemplates','Удалять шаблоны печтаных форм','0','Шаблоны печатных форм');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('512','1','Администратор','batchAssignRequest','Массовое переназначение исполнителей','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('513','1','Администратор','batchUpdateStatusRequest','Массовое изменение статуса заявок','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('514','2','Пользователь','batchAssignRequest','Массовое переназначение исполнителей','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('515','2','Пользователь','batchUpdateStatusRequest','Массовое изменение статуса заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('516','3','Исполнитель','batchAssignRequest','Массовое переназначение исполнителей','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('517','3','Исполнитель','batchUpdateStatusRequest','Массовое изменение статуса заявок','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('524','1','Администратор','viewAssignedRequest','Менеджер видит только назначенные ему заявки','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('525','2','Пользователь','viewAssignedRequest','Менеджер видит только назначенные ему заявки','0','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('526','3','Исполнитель','viewAssignedRequest','Менеджер видит только назначенные ему заявки','1','Заявка');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('527','1','Администратор','customReport','Доступ к отчету Сводный отчет','1','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('528','2','Пользователь','customReport','Доступ к отчету Сводный отчет','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('529','3','Исполнитель','customReport','Доступ к отчету Сводный отчет','0','Отчеты');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('530','1','Администратор','listCronRequest','Отображать список запланированных заявок','1','Заявки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('531','2','Пользователь','listCronRequest','Отображать список запланированных заявок','0','Заявки');
INSERT INTO `roles_rights` (`id`,`rid`,`rname`,`name`,`description`,`value`,`category`) VALUES
('532','3','Исполнитель','listCronRequest','Отображать список запланированных заявок','0','Заявки');



-- -------------------------------------------
-- TABLE DATA service
-- -------------------------------------------
INSERT INTO `service` (`id`,`name`,`description`,`sla`,`priority`,`manager`,`manager_name`,`availability`,`group`,`gtype`,`fieldset`,`company_id`,`company_name`,`content`,`watcher`,`matching`) VALUES
('1','Электронная почта','Управление электронной почтой, создание и удаление почтовых ящиков.','8x5 Basic','Средний','manager','Васин В.В.','90','Первая линия поддержки','2','2','1','Users Company','<p>

	       Прошу создать ящик электронной почты в домене @univef.ru

</p>','Кузнецов А.С.','');
INSERT INTO `service` (`id`,`name`,`description`,`sla`,`priority`,`manager`,`manager_name`,`availability`,`group`,`gtype`,`fieldset`,`company_id`,`company_name`,`content`,`watcher`,`matching`) VALUES
('2','Обслуживание сторонних клиентов','Выездное обслуживание клиентов','8x5 Basic','Низкий','manager','Васин В.В.','50','','1','1','1','Users Company','','','');
INSERT INTO `service` (`id`,`name`,`description`,`sla`,`priority`,`manager`,`manager_name`,`availability`,`group`,`gtype`,`fieldset`,`company_id`,`company_name`,`content`,`watcher`,`matching`) VALUES
('3','Обслуживание внутренних клиентов','Обслуживание внутренних пользователей компании','8x5 Basic','Низкий','manager','Васин В.В.','90','','1','0','1','Users Company','','','');



-- -------------------------------------------
-- TABLE DATA sla
-- -------------------------------------------
INSERT INTO `sla` (`id`,`name`,`retimeh`,`retimem`,`sltimeh`,`sltimem`,`rhours`,`shours`,`taxes`,`cost`,`wstime`,`wetime`,`round_hours`,`round_days`,`ntretimeh`,`ntretimem`,`ntsltimeh`,`ntsltimem`,`nrhours`,`nshours`) VALUES
('1','8x5 Basic','01','00','02','00','01:00','02:00','01.01.*, 02.01.*, 03.01.*,04.01.*,05.01.*,06.01.*,07.01.*,23.02.*,08.03.*,01.05.*,09.05*,12.06*,04.11.*','90','08:00','18:00','0','0','00','00','00','00','00:00','00:00');
INSERT INTO `sla` (`id`,`name`,`retimeh`,`retimem`,`sltimeh`,`sltimem`,`rhours`,`shours`,`taxes`,`cost`,`wstime`,`wetime`,`round_hours`,`round_days`,`ntretimeh`,`ntretimem`,`ntsltimeh`,`ntsltimem`,`nrhours`,`nshours`) VALUES
('2','8x5 Optimal','01','00','03','00','01:00','03:00','01.01.*, 02.01.*, 03.01.*,04.01.*,05.01.*,06.01.*,07.01.*,23.02.*,08.03.*,01.05.*,09.05*,12.06*,04.11.*','92','08:00','18:00','0','0','00','00','00','00','00:00','00:00');
INSERT INTO `sla` (`id`,`name`,`retimeh`,`retimem`,`sltimeh`,`sltimem`,`rhours`,`shours`,`taxes`,`cost`,`wstime`,`wetime`,`round_hours`,`round_days`,`ntretimeh`,`ntretimem`,`ntsltimeh`,`ntsltimem`,`nrhours`,`nshours`) VALUES
('3','8x7 VIP','00','30','01','30','00:30','01:30','01.01.*, 02.01.*, 03.01.*,04.01.*,05.01.*,06.01.*,07.01.*,23.02.*,08.03.*,01.05.*,09.05*,12.06*,04.11.*','95','08:00','18:00','0','0','00','00','00','00','00:00','00:00');



-- -------------------------------------------
-- TABLE DATA sms
-- -------------------------------------------
INSERT INTO `sms` (`id`,`name`,`content`) VALUES
('1','default','#{id} Изменен статус заявки {status}');



-- -------------------------------------------
-- TABLE DATA sureport
-- -------------------------------------------
INSERT INTO `sureport` (`id`,`dept`,`type`,`count`,`summary`) VALUES
('112','Users Company','Рабочая станция','1','39800');
INSERT INTO `sureport` (`id`,`dept`,`type`,`count`,`summary`) VALUES
('113','Users Company','Мобильное рабочее место','0','0');
INSERT INTO `sureport` (`id`,`dept`,`type`,`count`,`summary`) VALUES
('114','Users Company','Станция печати','0','0');
INSERT INTO `sureport` (`id`,`dept`,`type`,`count`,`summary`) VALUES
('115','Managers company','Рабочая станция','0','0');
INSERT INTO `sureport` (`id`,`dept`,`type`,`count`,`summary`) VALUES
('116','Managers company','Мобильное рабочее место','0','0');
INSERT INTO `sureport` (`id`,`dept`,`type`,`count`,`summary`) VALUES
('117','Managers company','Станция печати','0','0');



-- -------------------------------------------
-- TABLE DATA tbl_columns
-- -------------------------------------------
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('cusers-grid_1','fullname||company||department||position||Email||Phone||role_name||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('cusers-grid_2','fullname||company||department||position||Email||Phone||role_name||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('cusers-grid_3','fullname||company||department||position||Email||Phone||role_name||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('problems-grid_1','slabel||date||creator||priority||category||manager||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('problems-grid_2','slabel||date||creator||priority||category||manager||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('problems-grid_3','slabel||date||creator||priority||category||manager||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('request-grid-full_1','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('request-grid-full_2','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('request-grid-full_3','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('request-grid_1','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('request-grid_2','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия');
INSERT INTO `tbl_columns` (`id`,`data`) VALUES
('request-grid_3','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия');



-- -------------------------------------------
-- TABLE DATA tbl_migration
-- -------------------------------------------
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m160621_080728_service_name_100',1467292342);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m160629_070325_required_field',1467292477);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m160914_074331_30914',1472496373);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m160915_184634_optimiz', 1473970482);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m160918_095313_030918', 1473970482);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m160919_120214_309182', 1473970482);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m161030_165202_31030', 1477850795);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m161125_105300_31130', 1477850795);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m161212_073006_31212', 1477850795);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m170115_182307_40116', 1477850795);
INSERT INTO `tbl_migration` (`version`, `apply_time`) VALUES
  ('m170302_095633_40302', 1477850795);



-- -------------------------------------------
-- TABLE DATA uhistory
-- -------------------------------------------
INSERT INTO `uhistory` (`id`,`uid`,`date`,`user`,`action`) VALUES
('80','1','27.12.2014 14:05','admin','Добавлен актив: <b>Системный блок ПК Кузнецова</b>. Инвентарный номер: <b>PC-125987</b>');
INSERT INTO `uhistory` (`id`,`uid`,`date`,`user`,`action`) VALUES
('81','1','27.12.2014 14:05','admin','Добавлен актив: <b>Монитор Монитор Кузнецова</b>. Инвентарный номер: <b>MON-124598</b>');
INSERT INTO `uhistory` (`id`,`uid`,`date`,`user`,`action`) VALUES
('82','1','27.12.2014 14:05','admin','Добавлена КЕ: Рабочая станция Рабочее место Кузнецова. Инвентарный номер WS-156798. Дата ввода в эксплуатацию: 27.12.2014. Дата вывода из эксплуатации: ');
INSERT INTO `uhistory` (`id`,`uid`,`date`,`user`,`action`) VALUES
('83','1','27.12.2014 14:09','admin','Добавлен актив: <b>Клавиатура Logitech Black Keyboard</b>. Инвентарный номер: <b>KB-125798</b>');
INSERT INTO `uhistory` (`id`,`uid`,`date`,`user`,`action`) VALUES
('84','1','27.12.2014 14:09','admin','Добавлен актив: <b>Мышь Logitech Black Mouse</b>. Инвентарный номер: <b>MOU-156798</b>');
INSERT INTO `uhistory` (`id`,`uid`,`date`,`user`,`action`) VALUES
('85','1','27.12.2014 14:09','admin','Добавлен актив: <b>Операционная система Windows 8.1 Pro</b>. Инвентарный номер: <b></b>');



-- -------------------------------------------
-- TABLE DATA unit_templates
-- -------------------------------------------
INSERT INTO `unit_templates` (`id`,`name`,`content`,`format`,`type`,`type_name`) VALUES
('1','Карточка КЕ','<h1 style=\"text-align: center;\"><strong>Карточка КЕ №{id} </strong></h1>

<h1 style=\"text-align: center;\"><strong>{name}</strong></h1>

<hr>

<p>

	                      {QRCODE}

</p>

<hr>

<p>

	 <strong>Тип КЕ:</strong> {type}

</p>

<p>

	 <strong>Статус:</strong> {status}

</p>

<p>

	 <strong>Инвентарный номер:</strong> {inventory}

</p>

<p>

	 <strong>Стоимость: </strong>{cost}

</p>

<p>

	 <strong>Пользователь:</strong> {username}

</p>

<p>

	 <strong>Отдел:</strong> {department}

</p>

<p>

	 <strong>Компания:</strong> {company}

</p>

<p>

	 <strong>Дата ввода в эксплуатацию: </strong>{startexpdate}

</p>

<p>

	 <strong>Дата вывода из эксплуатации: </strong>{endexpdate}

</p>

<p>

	 <strong>Местоположение:</strong> {location}

</p>

<p>

	                        {assets}

</p>

 <br>

 <br>

<table>

<tbody>

<tr>

	<td>

		 <strong>Подпись ответственного ____________________                      </strong>

	</td>

	<td>

		 <strong>                                      Дата______________________</strong>

	</td>

</tr>

</tbody>

</table>','P','1','КЕ');
INSERT INTO `unit_templates` (`id`,`name`,`content`,`format`,`type`,`type_name`) VALUES
('2','Акт приема-передачи','<h1 style=\"text-align: center;\">Акт приема-передачи оборудования №________</h1>

<p style=\"text-align: right;\">

	                 Дата <u>{date}</u>

</p>

<p style=\"text-align: right;\">

	                 г. __________________

</p>

<p>

	            Данный документ, подтверждает, что один пользователь передал, указаное ниже оборудование, а другой пользователь это оборудование принял. По внешнему виду и составу оборудования претензий нет.

</p>

<hr id=\"horizontalrule\">

<h4><strong>Передаваемое оборудование:</strong></h4>

<table border=\"1px\">

<tbody>

<tr>

	<td>

		 <strong>Наименование:</strong>

	</td>

	<td>

		 <strong>Тип КЕ:</strong>

	</td>

	<td>

		 <strong>Статус:</strong>

	</td>

	<td>

		 <strong>Инвентарный номер:</strong>

	</td>

	<td>

		 <strong>Дата ввода в эксплуатацию: </strong>

	</td>

</tr>

<tr>

	<td>

		                            {name}

	</td>

	<td>

		                            {type}

	</td>

	<td>

		                            {status}

	</td>

	<td>

		                            {inventory}

	</td>

	<td>

		                            {startexpdate}

	</td>

</tr>

</tbody>

</table>

<h4>Состав оборудования:</h4>

<p>

	      {assets}

</p>

<hr>

<p>

	       Подписи сторон:

</p>

<p>

	 <strong>Оборудование передал ____________________/__________________</strong>

</p>

<p>

	 <strong>Оборудование принял   ____________________/__________________</strong>

</p>','P','1','КЕ');
INSERT INTO `unit_templates` (`id`,`name`,`content`,`format`,`type`,`type_name`) VALUES
('3','Карточка актива','<h1 style=\"text-align: center;\">Карточка актива №{id}</h1>

<h1 style=\"text-align: center;\"><span style=\"color: rgb(51, 51, 51);\">{name}</span></h1>

<hr>

<p>

	                 {QRCODE}

</p>

<hr>

<p>

	 <strong>Тип актива:</strong> {type}

</p>

<p>

	 <strong>Статус:</strong> {status}

</p>

<p>

	 <strong>Инвентарный номер:</strong> {inventory}

</p>

<p>

	 <strong>Стоимость:</strong> {cost}

</p>

<p>

	 <strong>Пользователь:</strong> {username}

</p>

<p>

	 <strong>Отдел:</strong> {department}

</p>

<p>

	 <strong>Местоположение:</strong> {location}

</p>

<p>

	   {assets}

</p>

<hr id=\"horizontalrule\">

<p>

	                 Подпись ответственного ____________________

</p>

<p>

	             Дата <u>{date}</u>

</p>','P','2','Актив');



-- -------------------------------------------
-- TABLE DATA ustatus
-- -------------------------------------------
INSERT INTO `ustatus` (`id`,`name`,`label`) VALUES
('1','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>');
INSERT INTO `ustatus` (`id`,`name`,`label`) VALUES
('2','В ремонте','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #fcb117\">В ремонте</span>');
INSERT INTO `ustatus` (`id`,`name`,`label`) VALUES
('3','В резерве','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #fcb117\">В резерве</span>');
INSERT INTO `ustatus` (`id`,`name`,`label`) VALUES
('4','На складе','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #58595b\">На складе</span>');
INSERT INTO `ustatus` (`id`,`name`,`label`) VALUES
('5','Списан','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #eb5f69\">Списан</span>');



-- -------------------------------------------
-- TABLE DATA zcategory
-- -------------------------------------------
INSERT INTO `zcategory` (`id`,`name`,`enabled`,`incident`) VALUES
('1','Заявка на обслуживание','1','0');
INSERT INTO `zcategory` (`id`,`name`,`enabled`,`incident`) VALUES
('2','Инцидент','1','1');
INSERT INTO `zcategory` (`id`,`name`,`enabled`,`incident`) VALUES
('3','Ремонт оборудования','1','0');



-- -------------------------------------------
-- TABLE DATA zpriority
-- -------------------------------------------
INSERT INTO `zpriority` (`id`,`name`,`cost`,`rcost`,`scost`) VALUES
('1','Низкий','+30','+30','+30');
INSERT INTO `zpriority` (`id`,`name`,`cost`,`rcost`,`scost`) VALUES
('2','Средний','+0','+0','+0');
INSERT INTO `zpriority` (`id`,`name`,`cost`,`rcost`,`scost`) VALUES
('3','Высокий','-30','-30','-30');
INSERT INTO `zpriority` (`id`,`name`,`cost`,`rcost`,`scost`) VALUES
('4','Критический','-50','-50','-50');
INSERT INTO `zpriority` (`id`,`name`,`cost`,`rcost`,`scost`) VALUES
('5','Планирование','+180','+180','+180');



-- -------------------------------------------
-- TABLE DATA zstatus
-- -------------------------------------------
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('1','Открыта','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Открыта</span>','#6ac28e','1','1','0','1','1','1','0','0','default','Новая заказчик','default','Новая исполнитель','Уведомление наблюдателя','default','default','0','0','0','default','default');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('2','Принята в исполнение','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #5692bb\">Принята в исполнение</span>','#5692bb','2','1','0','0','0','0','0','0','default','Заявка в работе заказчик','default','Заявка в работе исполнитель','Уведомление наблюдателя','default','default','0','0','0','default','default');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('3','Просрочена реакция','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #fcb117\">Просрочена реакция</span>','#fcb117','4','0','0','1','0','0','0','0','default','default','default','Просрочена реакция исполнитель','Уведомление наблюдателя','default','default','0','0','1','default','Скоро просрочена реакция');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('4','Просрочено исполнение','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #756994\">Просрочено исполнение</span>','#756994','5','1','0','1','0','0','0','0','default','Просрочена заявка заказчик','default','Просрочена заявка исполнитель','Уведомление наблюдателя','default','default','0','0','1','default','Скоро просрочено решение');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('5','Отменена','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #f56954\">Отменена</span>','#f56954','6','1','0','1','0','0','0','0','default','Заявка отменена','default','Заявка отменена','Уведомление наблюдателя','default','default','0','0','0','default','default');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('6','Требует уточнения','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #39cccc\">Требует уточнения</span>','#39cccc','0','1','0','0','0','0','0','0','default','default','default','default','Уведомление наблюдателя','default','default','0','1','0','default','default');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('7','Требует согласования','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #001f3f\">Требует согласования</span>','#001f3f','7','0','0','0','0','0','1','0','default','default','default','default','Уведомление наблюдателя','Заявка требует согласования','default','0','0','0','default','default');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('8','Согласовано','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #ff851b\">Согласовано</span>','#ff851b','0','0','0','1','0','0','0','0','default','default','default','Заявка согласована','default','default','default','0','0','0','default','default');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('9','Выполнена','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #d81b60\">Выполнена</span>','#d81b60','0','1','0','0','0','0','0','0','default','Заявка завершена','default','default','default','default','default','0','0','0','default','default');
INSERT INTO `zstatus` (`id`,`name`,`enabled`,`label`,`tag`,`close`,`notify_user`,`notify_user_sms`,`notify_manager`,`notify_manager_sms`,`notify_group`,`notify_matching`,`notify_matching_sms`,`sms`,`message`,`msms`,`mmessage`,`gmessage`,`matching_message`,`matching_sms`,`hide`,`freeze`,`show`,`mwsms`,`mwmessage`) VALUES
('10','Завершена','1','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #58595b\">Завершена</span>','#58595b','3','1','0','1','0','1','0','0','default','Заявка завершена','default','Заявка завершена','Уведомление наблюдателя','default','default','1','0','0','default','default');



-- -------------------------------------------
-- TABLE DATA zstatus_to_roles
-- -------------------------------------------
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('1','1');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('1','3');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('2','1');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('2','3');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('5','1');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('5','2');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('6','1');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('6','3');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('7','1');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('7','3');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('8','1');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('8','2');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('8','3');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('9','1');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('9','3');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('10','1');
INSERT INTO `zstatus_to_roles` (`zstatus_id`,`roles_id`) VALUES
('10','2');

-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
