-- MySQL dump 10.14  Distrib 5.5.50-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: demo
-- ------------------------------------------------------
-- Server version	5.5.50-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `CUsers`
--
SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION';
SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION';
DROP TABLE IF EXISTS `CUsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CUsers` (
                          `id` int(10) NOT NULL AUTO_INCREMENT,
                          `Username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `fullname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `Password` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `Email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `Phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `push_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `intphone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `role` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `role_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `company` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `room` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `department` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `umanager` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `birth` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `position` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `sendmail` int(1) DEFAULT '1',
                          `sendsms` int(1) DEFAULT '0',
                          `lang` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `active` int(1) NOT NULL DEFAULT '1',
                          `photo` int(1) DEFAULT '0',
                          `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `mobile` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `tbot` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `vbot` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `msbot` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `wbot` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                          `send_wbot` int(1) DEFAULT NULL,
                          `send_tbot` int(1) DEFAULT NULL,
                          `send_vbot` int(1) DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `Username` (`Username`),
                          KEY `idx_rolename` (`role_name`),
                          KEY `idx_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CUsers`
--

LOCK TABLES `CUsers` WRITE;
/*!40000 ALTER TABLE `CUsers` DISABLE KEYS */;
INSERT INTO `CUsers` VALUES (1,'admin','Администратор','b147fdcfe0a8120780ce85a8a6d27596','admin@email.com','+79003113133','','6013','univefadmin','Администратор','Компания А','','ИТ отдел','','','Администратор',1,0,'ru',1,1,'','',NULL,NULL,'NULL',NULL,NULL,NULL,NULL),(2,'manager','Васин В.В.','9a88fba853317e9496ca33824bda8ba8','manager@email.com','+79005556667','','','univefmanager','Исполнитель','Компания Б','','ИТ отдел','','','Инженер',1,0,'ru',1,1,'','',NULL,NULL,'NULL',NULL,NULL,NULL,NULL),(3,'user','Кузнецов А. С.','4417e18f1155c1f595e1006aed7d2e27','user@email.com','+79001000000','','6023','univefuser','Пользователь','Компания А','205','Отдел продаж','','','Менеджер',1,0,'ru',1,1,'','',NULL,NULL,'NULL',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `CUsers` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `update_user_name` BEFORE UPDATE ON `CUsers` FOR EACH ROW
    IF(old.`Username` <> new.`Username`) THEN UPDATE `request` SET `request`.`CUsers_id` = new.`Username` WHERE `request`.`CUsers_id` = old.`Username`;
    UPDATE `request` SET `request`.`phone` = new.`Phone` WHERE `request`.`CUsers_id` = old.`Username`;
    UPDATE `cunits` SET `cunits`.`user` = new.`Username` WHERE `cunits`.`user` = old.`Username`;
    UPDATE `asset` SET `asset`.`cusers_name` = new.`Username` WHERE `asset`.`cusers_name` = old.`Username`;
    END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `update_manager_request` AFTER UPDATE ON `CUsers` FOR EACH ROW
    IF(old.`fullname` <> new.`fullname`) THEN UPDATE `request` SET `mfullname` = new.`fullname` WHERE `Managers_id` = old.`Username`;
    UPDATE `request` SET `request`.`creator` = new.`fullname` WHERE `request`.`creator` = old.`fullname`;
    UPDATE `cunits` SET `cunits`.`fullname` = new.`fullname` WHERE `cunits`.`fullname` = old.`fullname`;
    UPDATE `asset` SET `asset`.`cusers_fullname` = new.`fullname` WHERE `asset`.`cusers_fullname` = old.`fullname`;
    UPDATE `request` SET `request`.`fullname` = new.`fullname` WHERE `request`.`CUsers_id` = old.`Username`;
    END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `MailQueue`
--

DROP TABLE IF EXISTS `MailQueue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MailQueue` (
                             `id` int(11) NOT NULL AUTO_INCREMENT,
                             `from` varchar(100) NOT NULL,
                             `to` varchar(100) NOT NULL,
                             `subject` varchar(100) NOT NULL,
                             `body` text,
                             `attachs` text,
                             `priority` smallint(2) NOT NULL DEFAULT '0',
                             `status` tinyint(1) DEFAULT NULL,
                             `createDate` datetime DEFAULT NULL,
                             `updateDate` datetime DEFAULT NULL,
                             `getmailconfig` varchar(50) DEFAULT NULL,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MailQueue`
--

LOCK TABLES `MailQueue` WRITE;
/*!40000 ALTER TABLE `MailQueue` DISABLE KEYS */;
/*!40000 ALTER TABLE `MailQueue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `YiiLog`
--

DROP TABLE IF EXISTS `YiiLog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `YiiLog` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `level` varchar(500) DEFAULT NULL,
                          `category` varchar(128) DEFAULT NULL,
                          `logtime` varchar(128) DEFAULT NULL,
                          `message` text,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `YiiSession`
--

DROP TABLE IF EXISTS `YiiSession`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `YiiSession` (
                              `id` char(32) NOT NULL,
                              `expire` int(11) DEFAULT NULL,
                              `data` longblob,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `YiiSession`
--

LOCK TABLES `YiiSession` WRITE;
/*!40000 ALTER TABLE `YiiSession` DISABLE KEYS */;
INSERT INTO `YiiSession` VALUES ('3vdaemqn6gr1fg7s3dufomnn56',1641803534,'89c9dbb55a9b93f99618d1c2dc137525__returnUrl|s:66:\"/request/getevents?start=2021-05-31&end=2021-07-12&_=1641802089040\";'),('4tbuf4tgjtrql595a4fje7mkm3',1641803589,'Yii.CCaptchaAction.2f84c72d.portal.captcha|s:16:\"помогать\";Yii.CCaptchaAction.2f84c72d.portal.captcharesult|s:16:\"помогать\";Yii.CCaptchaAction.2f84c72d.portal.captchacount|i:3;fields|a:0:{}'),('5pf6lcvqrf4k6g0e3gmpsfd0t7',1641803781,'Yii.CCaptchaAction.2f84c72d.portal.captcha|s:14:\"клиенты\";Yii.CCaptchaAction.2f84c72d.portal.captcharesult|s:14:\"клиенты\";Yii.CCaptchaAction.2f84c72d.portal.captchacount|i:1;'),('6q8f5eujlmbabqtatrp7nsve23',1641803654,'89c9dbb55a9b93f99618d1c2dc137525__returnUrl|s:66:\"/request/getevents?start=2021-05-31&end=2021-07-12&_=1641802209032\";'),('k1p84pji5id5h84ocjnnles6g7',1641803607,'89c9dbb55a9b93f99618d1c2dc137525__returnUrl|s:9:\"/request/\";89c9dbb55a9b93f99618d1c2dc137525__id|s:1:\"1\";89c9dbb55a9b93f99618d1c2dc137525__name|s:5:\"admin\";89c9dbb55a9b93f99618d1c2dc137525__states|a:0:{}tempRequestSaveFilter|a:8:{s:2:\"id\";s:0:\"\";s:7:\"channel\";s:0:\"\";s:6:\"rating\";s:0:\"\";s:4:\"Date\";s:0:\"\";s:7:\"EndTime\";s:0:\"\";s:4:\"Name\";s:0:\"\";s:8:\"fullname\";s:0:\"\";s:9:\"mfullname\";s:0:\"\";}tempSortFilter|N;'),('k6ehtgvsmvrpdl3erl84knn3m7',1641803714,'89c9dbb55a9b93f99618d1c2dc137525__returnUrl|s:66:\"/request/getevents?start=2021-05-31&end=2021-07-12&_=1641802269038\";'),('m2f6oenupg2uldsan3422ai8f0',1641803474,'89c9dbb55a9b93f99618d1c2dc137525__returnUrl|s:66:\"/request/getevents?start=2021-05-31&end=2021-07-12&_=1641802029057\";'),('ml851r9sso13a0h9185i3jvh44',1641803482,'Yii.CCaptchaAction.2f84c72d.portal.captcha|s:18:\"оказывать\";Yii.CCaptchaAction.2f84c72d.portal.captcharesult|s:18:\"оказывать\";Yii.CCaptchaAction.2f84c72d.portal.captchacount|i:1;'),('o3co1l2gr1404q9ucvb34248s1',1641803609,''),('odo5ng9vvghkca94slve4ttqg3',1641803594,'89c9dbb55a9b93f99618d1c2dc137525__returnUrl|s:66:\"/request/getevents?start=2021-05-31&end=2021-07-12&_=1641802149040\";'),('p3o08movu32k24br6d2p39u5c7',1641803745,''),('r34h7gue6s11g6sv4aecb9e247',1641803774,'89c9dbb55a9b93f99618d1c2dc137525__returnUrl|s:66:\"/request/getevents?start=2021-05-31&end=2021-07-12&_=1641802329018\";');
/*!40000 ALTER TABLE `YiiSession` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ahistory`
--

DROP TABLE IF EXISTS `ahistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ahistory` (
                            `id` int(10) NOT NULL,
                            `aid` int(10) DEFAULT NULL,
                            `date` varchar(50) DEFAULT NULL,
                            `user` varchar(50) DEFAULT NULL,
                            `action` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ahistory`
--

LOCK TABLES `ahistory` WRITE;
/*!40000 ALTER TABLE `ahistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `ahistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `alerts`
--

DROP TABLE IF EXISTS `alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alerts` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `user` varchar(70) DEFAULT NULL,
                          `name` varchar(70) DEFAULT NULL,
                          `message` varchar(500) DEFAULT NULL,
                          `shown` int(1) DEFAULT '0',
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alerts`
--

LOCK TABLES `alerts` WRITE;
/*!40000 ALTER TABLE `alerts` DISABLE KEYS */;
/*!40000 ALTER TABLE `alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `areport`
--

DROP TABLE IF EXISTS `areport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `areport` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areport`
--

LOCK TABLES `areport` WRITE;
/*!40000 ALTER TABLE `areport` DISABLE KEYS */;
/*!40000 ALTER TABLE `areport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset`
--

DROP TABLE IF EXISTS `asset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset` (
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
                         `description` text,
                         `image` varchar(500) DEFAULT NULL,
                         PRIMARY KEY (`id`),
                         KEY `FK_asset_asset_attrib` (`asset_attrib_id`),
                         KEY `cusers_id` (`cusers_id`),
                         CONSTRAINT `FK_asset_CUsers` FOREIGN KEY (`cusers_id`) REFERENCES `CUsers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                         CONSTRAINT `FK_asset_asset_attrib` FOREIGN KEY (`asset_attrib_id`) REFERENCES `asset_attrib` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset`
--

LOCK TABLES `asset` WRITE;
/*!40000 ALTER TABLE `asset` DISABLE KEYS */;
INSERT INTO `asset` VALUES (1,1,'27.12.2014 14:03','ПК Кузнецова','','PC-125987','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Используется</span>','22500',6,'Системный блок',0,'user','Кузнецов А. С.','Отдел продаж','','NULL'),(2,1,'27.12.2014 14:04','Монитор Кузнецова','','MON-124598','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Используется</span>','7500',17,'Монитор',0,'user','Кузнецов А. С.','Отдел продаж','','NULL'),(3,1,'27.12.2014 14:06','Logitech Black Keyboard','','KB-125798','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Используется</span>','850',16,'Клавиатура',0,'user','Кузнецов А. С.','Отдел продаж','','NULL'),(4,1,'27.12.2014 14:07','Logitech Black Mouse','','MOU-156798','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Используется</span>','450',19,'Мышь',0,'user','Кузнецов А. С.','Отдел продаж','','NULL'),(5,1,'27.12.2014 14:08','Windows 8.1 Pro','','','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Используется</span>','8500',18,'Операционная система',0,'user','Кузнецов А. С.','Отдел продаж','','NULL');
/*!40000 ALTER TABLE `asset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_attrib`
--

DROP TABLE IF EXISTS `asset_attrib`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_attrib` (
                                `id` int(10) NOT NULL AUTO_INCREMENT,
                                `name` varchar(200) DEFAULT NULL,
                                `asset_id` int(10) DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_attrib`
--

LOCK TABLES `asset_attrib` WRITE;
/*!40000 ALTER TABLE `asset_attrib` DISABLE KEYS */;
INSERT INTO `asset_attrib` VALUES (6,'Системный блок',0),(7,'Принтер',0),(9,'Маршрутизатор',0),(13,'МФУ',0),(14,'Сервер',0),(15,'Картридж',0),(16,'Клавиатура',0),(17,'Монитор',0),(18,'Операционная система',0),(19,'Мышь',0),(20,'Программное обеспечение',0);
/*!40000 ALTER TABLE `asset_attrib` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_attrib_value`
--

DROP TABLE IF EXISTS `asset_attrib_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_attrib_value` (
                                      `id` int(10) NOT NULL AUTO_INCREMENT,
                                      `asset_id` int(10) DEFAULT NULL,
                                      `asset_attrib_id` int(10) DEFAULT NULL,
                                      `name` varchar(200) DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `id` (`id`),
                                      KEY `asset_attrib_id` (`asset_attrib_id`),
                                      CONSTRAINT `FK_asset_attrib_value_asset_attrib` FOREIGN KEY (`asset_attrib_id`) REFERENCES `asset_attrib` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_attrib_value`
--

LOCK TABLES `asset_attrib_value` WRITE;
/*!40000 ALTER TABLE `asset_attrib_value` DISABLE KEYS */;
INSERT INTO `asset_attrib_value` VALUES (11,7,7,'Модель принтера'),(12,7,7,'Ресурс принтера'),(13,7,7,'Отпечатанных листов'),(14,7,7,'Модель картриджа'),(17,9,9,'Инвентарный номер'),(18,9,9,'Местонахождение'),(21,13,13,'Производитель'),(22,13,13,'Куплено'),(24,13,13,'Цена при покупке'),(25,14,14,'Производитель'),(26,14,14,'Бизнес задача'),(27,14,14,'Инвентарный номер'),(28,15,15,'Дата заправки'),(29,6,6,'Материнская плата'),(30,6,6,'Процессор'),(31,6,6,'Оперативная память'),(32,6,6,'Объем HDD'),(33,6,6,'Видеокарта'),(34,6,6,'Сетевая карта'),(40,17,17,'Модель'),(41,17,17,'Диагональ (дюймы)'),(42,17,17,'Максимальное разрешение'),(43,18,18,'Производитель'),(44,18,18,'Версия'),(45,18,18,'Разрядность'),(46,18,18,'Серийный номер'),(47,20,20,'Производитель'),(48,20,20,'Версия ПО'),(49,20,20,'Тип лицензии'),(50,20,20,'Серийный номер'),(51,6,6,'Доменное имя'),(52,6,6,'IP адрес'),(54,17,17,'Производитель');
/*!40000 ALTER TABLE `asset_attrib_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_files`
--

DROP TABLE IF EXISTS `asset_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_files` (
                               `asset_id` int(11) NOT NULL,
                               `file_id` int(11) NOT NULL,
                               PRIMARY KEY (`asset_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_files`
--

LOCK TABLES `asset_files` WRITE;
/*!40000 ALTER TABLE `asset_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `asset_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asset_values`
--

DROP TABLE IF EXISTS `asset_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_values` (
                                `id` int(10) NOT NULL AUTO_INCREMENT,
                                `asset_id` int(10) DEFAULT '0',
                                `asset_attrib_id` int(10) DEFAULT '0',
                                `asset_attrib_name` varchar(200) DEFAULT NULL,
                                `value` varchar(200) DEFAULT NULL,
                                PRIMARY KEY (`id`),
                                KEY `FK_asset_values_asset` (`asset_id`),
                                CONSTRAINT `FK_asset_values_asset` FOREIGN KEY (`asset_id`) REFERENCES `asset` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asset_values`
--

LOCK TABLES `asset_values` WRITE;
/*!40000 ALTER TABLE `asset_values` DISABLE KEYS */;
INSERT INTO `asset_values` VALUES (219,1,6,'Материнская плата','ASUS P8iX-LE'),(220,1,6,'Процессор','Intel Core i5 3,2GHz'),(221,1,6,'Оперативная память','4GB'),(222,1,6,'Объем HDD','500GB'),(223,1,6,'Видеокарта','ASUS GeForce GTX620'),(224,1,6,'Сетевая карта','Realtek 1000 LAN'),(225,1,6,'Доменное имя','KUZNETSOV-PC'),(226,1,6,'IP адрес','192.168.0.101'),(228,2,17,'Модель','P2770HD'),(229,2,17,'Диагональ (дюймы)','27'),(230,2,17,'Максимальное разрешение','1920x1080'),(232,5,18,'Версия','8.1 PRO'),(233,5,18,'Разрядность','x64'),(234,5,18,'Серийный номер','XKG2B-CGJ22-XKW9I-CDF5G-HGDKJ'),(236,2,17,'Производитель','Samsung');
/*!40000 ALTER TABLE `asset_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `astatus`
--

DROP TABLE IF EXISTS `astatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `astatus` (
                           `id` int(10) NOT NULL AUTO_INCREMENT,
                           `name` varchar(50) DEFAULT NULL,
                           `tag` varchar(50) DEFAULT NULL,
                           `label` varchar(400) DEFAULT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `astatus`
--

LOCK TABLES `astatus` WRITE;
/*!40000 ALTER TABLE `astatus` DISABLE KEYS */;
INSERT INTO `astatus` VALUES (1,'Используется','#6ac28e','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Используется</span>'),(2,'Сломан','#eb5f69','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #eb5f69; vertical-align: baseline; white-space: nowrap; border: 1px solid #eb5f69; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Сломан</span>'),(3,'В ремонте','#fcb117','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #fcb117; vertical-align: baseline; white-space: nowrap; border: 1px solid #fcb117; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">В ремонте</span>'),(4,'В резерве','#a39a44','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #a39a44; vertical-align: baseline; white-space: nowrap; border: 1px solid #a39a44; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">В резерве</span>'),(5,'На складе','#58595b','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #58595b; vertical-align: baseline; white-space: nowrap; border: 1px solid #58595b; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">На складе</span>'),(6,'Списан','#8b96a6','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #8b96a6; vertical-align: baseline; white-space: nowrap; border: 1px solid #8b96a6; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Списан</span>');
/*!40000 ALTER TABLE `astatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banlist`
--

DROP TABLE IF EXISTS `banlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banlist` (
                           `id` int(11) NOT NULL AUTO_INCREMENT,
                           `value` varchar(100) NOT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banlist`
--

LOCK TABLES `banlist` WRITE;
/*!40000 ALTER TABLE `banlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `banlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bcats`
--

DROP TABLE IF EXISTS `bcats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bcats` (
                         `id` int(10) NOT NULL AUTO_INCREMENT,
                         `name` varchar(50) DEFAULT NULL,
                         `access` varchar(700) DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bcats`
--

LOCK TABLES `bcats` WRITE;
/*!40000 ALTER TABLE `bcats` DISABLE KEYS */;
INSERT INTO `bcats` VALUES (1,'Проблемы','Пользователь,Исполнитель,Гость'),(2,'Инциденты','Пользователь,Исполнитель'),(3,'Регламенты','Исполнитель');
/*!40000 ALTER TABLE `bcats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brecords`
--

DROP TABLE IF EXISTS `brecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brecords` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brecords`
--

LOCK TABLES `brecords` WRITE;
/*!40000 ALTER TABLE `brecords` DISABLE KEYS */;
INSERT INTO `brecords` VALUES (1,1,'Проблемы','Тестовая запись ','<p>\n   Тестовый контент<br></p>','Администратор','26.02.2019 18:56','','Пользователь,Исполнитель,Гость');
/*!40000 ALTER TABLE `brecords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calls`
--

DROP TABLE IF EXISTS `calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calls` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `rid` int(11) DEFAULT NULL,
                         `uniqid` varchar(50) DEFAULT NULL,
                         `duniqid` varchar(50) DEFAULT NULL,
                         `date` timestamp NULL DEFAULT NULL,
                         `adate` timestamp NULL DEFAULT NULL,
                         `edate` timestamp NULL DEFAULT NULL,
                         `dialer` varchar(200) DEFAULT NULL,
                         `dialer_name` varchar(200) DEFAULT NULL,
                         `dr_number` varchar(200) DEFAULT NULL,
                         `dr_company` varchar(200) DEFAULT NULL,
                         `dialed` varchar(200) DEFAULT NULL,
                         `dialed_name` varchar(200) DEFAULT NULL,
                         `dd_number` varchar(200) DEFAULT NULL,
                         `status` varchar(200) DEFAULT NULL,
                         `slabel` varchar(200) DEFAULT NULL,
                         `shown` int(1) DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calls`
--

LOCK TABLES `calls` WRITE;
/*!40000 ALTER TABLE `calls` DISABLE KEYS */;
/*!40000 ALTER TABLE `calls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        `name` varchar(32) NOT NULL,
                        `reader` varchar(32) DEFAULT NULL,
                        `message` varchar(255) NOT NULL,
                        `rstate` int(1) DEFAULT '0',
                        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_read`
--

DROP TABLE IF EXISTS `chat_read`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_read` (
                             `user` int(11) NOT NULL,
                             `chat` int(11) NOT NULL,
                             UNIQUE KEY `user_chat_fk` (`user`,`chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_read`
--

LOCK TABLES `chat_read` WRITE;
/*!40000 ALTER TABLE `chat_read` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_read` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklist_fields`
--

DROP TABLE IF EXISTS `checklist_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklist_fields` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `checklist_id` int(11) NOT NULL,
                                    `name` varchar(64) NOT NULL,
                                    `sorting` int(11) NOT NULL DEFAULT '1',
                                    PRIMARY KEY (`id`),
                                    UNIQUE KEY `checklist_fields_checklist_id_name_uidx` (`checklist_id`,`name`),
                                    CONSTRAINT `checklists_checklist_id_fk` FOREIGN KEY (`checklist_id`) REFERENCES `checklists` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklist_fields`
--

LOCK TABLES `checklist_fields` WRITE;
/*!40000 ALTER TABLE `checklist_fields` DISABLE KEYS */;
INSERT INTO `checklist_fields` VALUES (1,1,'Создать учетную запись в AD',1),(2,1,'Создать учетную запись в КИС',2),(3,1,'Выдать ПК',3),(4,1,'Подписать корпоративный договор',4);
/*!40000 ALTER TABLE `checklist_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklists`
--

DROP TABLE IF EXISTS `checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checklists` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `name` varchar(64) NOT NULL,
                              PRIMARY KEY (`id`),
                              UNIQUE KEY `checklists_name_uidx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklists`
--

LOCK TABLES `checklists` WRITE;
/*!40000 ALTER TABLE `checklists` DISABLE KEYS */;
INSERT INTO `checklists` VALUES (1,'Новый сотрудник');
/*!40000 ALTER TABLE `checklists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment_files`
--

DROP TABLE IF EXISTS `comment_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment_files` (
                                 `comment_id` int(11) NOT NULL,
                                 `file_id` int(11) NOT NULL,
                                 PRIMARY KEY (`comment_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment_files`
--

LOCK TABLES `comment_files` WRITE;
/*!40000 ALTER TABLE `comment_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `rid` int(11) NOT NULL,
                            `timestamp` varchar(100) NOT NULL,
                            `author` varchar(100) NOT NULL,
                            `comment` text NOT NULL,
                            `show` int(1) unsigned NOT NULL DEFAULT '0',
                            `files` text NOT NULL,
                            `recipients` varchar(50) NOT NULL,
                            `readership` varchar(255) DEFAULT NULL,
                            `channel` varchar(100) DEFAULT NULL,
                            PRIMARY KEY (`id`),
                            KEY `rid` (`rid`),
                            CONSTRAINT `FK_comments_request` FOREIGN KEY (`rid`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
                             `id` int(10) NOT NULL AUTO_INCREMENT,
                             `name` varchar(100) DEFAULT NULL,
                             `director` varchar(50) DEFAULT NULL,
                             `head_position` varchar(100) DEFAULT NULL,
                             `head_name_writeable` varchar(100) DEFAULT NULL,
                             `uraddress` varchar(200) DEFAULT NULL,
                             `faddress` varchar(200) DEFAULT NULL,
                             `contact_name` varchar(100) DEFAULT NULL,
                             `phone` varchar(50) DEFAULT NULL,
                             `email` varchar(50) DEFAULT NULL,
                             `add1` text,
                             `add2` text,
                             `manager` varchar(50) DEFAULT NULL,
                             `inn` varchar(20) DEFAULT NULL,
                             `kpp` varchar(20) DEFAULT NULL,
                             `ogrn` varchar(20) DEFAULT NULL,
                             `bank` varchar(100) DEFAULT NULL,
                             `bik` varchar(20) DEFAULT NULL,
                             `korschet` varchar(50) DEFAULT NULL,
                             `schet` varchar(50) DEFAULT NULL,
                             `domains` text,
                             `image` varchar(500) DEFAULT NULL,
                             `head_positon` varchar(100) DEFAULT NULL,
                             PRIMARY KEY (`id`),
                             KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Компания А','Пупкин И.С.','Генерального директора','Пупкина Игоря Сергеевича','Москва, ул. Пупырина, д.6, кв.777','Москва, ул. Пупырина, д.6, кв.777','Кузнецов Алексей','+7(999)999-99-99','user@email.com','','','manager','12345678910','123456789','1234567891023','','045879524','45678912306547896514','25112555512658815252','email.com',NULL,'NULL'),(2,'Компания Б','Сатья Наделла','','','Москва','Москва','','','','','','','','','','','','','','email2.com',NULL,'NULL');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `company_update_users` AFTER UPDATE ON `companies` FOR EACH ROW
    IF(old.`name` <> new.`name`) THEN  UPDATE `CUsers` SET `CUsers`.`company` = new.`name` WHERE `CUsers`.`company` = old.`name`;
    UPDATE `request` SET `request`.`company` = new.`name` WHERE `request`.`company` = old.`name`;
    UPDATE `cunits` SET `cunits`.`company` = new.`name` WHERE `cunits`.`company` = old.`name`;
    UPDATE `depart` SET `depart`.`company` = new.`name` WHERE `depart`.`company` = old.`name`;
    END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `companies_files`
--

DROP TABLE IF EXISTS `companies_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies_files` (
                                   `companies_id` int(11) NOT NULL,
                                   `file_id` int(11) NOT NULL,
                                   PRIMARY KEY (`companies_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies_files`
--

LOCK TABLES `companies_files` WRITE;
/*!40000 ALTER TABLE `companies_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `companies_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_fields`
--

DROP TABLE IF EXISTS `company_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_fields` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `rid` int(11) DEFAULT NULL,
                                  `name` varchar(64) NOT NULL,
                                  `type` enum('toggle','date','textFieldRow','select') NOT NULL,
                                  `value` varchar(64) NOT NULL,
                                  `fid` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `rid` (`rid`),
                                  KEY `type` (`type`),
                                  KEY `value` (`value`),
                                  CONSTRAINT `fk-company-id` FOREIGN KEY (`rid`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_fields`
--

LOCK TABLES `company_fields` WRITE;
/*!40000 ALTER TABLE `company_fields` DISABLE KEYS */;
INSERT INTO `company_fields` VALUES (28,1,'Дата договора','date','',6),(29,2,'Дата договора','date','',6),(30,1,'Город','select','Москва',7),(31,2,'Город','select','Москва',7);
/*!40000 ALTER TABLE `company_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_fieldset`
--

DROP TABLE IF EXISTS `company_fieldset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_fieldset` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `fid` int(11) DEFAULT NULL,
                                    `sid` int(11) DEFAULT NULL,
                                    `name` varchar(100) DEFAULT NULL,
                                    `type` varchar(100) DEFAULT NULL,
                                    `req` tinyint(1) NOT NULL DEFAULT '0',
                                    `value` text,
                                    `select_id` int(11) DEFAULT NULL,
                                    PRIMARY KEY (`id`),
                                    KEY `fid` (`fid`),
                                    KEY `idx_company_fields_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_fieldset`
--

LOCK TABLES `company_fieldset` WRITE;
/*!40000 ALTER TABLE `company_fieldset` DISABLE KEYS */;
INSERT INTO `company_fieldset` VALUES (6,NULL,2,'Дата договора','date',0,NULL,0),(7,NULL,1,'Город','select',0,'Москва,Санкт-Петербург,Казань,Новосибирск,Екатеринбург,Нижний Новгород,Челябинск,Омск,Самара,Ростов-на-Дону,Уфа,Красноярск,Пермь,Воронеж,Волгоград,Краснодар,Саратов,Тюмень,Тольятти,Ижевск,Барнаул,Ульяновск,Иркутск,Хабаровск,Ярославль,Владивосток,Махачкала,Томск,Оренбург,Кемерово,Новокузнецк,Рязань,Астрахань,Пенза,Липецк,Киров,Чебоксары,Тула,Калининград,Балашиха,Курск,Ставрополь,Улан - Удэ,Севастополь,Тверь,Магнитогорск,Сочи,Иваново,Брянск,Комсомольск-на-Амуре',1);
/*!40000 ALTER TABLE `company_fieldset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_services`
--

DROP TABLE IF EXISTS `company_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_services` (
                                    `company_id` int(11) NOT NULL,
                                    `service_id` int(11) NOT NULL,
                                    PRIMARY KEY (`company_id`,`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_services`
--

LOCK TABLES `company_services` WRITE;
/*!40000 ALTER TABLE `company_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `company_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contractors`
--

DROP TABLE IF EXISTS `contractors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contractors` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contractors`
--

LOCK TABLES `contractors` WRITE;
/*!40000 ALTER TABLE `contractors` DISABLE KEYS */;
/*!40000 ALTER TABLE `contractors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contracts`
--

DROP TABLE IF EXISTS `contracts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts` (
                             `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                             `number` varchar(100) DEFAULT NULL,
                             `name` varchar(100) DEFAULT NULL,
                             `type` varchar(100) DEFAULT NULL,
                             `date` date DEFAULT NULL,
                             `date_view` varchar(20) DEFAULT NULL,
                             `customer_id` int(11) DEFAULT NULL,
                             `customer_name` varchar(100) DEFAULT NULL,
                             `company_id` int(11) DEFAULT NULL,
                             `company_name` varchar(100) DEFAULT NULL,
                             `tildate` date DEFAULT NULL,
                             `tildate_view` varchar(20) DEFAULT NULL,
                             `cost` int(100) DEFAULT NULL,
                             `stopservice` int(1) DEFAULT NULL,
                             `image` text,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contracts`
--

LOCK TABLES `contracts` WRITE;
/*!40000 ALTER TABLE `contracts` DISABLE KEYS */;
INSERT INTO `contracts` VALUES (1,'ТП0001','Договор на техническую поддержку с ООО \"Компания А\"',' Договор технической поддержки','2019-03-18','18.03.2019',1,'Компания А',2,'Компания Б','2020-03-18','18.03.2020',50000,0,'NULL');
/*!40000 ALTER TABLE `contracts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contracts_files`
--

DROP TABLE IF EXISTS `contracts_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracts_files` (
                                   `contracts_id` int(11) NOT NULL,
                                   `file_id` int(11) NOT NULL,
                                   PRIMARY KEY (`contracts_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contracts_files`
--

LOCK TABLES `contracts_files` WRITE;
/*!40000 ALTER TABLE `contracts_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `contracts_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron`
--

DROP TABLE IF EXISTS `cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(100) DEFAULT NULL,
                        `job_id` varchar(50) DEFAULT NULL,
                        `job` varchar(500) DEFAULT NULL,
                        `time` varchar(50) DEFAULT NULL,
                        PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron`
--

LOCK TABLES `cron` WRITE;
/*!40000 ALTER TABLE `cron` DISABLE KEYS */;
/*!40000 ALTER TABLE `cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron_req`
--

DROP TABLE IF EXISTS `cron_req`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron_req` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `service_id` int(11) NOT NULL,
                            `CUsers_id` varchar(32) NOT NULL,
                            `Status` varchar(32) NOT NULL,
                            `ZayavCategory_id` varchar(32) NOT NULL,
                            `Priority` varchar(100) NOT NULL,
                            `Name` varchar(50) NOT NULL,
                            `Content` text NOT NULL,
                            `watchers` varchar(500) DEFAULT NULL,
                            `cunits` varchar(500) DEFAULT NULL,
                            `Date` datetime NOT NULL,
                            `repeats` int(1) DEFAULT '0',
                            `enabled` int(1) DEFAULT '0',
                            `color` varchar(50) NOT NULL,
                            `fields` text NOT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron_req`
--

LOCK TABLES `cron_req` WRITE;
/*!40000 ALTER TABLE `cron_req` DISABLE KEYS */;
/*!40000 ALTER TABLE `cron_req` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cunit_types`
--

DROP TABLE IF EXISTS `cunit_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cunit_types` (
                               `id` int(10) NOT NULL AUTO_INCREMENT,
                               `name` varchar(70) DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cunit_types`
--

LOCK TABLES `cunit_types` WRITE;
/*!40000 ALTER TABLE `cunit_types` DISABLE KEYS */;
INSERT INTO `cunit_types` VALUES (1,'Рабочая станция'),(2,'Мобильное рабочее место'),(3,'Станция печати');
/*!40000 ALTER TABLE `cunit_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cunits`
--

DROP TABLE IF EXISTS `cunits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cunits` (
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
                          `description` text,
                          `image` varchar(500) DEFAULT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cunits`
--

LOCK TABLES `cunits` WRITE;
/*!40000 ALTER TABLE `cunits` DISABLE KEYS */;
INSERT INTO `cunits` VALUES (1,'Рабочее место Кузнецова','Рабочая станция','Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Используется</span>','39800','user','Кузнецов А. С.','Отдел продаж','WS-156798','27.12.2014 14:05','27.12.2014','','Компания А','5,1,2,3,4','','<p>asdsdasdas222</p>','NULL');
/*!40000 ALTER TABLE `cunits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cunits_files`
--

DROP TABLE IF EXISTS `cunits_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cunits_files` (
                                `cunits_id` int(11) NOT NULL,
                                `file_id` int(11) NOT NULL,
                                PRIMARY KEY (`cunits_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cunits_files`
--

LOCK TABLES `cunits_files` WRITE;
/*!40000 ALTER TABLE `cunits_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `cunits_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `depart`
--

DROP TABLE IF EXISTS `depart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `depart` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(100) DEFAULT NULL COMMENT 'Название',
                          `company` varchar(100) DEFAULT NULL,
                          `manager_id` int(11) DEFAULT NULL,
                          `manager` varchar(100) DEFAULT NULL,
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `depart`
--

LOCK TABLES `depart` WRITE;
/*!40000 ALTER TABLE `depart` DISABLE KEYS */;
INSERT INTO `depart` VALUES (1,'Отдел продаж','Компания А',2,NULL),(2,'ИТ отдел','Компания А',3,NULL),(3,'Руководство','Компания А',NULL,'NULL');
/*!40000 ALTER TABLE `depart` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `depart_request_update` AFTER UPDATE ON `depart` FOR EACH ROW
    IF(old.`name` <> new.`name`) THEN
        UPDATE `request` SET `request`.`depart` = new.`name` WHERE `request`.`depart_id` = old.`id`;
    END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `depart_services`
--

DROP TABLE IF EXISTS `depart_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `depart_services` (
                                   `depart_id` int(11) NOT NULL,
                                   `service_id` int(11) NOT NULL,
                                   PRIMARY KEY (`depart_id`,`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `depart_services`
--

LOCK TABLES `depart_services` WRITE;
/*!40000 ALTER TABLE `depart_services` DISABLE KEYS */;
/*!40000 ALTER TABLE `depart_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escalates`
--

DROP TABLE IF EXISTS `escalates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escalates` (
                             `id` int(11) NOT NULL AUTO_INCREMENT,
                             `service_id` int(11) NOT NULL,
                             `type_id` tinyint(1) NOT NULL,
                             `minutes` int(11) DEFAULT '0',
                             `manager_id` int(11) DEFAULT NULL,
                             `group_id` int(11) DEFAULT NULL,
                             PRIMARY KEY (`id`),
                             KEY `idx_escalates_service_id` (`service_id`),
                             KEY `idx_escalates_manager_id` (`manager_id`),
                             KEY `idx_escalates_group_id` (`group_id`),
                             KEY `idx_escalates_type_id` (`type_id`),
                             CONSTRAINT `fk_escalates_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                             CONSTRAINT `fk_escalates_manager_id` FOREIGN KEY (`manager_id`) REFERENCES `CUsers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                             CONSTRAINT `fk_escalates_service_id` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escalates`
--

LOCK TABLES `escalates` WRITE;
/*!40000 ALTER TABLE `escalates` DISABLE KEYS */;
/*!40000 ALTER TABLE `escalates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fieldsets`
--

DROP TABLE IF EXISTS `fieldsets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fieldsets` (
                             `id` int(11) NOT NULL AUTO_INCREMENT,
                             `name` varchar(100) DEFAULT NULL,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fieldsets`
--

LOCK TABLES `fieldsets` WRITE;
/*!40000 ALTER TABLE `fieldsets` DISABLE KEYS */;
INSERT INTO `fieldsets` VALUES (1,'Выездное обслуживание'),(2,'Электронная почта');
/*!40000 ALTER TABLE `fieldsets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fieldsets_fields`
--

DROP TABLE IF EXISTS `fieldsets_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fieldsets_fields` (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `fid` int(11) DEFAULT NULL,
                                    `sid` int(11) DEFAULT NULL,
                                    `name` varchar(100) DEFAULT NULL,
                                    `type` varchar(100) DEFAULT NULL,
                                    `req` tinyint(1) NOT NULL DEFAULT '0',
                                    `value` text,
                                    `select_id` int(11) DEFAULT NULL,
                                    PRIMARY KEY (`id`),
                                    KEY `fid` (`fid`),
                                    KEY `idx_fieldsets_fields_name` (`name`),
                                    CONSTRAINT `FK_fieldsets_fields_fieldsets` FOREIGN KEY (`fid`) REFERENCES `fieldsets` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fieldsets_fields`
--

LOCK TABLES `fieldsets_fields` WRITE;
/*!40000 ALTER TABLE `fieldsets_fields` DISABLE KEYS */;
INSERT INTO `fieldsets_fields` VALUES (1,1,2,'Требуется выезд','toggle',0,'',0),(2,1,3,'Дата выезда','date',0,'',0),(4,2,3,'Адрес электронной почты','textFieldRow',0,'',0),(8,2,2,'Новый ящик?','toggle',0,NULL,0),(10,1,5,'Город','select',0,'Москва,Санкт-Петербург,Казань,Новосибирск,Екатеринбург,Нижний Новгород,Челябинск,Омск,Самара,Ростов-на-Дону,Уфа,Красноярск,Пермь,Воронеж,Волгоград,Краснодар,Саратов,Тюмень,Тольятти,Ижевск,Барнаул,Ульяновск,Иркутск,Хабаровск,Ярославль,Владивосток,Махачкала,Томск,Оренбург,Кемерово,Новокузнецк,Рязань,Астрахань,Пенза,Липецк,Киров,Чебоксары,Тула,Калининград,Балашиха,Курск,Ставрополь,Улан - Удэ,Севастополь,Тверь,Магнитогорск,Сочи,Иваново,Брянск,Комсомольск-на-Амуре',1);
/*!40000 ALTER TABLE `fieldsets_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `files` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `name` varchar(128) NOT NULL,
                         `file_name` varchar(32) NOT NULL,
                         `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `idx_uq_file_name` (`file_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `files`
--

LOCK TABLES `files` WRITE;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
/*!40000 ALTER TABLE `files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filter`
--

DROP TABLE IF EXISTS `filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filter` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `uid` int(11) DEFAULT NULL,
                          `name` varchar(100) DEFAULT NULL,
                          `value` varchar(2000) DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `user` (`uid`),
                          CONSTRAINT `user` FOREIGN KEY (`uid`) REFERENCES `CUsers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `filter`
--

LOCK TABLES `filter` WRITE;
/*!40000 ALTER TABLE `filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(100) DEFAULT NULL,
                          `users` varchar(2000) DEFAULT NULL,
                          `phone` varchar(100) DEFAULT NULL,
                          `email` varchar(100) DEFAULT NULL,
                          `send` int(1) DEFAULT '0',
                          PRIMARY KEY (`id`),
                          KEY `idx_send` (`send`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'Первая линия поддержки','2','2255','group@email.com',0);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history` (
                           `id` int(10) NOT NULL AUTO_INCREMENT,
                           `zid` int(10) NOT NULL DEFAULT '0',
                           `cusers_id` varchar(50) NOT NULL DEFAULT '0',
                           `datetime` varchar(50) NOT NULL DEFAULT '0',
                           `action` text,
                           PRIMARY KEY (`id`),
                           KEY `FK_history_Zayavki` (`zid`),
                           CONSTRAINT `FK_history_request` FOREIGN KEY (`zid`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=463 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
INSERT INTO `history` VALUES (453,1,'Администратор','01.03.2021 14:35','Заявка создана'),(454,1,'Администратор','01.03.2021 14:35','Начало работ (план) установлено в: <b>01.03.2021 16:05</b>'),(455,1,'Администратор','01.03.2021 14:35','Окончание работ (план) установлено в: <b>01.03.2021 17:05</b>'),(456,1,'Администратор','01.03.2021 14:35','Изменен статус: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Открыта</span>'),(457,2,'Администратор','01.03.2021 14:36','Заявка создана'),(458,2,'Администратор','01.03.2021 14:36','Начало работ (план) установлено в: <b>01.03.2021 16:06</b>'),(459,2,'Администратор','01.03.2021 14:36','Окончание работ (план) установлено в: <b>01.03.2021 17:06</b>'),(460,2,'Администратор','01.03.2021 14:36','Изменен статус: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Открыта</span>'),(461,2,'Администратор','01.03.2021 14:36','Изменен статус: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #001f3f; vertical-align: baseline; white-space: nowrap; border: 1px solid #001f3f; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Требует согласования</span>'),(462,2,'Администратор','01.03.2021 14:36','Изменен исполнитель: <b>Администратор</b>');
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `influence`
--

DROP TABLE IF EXISTS `influence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `influence` (
                             `id` int(10) NOT NULL AUTO_INCREMENT,
                             `name` varchar(100) DEFAULT NULL,
                             `cost` varchar(50) DEFAULT NULL,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `influence`
--

LOCK TABLES `influence` WRITE;
/*!40000 ALTER TABLE `influence` DISABLE KEYS */;
INSERT INTO `influence` VALUES (1,'Незначительное влияние',''),(2,'Частичная неработоспособность',''),(3,'Полная неработоспособность','');
/*!40000 ALTER TABLE `influence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `knowledge_files`
--

DROP TABLE IF EXISTS `knowledge_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `knowledge_files` (
                                   `knowledge_id` int(11) NOT NULL,
                                   `file_id` int(11) NOT NULL,
                                   PRIMARY KEY (`knowledge_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `knowledge_files`
--

LOCK TABLES `knowledge_files` WRITE;
/*!40000 ALTER TABLE `knowledge_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `knowledge_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads` (
                         `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                         `name` varchar(200) DEFAULT NULL COMMENT 'Название',
                         `company_id` int(11) DEFAULT NULL,
                         `company` varchar(200) DEFAULT NULL COMMENT 'Компания',
                         `contact_id` int(11) DEFAULT NULL,
                         `contact` varchar(200) DEFAULT NULL COMMENT 'Контакт',
                         `contact_phone` varchar(200) DEFAULT NULL COMMENT 'Телефон',
                         `contact_email` varchar(200) DEFAULT NULL COMMENT 'E-mail',
                         `contact_position` varchar(200) DEFAULT NULL COMMENT 'Должность',
                         `created` datetime DEFAULT NULL COMMENT 'Дата создания',
                         `changed` datetime DEFAULT NULL COMMENT 'Дата изменения',
                         `closed` datetime DEFAULT NULL COMMENT 'Дата завершения',
                         `creator` varchar(200) DEFAULT NULL COMMENT 'Кем создана',
                         `changer` varchar(200) DEFAULT NULL COMMENT 'Кем изменена',
                         `manager_id` int(11) DEFAULT NULL,
                         `manager` varchar(200) DEFAULT NULL COMMENT 'Ответственный',
                         `status_id` int(11) DEFAULT NULL,
                         `status` varchar(400) DEFAULT NULL COMMENT 'Этап сделки',
                         `cost` varchar(100) DEFAULT NULL COMMENT 'Бюджет',
                         `tag` varchar(200) DEFAULT NULL COMMENT 'Тег',
                         `description` text COMMENT 'Описание',
                         `sort_id` int(11) DEFAULT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
                            `id` int(10) NOT NULL AUTO_INCREMENT,
                            `name` varchar(50) NOT NULL,
                            `subject` varchar(500) NOT NULL,
                            `content` text,
                            `static` int(1) DEFAULT '0',
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'default','[Ticket #{id}] {name}','<h3>Заявка № {id} \"{name}\"</h3>\n<table>\n<tbody>\n<tr>\n <th>\n     Статус\n </th>\n <td>\n     {status}\n </td>\n</tr>\n<tr>\n  <th>\n     Заказчик\n </th>\n <td>\n     {fullname}\n </td>\n</tr>\n<tr>\n  <th>\n     Исполнитель\n  </th>\n <td>\n     {manager_name}\n </td>\n</tr>\n<tr>\n  <th>\n     Телефон исполнителя\n  </th>\n <td>\n     {manager_phone}\n  </td>\n</tr>\n<tr>\n  <th>\n     Email исполнителя\n  </th>\n <td>\n     {manager_email}\n  </td>\n</tr>\n<tr>\n  <th>\n     Название\n </th>\n <td>\n     {name}\n </td>\n</tr>\n<tr>\n  <th>\n     Категория\n  </th>\n <td>\n     {category}\n </td>\n</tr>\n<tr>\n  <th>\n     Приоритет\n  </th>\n <td>\n     {priority}\n </td>\n</tr>\n<tr>\n  <th>\n     Создано\n  </th>\n <td>\n     {created}\n  </td>\n</tr>\n<tr>\n  <th>\n     Начало работ (план)\n  </th>\n <td>\n     {StartTime}\n  </td>\n</tr>\n<tr>\n  <th>\n     Начало работ (факт)\n  </th>\n <td>\n     {fStartTime}\n </td>\n</tr>\n<tr>\n  <th>\n     Окончание работ (план)\n </th>\n <td>\n     {EndTime}\n  </td>\n</tr>\n<tr>\n  <th>\n     Окончание работ (факт)\n </th>\n <td>\n     {fEndTime}\n </td>\n</tr>\n<tr>\n  <th>\n     Сервис\n </th>\n <td>\n     {service_name}\n </td>\n</tr>\n<tr>\n  <th>\n     Адрес\n  </th>\n <td>\n     {address}\n  </td>\n</tr>\n<tr>\n  <th>\n     Компания\n </th>\n <td>\n     {company}\n  </td>\n</tr>\n<tr>\n  <th>\n     Актив\n  </th>\n <td>\n     {unit}\n </td>\n</tr>\n<tr>\n  <th>\n     Комментарий\n  </th>\n <td>\n     {comment}\n  </td>\n</tr>\n<tr>\n  <th>\n     Содержание\n </th>\n <td>\n     {content}\n  </td>\n</tr>\n</tbody>\n</table>',0),(3,'Заявка в работе заказчик','[Ticket #{id}] {name}','<h3>Принята в работу заявка № {id} \"{name}\"</h3>\n<p>\n      Заявка была принята в работу Исполнителем <strong>{manager_name}, </strong>вы можете связаться с Исполнителем по телефону <strong>{manager_phone}</strong>\n</p>\n<p>\n      Срок исполнения до: <strong>{EndTime}</strong>\n</p>\n<hr>\n<p>\n   <strong>Содержание заявки:</strong>\n</p>\n<p>\n     {content}\n</p>\n<p>Ссылка на заявку {url}</p>\n<hr id=\"horizontalrule\">\n<p>\n  <strong>{comment_message}</strong>\n</p>',0),(4,'Заявка завершена','[Ticket #{id}] {name}','<h3>Заявка #{id} \"{name}\" была успешно завершена</h3>\n<hr>\n<p>\n Исполнитель <strong>{manager_name} </strong>исполнил заявку <strong>{fEndTime}</strong>, если Вы оказались недовольны качеством работ, можете обратиться к исполнителю по телефону <strong>{manager_phone} </strong>или по E-Mail <strong>{manager_email}</strong>\n</p>\n<hr>\n<p>\n</p>\n<p>{voting}</p>\n<p>{reopen}</p>',0),(5,'Заявка в работе исполнитель','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold;\">Вы приняли в работу заявка № {id} \"{name}\"</span></h3>\n<p>\n Вам необходимо завершить заявку до: <strong>{EndTime}</strong>\n</p>\n<hr>\n<p>\n  <strong>Содержание заявки:</strong>\n</p>\n<p>\n  {content}\n</p><p>Ссылка на заявку {url}</p>',0),(6,'Просрочена заявка исполнитель','[Ticket #{id}] {name}','<h3>Вы просрочили исполнение<span style=\"color: rgb(0, 0, 0); font-weight: bold;\"> заявки № {id} \"{name}\"</span></h3>\n<p>\n   Назначенная Вам заявка была просрочена  <strong>{EndTime}</strong>\n</p>\n<p>\n   Срочно исполните заявку!\n</p>\n<hr>\n<p>\n   <strong>Содержание заявки:</strong>\n</p>\n<p>\n  {content}\n</p><p>Ссылка на заявку {url}</p>',0),(7,'Просрочена реакция исполнитель','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold;\">Вы просрочили реакцию на заявку № {id} \"{name}\"</span></h3>\n<p>\n У назначенной Вам заявки был просрочен срок реакции <strong></strong><strong>{StartTime}</strong>\n</p>\n<p>\n  Срочно начните работу над заявкой!\n</p>\n<hr>\n<p>\n <strong>Содержание заявки:</strong>\n</p>\n<p>\n  {content}\n</p><p>Ссылка на заявку {url}</p>',0),(8,'Просрочена заявка заказчик','[Ticket #{id}] {name}','<h3>Исполнитель просрочил<span style=\"color: rgb(0, 0, 0); font-weight: bold;\"> исполнение заявки № {id} \"{name}\"</span></h3>\n<p>\n Созданная Вами заявка была просрочена <strong>{EndTime}</strong>\n</p>\n<p>\n Свяжитесь с исполнителем.\n</p>\n<hr>\n<p>\n  <strong>Содержание заявки:</strong>\n</p>\n<p>\n  {content}\n</p><p>Ссылка на заявку {url}</p>',0),(9,'Заявка отменена','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold;\">Исполнитель отменил исполнение заявки № {id} \"{name}\"</span></h3>\n<p>\n Свяжитесь с исполнителем.\n</p>\n<hr>\n<p>\n  <strong>Причина отмены:</strong>\n</p>\n<p>\n {comment}\n</p><p>Ссылка на заявку {url}</p>',0),(10,'Новая заказчик','[Ticket #{id}] {name}','<h3>Вы успешно зарегистрировали заявку № {id} {created}</h3>\n<hr>\n<p>\n Название заявки: <strong>{name}</strong>\n</p>\n<p>\n Заявке назначен исполнитель: <strong>{manager_name}</strong>\n</p>\n<p>\n Телефон исполнителя: <strong>{manager_phone}</strong>\n</p>\n<p>\n  E-mail исполнителя: <strong>{manager_email}</strong>\n</p>\n<p>\n Ваша заявка должна быть исполнена до <strong>{EndTime}</strong>\n</p>\n<hr>\n<p>\n  Содержание:\n</p>\n<p>\n  {content}\n</p>\n<p>\n  Ссылка на заявку {url}<br>\n\n</p>',0),(11,'Новая исполнитель','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold;\">Вам была назначена новая заявка № {id} {created}</span></h3>\n<hr>\n<p>\n    Название заявки: <strong>{name}</strong>\n</p>\n<p>\n   Заказчик: <strong>{fullname}</strong>\n</p>\n<p>\n    Вы должны приступить к работе до <strong>{StartTime}</strong>\n</p>\n<p>\n    Заявка должна быть исполнена до <strong>{EndTime}</strong>\n</p>\n<hr>\n<p>\n   Содержание:\n</p>\n<p>\n    {content}\n</p><p>Ссылка на заявку {url}</p>',0),(12,'Уведомление наблюдателя','[Ticket #{id}] {name}','<h3><span style=\"color: rgb(0, 0, 0); font-weight: bold; background-color: initial;\">Вы назначены наблюдателем заявки № {id} \"{name}\"</span></h3>\n<p>Произошли изменения в заявке, созданной <strong>{fullname}</strong></p>\n<p>Имя исполнителя: <strong style=\"background-color: initial;\">{manager_name}</strong></p>\n<p>Статус: <strong>{status}</strong></p>\n<p><span style=\"background-color: initial;\">Начало работ (план): </span><strong style=\"background-color: initial;\">{StartTime}</strong></p>\n<p>Срок исполнения до: <strong>{EndTime}</strong></p>\n<p><span style=\"background-color: initial;\">Начало работ (факт): </span><strong style=\"background-color: initial;\">{fStartTime}</strong></p>\n<p>Окончание работ (факт): <strong style=\"background-color: initial;\">{fEndTime}</strong></p>\n<hr>\n<p><strong>Содержание заявки:</strong></p>\n<p>{content}</p><p>Ссылка на заявку {url}</p>',0),(13,'Скоро просрочена реакция','[Ticket #{id}] {name}','<h3><span style=\"\\&quot;background-color:\" initial;\\\"=\"\">Истекает время</span><span style=\"\\&quot;color:\" rgb(0,=\"\" 0,=\"\" 0);=\"\" font-weight:=\"\" bold;=\"\" background-color:=\"\" initial;\\\"=\"\"> реакции на заявку № {id} \"{name}\"</span></h3>\n<p>\n   У назначенной Вам заявки скоро истекает срок реакции <strong>{StartTime}</strong>\n</p>\n<p>\n   Срочно начните работу над заявкой!\n</p>\n<hr>\n<p>\n   <strong>Содержание заявки:</strong>\n</p>\n<p>\n   {content}\n</p><p>Ссылка на заявку {url}</p>',0),(14,'Скоро просрочено решение','[Ticket #{id}] {name}','<h3>Истекает время исполнения заявки № {id} \"{name}\"</h3>\n<p>\n   У назначенной Вам заявки скоро истекает срок исполнения <strong>{EndTime}</strong>\n</p>\n<p>\n   Срочно начните работу над заявкой!\n</p>\n<hr>\n<p>\n   <strong>Содержание заявки:</strong>\n</p>\n<p>\n   <span style=\"\\&quot;background-color:\" initial;\\\"=\"\">{content}</span>\n</p><p>Ссылка на заявку {url}</p>',0),(15,'Заявка требует согласования','[Ticket #{id}] {name} Требуется согласование','<h3><strong>Вам необходимо согласовать заявку</strong> № {id} \"{name}\"</h3>\r\n<p>\r\n   Имя заказчика: <strong>{fullname}</strong>\r\n</p>\r\n<p>\r\n   Имя исполнителя: <strong>{manager_name}</strong>\r\n</p>\r\n<p>\r\n   Статус: <strong>{status}</strong>\r\n</p>\r\n<p>\r\n   Начало работ (план): <strong>{StartTime}</strong>\r\n</p>\r\n<p>\r\n   Срок исполнения до: <strong>{EndTime}</strong>\r\n</p>\r\n<p>\r\n   Начало работ (факт): <strong>{fStartTime}</strong>\r\n</p>\r\n<p>\r\n   Окончание работ (факт): <strong>{fEndTime}</strong>\r\n</p>\r\n<hr>\r\n<p>\r\n   <strong>Содержание заявки:</strong>\r\n</p>\r\n<p>\r\n   {content}\r\n</p><p><b>{agreed}</b></p>\r\n<p><b>{denied}</b></p>\r\n<p><b>{add_info}</b></p>\r\n<p>Ссылка на заявку {url}</p>',0),(16,'Заявка согласована','[Ticket #{id}] {name} Заявка согласаована','<p>\r\n   <strong style=\"color: rgb(0, 0, 0); font-size: 24px;\">Ваша заявка согласована</strong><span style=\"color: rgb(0, 0, 0); font-size: 24px; font-weight: bold;\"> № {id} \"{name}\"</span>\r\n</p>\r\n<p>\r\n   Имя заказчика: <strong>{fullname}</strong>\r\n</p>\r\n<p>\r\n   Имя исполнителя: <strong>{manager_name}</strong>\r\n</p>\r\n<p>\r\n   Статус: <strong>{status}</strong>\r\n</p>\r\n<p>\r\n   Начало работ (план): <strong>{StartTime}</strong>\r\n</p>\r\n<p>\r\n   Срок исполнения до: <strong>{EndTime}</strong>\r\n</p>\r\n<p>\r\n   Начало работ (факт): <strong>{fStartTime}</strong>\r\n</p>\r\n<p>\r\n   Окончание работ (факт): <strong>{fEndTime}</strong>\r\n</p>\r\n<hr>\r\n<p>\r\n   <strong>Содержание заявки:</strong>\r\n</p>\r\n<p>\r\n   {content}</p><p>Ссылка на заявку {url}</p>',0),(17,'Заявка открыта повторно','[Ticket #{id}] {name}','<h3>Заявка № {id} была открыта заказчиком повторно</h3>\n<p>Название заявки:&nbsp;<strong>{name}</strong></p>\n<p>Заказчик:&nbsp;<strong>{fullname}</strong></p>\n<p>Вы должны приступить к работе до&nbsp;<strong>{StartTime}</strong></p>\n<p>Заявка должна быть исполнена до&nbsp;<strong>{EndTime}</strong></p>\n<p>Содержание:</p>\n<p>{content}</p><p>Ссылка на заявку {url}</p>',0),(18,'{registration}','Регистрация в системе Univef','<h3>Успешная регистрация в системе технической поддержки продукта Univef service desk.</h3>\n<p>Добрый день! Вы успешно зарегистрировались на портале&nbsp;технической поддержки Univef service desk, теперь вы можете:</p>\n<ul><li>оставлять заявки на поддержку и обслуживание;</li><li>видеть последние новости компании;</li>\n<li>получать самостоятельную помощь из опубликованных записей Базы знаний;</li></ul>\n<p>Ваш логин в системе: <strong>{login}</strong></p>\n<p>Ваш пароль в системе:<strong> {password}</strong></p>\n<p>Перейдите на портал технической поддержки и начните работу!</p>',1),(19,'Заявка приостановлена','[Ticket #{id}] {name}','<h3>Приостановлена работа над заявкой № {id} \"{name}\"</h3>\n<p>Заявка была приостановлена Исполнителем&nbsp;<strong>{manager_name},&nbsp;</strong>вы можете связаться с Исполнителем по телефону&nbsp;<strong>{manager_phone}</strong></p>\n<p><strong>Содержание заявки:</strong></p>\n<p>{content}</p>\n<p>Ссылка на заявку {url}</p>\n<p><strong>{comment_message}</strong></p><p></p>',0),(20,'{escalate}','[Ticket #{id}] {name}','<p>Произошла автоматическая эскалация заявки номер <b>{id}</b>&nbsp;<strong>{name}</strong></p><p>Был назначен новый исполнитель <b>{manager_name}</b>&nbsp;</p><p>Посмотреть заявку&nbsp;{url}</p>',2),(21,'{comments}','[Ticket #{id}] {name}','<p><strong>Добавлен новый комментарий:</strong></p><p>{date} {author} написал:&nbsp;</p><p>{comment}</p><p>Посмотреть заявку {url}</p><p>{comments_list}</p>',3),(22,'{escalate_group}','[Ticket #{id}] {name}','<p>Произошла эскалация заявки номер <b>{id}</b>&nbsp;<strong>{name}</strong></p><p>Была назначена группа исполнителей <b>{groupname}</b>&nbsp;</p><p>Посмотреть заявку&nbsp;{url}</p>',4);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
                        `id` int(10) NOT NULL AUTO_INCREMENT,
                        `author` varchar(50) DEFAULT NULL,
                        `name` varchar(50) DEFAULT NULL,
                        `content` text,
                        `date` varchar(50) DEFAULT NULL,
                        PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Администратор','Отключение электропитания c 15-00 до 18-00!','<p>\n  <strong>Уважаемые пользователи,  обращаем ваше внимание на то, что 25 декабря будет отключено электропитание с 15-00 по 18-00, будет недоступны все сетевые сервисы!</strong>\n</p>','27.02.2019 12:47');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phistory`
--

DROP TABLE IF EXISTS `phistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phistory` (
                            `id` int(10) NOT NULL AUTO_INCREMENT,
                            `pid` int(10) NOT NULL DEFAULT '0',
                            `date` varchar(50) DEFAULT NULL,
                            `user` varchar(50) DEFAULT NULL,
                            `action` text,
                            PRIMARY KEY (`id`),
                            KEY `pid` (`pid`),
                            CONSTRAINT `FK_phistory_problems` FOREIGN KEY (`pid`) REFERENCES `problems` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phistory`
--

LOCK TABLES `phistory` WRITE;
/*!40000 ALTER TABLE `phistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `phistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pipeline`
--

DROP TABLE IF EXISTS `pipeline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pipeline` (
                            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                            `name` varchar(200) DEFAULT NULL COMMENT 'Название',
                            `label` varchar(400) DEFAULT NULL COMMENT 'Ярлык',
                            `tag` varchar(50) DEFAULT NULL COMMENT 'Ярлык',
                            `send_email` tinyint(1) DEFAULT NULL COMMENT 'Отправить Email?',
                            `email_template` text COMMENT 'Шаблон Email сообщения',
                            `send_sms` tinyint(1) DEFAULT NULL COMMENT 'Отправить SMS?',
                            `sms_template` text COMMENT 'Шаблон SMS сообщения',
                            `create_task` tinyint(1) DEFAULT NULL COMMENT 'Создать задачу?',
                            `task_deadline` datetime DEFAULT NULL COMMENT 'Дата выполнения задачи',
                            `task_description` text COMMENT 'Описание задачи',
                            `close_deal` tinyint(1) DEFAULT NULL COMMENT 'Звершить сделку успешно',
                            `cancel_deal` tinyint(1) DEFAULT NULL COMMENT 'Звершить сделку неуспешно',
                            `sort_id` int(11) DEFAULT NULL,
                            PRIMARY KEY (`id`),
                            KEY `sort_id` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pipeline`
--

LOCK TABLES `pipeline` WRITE;
/*!40000 ALTER TABLE `pipeline` DISABLE KEYS */;
INSERT INTO `pipeline` VALUES (1,'Неразобранное','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #15bf63\">Неразобранное</span>','#15bf63',1,'<p>Здравствуйте {contact}!<br>Спасибо за обращение в нашу компанию! Ответственным менеджером по вашему обращению назначен&nbsp;{manager}.</p>',0,'Здравствуйте {contact}! Спасибо за обращение в нашу компанию! Ответственным менеджером по вашему обращению назначен {manager}.',0,'0000-00-00 00:00:00','',0,0,1),(2,'Первичный контакт','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #25cfcf\">Первичный контакт</span>','#25cfcf',0,'',0,'',0,'0000-00-00 00:00:00','',0,0,2),(3,'Принимают решение','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #1b77e3\">Принимают решение</span>','#1b77e3',0,'',0,'',0,'0000-00-00 00:00:00','',0,0,3),(5,'Завершена успешно','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #f78702\">Завершена успешно</span>','#f78702',0,'',0,'',0,'0000-00-00 00:00:00','',1,0,5),(6,'Завершена неуспешно','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #cc6666\">Завершена неуспешно</span>','#cc6666',0,'',0,'',0,'0000-00-00 00:00:00','',0,1,6);
/*!40000 ALTER TABLE `pipeline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problem_cats`
--

DROP TABLE IF EXISTS `problem_cats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem_cats` (
                                `id` int(10) NOT NULL AUTO_INCREMENT,
                                `name` varchar(100) DEFAULT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problem_cats`
--

LOCK TABLES `problem_cats` WRITE;
/*!40000 ALTER TABLE `problem_cats` DISABLE KEYS */;
INSERT INTO `problem_cats` VALUES (1,'Известные проблемы'),(2,'Новые проблемы');
/*!40000 ALTER TABLE `problem_cats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problem_files`
--

DROP TABLE IF EXISTS `problem_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem_files` (
                                 `problem_id` int(11) NOT NULL,
                                 `file_id` int(11) NOT NULL,
                                 PRIMARY KEY (`problem_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problem_files`
--

LOCK TABLES `problem_files` WRITE;
/*!40000 ALTER TABLE `problem_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `problem_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problems`
--

DROP TABLE IF EXISTS `problems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problems` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problems`
--

LOCK TABLES `problems` WRITE;
/*!40000 ALTER TABLE `problems` DISABLE KEYS */;
/*!40000 ALTER TABLE `problems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `psreport`
--

DROP TABLE IF EXISTS `psreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `psreport` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `psreport`
--

LOCK TABLES `psreport` WRITE;
/*!40000 ALTER TABLE `psreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `psreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pstatus`
--

DROP TABLE IF EXISTS `pstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pstatus` (
                           `id` int(10) NOT NULL AUTO_INCREMENT,
                           `name` varchar(50) DEFAULT NULL,
                           `label` varchar(500) DEFAULT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pstatus`
--

LOCK TABLES `pstatus` WRITE;
/*!40000 ALTER TABLE `pstatus` DISABLE KEYS */;
INSERT INTO `pstatus` VALUES (1,'Зарегистрирована','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Зарегистрирована</span>'),(2,'Обходное решение','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #5692bb; vertical-align: baseline; white-space: nowrap; border: 1px solid #5692bb; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Обходное решение</span>'),(3,'Решена','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #58595b; vertical-align: baseline; white-space: nowrap; border: 1px solid #58595b; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Решена</span>');
/*!40000 ALTER TABLE `pstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pureport`
--

DROP TABLE IF EXISTS `pureport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pureport` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pureport`
--

LOCK TABLES `pureport` WRITE;
/*!40000 ALTER TABLE `pureport` DISABLE KEYS */;
/*!40000 ALTER TABLE `pureport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pushs`
--

DROP TABLE IF EXISTS `pushs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pushs` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         `user_id` int(11) NOT NULL,
                         `notification` varchar(128) NOT NULL,
                         `url` varchar(128) NOT NULL,
                         PRIMARY KEY (`id`),
                         KEY `idx_pushs_user_id` (`user_id`),
                         KEY `idx_pushs_user_id_created_at` (`user_id`,`created_at`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pushs`
--

LOCK TABLES `pushs` WRITE;
/*!40000 ALTER TABLE `pushs` DISABLE KEYS */;
/*!40000 ALTER TABLE `pushs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reply_templates`
--

DROP TABLE IF EXISTS `reply_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reply_templates` (
                                   `id` int(11) NOT NULL AUTO_INCREMENT,
                                   `name` varchar(100) DEFAULT NULL,
                                   `content` text,
                                   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reply_templates`
--

LOCK TABLES `reply_templates` WRITE;
/*!40000 ALTER TABLE `reply_templates` DISABLE KEYS */;
INSERT INTO `reply_templates` VALUES (1,'Недостаточно информации для выполнения заявки','<p>\n\n   <strong>Уважаемый {fullname}, для выполнения вашей заявки №{id} недостаточно информации, уточните пожалуйста следующее:</strong>\n\n</p>');
/*!40000 ALTER TABLE `reply_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request` (
                           `id` int(10) NOT NULL AUTO_INCREMENT,
                           `pid` int(10) NOT NULL,
                           `child` varchar(500) DEFAULT NULL,
                           `channel` varchar(100) DEFAULT NULL,
                           `channel_icon` varchar(100) DEFAULT NULL,
                           `Name` varchar(100) DEFAULT NULL,
                           `Type` varchar(50) DEFAULT NULL,
                           `ZayavCategory_id` varchar(100) DEFAULT NULL,
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
                           `Address` varchar(200) DEFAULT NULL,
                           `company` varchar(100) DEFAULT NULL,
                           `Content` text,
                           `Comment` text,
                           `cunits` varchar(500) DEFAULT NULL,
                           `closed` varchar(50) DEFAULT NULL,
                           `service_id` int(10) DEFAULT NULL,
                           `service_name` varchar(250) DEFAULT NULL,
                           `image` varchar(250) DEFAULT NULL,
                           `timestamp` datetime DEFAULT NULL,
                           `timestampStart` datetime DEFAULT NULL,
                           `timestampfStart` datetime DEFAULT NULL,
                           `timestampEnd` datetime DEFAULT NULL,
                           `timestampfEnd` datetime DEFAULT NULL,
                           `fullname` varchar(100) DEFAULT NULL,
                           `mfullname` varchar(100) DEFAULT NULL,
                           `gfullname` varchar(100) DEFAULT NULL,
                           `depart` varchar(100) DEFAULT NULL,
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
                           `delayed_start` tinyint(1) unsigned DEFAULT '0',
                           `delayed_end` tinyint(1) unsigned DEFAULT '0',
                           `timestampClose` datetime DEFAULT NULL,
                           `delayedHours` int(11) unsigned DEFAULT '0',
                           `lastactivity` datetime DEFAULT NULL,
                           `tchat_id` varchar(100) DEFAULT NULL,
                           `viber_id` varchar(100) DEFAULT NULL,
                           `gr_id` int(10) DEFAULT NULL,
                           `jira` varchar(255) DEFAULT NULL,
                           `paused` datetime DEFAULT NULL,
                           `previous_paused_status_id` int(11) DEFAULT NULL,
                           `paused_total_time` int(11) DEFAULT '0',
                           `reopened` int(1) DEFAULT NULL,
                           `canceled` int(1) DEFAULT NULL,
                           `delayed` int(1) DEFAULT NULL,
                           `waspaused` int(1) DEFAULT NULL,
                           `wasautoclosed` int(1) DEFAULT NULL,
                           `wasescalated` int(1) DEFAULT NULL,
                           `sort_id` int(11) DEFAULT NULL,
                           `company_id` int(11) DEFAULT NULL,
                           `depart_id` int(11) DEFAULT NULL,
                           `msbot_id` varchar(255) DEFAULT NULL,
                           `msbot_params` text,
                           `service_category_id` int(11) DEFAULT NULL,
                           `creator_id` int(11) DEFAULT NULL,
                           `getmailconfig` varchar(50) DEFAULT NULL,
                           `wbot_id` varchar(255) DEFAULT NULL,
                           PRIMARY KEY (`id`),
                           KEY `CUsers_id` (`CUsers_id`),
                           KEY `FK_Zayavki_service` (`service_id`),
                           KEY `idx_gr_id` (`gr_id`),
                           KEY `idx_Managers_id` (`Managers_id`),
                           KEY `idx_company` (`company`),
                           KEY `idx_Status` (`Status`),
                           KEY `idx_timestamp` (`timestamp`),
                           KEY `idx_groups_id` (`groups_id`),
                           KEY `idx_rating` (`rating`),
                           KEY `idx_pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request`
--

LOCK TABLES `request` WRITE;
/*!40000 ALTER TABLE `request` DISABLE KEYS */;
INSERT INTO `request` VALUES (1,0,NULL,'Manual','iicon-pencil-line','Обслуживание внутренних пользователей компании',NULL,'Заявка на обслуживание','01.03.2021 14:35','01.03.2021 16:05',NULL,'01.03.2021 17:05',NULL,'Открыта','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Открыта</span>','Низкий',NULL,'user','+79001000000','205','Москва, ул. Пупырина, д.6, кв.777','Компания А','<p>Тестовая заявка с чек-листом</p>','',NULL,NULL,3,'Обслуживание внутренних клиентов',NULL,'2021-03-01 14:35:38','2021-03-01 16:05:00',NULL,'2021-03-01 17:05:00',NULL,'Кузнецов А. С.',NULL,'Первая линия поддержки','Отдел продаж','Администратор',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,0,1,'','0033af23be650e730a1372aaa8fcca6b',0,0,NULL,0,'2021-03-01 14:35:38',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,1,NULL,NULL),(2,0,NULL,'Manual','iicon-pencil-line','Выездное обслуживание клиентов',NULL,'Заявка на обслуживание','01.03.2021 14:36','01.03.2021 16:06',NULL,'01.03.2021 17:06',NULL,'Требует согласования','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #001f3f; vertical-align: baseline; white-space: nowrap; border: 1px solid #001f3f; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Требует согласования</span>','Низкий',NULL,'user','+79001000000','205','Москва, ул. Пупырина, д.6, кв.777','Компания А','<p>Тестовая заявка с согласованием</p>',NULL,NULL,NULL,2,'Обслуживание сторонних клиентов',NULL,'2021-03-01 14:36:03','2021-03-01 16:06:00',NULL,'2021-03-01 17:06:00',NULL,'Кузнецов А. С.',NULL,'Первая линия поддержки','Отдел продаж','Администратор',NULL,'1',NULL,NULL,NULL,NULL,0,NULL,0,1,'','095cdeb23d08e851b9de86ccf35f0dff',0,0,NULL,0,'2021-03-01 14:36:10',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_checklist_fields`
--

DROP TABLE IF EXISTS `request_checklist_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_checklist_fields` (
                                            `id` int(11) NOT NULL AUTO_INCREMENT,
                                            `request_id` int(11) NOT NULL,
                                            `checklist_field_id` int(11) NOT NULL,
                                            `checked` tinyint(1) NOT NULL DEFAULT '0',
                                            `sorting` int(11) NOT NULL DEFAULT '1',
                                            `checked_user_id` int(11) DEFAULT NULL,
                                            `checked_time` datetime DEFAULT NULL,
                                            PRIMARY KEY (`id`),
                                            KEY `request_checklist_fields_request_id_fk` (`request_id`),
                                            KEY `request_checklist_fields_checklist_field_id_fk` (`checklist_field_id`),
                                            KEY `request_checklist_fields_checked_user_id_fk` (`checked_user_id`),
                                            CONSTRAINT `request_checklist_fields_checked_user_id_fk` FOREIGN KEY (`checked_user_id`) REFERENCES `CUsers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                                            CONSTRAINT `request_checklist_fields_checklist_field_id_fk` FOREIGN KEY (`checklist_field_id`) REFERENCES `checklist_fields` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                                            CONSTRAINT `request_checklist_fields_request_id_fk` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_checklist_fields`
--

LOCK TABLES `request_checklist_fields` WRITE;
/*!40000 ALTER TABLE `request_checklist_fields` DISABLE KEYS */;
INSERT INTO `request_checklist_fields` VALUES (1,1,1,0,1,NULL,NULL),(2,1,2,0,2,NULL,NULL),(3,1,3,0,3,NULL,NULL),(4,1,4,0,4,NULL,NULL);
/*!40000 ALTER TABLE `request_checklist_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_escalates`
--

DROP TABLE IF EXISTS `request_escalates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_escalates` (
                                     `request_id` int(11) NOT NULL,
                                     `escalate_id` int(11) NOT NULL,
                                     PRIMARY KEY (`request_id`,`escalate_id`),
                                     KEY `idx_request_escalates_request_id` (`request_id`),
                                     KEY `idx_request_escalates_escalate_id` (`escalate_id`),
                                     CONSTRAINT `fk_request_escalates_escalate_id` FOREIGN KEY (`escalate_id`) REFERENCES `escalates` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                                     CONSTRAINT `fk_request_escalates_request_id` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_escalates`
--

LOCK TABLES `request_escalates` WRITE;
/*!40000 ALTER TABLE `request_escalates` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_escalates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_fields`
--

DROP TABLE IF EXISTS `request_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_fields` (
                                  `id` int(11) NOT NULL AUTO_INCREMENT,
                                  `rid` int(11) DEFAULT NULL,
                                  `name` varchar(64) NOT NULL,
                                  `type` enum('toggle','date','textFieldRow','select') NOT NULL,
                                  `value` varchar(500) DEFAULT NULL,
                                  `fid` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `rid` (`rid`),
                                  KEY `type` (`type`),
                                  KEY `value` (`value`(255)),
                                  CONSTRAINT `FK_request_fields_request` FOREIGN KEY (`rid`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_fields`
--

LOCK TABLES `request_fields` WRITE;
/*!40000 ALTER TABLE `request_fields` DISABLE KEYS */;
INSERT INTO `request_fields` VALUES (3,2,'Требуется выезд','toggle','1',1),(4,2,'Дата выезда','date','26.03.2021',2),(5,2,'Город','select','',10);
/*!40000 ALTER TABLE `request_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_files`
--

DROP TABLE IF EXISTS `request_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_files` (
                                 `request_id` int(11) NOT NULL,
                                 `file_id` int(11) NOT NULL,
                                 PRIMARY KEY (`request_id`,`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_files`
--

LOCK TABLES `request_files` WRITE;
/*!40000 ALTER TABLE `request_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_matching_reaction`
--

DROP TABLE IF EXISTS `request_matching_reaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_matching_reaction` (
                                             `id` int(11) NOT NULL AUTO_INCREMENT,
                                             `request_id` int(11) NOT NULL,
                                             `iteration` int(11) NOT NULL DEFAULT '0',
                                             `user_id` int(11) NOT NULL,
                                             `checked` tinyint(1) NOT NULL DEFAULT '0',
                                             `reaction_time` datetime DEFAULT NULL,
                                             PRIMARY KEY (`id`),
                                             UNIQUE KEY `request_matching_reaction_iteration_uniq` (`request_id`,`iteration`,`user_id`),
                                             KEY `request_matching_reaction_user_id_fk` (`user_id`),
                                             KEY `request_matching_reaction_checked_idx` (`checked`),
                                             KEY `request_matching_reaction_reaction_time_idx` (`reaction_time`),
                                             KEY `request_matching_reaction_iteration_idx` (`iteration`),
                                             KEY `request_matching_reaction_request_id_iteration_idx` (`request_id`,`iteration`),
                                             CONSTRAINT `request_matching_reaction_request_id_fk` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                                             CONSTRAINT `request_matching_reaction_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `CUsers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_matching_reaction`
--

LOCK TABLES `request_matching_reaction` WRITE;
/*!40000 ALTER TABLE `request_matching_reaction` DISABLE KEYS */;
INSERT INTO `request_matching_reaction` VALUES (1,2,1,1,0,NULL);
/*!40000 ALTER TABLE `request_matching_reaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_processing_rule_actions`
--

DROP TABLE IF EXISTS `request_processing_rule_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_processing_rule_actions` (
                                                   `id` int(11) NOT NULL AUTO_INCREMENT,
                                                   `request_processing_rule_id` int(11) NOT NULL,
                                                   `target` tinyint(3) unsigned NOT NULL,
                                                   `val` varchar(255) NOT NULL,
                                                   PRIMARY KEY (`id`),
                                                   KEY `request_processing_rule_actions_request_processing_rule_id_fk` (`request_processing_rule_id`),
                                                   CONSTRAINT `request_processing_rule_actions_request_processing_rule_id_fk` FOREIGN KEY (`request_processing_rule_id`) REFERENCES `request_processing_rules` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_processing_rule_actions`
--

LOCK TABLES `request_processing_rule_actions` WRITE;
/*!40000 ALTER TABLE `request_processing_rule_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_processing_rule_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_processing_rule_conditions`
--

DROP TABLE IF EXISTS `request_processing_rule_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_processing_rule_conditions` (
                                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                                      `request_processing_rule_id` int(11) NOT NULL,
                                                      `val` varchar(255) NOT NULL,
                                                      `target` tinyint(3) unsigned NOT NULL,
                                                      `condition` tinyint(3) unsigned NOT NULL,
                                                      PRIMARY KEY (`id`),
                                                      KEY `request_processing_rule_conditions_request_processing_rule_id_fk` (`request_processing_rule_id`),
                                                      CONSTRAINT `request_processing_rule_conditions_request_processing_rule_id_fk` FOREIGN KEY (`request_processing_rule_id`) REFERENCES `request_processing_rules` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_processing_rule_conditions`
--

LOCK TABLES `request_processing_rule_conditions` WRITE;
/*!40000 ALTER TABLE `request_processing_rule_conditions` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_processing_rule_conditions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_processing_rules`
--

DROP TABLE IF EXISTS `request_processing_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `request_processing_rules` (
                                            `id` int(11) NOT NULL AUTO_INCREMENT,
                                            `name` varchar(500) NOT NULL,
                                            `is_all_match` tinyint(1) NOT NULL DEFAULT '0',
                                            `is_apply_to_bots` tinyint(1) NOT NULL DEFAULT '0',
                                            `creator_id` int(11) NOT NULL,
                                            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                            PRIMARY KEY (`id`),
                                            KEY `request_processing_rules_creator_id_fk` (`creator_id`),
                                            CONSTRAINT `request_processing_rules_creator_id_fk` FOREIGN KEY (`creator_id`) REFERENCES `CUsers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_processing_rules`
--

LOCK TABLES `request_processing_rules` WRITE;
/*!40000 ALTER TABLE `request_processing_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_processing_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
                         `id` int(1) NOT NULL AUTO_INCREMENT,
                         `name` varchar(50) NOT NULL,
                         `value` varchar(50) NOT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Администратор','univefadmin'),(2,'Пользователь','univefuser'),(3,'Исполнитель','univefmanager');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles_rights`
--

DROP TABLE IF EXISTS `roles_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles_rights` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `rid` int(11) DEFAULT NULL,
                                `rname` varchar(70) DEFAULT NULL,
                                `name` varchar(70) DEFAULT NULL,
                                `description` varchar(100) DEFAULT NULL,
                                `value` int(11) DEFAULT NULL,
                                `category` varchar(70) DEFAULT NULL,
                                PRIMARY KEY (`id`),
                                KEY `rid` (`rid`),
                                CONSTRAINT `FK_roles_rights_roles` FOREIGN KEY (`rid`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=880 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles_rights`
--

LOCK TABLES `roles_rights` WRITE;
/*!40000 ALTER TABLE `roles_rights` DISABLE KEYS */;
INSERT INTO `roles_rights` VALUES (1,1,'Администратор','systemUser','Системная роль Пользователь',0,'Системная роль'),(2,1,'Администратор','systemManager','Системная роль Исполнитель',0,'Системная роль'),(3,1,'Администратор','systemAdmin','Системная роль Администратор',1,'Системная роль'),(4,1,'Администратор','createRequest','Cоздавать заявки',1,'Заявка'),(5,1,'Администратор','updateRequest','Редактировать заявки',1,'Заявка'),(6,1,'Администратор','viewRequest','Просмотр заявок',1,'Заявка'),(7,1,'Администратор','listRequest','Отображать список заявок',1,'Заявка'),(8,1,'Администратор','deleteRequest','Удаление заявок',1,'Заявка'),(9,1,'Администратор','batchUpdateRequest','Массовое закрытие заявок',1,'Заявка'),(10,1,'Администратор','batchDeleteRequest','Массовое удаление заявок',1,'Заявка'),(11,1,'Администратор','uploadFilesRequest','Пользователь может прикреплять файлы к заявке',1,'Заявка'),(12,1,'Администратор','viewMyselfRequest','Пользователь видит только свои заявки',0,'Заявка'),(14,1,'Администратор','updateDatesRequest','Пользователь может редактировать сроки дедлайнов заявок',1,'Заявка'),(15,1,'Администратор','canAssignRequest','Исполнитель может назначать заявку другому исполнителю',1,'Заявка'),(16,1,'Администратор','viewHistoryRequest','Пользователь может видеть историю заявки',1,'Заявка'),(17,1,'Администратор','canSetUnitRequest','Пользователь может выбирать КЕ в форме заявки',1,'Заявка'),(18,1,'Администратор','canSetObserversRequest','Пользователь может выбирать наблюдателей в форме заявки',1,'Заявка'),(19,1,'Администратор','canSetFieldsRequest','Пользователь может заполнять наборы полей в форме заявки',1,'Заявка'),(20,1,'Администратор','createProblem','Создавать проблемы',0,'Проблема'),(21,1,'Администратор','viewProblem','Просмотр проблем',0,'Проблема'),(22,1,'Администратор','listProblem','Отображать список проблем',0,'Проблема'),(23,1,'Администратор','updateProblem','Редактировать проблемы',0,'Проблема'),(24,1,'Администратор','deleteProblem','Удалять проблемы',0,'Проблема'),(25,1,'Администратор','canAssignProblem','Исполнитель может назначать проблему другому исполнителю',0,'Проблема'),(26,1,'Администратор','uploadFilesProblem','Пользователь может прикреплять файлы к проблеме',0,'Проблема'),(27,1,'Администратор','batchUpdateProblem','Массовое закрытие проблем',0,'Проблема'),(28,1,'Администратор','batchDeleteProblem','Массовое удаление проблем',0,'Проблема'),(29,1,'Администратор','viewHistoryProblem','Пользователь может видеть историю проблемы',0,'Проблема'),(30,1,'Администратор','createService','Создавать сервисы',1,'Сервис'),(31,1,'Администратор','viewService','Просмотр сервисов',1,'Сервис'),(32,1,'Администратор','listService','Отображать список сервисов',1,'Сервис'),(33,1,'Администратор','updateService','Редактировать сервисы',1,'Сервис'),(34,1,'Администратор','deleteService','Удалять сервисы',1,'Сервис'),(35,1,'Администратор','createSla','Создавать уровни сервиса',1,'Sla'),(36,1,'Администратор','viewSla','Просмотр уровней сервиса',1,'Sla'),(37,1,'Администратор','listSla','Отображать список уровней сервисов',1,'Sla'),(38,1,'Администратор','updateSla','Редактировать уровни сервисов',1,'Sla'),(39,1,'Администратор','deleteSla','Удалять уровни сервиса',1,'Sla'),(40,1,'Администратор','createAsset','Создавать активы',1,'Актив'),(41,1,'Администратор','viewAsset','Просматривать активы',1,'Актив'),(42,1,'Администратор','listAsset','Отображать список активов',1,'Актив'),(43,1,'Администратор','updateAsset','Редактировать активы',1,'Актив'),(44,1,'Администратор','deleteAsset','Удалить активы',1,'Актив'),(45,1,'Администратор','exportAsset','Экспортировать список активов',1,'Актив'),(46,1,'Администратор','printAsset','Распечатывать карточку актива',1,'Актив'),(47,1,'Администратор','createAssetType','Создавать типы активов',1,'Тип актива'),(48,1,'Администратор','listAssetType','Отображать список типов актива',1,'Тип актива'),(49,1,'Администратор','updateAssetType','Редактировать типы актива',1,'Тип актива'),(50,1,'Администратор','deleteAssetType','Удалить типы актива',1,'Тип актива'),(51,1,'Администратор','createUnit','Создавать КЕ',1,'КЕ'),(52,1,'Администратор','viewUnit','Просматривать КЕ',1,'КЕ'),(53,1,'Администратор','listUnit','Отображать список КЕ',1,'КЕ'),(54,1,'Администратор','updateUnit','Редактировать КЕ',1,'КЕ'),(55,1,'Администратор','deleteUnit','Удалять КЕ',1,'КЕ'),(56,1,'Администратор','exportUnit','Экспортировать список КЕ',1,'КЕ'),(57,1,'Администратор','printUnit','Печать карточки КЕ',1,'КЕ'),(58,1,'Администратор','viewMyselfUnit','Пользователь видит только свои КЕ',0,'КЕ'),(59,1,'Администратор','createUnitType','Создавать типы КЕ',1,'Типы КЕ'),(60,1,'Администратор','listUnitType','Отображать список типов КЕ',1,'Типы КЕ'),(61,1,'Администратор','updateUnitType','Редактировать типы КЕ',1,'Типы КЕ'),(62,1,'Администратор','deleteUnitType','Удалять типы КЕ',1,'Типы КЕ'),(63,1,'Администратор','createKB','Создавать записи Базы знаний',1,'База знаний'),(64,1,'Администратор','viewKB','Просматривать записи Базы знаний',1,'База знаний'),(65,1,'Администратор','listKB','Отображать список Базы знаний',1,'База знаний'),(66,1,'Администратор','updateKB','Редактировать записи Базы знаний',1,'База знаний'),(67,1,'Администратор','deleteKB','Удалять записи Базы знаний',1,'База знаний'),(68,1,'Администратор','uploadFilesKB','Пользователь может прикреплять файлы к записи Базы знаний',1,'База знаний'),(69,1,'Администратор','createKBCat','Создавать категории Базы знаний',1,'Категории базы знаний'),(70,1,'Администратор','listKBCat','Отображать список категорий Базы знаний',1,'Категории базы знаний'),(71,1,'Администратор','updateKBCat','Редактировать категории Базы знаний',1,'Категории базы знаний'),(72,1,'Администратор','deleteKBCat','Удалять категории Базы знаний',1,'Категории базы знаний'),(73,1,'Администратор','createNews','Создать новость',1,'Новости'),(74,1,'Администратор','viewNews','Просматривать новости',1,'Новости'),(75,1,'Администратор','listNews','Отображать список новостей',1,'Новости'),(76,1,'Администратор','updateNews','Редактировать новости',1,'Новости'),(77,1,'Администратор','deleteNews','Удалять новости',1,'Новости'),(78,1,'Администратор','createUser','Создавать пользователей',1,'Пользователь'),(79,1,'Администратор','viewUser','Просматривать пользователей',1,'Пользователь'),(80,1,'Администратор','listUser','Отображать список пользователей',1,'Пользователь'),(81,1,'Администратор','updateUser','Редактировать пользователей',1,'Пользователь'),(82,1,'Администратор','deleteUser','Удалить пользователей',1,'Пользователь'),(83,1,'Администратор','exportUser','Экспортировать список пользователей',1,'Пользователь'),(84,1,'Администратор','createCompany','Создавать компании',1,'Компания'),(85,1,'Администратор','viewCompany','Просматривать компании',1,'Компания'),(86,1,'Администратор','listCompany','Отображать список компаний',1,'Компания'),(87,1,'Администратор','updateCompany','Редактировать компании',1,'Компания'),(88,1,'Администратор','deleteCompany','Удалять компании',1,'Компания'),(89,1,'Администратор','createDepart','Создавать подразделения',1,'Подразделение'),(90,1,'Администратор','listDepart','Отображать список подразделений',1,'Подразделение'),(91,1,'Администратор','updateDepart','Редактировать подразделения',1,'Подразделение'),(92,1,'Администратор','deleteDepart','Удалять подразделения',1,'Подразделение'),(93,1,'Администратор','createGroup','Создавать группы',1,'Группа исполнителей'),(94,1,'Администратор','listGroup','Отображать список групп',1,'Группа исполнителей'),(95,1,'Администратор','updateGroup','Редактировать группы',1,'Группа исполнителей'),(96,1,'Администратор','deleteGroup','Удалять группы',1,'Группа исполнителей'),(97,1,'Администратор','createPriority','Создавать приоритеты',1,'Приоритет'),(98,1,'Администратор','listPriority','Отображать список приоритетов',1,'Приоритет'),(99,1,'Администратор','updatePriority','Редактировать приоритеты',1,'Приоритет'),(100,1,'Администратор','deletePriority','Удалять приоритеты',1,'Приоритет'),(101,1,'Администратор','createStatus','Создавать статусы',1,'Статус'),(102,1,'Администратор','listStatus','Отображать список статусов',1,'Статус'),(103,1,'Администратор','updateStatus','Редактировать статусы',1,'Статус'),(104,1,'Администратор','deleteStatus','Удалять статусы',1,'Статус'),(105,1,'Администратор','createCategory','Создать категории заявок',1,'Категория'),(106,1,'Администратор','listCategory','Отображать список категорий заявок',1,'Категория'),(107,1,'Администратор','updateCategory','Редактировать категории заявок',1,'Категория'),(108,1,'Администратор','deleteCategory','Удаление категорий заявок',1,'Категория'),(109,1,'Администратор','createETemplate','Создать E-mail шаблон',1,'Шаблоны E-mail уведомлений'),(110,1,'Администратор','viewETemplate','Просматривать Email шаблоны',1,'Шаблоны E-mail уведомлений'),(111,1,'Администратор','listETemplate','Отображать список Email шаблонов',1,'Шаблоны E-mail уведомлений'),(112,1,'Администратор','updateETemplate','Редактировать Email шаблоны',1,'Шаблоны E-mail уведомлений'),(113,1,'Администратор','deleteETemplate','Удалять Email шаблоны',1,'Шаблоны E-mail уведомлений'),(114,1,'Администратор','createSTemplate','Создать SMS шаблон',1,'Шаблоны SMS уведомлений'),(115,1,'Администратор','viewSTemplate','Просматривать SMS шаблоны',1,'Шаблоны SMS уведомлений'),(116,1,'Администратор','listSTemplate','Отображать список SMS шаблонов',1,'Шаблоны SMS уведомлений'),(117,1,'Администратор','updateSTemplate','Редактировать SMS шаблоны',1,'Шаблоны SMS уведомлений'),(118,1,'Администратор','deleteSTemplate','Удалять SMS шаблоны',1,'Шаблоны SMS уведомлений'),(119,1,'Администратор','createFieldsets','Создавать наборы полей',1,'Наборы полей'),(120,1,'Администратор','listFieldsets','Отображать наборы полей',1,'Наборы полей'),(121,1,'Администратор','updateFieldsets','Редактировать наборы полей',1,'Наборы полей'),(122,1,'Администратор','deleteFieldsets','Удалять наборы полей',1,'Наборы полей'),(123,1,'Администратор','usersReport','Доступ к отчету Заявки по заявителям',1,'Отчеты'),(124,1,'Администратор','companiesReport','Доступ к отчету Заявки по компаниям',1,'Отчеты'),(125,1,'Администратор','managersReport','Доступ к отчету Заявки по менеджерам',1,'Отчеты'),(126,1,'Администратор','serviceReport','Доступ к отчету Заявки по сервисам',1,'Отчеты'),(127,1,'Администратор','assetReport','Доступ к отчету Заявки по КЕ',1,'Отчеты'),(128,1,'Администратор','unitProblemReport','Доступ к отчету Проблемы по КЕ',0,'Отчеты'),(129,1,'Администратор','monthServiceProblemReport','Доступ к отчету Проблемы по сервисам за месяц',0,'Отчеты'),(130,1,'Администратор','serviceProblemReport','Доступ к отчету Проблемы по сервисам',0,'Отчеты'),(131,1,'Администратор','unitSProblemReport','Доступ к отчету Сводный отчет по КЕ',1,'Отчеты'),(132,1,'Администратор','rolesSettings','Доступ к управлению ролями',1,'Настройки'),(133,1,'Администратор','mainSettings','Доступ к основным настройкам',1,'Настройки'),(134,1,'Администратор','mailParserSettings','Доступ к настройкам парсера почты',1,'Настройки'),(135,1,'Администратор','adSettings','Доступ к настройкам интеграции с AD',1,'Настройки'),(136,1,'Администратор','smsSettings','Доступ к настройкам SMS шлюза',1,'Настройки'),(137,1,'Администратор','ticketSettings','Доступ к настройкам заявки по умолчанию',1,'Настройки'),(138,1,'Администратор','attachSettings','Доступ к настройкам вложений',1,'Настройки'),(139,1,'Администратор','appearSettings','Доступ к настройкам внешнего вида',1,'Настройки'),(140,1,'Администратор','shedulerSettings','Доступ к настройкам планировщика задач',1,'Настройки'),(141,1,'Администратор','logSettings','Доступ к анализатору лога',1,'Настройки'),(142,1,'Администратор','backupSettings','Доступ к резервному копированию',1,'Настройки'),(143,1,'Администратор','importSettings','Импорт из CSV',1,'Настройки'),(144,1,'Администратор','showTicketGraph','Отображать график заявок на главной панели',1,'Графики'),(145,1,'Администратор','showProblemGraph','Отображать график проблем на главной панели',1,'Графики'),(146,1,'Администратор','showlastNews','Отображать список последних новостей на главной панели',1,'Интерфейс'),(147,1,'Администратор','showlastKB','Отображать список последних записей Базы знаний на главной панели',1,'Интерфейс'),(148,1,'Администратор','showSearchKB','Отображать строку поиска по Базе знаний',0,'Интерфейс'),(149,2,'Пользователь','systemUser','Системная роль Пользователь',1,'Системная роль'),(150,2,'Пользователь','systemManager','Системная роль Исполнитель',0,'Системная роль'),(151,2,'Пользователь','systemAdmin','Системная роль Администратор',0,'Системная роль'),(152,2,'Пользователь','createRequest','Cоздавать заявки',1,'Заявка'),(153,2,'Пользователь','updateRequest','Редактировать заявки',1,'Заявка'),(154,2,'Пользователь','viewRequest','Просмотр заявок',1,'Заявка'),(155,2,'Пользователь','listRequest','Отображать список заявок',1,'Заявка'),(156,2,'Пользователь','deleteRequest','Удаление заявок',0,'Заявка'),(157,2,'Пользователь','batchUpdateRequest','Массовое закрытие заявок',1,'Заявка'),(158,2,'Пользователь','batchDeleteRequest','Массовое удаление заявок',0,'Заявка'),(159,2,'Пользователь','uploadFilesRequest','Пользователь может прикреплять файлы к заявке',1,'Заявка'),(160,2,'Пользователь','viewMyselfRequest','Пользователь видит только свои заявки',1,'Заявка'),(162,2,'Пользователь','updateDatesRequest','Пользователь может редактировать сроки дедлайнов заявок',0,'Заявка'),(163,2,'Пользователь','canAssignRequest','Исполнитель может назначать заявку другому исполнителю',0,'Заявка'),(164,2,'Пользователь','viewHistoryRequest','Пользователь может видеть историю заявки',0,'Заявка'),(165,2,'Пользователь','canSetUnitRequest','Пользователь может выбирать КЕ в форме заявки',1,'Заявка'),(166,2,'Пользователь','canSetObserversRequest','Пользователь может выбирать наблюдателей в форме заявки',1,'Заявка'),(167,2,'Пользователь','canSetFieldsRequest','Пользователь может заполнять наборы полей в форме заявки',1,'Заявка'),(168,2,'Пользователь','createProblem','Создавать проблемы',0,'Проблема'),(169,2,'Пользователь','viewProblem','Просмотр проблем',0,'Проблема'),(170,2,'Пользователь','listProblem','Отображать список проблем',0,'Проблема'),(171,2,'Пользователь','updateProblem','Редактировать проблемы',0,'Проблема'),(172,2,'Пользователь','deleteProblem','Удалять проблемы',0,'Проблема'),(173,2,'Пользователь','canAssignProblem','Исполнитель может назначать проблему другому исполнителю',0,'Проблема'),(174,2,'Пользователь','uploadFilesProblem','Пользователь может прикреплять файлы к проблеме',0,'Проблема'),(175,2,'Пользователь','batchUpdateProblem','Массовое закрытие проблем',0,'Проблема'),(176,2,'Пользователь','batchDeleteProblem','Массовое удаление проблем',0,'Проблема'),(177,2,'Пользователь','viewHistoryProblem','Пользователь может видеть историю проблемы',0,'Проблема'),(178,2,'Пользователь','createService','Создавать сервисы',0,'Сервис'),(179,2,'Пользователь','viewService','Просмотр сервисов',0,'Сервис'),(180,2,'Пользователь','listService','Отображать список сервисов',0,'Сервис'),(181,2,'Пользователь','updateService','Редактировать сервисы',0,'Сервис'),(182,2,'Пользователь','deleteService','Удалять сервисы',0,'Сервис'),(183,2,'Пользователь','createSla','Создавать уровни сервиса',0,'Sla'),(184,2,'Пользователь','viewSla','Просмотр уровней сервиса',0,'Sla'),(185,2,'Пользователь','listSla','Отображать список уровней сервисов',0,'Sla'),(186,2,'Пользователь','updateSla','Редактировать уровни сервисов',0,'Sla'),(187,2,'Пользователь','deleteSla','Удалять уровни сервиса',0,'Sla'),(188,2,'Пользователь','createAsset','Создавать активы',0,'Актив'),(189,2,'Пользователь','viewAsset','Просматривать активы',1,'Актив'),(190,2,'Пользователь','listAsset','Отображать список активов',0,'Актив'),(191,2,'Пользователь','updateAsset','Редактировать активы',0,'Актив'),(192,2,'Пользователь','deleteAsset','Удалить активы',0,'Актив'),(193,2,'Пользователь','exportAsset','Экспортировать список активов',0,'Актив'),(194,2,'Пользователь','printAsset','Распечатывать карточку актива',0,'Актив'),(195,2,'Пользователь','createAssetType','Создавать типы активов',0,'Тип актива'),(196,2,'Пользователь','listAssetType','Отображать список типов актива',0,'Тип актива'),(197,2,'Пользователь','updateAssetType','Редактировать типы актива',0,'Тип актива'),(198,2,'Пользователь','deleteAssetType','Удалить типы актива',0,'Тип актива'),(199,2,'Пользователь','createUnit','Создавать КЕ',0,'КЕ'),(200,2,'Пользователь','viewUnit','Просматривать КЕ',1,'КЕ'),(201,2,'Пользователь','listUnit','Отображать список КЕ',0,'КЕ'),(202,2,'Пользователь','updateUnit','Редактировать КЕ',0,'КЕ'),(203,2,'Пользователь','deleteUnit','Удалять КЕ',0,'КЕ'),(204,2,'Пользователь','exportUnit','Экспортировать список КЕ',0,'КЕ'),(205,2,'Пользователь','printUnit','Печать карточки КЕ',0,'КЕ'),(206,2,'Пользователь','viewMyselfUnit','Пользователь видит только свои КЕ',1,'КЕ'),(207,2,'Пользователь','createUnitType','Создавать типы КЕ',0,'Типы КЕ'),(208,2,'Пользователь','listUnitType','Отображать список типов КЕ',0,'Типы КЕ'),(209,2,'Пользователь','updateUnitType','Редактировать типы КЕ',0,'Типы КЕ'),(210,2,'Пользователь','deleteUnitType','Удалять типы КЕ',0,'Типы КЕ'),(211,2,'Пользователь','createKB','Создавать записи Базы знаний',0,'База знаний'),(212,2,'Пользователь','viewKB','Просматривать записи Базы знаний',1,'База знаний'),(213,2,'Пользователь','listKB','Отображать список Базы знаний',1,'База знаний'),(214,2,'Пользователь','updateKB','Редактировать записи Базы знаний',0,'База знаний'),(215,2,'Пользователь','deleteKB','Удалять записи Базы знаний',0,'База знаний'),(216,2,'Пользователь','uploadFilesKB','Пользователь может прикреплять файлы к записи Базы знаний',0,'База знаний'),(217,2,'Пользователь','createKBCat','Создавать категории Базы знаний',0,'Категории базы знаний'),(218,2,'Пользователь','listKBCat','Отображать список категорий Базы знаний',0,'Категории базы знаний'),(219,2,'Пользователь','updateKBCat','Редактировать категории Базы знаний',0,'Категории базы знаний'),(220,2,'Пользователь','deleteKBCat','Удалять категории Базы знаний',0,'Категории базы знаний'),(221,2,'Пользователь','createNews','Создать новость',0,'Новости'),(222,2,'Пользователь','viewNews','Просматривать новости',1,'Новости'),(223,2,'Пользователь','listNews','Отображать список новостей',1,'Новости'),(224,2,'Пользователь','updateNews','Редактировать новости',0,'Новости'),(225,2,'Пользователь','deleteNews','Удалять новости',0,'Новости'),(226,2,'Пользователь','createUser','Создавать пользователей',0,'Пользователь'),(227,2,'Пользователь','viewUser','Просматривать пользователей',1,'Пользователь'),(228,2,'Пользователь','listUser','Отображать список пользователей',1,'Пользователь'),(229,2,'Пользователь','updateUser','Редактировать пользователей',1,'Пользователь'),(230,2,'Пользователь','deleteUser','Удалить пользователей',0,'Пользователь'),(231,2,'Пользователь','exportUser','Экспортировать список пользователей',0,'Пользователь'),(232,2,'Пользователь','createCompany','Создавать компании',0,'Компания'),(233,2,'Пользователь','viewCompany','Просматривать компании',0,'Компания'),(234,2,'Пользователь','listCompany','Отображать список компаний',0,'Компания'),(235,2,'Пользователь','updateCompany','Редактировать компании',0,'Компания'),(236,2,'Пользователь','deleteCompany','Удалять компании',0,'Компания'),(237,2,'Пользователь','createDepart','Создавать подразделения',0,'Подразделение'),(238,2,'Пользователь','listDepart','Отображать список подразделений',0,'Подразделение'),(239,2,'Пользователь','updateDepart','Редактировать подразделения',0,'Подразделение'),(240,2,'Пользователь','deleteDepart','Удалять подразделения',0,'Подразделение'),(241,2,'Пользователь','createGroup','Создавать группы',0,'Группа исполнителей'),(242,2,'Пользователь','listGroup','Отображать список групп',0,'Группа исполнителей'),(243,2,'Пользователь','updateGroup','Редактировать группы',0,'Группа исполнителей'),(244,2,'Пользователь','deleteGroup','Удалять группы',0,'Группа исполнителей'),(245,2,'Пользователь','createPriority','Создавать приоритеты',0,'Приоритет'),(246,2,'Пользователь','listPriority','Отображать список приоритетов',0,'Приоритет'),(247,2,'Пользователь','updatePriority','Редактировать приоритеты',0,'Приоритет'),(248,2,'Пользователь','deletePriority','Удалять приоритеты',0,'Приоритет'),(249,2,'Пользователь','createStatus','Создавать статусы',0,'Статус'),(250,2,'Пользователь','listStatus','Отображать список статусов',0,'Статус'),(251,2,'Пользователь','updateStatus','Редактировать статусы',0,'Статус'),(252,2,'Пользователь','deleteStatus','Удалять статусы',0,'Статус'),(253,2,'Пользователь','createCategory','Создать категории заявок',0,'Категория'),(254,2,'Пользователь','listCategory','Отображать список категорий заявок',0,'Категория'),(255,2,'Пользователь','updateCategory','Редактировать категории заявок',0,'Категория'),(256,2,'Пользователь','deleteCategory','Удаление категорий заявок',0,'Категория'),(257,2,'Пользователь','createETemplate','Создать E-mail шаблон',0,'Шаблоны E-mail уведомлений'),(258,2,'Пользователь','viewETemplate','Просматривать Email шаблоны',0,'Шаблоны E-mail уведомлений'),(259,2,'Пользователь','listETemplate','Отображать список Email шаблонов',0,'Шаблоны E-mail уведомлений'),(260,2,'Пользователь','updateETemplate','Редактировать Email шаблоны',0,'Шаблоны E-mail уведомлений'),(261,2,'Пользователь','deleteETemplate','Удалять Email шаблоны',0,'Шаблоны E-mail уведомлений'),(262,2,'Пользователь','createSTemplate','Создать SMS шаблон',0,'Шаблоны SMS уведомлений'),(263,2,'Пользователь','viewSTemplate','Просматривать SMS шаблоны',0,'Шаблоны SMS уведомлений'),(264,2,'Пользователь','listSTemplate','Отображать список SMS шаблонов',0,'Шаблоны SMS уведомлений'),(265,2,'Пользователь','updateSTemplate','Редактировать SMS шаблоны',0,'Шаблоны SMS уведомлений'),(266,2,'Пользователь','deleteSTemplate','Удалять SMS шаблоны',0,'Шаблоны SMS уведомлений'),(267,2,'Пользователь','createFieldsets','Создавать наборы полей',0,'Наборы полей'),(268,2,'Пользователь','listFieldsets','Отображать наборы полей',0,'Наборы полей'),(269,2,'Пользователь','updateFieldsets','Редактировать наборы полей',0,'Наборы полей'),(270,2,'Пользователь','deleteFieldsets','Удалять наборы полей',0,'Наборы полей'),(271,2,'Пользователь','usersReport','Доступ к отчету Заявки по заявителям',0,'Отчеты'),(272,2,'Пользователь','companiesReport','Доступ к отчету Заявки по компаниям',0,'Отчеты'),(273,2,'Пользователь','managersReport','Доступ к отчету Заявки по менеджерам',0,'Отчеты'),(274,2,'Пользователь','serviceReport','Доступ к отчету Заявки по сервисам',0,'Отчеты'),(275,2,'Пользователь','assetReport','Доступ к отчету Заявки по КЕ',0,'Отчеты'),(276,2,'Пользователь','unitProblemReport','Доступ к отчету Проблемы по КЕ',0,'Отчеты'),(277,2,'Пользователь','monthServiceProblemReport','Доступ к отчету Проблемы по сервисам за месяц',0,'Отчеты'),(278,2,'Пользователь','serviceProblemReport','Доступ к отчету Проблемы по сервисам',0,'Отчеты'),(279,2,'Пользователь','unitSProblemReport','Доступ к отчету Сводный отчет по КЕ',0,'Отчеты'),(280,2,'Пользователь','rolesSettings','Доступ к управлению ролями',0,'Настройки'),(281,2,'Пользователь','mainSettings','Доступ к основным настройкам',0,'Настройки'),(282,2,'Пользователь','mailParserSettings','Доступ к настройкам парсера почты',0,'Настройки'),(283,2,'Пользователь','adSettings','Доступ к настройкам интеграции с AD',0,'Настройки'),(284,2,'Пользователь','smsSettings','Доступ к настройкам SMS шлюза',0,'Настройки'),(285,2,'Пользователь','ticketSettings','Доступ к настройкам заявки по умолчанию',0,'Настройки'),(286,2,'Пользователь','attachSettings','Доступ к настройкам вложений',0,'Настройки'),(287,2,'Пользователь','appearSettings','Доступ к настройкам внешнего вида',0,'Настройки'),(288,2,'Пользователь','shedulerSettings','Доступ к настройкам планировщика задач',0,'Настройки'),(289,2,'Пользователь','logSettings','Доступ к анализатору лога',0,'Настройки'),(290,2,'Пользователь','backupSettings','Доступ к резервному копированию',0,'Настройки'),(291,2,'Пользователь','importSettings','Импорт из CSV',0,'Настройки'),(292,2,'Пользователь','showTicketGraph','Отображать график заявок на главной панели',0,'Графики'),(293,2,'Пользователь','showProblemGraph','Отображать график проблем на главной панели',0,'Графики'),(294,2,'Пользователь','showlastNews','Отображать список последних новостей на главной панели',1,'Интерфейс'),(295,2,'Пользователь','showlastKB','Отображать список последних записей Базы знаний на главной панели',1,'Интерфейс'),(296,2,'Пользователь','showSearchKB','Отображать строку поиска по Базе знаний',1,'Интерфейс'),(297,3,'Исполнитель','systemUser','Системная роль Пользователь',0,'Системная роль'),(298,3,'Исполнитель','systemManager','Системная роль Исполнитель',1,'Системная роль'),(299,3,'Исполнитель','systemAdmin','Системная роль Администратор',0,'Системная роль'),(300,3,'Исполнитель','createRequest','Cоздавать заявки',1,'Заявка'),(301,3,'Исполнитель','updateRequest','Редактировать заявки',1,'Заявка'),(302,3,'Исполнитель','viewRequest','Просмотр заявок',1,'Заявка'),(303,3,'Исполнитель','listRequest','Отображать список заявок',1,'Заявка'),(304,3,'Исполнитель','deleteRequest','Удаление заявок',0,'Заявка'),(305,3,'Исполнитель','batchUpdateRequest','Массовое закрытие заявок',1,'Заявка'),(306,3,'Исполнитель','batchDeleteRequest','Массовое удаление заявок',0,'Заявка'),(307,3,'Исполнитель','uploadFilesRequest','Пользователь может прикреплять файлы к заявке',1,'Заявка'),(308,3,'Исполнитель','viewMyselfRequest','Пользователь видит только свои заявки',0,'Заявка'),(310,3,'Исполнитель','updateDatesRequest','Пользователь может редактировать сроки дедлайнов заявок',0,'Заявка'),(311,3,'Исполнитель','canAssignRequest','Исполнитель может назначать заявку другому исполнителю',1,'Заявка'),(312,3,'Исполнитель','viewHistoryRequest','Пользователь может видеть историю заявки',0,'Заявка'),(313,3,'Исполнитель','canSetUnitRequest','Пользователь может выбирать КЕ в форме заявки',1,'Заявка'),(314,3,'Исполнитель','canSetObserversRequest','Пользователь может выбирать наблюдателей в форме заявки',1,'Заявка'),(315,3,'Исполнитель','canSetFieldsRequest','Пользователь может заполнять наборы полей в форме заявки',1,'Заявка'),(316,3,'Исполнитель','createProblem','Создавать проблемы',0,'Проблема'),(317,3,'Исполнитель','viewProblem','Просмотр проблем',0,'Проблема'),(318,3,'Исполнитель','listProblem','Отображать список проблем',0,'Проблема'),(319,3,'Исполнитель','updateProblem','Редактировать проблемы',0,'Проблема'),(320,3,'Исполнитель','deleteProblem','Удалять проблемы',0,'Проблема'),(321,3,'Исполнитель','canAssignProblem','Исполнитель может назначать проблему другому исполнителю',0,'Проблема'),(322,3,'Исполнитель','uploadFilesProblem','Пользователь может прикреплять файлы к проблеме',0,'Проблема'),(323,3,'Исполнитель','batchUpdateProblem','Массовое закрытие проблем',0,'Проблема'),(324,3,'Исполнитель','batchDeleteProblem','Массовое удаление проблем',0,'Проблема'),(325,3,'Исполнитель','viewHistoryProblem','Пользователь может видеть историю проблемы',0,'Проблема'),(326,3,'Исполнитель','createService','Создавать сервисы',0,'Сервис'),(327,3,'Исполнитель','viewService','Просмотр сервисов',1,'Сервис'),(328,3,'Исполнитель','listService','Отображать список сервисов',1,'Сервис'),(329,3,'Исполнитель','updateService','Редактировать сервисы',0,'Сервис'),(330,3,'Исполнитель','deleteService','Удалять сервисы',0,'Сервис'),(331,3,'Исполнитель','createSla','Создавать уровни сервиса',0,'Sla'),(332,3,'Исполнитель','viewSla','Просмотр уровней сервиса',1,'Sla'),(333,3,'Исполнитель','listSla','Отображать список уровней сервисов',1,'Sla'),(334,3,'Исполнитель','updateSla','Редактировать уровни сервисов',0,'Sla'),(335,3,'Исполнитель','deleteSla','Удалять уровни сервиса',0,'Sla'),(336,3,'Исполнитель','createAsset','Создавать активы',0,'Актив'),(337,3,'Исполнитель','viewAsset','Просматривать активы',0,'Актив'),(338,3,'Исполнитель','listAsset','Отображать список активов',0,'Актив'),(339,3,'Исполнитель','updateAsset','Редактировать активы',0,'Актив'),(340,3,'Исполнитель','deleteAsset','Удалить активы',0,'Актив'),(341,3,'Исполнитель','exportAsset','Экспортировать список активов',0,'Актив'),(342,3,'Исполнитель','printAsset','Распечатывать карточку актива',0,'Актив'),(343,3,'Исполнитель','createAssetType','Создавать типы активов',0,'Тип актива'),(344,3,'Исполнитель','listAssetType','Отображать список типов актива',0,'Тип актива'),(345,3,'Исполнитель','updateAssetType','Редактировать типы актива',0,'Тип актива'),(346,3,'Исполнитель','deleteAssetType','Удалить типы актива',0,'Тип актива'),(347,3,'Исполнитель','createUnit','Создавать КЕ',0,'КЕ'),(348,3,'Исполнитель','viewUnit','Просматривать КЕ',1,'КЕ'),(349,3,'Исполнитель','listUnit','Отображать список КЕ',1,'КЕ'),(350,3,'Исполнитель','updateUnit','Редактировать КЕ',0,'КЕ'),(351,3,'Исполнитель','deleteUnit','Удалять КЕ',0,'КЕ'),(352,3,'Исполнитель','exportUnit','Экспортировать список КЕ',1,'КЕ'),(353,3,'Исполнитель','printUnit','Печать карточки КЕ',1,'КЕ'),(354,3,'Исполнитель','viewMyselfUnit','Пользователь видит только свои КЕ',0,'КЕ'),(355,3,'Исполнитель','createUnitType','Создавать типы КЕ',0,'Типы КЕ'),(356,3,'Исполнитель','listUnitType','Отображать список типов КЕ',0,'Типы КЕ'),(357,3,'Исполнитель','updateUnitType','Редактировать типы КЕ',0,'Типы КЕ'),(358,3,'Исполнитель','deleteUnitType','Удалять типы КЕ',0,'Типы КЕ'),(359,3,'Исполнитель','createKB','Создавать записи Базы знаний',1,'База знаний'),(360,3,'Исполнитель','viewKB','Просматривать записи Базы знаний',1,'База знаний'),(361,3,'Исполнитель','listKB','Отображать список Базы знаний',1,'База знаний'),(362,3,'Исполнитель','updateKB','Редактировать записи Базы знаний',1,'База знаний'),(363,3,'Исполнитель','deleteKB','Удалять записи Базы знаний',0,'База знаний'),(364,3,'Исполнитель','uploadFilesKB','Пользователь может прикреплять файлы к записи Базы знаний',1,'База знаний'),(365,3,'Исполнитель','createKBCat','Создавать категории Базы знаний',0,'Категории базы знаний'),(366,3,'Исполнитель','listKBCat','Отображать список категорий Базы знаний',0,'Категории базы знаний'),(367,3,'Исполнитель','updateKBCat','Редактировать категории Базы знаний',0,'Категории базы знаний'),(368,3,'Исполнитель','deleteKBCat','Удалять категории Базы знаний',0,'Категории базы знаний'),(369,3,'Исполнитель','createNews','Создать новость',1,'Новости'),(370,3,'Исполнитель','viewNews','Просматривать новости',1,'Новости'),(371,3,'Исполнитель','listNews','Отображать список новостей',1,'Новости'),(372,3,'Исполнитель','updateNews','Редактировать новости',1,'Новости'),(373,3,'Исполнитель','deleteNews','Удалять новости',0,'Новости'),(374,3,'Исполнитель','createUser','Создавать пользователей',0,'Пользователь'),(375,3,'Исполнитель','viewUser','Просматривать пользователей',1,'Пользователь'),(376,3,'Исполнитель','listUser','Отображать список пользователей',1,'Пользователь'),(377,3,'Исполнитель','updateUser','Редактировать пользователей',0,'Пользователь'),(378,3,'Исполнитель','deleteUser','Удалить пользователей',0,'Пользователь'),(379,3,'Исполнитель','exportUser','Экспортировать список пользователей',0,'Пользователь'),(380,3,'Исполнитель','createCompany','Создавать компании',0,'Компания'),(381,3,'Исполнитель','viewCompany','Просматривать компании',1,'Компания'),(382,3,'Исполнитель','listCompany','Отображать список компаний',1,'Компания'),(383,3,'Исполнитель','updateCompany','Редактировать компании',0,'Компания'),(384,3,'Исполнитель','deleteCompany','Удалять компании',0,'Компания'),(385,3,'Исполнитель','createDepart','Создавать подразделения',0,'Подразделение'),(386,3,'Исполнитель','listDepart','Отображать список подразделений',1,'Подразделение'),(387,3,'Исполнитель','updateDepart','Редактировать подразделения',0,'Подразделение'),(388,3,'Исполнитель','deleteDepart','Удалять подразделения',0,'Подразделение'),(389,3,'Исполнитель','createGroup','Создавать группы',0,'Группа исполнителей'),(390,3,'Исполнитель','listGroup','Отображать список групп',0,'Группа исполнителей'),(391,3,'Исполнитель','updateGroup','Редактировать группы',0,'Группа исполнителей'),(392,3,'Исполнитель','deleteGroup','Удалять группы',0,'Группа исполнителей'),(393,3,'Исполнитель','createPriority','Создавать приоритеты',0,'Приоритет'),(394,3,'Исполнитель','listPriority','Отображать список приоритетов',1,'Приоритет'),(395,3,'Исполнитель','updatePriority','Редактировать приоритеты',0,'Приоритет'),(396,3,'Исполнитель','deletePriority','Удалять приоритеты',0,'Приоритет'),(397,3,'Исполнитель','createStatus','Создавать статусы',0,'Статус'),(398,3,'Исполнитель','listStatus','Отображать список статусов',0,'Статус'),(399,3,'Исполнитель','updateStatus','Редактировать статусы',0,'Статус'),(400,3,'Исполнитель','deleteStatus','Удалять статусы',0,'Статус'),(401,3,'Исполнитель','createCategory','Создать категории заявок',0,'Категория'),(402,3,'Исполнитель','listCategory','Отображать список категорий заявок',0,'Категория'),(403,3,'Исполнитель','updateCategory','Редактировать категории заявок',0,'Категория'),(404,3,'Исполнитель','deleteCategory','Удаление категорий заявок',0,'Категория'),(405,3,'Исполнитель','createETemplate','Создать E-mail шаблон',0,'Шаблоны E-mail уведомлений'),(406,3,'Исполнитель','viewETemplate','Просматривать Email шаблоны',0,'Шаблоны E-mail уведомлений'),(407,3,'Исполнитель','listETemplate','Отображать список Email шаблонов',0,'Шаблоны E-mail уведомлений'),(408,3,'Исполнитель','updateETemplate','Редактировать Email шаблоны',0,'Шаблоны E-mail уведомлений'),(409,3,'Исполнитель','deleteETemplate','Удалять Email шаблоны',0,'Шаблоны E-mail уведомлений'),(410,3,'Исполнитель','createSTemplate','Создать SMS шаблон',0,'Шаблоны SMS уведомлений'),(411,3,'Исполнитель','viewSTemplate','Просматривать SMS шаблоны',0,'Шаблоны SMS уведомлений'),(412,3,'Исполнитель','listSTemplate','Отображать список SMS шаблонов',0,'Шаблоны SMS уведомлений'),(413,3,'Исполнитель','updateSTemplate','Редактировать SMS шаблоны',0,'Шаблоны SMS уведомлений'),(414,3,'Исполнитель','deleteSTemplate','Удалять SMS шаблоны',0,'Шаблоны SMS уведомлений'),(415,3,'Исполнитель','createFieldsets','Создавать наборы полей',0,'Наборы полей'),(416,3,'Исполнитель','listFieldsets','Отображать наборы полей',0,'Наборы полей'),(417,3,'Исполнитель','updateFieldsets','Редактировать наборы полей',0,'Наборы полей'),(418,3,'Исполнитель','deleteFieldsets','Удалять наборы полей',0,'Наборы полей'),(419,3,'Исполнитель','usersReport','Доступ к отчету Заявки по заявителям',1,'Отчеты'),(420,3,'Исполнитель','companiesReport','Доступ к отчету Заявки по компаниям',1,'Отчеты'),(421,3,'Исполнитель','managersReport','Доступ к отчету Заявки по менеджерам',0,'Отчеты'),(422,3,'Исполнитель','serviceReport','Доступ к отчету Заявки по сервисам',1,'Отчеты'),(423,3,'Исполнитель','assetReport','Доступ к отчету Заявки по КЕ',1,'Отчеты'),(424,3,'Исполнитель','unitProblemReport','Доступ к отчету Проблемы по КЕ',0,'Отчеты'),(425,3,'Исполнитель','monthServiceProblemReport','Доступ к отчету Проблемы по сервисам за месяц',0,'Отчеты'),(426,3,'Исполнитель','serviceProblemReport','Доступ к отчету Проблемы по сервисам',0,'Отчеты'),(427,3,'Исполнитель','unitSProblemReport','Доступ к отчету Сводный отчет по КЕ',0,'Отчеты'),(428,3,'Исполнитель','rolesSettings','Доступ к управлению ролями',0,'Настройки'),(429,3,'Исполнитель','mainSettings','Доступ к основным настройкам',0,'Настройки'),(430,3,'Исполнитель','mailParserSettings','Доступ к настройкам парсера почты',0,'Настройки'),(431,3,'Исполнитель','adSettings','Доступ к настройкам интеграции с AD',0,'Настройки'),(432,3,'Исполнитель','smsSettings','Доступ к настройкам SMS шлюза',0,'Настройки'),(433,3,'Исполнитель','ticketSettings','Доступ к настройкам заявки по умолчанию',0,'Настройки'),(434,3,'Исполнитель','attachSettings','Доступ к настройкам вложений',0,'Настройки'),(435,3,'Исполнитель','appearSettings','Доступ к настройкам внешнего вида',0,'Настройки'),(436,3,'Исполнитель','shedulerSettings','Доступ к настройкам планировщика задач',0,'Настройки'),(437,3,'Исполнитель','logSettings','Доступ к анализатору лога',0,'Настройки'),(438,3,'Исполнитель','backupSettings','Доступ к резервному копированию',0,'Настройки'),(439,3,'Исполнитель','importSettings','Импорт из CSV',0,'Настройки'),(440,3,'Исполнитель','showTicketGraph','Отображать график заявок на главной панели',0,'Графики'),(441,3,'Исполнитель','showProblemGraph','Отображать график проблем на главной панели',0,'Графики'),(442,3,'Исполнитель','showlastNews','Отображать список последних новостей на главной панели',1,'Интерфейс'),(443,3,'Исполнитель','showlastKB','Отображать список последних записей Базы знаний на главной панели',1,'Интерфейс'),(444,3,'Исполнитель','showSearchKB','Отображать строку поиска по Базе знаний',1,'Интерфейс'),(445,1,'Администратор','viewCompanyRequest','Менеджер видит только заявки его компаний',0,'Заявка'),(446,2,'Пользователь','viewCompanyRequest','Менеджер видит только заявки его компаний',0,'Заявка'),(447,3,'Исполнитель','viewCompanyRequest','Менеджер видит только заявки его компаний',0,'Заявка'),(448,1,'Администратор','requestSReport','Доступ к отчету Сводный отчет по заявкам',1,'Отчеты'),(449,2,'Пользователь','requestSReport','Доступ к отчету Сводный отчет по заявкам',0,'Отчеты'),(450,3,'Исполнитель','requestSReport','Доступ к отчету Сводный отчет по заявкам',0,'Отчеты'),(451,1,'Администратор','monthServiceRequestsReport','Доступ к отчету Заявки по сервисам за месяц',1,'Отчеты'),(452,2,'Пользователь','monthServiceRequestsReport','Доступ к отчету Заявки по сервисам за месяц',0,'Отчеты'),(453,3,'Исполнитель','monthServiceRequestsReport','Доступ к отчету Заявки по сервисам за месяц',0,'Отчеты'),(454,1,'Администратор','contractorsReport','Доступ к отчету Заявки по подрядчикам',1,'Отчеты'),(455,2,'Пользователь','contractorsReport','Доступ к отчету Заявки по подрядчикам',0,'Отчеты'),(456,3,'Исполнитель','contractorsReport','Доступ к отчету Заявки по подрядчикам',1,'Отчеты'),(457,1,'Администратор','mainGraphAllGroupsManagers','Отображать график по группам исполнитилей',0,'Графики'),(458,1,'Администратор','viewMyCompanyRequest','Пользователь видит все заявки своей компании',0,'Заявка'),(459,1,'Администратор','canViewFieldsRequest','Пользователь видит наборы полей в окне просмотра заявки',1,'Заявка'),(460,1,'Администратор','requestSReport','Доступ у сводному отчету по заявкам',1,'Отчеты'),(462,1,'Администратор','createTemplates','Создавать шаблоны ответа',1,'Шаблоны ответа'),(463,1,'Администратор','listTemplates','Отображать список шаблонов ответа',1,'Шаблоны ответа'),(464,1,'Администратор','updateTemplates','Редактировать шаблоны ответа',1,'Шаблоны ответа'),(465,1,'Администратор','deleteTemplates','Удалять шаблоны ответа',1,'Шаблоны ответа'),(466,1,'Администратор','mainGraphAllGroupsManagers','Отображать график групп исполнителей',0,'Графики'),(467,1,'Администратор','mainGraphAllUsers','Отображать график по заявителям',0,'Графики'),(468,1,'Администратор','mainGraphManagers','Отображать график по исполнителям',1,'Графики'),(469,1,'Администратор','mainGraphAllCompanys','Отображать график по компаниям',0,'Графики'),(470,1,'Администратор','mainGraphCurentUserStatus','График заявок текущего пользователя',0,'Графики'),(471,1,'Администратор','mainGraphCompanyCurentUserStatus','График заявок по компании текущего пользователя',0,'Графики'),(472,2,'Пользователь','viewMyCompanyRequest','Пользователь видит все заявки своей компании',0,'Заявка'),(473,2,'Пользователь','canViewFieldsRequest','Пользователь видит наборы полей в окне просмотра заявки',0,'Заявка'),(474,2,'Пользователь','requestSReport','Доступ у сводному отчету по заявкам',0,'Отчеты'),(476,2,'Пользователь','createTemplates','Создавать шаблоны ответа',0,'Шаблоны ответа'),(477,2,'Пользователь','listTemplates','Отображать список шаблонов ответа',0,'Шаблоны ответа'),(478,2,'Пользователь','updateTemplates','Редактировать шаблоны ответа',0,'Шаблоны ответа'),(479,2,'Пользователь','deleteTemplates','Удалять шаблоны ответа',0,'Шаблоны ответа'),(480,2,'Пользователь','mainGraphAllGroupsManagers','Отображать график групп исполнителей',0,'Графики'),(481,2,'Пользователь','mainGraphAllUsers','Отображать график по заявителям',0,'Графики'),(482,2,'Пользователь','mainGraphManagers','Отображать график по исполнителям',0,'Графики'),(483,2,'Пользователь','mainGraphAllCompanys','Отображать график по компаниям',0,'Графики'),(484,2,'Пользователь','mainGraphCurentUserStatus','График заявок текущего пользователя',0,'Графики'),(485,2,'Пользователь','mainGraphCompanyCurentUserStatus','График заявок по компании текущего пользователя',0,'Графики'),(486,3,'Исполнитель','viewMyCompanyRequest','Пользователь видит все заявки своей компании',0,'Заявка'),(487,3,'Исполнитель','canViewFieldsRequest','Пользователь видит наборы полей в окне просмотра заявки',1,'Заявка'),(488,3,'Исполнитель','requestSReport','Доступ у сводному отчету по заявкам',0,'Отчеты'),(490,3,'Исполнитель','createTemplates','Создавать шаблоны ответа',0,'Шаблоны ответа'),(491,3,'Исполнитель','listTemplates','Отображать список шаблонов ответа',0,'Шаблоны ответа'),(492,3,'Исполнитель','updateTemplates','Редактировать шаблоны ответа',0,'Шаблоны ответа'),(493,3,'Исполнитель','deleteTemplates','Удалять шаблоны ответа',0,'Шаблоны ответа'),(494,3,'Исполнитель','mainGraphAllGroupsManagers','Отображать график групп исполнителей',0,'Графики'),(495,3,'Исполнитель','mainGraphAllUsers','Отображать график по заявителям',0,'Графики'),(496,3,'Исполнитель','mainGraphManagers','Отображать график по исполнителям',0,'Графики'),(497,3,'Исполнитель','mainGraphAllCompanys','Отображать график по компаниям',0,'Графики'),(498,3,'Исполнитель','mainGraphCurentUserStatus','График заявок текущего пользователя',0,'Графики'),(499,3,'Исполнитель','mainGraphCompanyCurentUserStatus','График заявок по компании текущего пользователя',0,'Графики'),(500,1,'Администратор','createUnitTemplates','Создавать шаблоны печатной формы',1,'Шаблоны печатных форм'),(501,1,'Администратор','listUnitTemplates','Просматривать список шаблонов печтаных форм',1,'Шаблоны печатных форм'),(502,1,'Администратор','updateUnitTemplates','Редактировать шаблоны печатных форм',1,'Шаблоны печатных форм'),(503,1,'Администратор','deleteUnitTemplates','Удалять шаблоны печтаных форм',1,'Шаблоны печатных форм'),(504,2,'Пользователь','createUnitTemplates','Создавать шаблоны печатной формы',0,'Шаблоны печатных форм'),(505,2,'Пользователь','listUnitTemplates','Просматривать список шаблонов печтаных форм',0,'Шаблоны печатных форм'),(506,2,'Пользователь','updateUnitTemplates','Редактировать шаблоны печатных форм',0,'Шаблоны печатных форм'),(507,2,'Пользователь','deleteUnitTemplates','Удалять шаблоны печтаных форм',0,'Шаблоны печатных форм'),(508,3,'Исполнитель','createUnitTemplates','Создавать шаблоны печатной формы',0,'Шаблоны печатных форм'),(509,3,'Исполнитель','listUnitTemplates','Просматривать список шаблонов печтаных форм',0,'Шаблоны печатных форм'),(510,3,'Исполнитель','updateUnitTemplates','Редактировать шаблоны печатных форм',0,'Шаблоны печатных форм'),(511,3,'Исполнитель','deleteUnitTemplates','Удалять шаблоны печтаных форм',0,'Шаблоны печатных форм'),(512,1,'Администратор','batchAssignRequest','Массовое переназначение исполнителей',1,'Заявка'),(513,1,'Администратор','batchUpdateStatusRequest','Массовое изменение статуса заявок',1,'Заявка'),(514,2,'Пользователь','batchAssignRequest','Массовое переназначение исполнителей',0,'Заявка'),(515,2,'Пользователь','batchUpdateStatusRequest','Массовое изменение статуса заявок',0,'Заявка'),(516,3,'Исполнитель','batchAssignRequest','Массовое переназначение исполнителей',1,'Заявка'),(517,3,'Исполнитель','batchUpdateStatusRequest','Массовое изменение статуса заявок',0,'Заявка'),(524,1,'Администратор','viewAssignedRequest','Менеджер видит только назначенные ему заявки',0,'Заявка'),(525,2,'Пользователь','viewAssignedRequest','Менеджер видит только назначенные ему заявки',0,'Заявка'),(526,3,'Исполнитель','viewAssignedRequest','Менеджер видит только назначенные ему заявки',0,'Заявка'),(527,1,'Администратор','customReport','Доступ к отчету Сводный отчет',1,'Отчеты'),(528,2,'Пользователь','customReport','Доступ к отчету Сводный отчет',0,'Отчеты'),(529,3,'Исполнитель','customReport','Доступ к отчету Сводный отчет',0,'Отчеты'),(530,1,'Администратор','listCronRequest','Отображать список запланированных заявок',1,'Заявка'),(531,2,'Пользователь','listCronRequest','Отображать список запланированных заявок',0,'Заявка'),(532,3,'Исполнитель','listCronRequest','Отображать список запланированных заявок',0,'Заявка'),(533,1,'Администратор','createAstatus','Создавать статусы активов и КЕ',1,'Статусы активов и КЕ'),(534,1,'Администратор','listAstatus','Отображать список статусов активов и КЕ',1,'Статусы активов и КЕ'),(535,1,'Администратор','updateAstatus','Редактировать статусы активов и КЕ',1,'Статусы активов и КЕ'),(536,1,'Администратор','deleteAstatus','Удалять статусы активов и КЕ',1,'Статусы активов и КЕ'),(537,1,'Администратор','canEditCommentsRequest','Пользователь может редактировать комментарии в заявке',1,'Заявка'),(538,2,'Пользователь','createAstatus','Создавать статусы активов и КЕ',0,'Статусы активов и КЕ'),(539,2,'Пользователь','listAstatus','Отображать список статусов активов и КЕ',0,'Статусы активов и КЕ'),(540,2,'Пользователь','updateAstatus','Редактировать статусы активов и КЕ',0,'Статусы активов и КЕ'),(541,2,'Пользователь','deleteAstatus','Удалять статусы активов и КЕ',0,'Статусы активов и КЕ'),(542,2,'Пользователь','canEditCommentsRequest','Пользователь может редактировать комментарии в заявке',0,'Заявка'),(543,3,'Исполнитель','createAstatus','Создавать статусы активов и КЕ',0,'Статусы активов и КЕ'),(544,3,'Исполнитель','listAstatus','Отображать список статусов активов и КЕ',0,'Статусы активов и КЕ'),(545,3,'Исполнитель','updateAstatus','Редактировать статусы активов и КЕ',0,'Статусы активов и КЕ'),(546,3,'Исполнитель','deleteAstatus','Удалять статусы активов и КЕ',0,'Статусы активов и КЕ'),(547,3,'Исполнитель','canEditCommentsRequest','Пользователь может редактировать комментарии в заявке',0,'Заявка'),(548,1,'Администратор','readChat','Доступ к посмотру чата',0,'Чат'),(549,1,'Администратор','adminChat','Администрирование чата',0,'Чат'),(550,1,'Администратор','viewMyAssignedRequest','Менеджер видит как назначенные ему, так и свои заявки',0,'Заявка'),(551,1,'Администратор','unitByUserRequest','Отображать только принадлежащие пользователю КЕ',0,'Заявка'),(552,1,'Администратор','printRequest','Печать заявки',1,'Заявка'),(553,1,'Администратор','canSetPriority','Разрешить пользователю выбирать приоритет в полной форме заявки',0,'Заявка'),(554,1,'Администратор','canEditContent','Пользователь может редактировать содержание в заявке',1,'Заявка'),(555,1,'Администратор','canAddTemplate','Пользователь может добавить шаблон ответа из комментария',1,'Заявка'),(556,1,'Администратор','canAddKBreply','Пользователь может добавить в базу знаний ответ из комментария',1,'Заявка'),(557,1,'Администратор','pushSettings','Push уведомления',1,'Настройки'),(558,1,'Администратор','amiSettings','Интеграция с Asterisk',1,'Настройки'),(559,1,'Администратор','allowSoundNotify','Разрешить звуковые уведомления',1,'Интерфейс'),(560,1,'Администратор','createAPI','Create APIs',1,'API'),(561,1,'Администратор','updateAPI','Update APIs',1,'API'),(562,1,'Администратор','viewAPI','View APIs',1,'API'),(563,1,'Администратор','listAPI','List APIs',1,'API'),(564,1,'Администратор','deleteAPI','Delete APIs',1,'API'),(565,1,'Администратор','viewCalls','Просматривать звонки',1,'Звонки'),(566,1,'Администратор','listCalls','Отображать список звонков',1,'Звонки'),(567,1,'Администратор','deleteCalls','Удалять звонки',1,'Звонки'),(568,2,'Пользователь','readChat','Доступ к посмотру чата',0,'Чат'),(569,2,'Пользователь','adminChat','Администрирование чата',0,'Чат'),(570,2,'Пользователь','viewMyAssignedRequest','Менеджер видит как назначенные ему, так и свои заявки',0,'Заявка'),(571,2,'Пользователь','unitByUserRequest','Отображать только принадлежащие пользователю КЕ',1,'Заявка'),(572,2,'Пользователь','printRequest','Печать заявки',0,'Заявка'),(573,2,'Пользователь','canSetPriority','Разрешить пользователю выбирать приоритет в полной форме заявки',1,'Заявка'),(574,2,'Пользователь','canEditContent','Пользователь может редактировать содержание в заявке',0,'Заявка'),(575,2,'Пользователь','canAddTemplate','Пользователь может добавить шаблон ответа из комментария',0,'Заявка'),(576,2,'Пользователь','canAddKBreply','Пользователь может добавить в базу знаний ответ из комментария',0,'Заявка'),(577,2,'Пользователь','pushSettings','Push уведомления',0,'Настройки'),(578,2,'Пользователь','amiSettings','Интеграция с Asterisk',0,'Настройки'),(579,2,'Пользователь','allowSoundNotify','Разрешить звуковые уведомления',0,'Интерфейс'),(580,2,'Пользователь','createAPI','Create APIs',0,'API'),(581,2,'Пользователь','updateAPI','Update APIs',0,'API'),(582,2,'Пользователь','viewAPI','View APIs',0,'API'),(583,2,'Пользователь','listAPI','List APIs',0,'API'),(584,2,'Пользователь','deleteAPI','Delete APIs',0,'API'),(585,2,'Пользователь','viewCalls','Просматривать звонки',0,'Звонки'),(586,2,'Пользователь','listCalls','Отображать список звонков',0,'Звонки'),(587,2,'Пользователь','deleteCalls','Удалять звонки',0,'Звонки'),(588,3,'Исполнитель','readChat','Доступ к посмотру чата',0,'Чат'),(589,3,'Исполнитель','adminChat','Администрирование чата',0,'Чат'),(590,3,'Исполнитель','viewMyAssignedRequest','Менеджер видит как назначенные ему, так и свои заявки',1,'Заявка'),(591,3,'Исполнитель','unitByUserRequest','Отображать только принадлежащие пользователю КЕ',0,'Заявка'),(592,3,'Исполнитель','printRequest','Печать заявки',0,'Заявка'),(593,3,'Исполнитель','canSetPriority','Разрешить пользователю выбирать приоритет в полной форме заявки',0,'Заявка'),(594,3,'Исполнитель','canEditContent','Пользователь может редактировать содержание в заявке',0,'Заявка'),(595,3,'Исполнитель','canAddTemplate','Пользователь может добавить шаблон ответа из комментария',0,'Заявка'),(596,3,'Исполнитель','canAddKBreply','Пользователь может добавить в базу знаний ответ из комментария',0,'Заявка'),(597,3,'Исполнитель','pushSettings','Push уведомления',0,'Настройки'),(598,3,'Исполнитель','amiSettings','Интеграция с Asterisk',0,'Настройки'),(599,3,'Исполнитель','allowSoundNotify','Разрешить звуковые уведомления',0,'Интерфейс'),(600,3,'Исполнитель','createAPI','Create APIs',0,'API'),(601,3,'Исполнитель','updateAPI','Update APIs',0,'API'),(602,3,'Исполнитель','viewAPI','View APIs',0,'API'),(603,3,'Исполнитель','listAPI','List APIs',0,'API'),(604,3,'Исполнитель','deleteAPI','Delete APIs',0,'API'),(605,3,'Исполнитель','viewCalls','Просматривать звонки',0,'Звонки'),(606,3,'Исполнитель','listCalls','Отображать список звонков',0,'Звонки'),(607,3,'Исполнитель','deleteCalls','Удалять звонки',0,'Звонки'),(609,1,'Администратор','prevnextRequest','Пользователь видит виджет перелистывания заявок в окне просмотра',0,'Заявка'),(610,1,'Администратор','tbotSettings','Интеграция c Telegram ботом',1,'Настройки'),(611,1,'Администратор','slackSettings','Интеграция со Slack',1,'Настройки'),(613,2,'Пользователь','prevnextRequest','Пользователь видит виджет перелистывания заявок в окне просмотра',0,'Заявка'),(614,2,'Пользователь','tbotSettings','Интеграция c Telegram ботом',0,'Настройки'),(615,2,'Пользователь','slackSettings','Интеграция со Slack',0,'Настройки'),(617,3,'Исполнитель','prevnextRequest','Пользователь видит виджет перелистывания заявок в окне просмотра',0,'Заявка'),(618,3,'Исполнитель','tbotSettings','Интеграция c Telegram ботом',0,'Настройки'),(619,3,'Исполнитель','slackSettings','Интеграция со Slack',0,'Настройки'),(623,1,'Администратор','updateLeadRequest','Пользователь может редактировать затраченное время на выполнение заявки',1,'Заявка'),(624,2,'Пользователь','updateLeadRequest','Пользователь может редактировать затраченное время на выполнение заявки',0,'Заявка'),(625,3,'Исполнитель','updateLeadRequest','Пользователь может редактировать затраченное время на выполнение заявки',0,'Заявка'),(626,1,'Администратор','widgetSettings','Виджет на сайт',1,'Настройки'),(627,2,'Пользователь','widgetSettings','Виджет на сайт',0,'Настройки'),(628,3,'Исполнитель','widgetSettings','Виджет на сайт',0,'Настройки'),(629,1,'Администратор','liteformRequest','Пользователь использует упрощенную форму',0,'Заявка'),(630,2,'Пользователь','liteformRequest','Пользователь использует упрощенную форму',0,'Заявка'),(631,3,'Исполнитель','liteformRequest','Пользователь использует упрощенную форму',0,'Заявка'),(632,1,'Администратор','downfieldsRequest','Отображать дополнительные поля под содержанием',1,'Заявка'),(633,2,'Пользователь','downfieldsRequest','Отображать дополнительные поля под содержанием',1,'Заявка'),(634,3,'Исполнитель','downfieldsRequest','Отображать дополнительные поля под содержанием',1,'Заявка'),(635,1,'Администратор','canViewFieldsRequestList','Пользователь видит доп. поля в списке заявок',1,'Заявка'),(636,1,'Администратор','deleteUnitTemplates','Удалять шаблоны печатных форм',0,'Шаблоны печатных форм'),(637,1,'Администратор','listSelects','Отображать списки',1,'Списки'),(638,1,'Администратор','createSelects','Создавать списки',1,'Списки'),(639,1,'Администратор','updateSelects','Редактировать списки',1,'Списки'),(640,1,'Администратор','deleteSelects','Удалять списки',1,'Списки'),(641,2,'Пользователь','canViewFieldsRequestList','Пользователь видит доп. поля в списке заявок',0,'Заявка'),(642,2,'Пользователь','deleteUnitTemplates','Удалять шаблоны печатных форм',0,'Шаблоны печатных форм'),(643,2,'Пользователь','listSelects','Отображать списки',0,'Списки'),(644,2,'Пользователь','createSelects','Создавать списки',0,'Списки'),(645,2,'Пользователь','updateSelects','Редактировать списки',0,'Списки'),(646,2,'Пользователь','deleteSelects','Удалять списки',0,'Списки'),(647,3,'Исполнитель','canViewFieldsRequestList','Пользователь видит доп. поля в списке заявок',1,'Заявка'),(648,3,'Исполнитель','deleteUnitTemplates','Удалять шаблоны печатных форм',0,'Шаблоны печатных форм'),(649,3,'Исполнитель','listSelects','Отображать списки',0,'Списки'),(650,3,'Исполнитель','createSelects','Создавать списки',0,'Списки'),(651,3,'Исполнитель','updateSelects','Редактировать списки',0,'Списки'),(652,3,'Исполнитель','deleteSelects','Удалять списки',0,'Списки'),(653,1,'Администратор','fieldsCompany','Управлять полями компании',1,'Компания'),(654,2,'Пользователь','fieldsCompany','Управлять полями компании',0,'Компания'),(655,3,'Исполнитель','fieldsCompany','Управлять полями компании',0,'Компания'),(656,1,'Администратор','vbotSettings','Интеграция c Viber ботом',1,'Настройки'),(657,2,'Пользователь','vbotSettings','Интеграция c Viber ботом',0,'Настройки'),(658,3,'Исполнитель','vbotSettings','Интеграция c Viber ботом',0,'Настройки'),(659,1,'Администратор','canStartTWSession','Пользователь может инициализировать сессию Team Viewer',1,'Заявка'),(660,1,'Администратор','batchDeleteAsset','Массовое удаление активов',1,'Актив'),(661,1,'Администратор','batchDeleteUnit','Массовое удаление КЕ',1,'КЕ'),(662,1,'Администратор','batchDeleteUser','Массовое удаление пользователей',1,'Пользователь'),(663,1,'Администратор','batchDeleteCompany','Массовое удаление компаний',1,'Компания'),(664,1,'Администратор','twSettings','Интеграция c TeamViewer',1,'Настройки'),(665,2,'Пользователь','canStartTWSession','Пользователь может инициализировать сессию Team Viewer',0,'Заявка'),(666,2,'Пользователь','batchDeleteAsset','Массовое удаление активов',0,'Актив'),(667,2,'Пользователь','batchDeleteUnit','Массовое удаление КЕ',0,'КЕ'),(668,2,'Пользователь','batchDeleteUser','Массовое удаление пользователей',0,'Пользователь'),(669,2,'Пользователь','batchDeleteCompany','Массовое удаление компаний',0,'Компания'),(670,2,'Пользователь','twSettings','Интеграция c TeamViewer',0,'Настройки'),(671,3,'Исполнитель','canStartTWSession','Пользователь может инициализировать сессию Team Viewer',1,'Заявка'),(672,3,'Исполнитель','batchDeleteAsset','Массовое удаление активов',0,'Актив'),(673,3,'Исполнитель','batchDeleteUnit','Массовое удаление КЕ',0,'КЕ'),(674,3,'Исполнитель','batchDeleteUser','Массовое удаление пользователей',0,'Пользователь'),(675,3,'Исполнитель','batchDeleteCompany','Массовое удаление компаний',0,'Компания'),(676,3,'Исполнитель','twSettings','Интеграция c TeamViewer',0,'Настройки'),(677,1,'Администратор','amiCalls','Звонки через Asterisk',1,'Настройки'),(678,2,'Пользователь','amiCalls','Звонки через Asterisk',0,'Настройки'),(679,3,'Исполнитель','amiCalls','Звонки через Asterisk',1,'Настройки'),(680,1,'Администратор','viewPhonebook','Просмотр пользователей телефонной книги',1,'Телефонная книга'),(681,1,'Администратор','listPhonebook','Просмотр телефонной книги',1,'Телефонная книга'),(682,2,'Пользователь','viewPhonebook','Просмотр пользователей телефонной книги',1,'Телефонная книга'),(683,2,'Пользователь','listPhonebook','Просмотр телефонной книги',1,'Телефонная книга'),(684,3,'Исполнитель','viewPhonebook','Просмотр пользователей телефонной книги',1,'Телефонная книга'),(685,3,'Исполнитель','listPhonebook','Просмотр телефонной книги',1,'Телефонная книга'),(686,1,'Администратор','viewGroupRequest','Исполнитель может видеть завершенные заявки членов своей группы',0,'Заявка'),(687,2,'Пользователь','viewGroupRequest','Исполнитель может видеть завершенные заявки членов своей группы',0,'Заявка'),(688,3,'Исполнитель','viewGroupRequest','Исполнитель может видеть завершенные заявки членов своей группы',0,'Заявка'),(689,1,'Администратор','jiraSettings','Интеграция с Jira',1,'Настройки'),(690,1,'Администратор','showTicketCalendar','Отображать календарь заявок на главной панели',1,'Интерфейс'),(691,2,'Пользователь','jiraSettings','Интеграция с Jira',0,'Настройки'),(692,2,'Пользователь','showTicketCalendar','Отображать календарь заявок на главной панели',0,'Интерфейс'),(693,3,'Исполнитель','jiraSettings','Интеграция с Jira',0,'Настройки'),(694,3,'Исполнитель','showTicketCalendar','Отображать календарь заявок на главной панели',0,'Интерфейс'),(695,1,'Администратор','viewAllGroupRequest','Исполнитель может видеть все заявки членов своей группы',0,'Заявка'),(696,1,'Администратор','canSuspendRequest','Пользователь может поставть заявку на паузу',1,'Заявка'),(697,1,'Администратор','uploadFilesAsset','Пользователь может прикреплять файлы к активу',1,'Актив'),(698,1,'Администратор','uploadFilesUnit','Пользователь может прикреплять файлы к КЕ',1,'КЕ'),(699,1,'Администратор','uploadFilesCompany','Пользователь может прикреплять файлы к компании',1,'Компания'),(700,2,'Пользователь','viewAllGroupRequest','Исполнитель может видеть все заявки членов своей группы',0,'Заявка'),(701,2,'Пользователь','canSuspendRequest','Пользователь может поставть заявку на паузу',0,'Заявка'),(702,2,'Пользователь','uploadFilesAsset','Пользователь может прикреплять файлы к активу',0,'Актив'),(703,2,'Пользователь','uploadFilesUnit','Пользователь может прикреплять файлы к КЕ',0,'КЕ'),(704,2,'Пользователь','uploadFilesCompany','Пользователь может прикреплять файлы к компании',0,'Компания'),(705,3,'Исполнитель','viewAllGroupRequest','Исполнитель может видеть все заявки членов своей группы',0,'Заявка'),(706,3,'Исполнитель','canSuspendRequest','Пользователь может поставть заявку на паузу',1,'Заявка'),(707,3,'Исполнитель','uploadFilesAsset','Пользователь может прикреплять файлы к активу',0,'Актив'),(708,3,'Исполнитель','uploadFilesUnit','Пользователь может прикреплять файлы к КЕ',0,'КЕ'),(709,3,'Исполнитель','uploadFilesCompany','Пользователь может прикреплять файлы к компании',0,'Компания'),(710,1,'Администратор','portalSettings','Настройки портала самообслуживания',1,'Настройки'),(711,2,'Пользователь','portalSettings','Настройки портала самообслуживания',0,'Настройки'),(712,3,'Исполнитель','portalSettings','Настройки портала самообслуживания',0,'Настройки'),(722,1,'Администратор','msbotSettings','Интеграция с Microsoft bot framework',1,'Настройки'),(729,2,'Пользователь','msbotSettings','Интеграция с Microsoft bot framework',0,'Настройки'),(736,3,'Исполнитель','msbotSettings','Интеграция с Microsoft bot framework',0,'Настройки'),(737,1,'Администратор','createContracts','Создавать договоры',1,'Договоры'),(738,1,'Администратор','viewContracts','Просматривать договоры',1,'Договоры'),(739,1,'Администратор','listContracts','Отображать список договоров',1,'Договоры'),(740,1,'Администратор','updateContracts','Редактировать договоры',1,'Договоры'),(741,1,'Администратор','deleteContracts','Удалять договоры',1,'Договоры'),(742,1,'Администратор','uploadFilesContracts','Пользователь может прикреплять файлы к договорам',1,'Договоры'),(743,2,'Пользователь','createContracts','Создавать договоры',0,'Договоры'),(744,2,'Пользователь','viewContracts','Просматривать договоры',0,'Договоры'),(745,2,'Пользователь','listContracts','Отображать список договоров',0,'Договоры'),(746,2,'Пользователь','updateContracts','Редактировать договоры',0,'Договоры'),(747,2,'Пользователь','deleteContracts','Удалять договоры',0,'Договоры'),(748,2,'Пользователь','uploadFilesContracts','Пользователь может прикреплять файлы к договорам',0,'Договоры'),(749,3,'Исполнитель','createContracts','Создавать договоры',0,'Договоры'),(750,3,'Исполнитель','viewContracts','Просматривать договоры',0,'Договоры'),(751,3,'Исполнитель','listContracts','Отображать список договоров',0,'Договоры'),(752,3,'Исполнитель','updateContracts','Редактировать договоры',0,'Договоры'),(753,3,'Исполнитель','deleteContracts','Удалять договоры',0,'Договоры'),(754,3,'Исполнитель','uploadFilesContracts','Пользователь может прикреплять файлы к договорам',0,'Договоры'),(755,1,'Администратор','printContracts','Печать договоров',1,'Договоры'),(756,2,'Пользователь','printContracts','Печать договоров',0,'Договоры'),(757,3,'Исполнитель','printContracts','Печать договоров',0,'Договоры'),(761,1,'Администратор','managersKPIReport','Отчет по KPI',1,'Отчеты'),(762,2,'Пользователь','managersKPIReport','Отчет по KPI',0,'Отчеты'),(763,3,'Исполнитель','managersKPIReport','Отчет по KPI',0,'Отчеты'),(764,1,'Администратор','readChat','Доступ к просмотру чата',0,'Чат'),(765,1,'Администратор','canDeleteCommentsRequest','Пользователь может удалять комментарии в заявке',1,'Заявка'),(766,1,'Администратор','canAddCommentsRequest','Пользователь может добавлять комментарии',1,'Заявка'),(767,1,'Администратор','viewMyDepartRequest','Пользователь может видеть все заявки Подразделений, где он руководитель',0,'Заявка'),(768,1,'Администратор','canSuspendRequest','Пользователь может поставить заявку на паузу',1,'Заявка'),(769,1,'Администратор','canChangeUser','Исполнитель может изменить заказчика в заявке',1,'Заявка'),(770,1,'Администратор','canSelectDeadline','Пользователь может выбрать дедлайн и исполнителя в форме заявки',0,'Заявка'),(771,1,'Администратор','viewOnlyUserCompanyPhonebook','Отображать только контакты компании пользователя в телефонной книге',0,'Телефонная книга'),(772,1,'Администратор','listUnitTemplates','Просматривать список шаблонов печатных форм',0,'Шаблоны печатных форм'),(773,2,'Пользователь','readChat','Доступ к просмотру чата',0,'Чат'),(774,2,'Пользователь','canDeleteCommentsRequest','Пользователь может удалять комментарии в заявке',0,'Заявка'),(775,2,'Пользователь','canAddCommentsRequest','Пользователь может добавлять комментарии',1,'Заявка'),(776,2,'Пользователь','viewMyDepartRequest','Пользователь может видеть все заявки Подразделений, где он руководитель',0,'Заявка'),(777,2,'Пользователь','canSuspendRequest','Пользователь может поставить заявку на паузу',0,'Заявка'),(778,2,'Пользователь','canChangeUser','Исполнитель может изменить заказчика в заявке',0,'Заявка'),(779,2,'Пользователь','canSelectDeadline','Пользователь может выбрать дедлайн и исполнителя в форме заявки',0,'Заявка'),(780,2,'Пользователь','viewOnlyUserCompanyPhonebook','Отображать только контакты компании пользователя в телефонной книге',0,'Телефонная книга'),(781,2,'Пользователь','listUnitTemplates','Просматривать список шаблонов печатных форм',0,'Шаблоны печатных форм'),(782,3,'Исполнитель','readChat','Доступ к просмотру чата',0,'Чат'),(783,3,'Исполнитель','canDeleteCommentsRequest','Пользователь может удалять комментарии в заявке',0,'Заявка'),(784,3,'Исполнитель','canAddCommentsRequest','Пользователь может добавлять комментарии',1,'Заявка'),(785,3,'Исполнитель','viewMyDepartRequest','Пользователь может видеть все заявки Подразделений, где он руководитель',0,'Заявка'),(786,3,'Исполнитель','canSuspendRequest','Пользователь может поставить заявку на паузу',0,'Заявка'),(787,3,'Исполнитель','canChangeUser','Исполнитель может изменить заказчика в заявке',0,'Заявка'),(788,3,'Исполнитель','canSelectDeadline','Пользователь может выбрать дедлайн и исполнителя в форме заявки',0,'Заявка'),(789,3,'Исполнитель','viewOnlyUserCompanyPhonebook','Отображать только контакты компании пользователя в телефонной книге',0,'Телефонная книга'),(790,3,'Исполнитель','listUnitTemplates','Просматривать список шаблонов печатных форм',0,'Шаблоны печатных форм'),(791,1,'Администратор','cantSelectCustomer','Исполнитель не может выбирать заказчика в форме создания заявки',0,'Заявка'),(792,2,'Пользователь','cantSelectCustomer','Исполнитель не может выбирать заказчика в форме создания заявки',0,'Заявка'),(793,3,'Исполнитель','cantSelectCustomer','Исполнитель не может выбирать заказчика в форме создания заявки',0,'Заявка'),(794,1,'Администратор','doNotSelectServiceCategories','Не использовать выбор категории сервиса',1,'Заявка'),(812,1,'Администратор','createServiceCategory','Создавать категории сервисов',1,'Категория сервиса'),(813,1,'Администратор','viewServiceCategory','Просматривать категории сервисов',1,'Категория сервиса'),(814,1,'Администратор','listServiceCategory','Отображать список категорий сервисов',1,'Категория сервиса'),(815,1,'Администратор','updateServiceCategory','Редактировать категории сервисов',1,'Категория сервиса'),(816,1,'Администратор','deleteServiceCategory','Удалять категории сервисов',1,'Категория сервиса'),(817,2,'Пользователь','doNotSelectServiceCategories','Не использовать выбор категории сервиса',1,'Заявка'),(818,2,'Пользователь','createServiceCategory','Создавать категории сервисов',0,'Категория сервиса'),(819,2,'Пользователь','viewServiceCategory','Просматривать категории сервисов',0,'Категория сервиса'),(820,2,'Пользователь','listServiceCategory','Отображать список категорий сервисов',0,'Категория сервиса'),(821,2,'Пользователь','updateServiceCategory','Редактировать категории сервисов',0,'Категория сервиса'),(822,2,'Пользователь','deleteServiceCategory','Удалять категории сервисов',0,'Категория сервиса'),(823,3,'Исполнитель','doNotSelectServiceCategories','Не использовать выбор категории сервиса',1,'Заявка'),(824,3,'Исполнитель','createServiceCategory','Создавать категории сервисов',0,'Категория сервиса'),(825,3,'Исполнитель','viewServiceCategory','Просматривать категории сервисов',0,'Категория сервиса'),(826,3,'Исполнитель','listServiceCategory','Отображать список категорий сервисов',0,'Категория сервиса'),(827,3,'Исполнитель','updateServiceCategory','Редактировать категории сервисов',0,'Категория сервиса'),(828,3,'Исполнитель','deleteServiceCategory','Удалять категории сервисов',0,'Категория сервиса'),(829,1,'Администратор','allowAlertNotify','Использовать всплывающие уведомления (Снижает производительность)',0,'Интерфейс'),(830,2,'Пользователь','allowAlertNotify','Использовать всплывающие уведомления (Снижает производительность)',0,'Интерфейс'),(831,3,'Исполнитель','allowAlertNotify','Использовать всплывающие уведомления (Снижает производительность)',0,'Интерфейс'),(832,1,'Администратор','batchMergeRequest','Массовое объединение заявок',1,'Заявка'),(833,1,'Администратор','canChangeChecklist','Отображать и заполнять чеклисты в форме просмотра',1,'Заявка'),(834,1,'Администратор','viewOnlyChecklist','Отображать чеклисты только для чтения',0,'Заявка'),(835,1,'Администратор','createChecklists','Создавать чек листы',1,'Чеклисты'),(836,1,'Администратор','listChecklists','Просмотр списка чек листов',1,'Чеклисты'),(837,1,'Администратор','updateChecklists','Редактирование чек листов',1,'Чеклисты'),(838,1,'Администратор','deleteChecklists','Удаление чеклистов',1,'Чеклисты'),(839,2,'Пользователь','batchMergeRequest','Массовое объединение заявок',0,'Заявка'),(840,2,'Пользователь','canChangeChecklist','Отображать и заполнять чеклисты в форме просмотра',0,'Заявка'),(841,2,'Пользователь','viewOnlyChecklist','Отображать чеклисты только для чтения',1,'Заявка'),(842,2,'Пользователь','createChecklists','Создавать чек листы',0,'Чеклисты'),(843,2,'Пользователь','listChecklists','Просмотр списка чек листов',0,'Чеклисты'),(844,2,'Пользователь','updateChecklists','Редактирование чек листов',0,'Чеклисты'),(845,2,'Пользователь','deleteChecklists','Удаление чеклистов',0,'Чеклисты'),(846,3,'Исполнитель','batchMergeRequest','Массовое объединение заявок',1,'Заявка'),(847,3,'Исполнитель','canChangeChecklist','Отображать и заполнять чеклисты в форме просмотра',1,'Заявка'),(848,3,'Исполнитель','viewOnlyChecklist','Отображать чеклисты только для чтения',0,'Заявка'),(849,3,'Исполнитель','createChecklists','Создавать чек листы',0,'Чеклисты'),(850,3,'Исполнитель','listChecklists','Просмотр списка чек листов',0,'Чеклисты'),(851,3,'Исполнитель','updateChecklists','Редактирование чек листов',0,'Чеклисты'),(852,3,'Исполнитель','deleteChecklists','Удаление чеклистов',0,'Чеклисты'),(853,1,'Администратор','updateCronRequest','Редактировать запланированные заявки',1,'Заявка'),(854,1,'Администратор','createCronRequest','Создавать запланированные заявки',1,'Заявка'),(855,1,'Администратор','deleteCronRequest','Удалять запланированные заявки',1,'Заявка'),(856,1,'Администратор','wbotSettings','Интеграция с WhatsApp',1,'Настройки'),(857,1,'Администратор','createRequestProcessingRules','Создавать правила обработки заявок',1,'Правила обработки заявок'),(858,1,'Администратор','viewRequestProcessingRules','Просмотр правил обработки заявок',1,'Правила обработки заявок'),(859,1,'Администратор','listRequestProcessingRules','Отображать список правил обработки заявок',1,'Правила обработки заявок'),(860,1,'Администратор','updateRequestProcessingRules','Редактирование правил обработки заявок',1,'Правила обработки заявок'),(861,1,'Администратор','deleteRequestProcessingRules','Удаление правил обработки заявок',1,'Правила обработки заявок'),(862,2,'Пользователь','updateCronRequest','Редактировать запланированные заявки',0,'Заявка'),(863,2,'Пользователь','createCronRequest','Создавать запланированные заявки',0,'Заявка'),(864,2,'Пользователь','deleteCronRequest','Удалять запланированные заявки',0,'Заявка'),(865,2,'Пользователь','wbotSettings','Интеграция с WhatsApp',0,'Настройки'),(866,2,'Пользователь','createRequestProcessingRules','Создавать правила обработки заявок',0,'Правила обработки заявок'),(867,2,'Пользователь','viewRequestProcessingRules','Просмотр правил обработки заявок',0,'Правила обработки заявок'),(868,2,'Пользователь','listRequestProcessingRules','Отображать список правил обработки заявок',0,'Правила обработки заявок'),(869,2,'Пользователь','updateRequestProcessingRules','Редактирование правил обработки заявок',0,'Правила обработки заявок'),(870,2,'Пользователь','deleteRequestProcessingRules','Удаление правил обработки заявок',0,'Правила обработки заявок'),(871,3,'Исполнитель','updateCronRequest','Редактировать запланированные заявки',0,'Заявка'),(872,3,'Исполнитель','createCronRequest','Создавать запланированные заявки',0,'Заявка'),(873,3,'Исполнитель','deleteCronRequest','Удалять запланированные заявки',0,'Заявка'),(874,3,'Исполнитель','wbotSettings','Интеграция с WhatsApp',0,'Настройки'),(875,3,'Исполнитель','createRequestProcessingRules','Создавать правила обработки заявок',0,'Правила обработки заявок'),(876,3,'Исполнитель','viewRequestProcessingRules','Просмотр правил обработки заявок',0,'Правила обработки заявок'),(877,3,'Исполнитель','listRequestProcessingRules','Отображать список правил обработки заявок',0,'Правила обработки заявок'),(878,3,'Исполнитель','updateRequestProcessingRules','Редактирование правил обработки заявок',0,'Правила обработки заявок'),(879,3,'Исполнитель','deleteRequestProcessingRules','Удаление правил обработки заявок',0,'Правила обработки заявок');
/*!40000 ALTER TABLE `roles_rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selects`
--

DROP TABLE IF EXISTS `selects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `selects` (
                           `id` int(11) NOT NULL AUTO_INCREMENT,
                           `select_name` varchar(128) NOT NULL,
                           `select_value` text NOT NULL,
                           PRIMARY KEY (`id`),
                           UNIQUE KEY `idx_uq_select_name` (`select_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selects`
--

LOCK TABLES `selects` WRITE;
/*!40000 ALTER TABLE `selects` DISABLE KEYS */;
INSERT INTO `selects` VALUES (1,'Города','Москва,Санкт-Петербург,Казань,Новосибирск,Екатеринбург,Нижний Новгород,Челябинск,Омск,Самара,Ростов-на-Дону,Уфа,Красноярск,Пермь,Воронеж,Волгоград,Краснодар,Саратов,Тюмень,Тольятти,Ижевск,Барнаул,Ульяновск,Иркутск,Хабаровск,Ярославль,Владивосток,Махачкала,Томск,Оренбург,Кемерово,Новокузнецк,Рязань,Астрахань,Пенза,Липецк,Киров,Чебоксары,Тула,Калининград,Балашиха,Курск,Ставрополь,Улан - Удэ,Севастополь,Тверь,Магнитогорск,Сочи,Иваново,Брянск,Комсомольск-на-Амуре');
/*!40000 ALTER TABLE `selects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
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
                           `content` text NOT NULL,
                           `watcher` varchar(500) DEFAULT NULL,
                           `matching` varchar(50) DEFAULT NULL,
                           `shared` tinyint(1) DEFAULT '0',
                           `category_id` int(11) DEFAULT NULL,
                           `autoinwork` int(1) DEFAULT NULL,
                           `checklist_id` int(11) DEFAULT NULL,
                           `matchings` varchar(200) DEFAULT NULL,
                           PRIMARY KEY (`id`),
                           KEY `fk_service_category_id` (`category_id`),
                           KEY `service_checklist_id_fk` (`checklist_id`),
                           CONSTRAINT `fk_service_category_id` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
                           CONSTRAINT `service_checklist_id_fk` FOREIGN KEY (`checklist_id`) REFERENCES `checklists` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (1,'Электронная почта','Управление электронной почтой, создание и удаление почтовых ящиков.','8x5 Basic','Низкий',NULL,NULL,90,'Первая линия поддержки','2',2,'<p>\r\n         Прошу создать ящик электронной почты в домене @univef.ru\r\n</p>',NULL,'',1,1,0,NULL,NULL),(2,'Обслуживание сторонних клиентов','Выездное обслуживание клиентов','8x5 Basic','Низкий',NULL,NULL,50,'Первая линия поддержки','2',1,'',NULL,'',1,2,0,NULL,'1'),(3,'Обслуживание внутренних клиентов','Обслуживание внутренних пользователей компании','8x5 Basic','Низкий',NULL,NULL,90,'Первая линия поддержки','2',NULL,'',NULL,'',1,2,0,1,NULL);
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `service_request_update` AFTER UPDATE ON `service` FOR EACH ROW
    IF(old.`name` <> new.`name`) THEN
        UPDATE `request` SET `request`.`service_name` = new.`name` WHERE `request`.`service_id` = old.`id`;
        UPDATE `problems` SET `problems`.`service` = new.`name` WHERE `problems`.`service` = old.`name`;
    END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_categories` (
                                      `id` int(11) NOT NULL AUTO_INCREMENT,
                                      `name` varchar(128) NOT NULL,
                                      PRIMARY KEY (`id`),
                                      UNIQUE KEY `idx_service_categories_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_categories`
--

LOCK TABLES `service_categories` WRITE;
/*!40000 ALTER TABLE `service_categories` DISABLE KEYS */;
INSERT INTO `service_categories` VALUES (2,'Внешний отдел'),(1,'Внутренний отдел');
/*!40000 ALTER TABLE `service_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sla`
--

DROP TABLE IF EXISTS `sla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sla` (
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
                       `autoCloseHours` int(1) NOT NULL DEFAULT '0',
                       `autoCloseStatus` int(11) DEFAULT '0',
                       PRIMARY KEY (`id`),
                       KEY `idx_autoCloseStatus` (`autoCloseStatus`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sla`
--

LOCK TABLES `sla` WRITE;
/*!40000 ALTER TABLE `sla` DISABLE KEYS */;
INSERT INTO `sla` VALUES (1,'8x5 Basic','01','00','02','00','01:00','02:00','01.01.*,02.01.*,03.01.*,04.01.*,05.01.*,06.01.*,07.01.*,08.01.*,22.02.*,23.02.*,08.03.*,01.05.*,03.05.*,09.05.*,10.05.*,12.06.*,14.06.*,04.11.*,05.11.*,31.12.*','90','08:00','18:00',0,0,'00','10','00','10','00:10','00:10',0,10),(2,'8x5 Optimal','08','00','24','00','08:00','24:00','01.01.*,02.01.*,03.01.*,04.01.*,05.01.*,06.01.*,07.01.*,08.01.*,22.02.*,23.02.*,08.03.*,01.05.*,03.05.*,09.05.*,10.05.*,12.06.*,14.06.*,04.11.*,05.11.*,31.12.*','92','09:00','17:00',0,0,'00','00','00','00','00:00','00:00',0,1),(3,'8x7 VIP','00','30','01','30','00:30','01:30','01.01.*,02.01.*,03.01.*,04.01.*,05.01.*,06.01.*,07.01.*,08.01.*,22.02.*,23.02.*,08.03.*,01.05.*,03.05.*,09.05.*,10.05.*,12.06.*,14.06.*,04.11.*,05.11.*,31.12.*','95','08:00','18:00',0,0,'00','00','00','00','00:00','00:00',0,1);
/*!40000 ALTER TABLE `sla` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 */ /*!50003 TRIGGER `sla_service_update` AFTER UPDATE ON `sla` FOR EACH ROW
    IF(old.`name` <> new.`name`) THEN
        UPDATE `service` SET `service`.`sla` = new.`name` WHERE `service`.`sla` = old.`name`;
    END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `sms`
--

DROP TABLE IF EXISTS `sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms` (
                       `id` int(10) NOT NULL AUTO_INCREMENT,
                       `name` varchar(50) DEFAULT NULL,
                       `content` varchar(500) DEFAULT NULL,
                       PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sms`
--

LOCK TABLES `sms` WRITE;
/*!40000 ALTER TABLE `sms` DISABLE KEYS */;
INSERT INTO `sms` VALUES (1,'default','#{id} Изменен статус заявки {status}'),(2,'SMS или боты заявка открыта','Здравствуйте, {fullname}!\nВы успешно зарегистрировали заявку №{id}.\nЗаявке назначен исполнитель: {manager_name}\nТелефон исполнителя: {manager_phone}'),(3,'SMS или боты заявка принята','{fullname}, ваша заявка принята в исполнение {fStartTime}!\nИсполнитель: {manager_name}\nТелефон исполнителя: {manager_phone}\nEmail исполнителя: {manager_email}'),(4,'SMS или боты заявка закрыта','Уважаемый {fullname}, Ваша заявка была закрыта исполнителем {manager_name} {fEndTime}.\nОцените пожалуйста качество работ!');
/*!40000 ALTER TABLE `sms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sreport`
--

DROP TABLE IF EXISTS `sreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sreport` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sreport`
--

LOCK TABLES `sreport` WRITE;
/*!40000 ALTER TABLE `sreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `sreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscribers`
--

DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscribers` (
                               `user_id` int(11) NOT NULL,
                               `subscriber_id` varchar(160) NOT NULL,
                               `chrome` tinyint(1) DEFAULT NULL,
                               UNIQUE KEY `idx_uq_subscribers_user_id` (`user_id`),
                               UNIQUE KEY `idx_uq_subscribers_subscriber_id` (`subscriber_id`),
                               UNIQUE KEY `idx_subscribers_user_subscriber` (`user_id`,`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscribers`
--

LOCK TABLES `subscribers` WRITE;
/*!40000 ALTER TABLE `subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sureport`
--

DROP TABLE IF EXISTS `sureport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sureport` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `dept` varchar(100) NOT NULL,
                            `type` varchar(100) NOT NULL,
                            `count` int(10) NOT NULL,
                            `summary` varchar(50) NOT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sureport`
--

LOCK TABLES `sureport` WRITE;
/*!40000 ALTER TABLE `sureport` DISABLE KEYS */;
/*!40000 ALTER TABLE `sureport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_columns`
--

DROP TABLE IF EXISTS `tbl_columns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_columns` (
                               `id` varchar(100) NOT NULL,
                               `data` varchar(1024) DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_columns`
--

LOCK TABLES `tbl_columns` WRITE;
/*!40000 ALTER TABLE `tbl_columns` DISABLE KEYS */;
INSERT INTO `tbl_columns` VALUES ('assets-grid_1','date||asset_attrib_name||name||cusers_fullname||cusers_dept||slabel||location||inventory||cost||Действия'),('companies-grid_1','name||contact_name||phone||email||add1||ff_id_6||Действия'),('companies-grid_2','name||director||email||contact_name||manager||Действия'),('cunits-grid_1','date||type||name||slabel||company||fullname||dept||inventory||location||cost||Действия'),('cusers-grid_1','photo||fullname||company||department||position||Email||Phone||role_name||Действия'),('cusers-grid_2','fullname||company||department||position||Email||Phone||role_name||Действия'),('cusers-grid_3','fullname||company||department||position||Email||Phone||role_name||Действия'),('leads-grid_1','status||name||created||creator||manager||company||contact||contact_phone||contact_email||contact_position||cost||Действия'),('phonebook-grid_1','photo||fullname||city||department||Phone||intphone||mobile||position||Email'),('phonebook-grid_2','photo||fullname||city||department||Phone||intphone||mobile||position||Email'),('phonebook-grid_3','photo||fullname||city||department||Phone||intphone||mobile||position||Email'),('problems-grid_1','slabel||date||creator||priority||category||manager||description||Действия'),('problems-grid_2','slabel||date||creator||priority||category||manager||Действия'),('problems-grid_3','slabel||date||creator||priority||category||manager||Действия'),('request-grid-full-report_1','channel||slabel||delays||delayedHours||Date||StartTime||fStartTime||EndTime||fEndTime||lead_time||Name||phone||room||Address||company||fullname||cunits||service_name||mfullname||groups_id||ZayavCategory_id||Priority||Content||rating'),('request-grid-full2_1','channel||rating||slabel||Date||EndTime||Name||fullname||mfullname||Действия'),('request-grid-full2_2','channel||rating||slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия'),('request-grid-full2_3','rating||slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия'),('request-grid-full_1','rating||slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия'),('request-grid-full_2','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия'),('request-grid-full_3','channel||rating||slabel||Date||EndTime||Name||mfullname||Действия'),('request-grid_1','slabel||Date||EndTime||Name||fullname||Действия'),('request-grid_2','slabel||Date||EndTime||Name||fullname||mfullname||ZayavCategory_id||Priority||Действия'),('request-grid_3','slabel||EndTime||Name||mfullname||Действия');
/*!40000 ALTER TABLE `tbl_columns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_migration`
--

DROP TABLE IF EXISTS `tbl_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_migration` (
                                 `version` varchar(255) NOT NULL,
                                 `apply_time` int(11) DEFAULT NULL,
                                 PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_migration`
--

LOCK TABLES `tbl_migration` WRITE;
/*!40000 ALTER TABLE `tbl_migration` DISABLE KEYS */;
INSERT INTO `tbl_migration` VALUES ('m160621_080728_service_name_100',1467292342),('m160629_070325_required_field',1467292477),('m160914_074331_30914',1472496373),('m160915_184634_optimiz',1473970482),('m160918_095313_030918',1473970482),('m160919_120214_309182',1473970482),('m161030_165202_31030',1477850795),('m161125_105300_31130',1477850795),('m161212_073006_31212',1477850795),('m170115_182307_40116',1477850795),('m170302_095633_40302',1477850795),('m170322_083228_40420',1503911617),('m170621_101254_40630',1503911617),('m170718_094135_40730',1503911617),('m170817_101337_40830',1503911740),('m170910_093205_41002',1509710824),('m171009_104738_41130',1509710824),('m171106_084110_41220',1512738145),('m180124_081753_180124',1520064436),('m180204_135747_50204',1520064436),('m180207_070507_50303',1520064436),('m180320_093226_50404',1525334460),('m180424_083445_50505',1525334460),('m180814_070219_50815',1542004604),('m181112_061452_51114',1542004604),('m190213_154051_60103',1550075688),('m190319_120022_60610',1573111798),('m191101_080141_70000',1575011615),('m191101_102240_70000_2',1575011615),('m200406_080402_templates',1614598097),('m200618_070613_triggers',1614598097),('m200808_144222_checklists',1614598098),('m201226_154846_matchings',1614598098),('m210330_063158_pidindex',1641802034),('m210528_173227_create_request_processing_rules',1641802034),('m210903_200003_wbot',1641802035);
/*!40000 ALTER TABLE `tbl_migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teamviewer_sessions`
--

DROP TABLE IF EXISTS `teamviewer_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teamviewer_sessions` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
                                       `request_id` int(11) NOT NULL,
                                       `code` varchar(32) NOT NULL,
                                       `supporter_link` varchar(64) NOT NULL,
                                       `end_customer_link` varchar(64) NOT NULL,
                                       `valid_until` datetime NOT NULL,
                                       PRIMARY KEY (`id`),
                                       UNIQUE KEY `idx_teamviewer_sessions_request_id` (`request_id`),
                                       CONSTRAINT `fk_teamviewer_sessions_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teamviewer_sessions`
--

LOCK TABLES `teamviewer_sessions` WRITE;
/*!40000 ALTER TABLE `teamviewer_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `teamviewer_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uhistory`
--

DROP TABLE IF EXISTS `uhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uhistory` (
                            `id` int(10) NOT NULL AUTO_INCREMENT,
                            `uid` int(10) DEFAULT NULL,
                            `date` varchar(50) DEFAULT NULL,
                            `user` varchar(50) DEFAULT NULL,
                            `action` text,
                            PRIMARY KEY (`id`),
                            KEY `uid` (`uid`),
                            CONSTRAINT `FK_uhistory_cunits` FOREIGN KEY (`uid`) REFERENCES `cunits` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uhistory`
--

LOCK TABLES `uhistory` WRITE;
/*!40000 ALTER TABLE `uhistory` DISABLE KEYS */;
INSERT INTO `uhistory` VALUES (80,1,'27.12.2014 14:05','admin','Добавлен актив: <b>Системный блок ПК Кузнецова</b>. Инвентарный номер: <b>PC-125987</b>'),(81,1,'27.12.2014 14:05','admin','Добавлен актив: <b>Монитор Монитор Кузнецова</b>. Инвентарный номер: <b>MON-124598</b>'),(82,1,'27.12.2014 14:05','admin','Добавлена КЕ: Рабочая станция Рабочее место Кузнецова. Инвентарный номер WS-156798. Дата ввода в эксплуатацию: 27.12.2014. Дата вывода из эксплуатации: '),(83,1,'27.12.2014 14:09','admin','Добавлен актив: <b>Клавиатура Logitech Black Keyboard</b>. Инвентарный номер: <b>KB-125798</b>'),(84,1,'27.12.2014 14:09','admin','Добавлен актив: <b>Мышь Logitech Black Mouse</b>. Инвентарный номер: <b>MOU-156798</b>'),(85,1,'27.12.2014 14:09','admin','Добавлен актив: <b>Операционная система Windows 8.1 Pro</b>. Инвентарный номер: <b></b>'),(86,1,'24.11.2017 12:24','admin','Изменен статус КЕ: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #fcb117\">В ремонте</span>'),(87,1,'24.11.2017 12:24','admin','Изменен статус КЕ: <span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>'),(89,1,'25.11.2017 12:48','admin','Удален актив: <b> Windows 8.1 Pro</b>. Инвентарный номер: <b></b>'),(90,1,'25.11.2017 12:48','admin','Добавлен актив: <b>Операционная система Windows 8.1 Pro</b>. Инвентарный номер: <b></b>'),(91,1,'28.11.2017 15:15','admin','Удален актив: <b> Windows 8.1 Pro</b>. Инвентарный номер: <b></b>'),(92,1,'28.11.2017 15:15','admin','Добавлен актив: <b>Операционная система Windows 8.1 Pro</b>. Инвентарный номер: <b></b>'),(93,1,'07.12.2017 16:25','admin','Изменено описание: <b>asdsdasdas222</b>'),(94,1,'06.05.2019 13:10','admin','Изменено описание: <b><p>asdsdasdas222</p></b>');
/*!40000 ALTER TABLE `uhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit_templates`
--

DROP TABLE IF EXISTS `unit_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unit_templates` (
                                  `id` int(10) NOT NULL AUTO_INCREMENT,
                                  `name` varchar(100) NOT NULL,
                                  `content` text NOT NULL,
                                  `format` varchar(1) DEFAULT NULL,
                                  `type` int(1) NOT NULL,
                                  `type_name` varchar(100) NOT NULL,
                                  `page_format` varchar(50) DEFAULT 'A4',
                                  `page_width` int(11) DEFAULT NULL,
                                  `page_height` int(11) DEFAULT NULL,
                                  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit_templates`
--

LOCK TABLES `unit_templates` WRITE;
/*!40000 ALTER TABLE `unit_templates` DISABLE KEYS */;
INSERT INTO `unit_templates` VALUES (1,'Карточка КЕ','<h1><strong>Карточка КЕ №{id} </strong></h1>\n<h1><strong>{name}</strong></h1>\n<hr>\n<p>\n                        {QRCODE}\n</p>\n<hr>\n<p>\n   <strong>Тип КЕ:</strong> {type}\n</p>\n<p>\n   <strong>Статус:</strong> {status}\n</p>\n<p>\n   <strong>Инвентарный номер:</strong> {inventory}\n</p>\n<p>\n   <strong>Стоимость: </strong>{cost}\n</p>\n<p>\n   <strong>Пользователь:</strong> {username}\n</p>\n<p>\n   <strong>Отдел:</strong> {department}\n</p>\n<p>\n   <strong>Компания:</strong> {company}\n</p>\n<p>\n   <strong>Дата ввода в эксплуатацию: </strong>{startexpdate}\n</p>\n<p>\n   <strong>Дата вывода из эксплуатации: </strong>{endexpdate}\n</p>\n<p>\n   <strong>Местоположение:</strong> {location}\n</p>\n<p>\n   <strong>Описание:</strong> {description}\n</p>\n<p>\n                          {assets}\n</p>\n<table>\n<tbody>\n<tr>\n  <td>\n     <strong>Подпись ответственного ____________________                      </strong>\n  </td>\n  <td>\n     <strong>                                      Дата______________________</strong>\n  </td>\n</tr>\n</tbody>\n</table>','P',1,'КЕ','A4',NULL,NULL),(2,'Акт приема-передачи','<h1 class=\"text-center\">Акт приема-передачи оборудования №________</h1>\n<p style=\"text-align: right;\">\n                   Дата <u>{date}</u>\n</p>\n<p style=\"text-align: right;\">\n                   г. __________________\n</p>\n<p>\n              Данный документ, подтверждает, что один пользователь передал, указаное ниже оборудование, а другой пользователь это оборудование принял. По внешнему виду и составу оборудования претензий нет.\n</p>\n<hr id=\"horizontalrule\">\n<h4><strong>Передаваемое оборудование:</strong></h4>\n<table border=\"1px\">\n<tbody>\n<tr>\n  <td>\n     <strong>Наименование:</strong>\n  </td>\n  <td>\n     <strong>Тип КЕ:</strong>\n  </td>\n  <td>\n     <strong>Статус:</strong>\n  </td>\n  <td>\n     <strong>Инвентарный номер:</strong>\n  </td>\n  <td>\n     <strong>Дата ввода в эксплуатацию: </strong>\n  </td>\n</tr>\n<tr>\n  <td>\n                                {name}\n  </td>\n  <td>\n                                {type}\n  </td>\n  <td>\n                                {status}\n  </td>\n  <td>\n                                {inventory}\n  </td>\n  <td>\n                                {startexpdate}\n  </td>\n</tr>\n</tbody>\n</table>\n<h4>Состав оборудования:</h4>\n<p>\n        {assets}\n</p>\n<hr>\n<p>\n         Подписи сторон:\n</p>\n<p>\n   <strong>Оборудование передал ____________________/__________________</strong>\n</p>\n<p>\n   <strong>Оборудование принял   ____________________/__________________</strong>\n</p>','P',1,'КЕ','A4',NULL,NULL),(3,'Карточка актива','<h1 class=\"text-center\">Карточка актива №{id}</h1>\n<h1 class=\"text-center\"><span style=\"color: rgb(51, 51, 51);\">{name}</span></h1>\n<hr>\n<p>\n                   {QRCODE}\n</p>\n<hr>\n<p>\n   <strong>Тип актива:</strong> {type}\n</p>\n<p>\n   <strong>Статус:</strong> {status}\n</p>\n<p>\n   <strong>Инвентарный номер:</strong> {inventory}\n</p>\n<p>\n   <strong>Стоимость:</strong> {cost}\n</p>\n<p>\n   <strong>Пользователь:</strong> {username}\n</p>\n<p>\n   <strong>Отдел:</strong> {department}\n</p>\n<p>\n   <strong>Местоположение:</strong> {location}\n</p>\n<p><strong>Описание:</strong> {description}</p>\n<p>\n     {assets}\n</p>\n<hr id=\"horizontalrule\">\n<p>\n                   Подпись ответственного ____________________\n</p>\n<p>\n               Дата <u>{date}</u>\n</p>','P',2,'Актив','A4',NULL,NULL),(4,'Печатная форма заявки','<h3>Заявка № {id} \"{name}\"</h3>\n<table>\n<tbody>\n<tr>\n <th>\n     Статус\n </th>\n <td>\n     {status}\n </td>\n</tr>\n<tr>\n  <th>\n     Заказчик\n </th>\n <td>\n     {fullname}\n </td>\n</tr>\n<tr>\n  <th>\n     Исполнитель\n  </th>\n <td>\n     {manager_name}\n </td>\n</tr>\n<tr>\n  <th>\n     Телефон исполнителя\n  </th>\n <td>\n     {manager_phone}\n  </td>\n</tr>\n<tr>\n  <th>\n     Email исполнителя\n  </th>\n <td>\n     {manager_email}\n  </td>\n</tr>\n<tr>\n  <th>\n     Название\n </th>\n <td>\n     {name}\n </td>\n</tr>\n<tr>\n  <th>\n     Категория\n  </th>\n <td>\n     {category}\n </td>\n</tr>\n<tr>\n  <th>\n     Приоритет\n  </th>\n <td>\n     {priority}\n </td>\n</tr>\n<tr>\n  <th>\n     Создано\n  </th>\n <td>\n     {created}\n  </td>\n</tr>\n<tr>\n  <th>\n     Начало работ (план)\n  </th>\n <td>\n     {StartTime}\n  </td>\n</tr>\n<tr>\n  <th>\n     Начало работ (факт)\n  </th>\n <td>\n     {fStartTime}\n </td>\n</tr>\n<tr>\n  <th>\n     Окончание работ (план)\n </th>\n <td>\n     {EndTime}\n  </td>\n</tr>\n<tr>\n  <th>\n     Окончание работ (факт)\n </th>\n <td>\n     {fEndTime}\n </td>\n</tr>\n<tr>\n  <th>\n     Сервис\n </th>\n <td>\n     {service_name}\n </td>\n</tr>\n<tr>\n  <th>\n     Адрес\n  </th>\n <td>\n     {address}\n  </td>\n</tr>\n<tr>\n  <th>\n     Компания\n </th>\n <td>\n     {company}\n  </td>\n</tr>\n<tr>\n  <th>\n     Актив\n  </th>\n <td>\n     {unit}\n </td>\n</tr>\n</tbody>\n</table>\n<p><strong>Поля:</strong></p>\n<p>{fields}</p>\n<p><strong>Содержание:</strong></p>\n<p>{content}</p>\n<p><strong>Комментарии:</strong></p>\n<p>{comments}</p>','L',3,'Заявка','A4',NULL,NULL),(5,'Договор техподдержка','<p><strong>ДОГОВОР № {contract_number} ОТ {contract_date}<br></strong></p>\n<p><strong>на оказание услуг по технической поддержке программного обеспечения</strong></p><p></p>\n<p>г. Москва {contract_date}</p><p></p>\n<p>{contract_contractor} в лице {contract_contractor_director_position_write} {contract_contractor_director_write}, именуемый в дальнейшем «ИСПОЛНИТЕЛЬ», с одной стороны, и {contract_customer}, в лице {contract_customer_director_position_write}&nbsp; {contract_customer_director_write}, действующего на основании Устава, именуемое в дальнейшем «ЗАКАЗЧИК», с другой стороны, именуемые совместно \"Стороны\", заключили настоящий договор (далее, Договор) о нижеследующем:</p><p></p>\n<p><strong>1. ПРЕДМЕТ ДОГОВОРА</strong></p><p></p>\n<p>1.1. Исполнитель обязуется выполнять услуги технической поддержки Информационной Системы (ИС), а Заказчик обязуется оплатить эти услуги.</p>\n<p>1.2. В техническую поддержку ИС, оказываемую Исполнителем, входят следующие работы и услуги:</p>\n<p>1.2.1. Оказание помощи в решении технических проблем, связанных с эксплуатацией программных и аппаратных средств у Заказчика, в том числе, предоставление ответов на вопросы, консультаций по функционированию ИС.</p>\n<p>1.2.2. Рассмотрение и регистрация предложений Заказчика по развитию или модификации функций, выполняемых ИС.</p>\n<p>1.2.3. Оказание Заказчику содействия в настройке и эксплуатации ИС, при изменении конфигурации операционных систем и других базовых программных продуктов или технических средств Заказчика.</p>\n<p>1.3. Услуги по устранению существенных для Исполнителя замечаний и/или предложений Заказчика по работе ИС, не связанных напрямую с наличием ошибок внутри ИС, оказываются Исполнителем в рамках отдельных дополнительных соглашений к настоящему Договору, определяющих стоимость и сроки оказания данных услуг.</p><p></p>\n<p><strong>2. СТОИМОСТЬ УСЛУГ И ПОРЯДОК РАСЧЕТОВ</strong></p><p></p>\n<p>2.1. Стоимость услуг по настоящему Договору составляет <strong>{contract_cost}</strong> рублей (<strong>{contract_cost_write}</strong>).&nbsp;</p>\n<p>2.2. Расчет производятся Заказчиком путем безналичного перечисления денежных средств на р/с Исполнителя в течение 10 рабочих дней с момента подписания настоящего Договора.</p>\n<p>2.3. Обязательство по оплате считается выполненным с момента зачисления суммы, указанной в п. 2.1 Договора, на расчетный счет Исполнителя.&nbsp;</p>\n<p>2.4. В случае пролонгации Договора согласно п. 9.2, стоимость оказания услуг по настоящему Договору изменяется в соответствии с действующим на момент выставления счета прайс-листом. При этом Исполнитель выставляет счет на оплату услуг, а Заказчик оплачивает стоимость пролонгированного Договора без подписания каких-либо дополнительных соглашений.&nbsp;</p><p></p>\n<p><strong>3. УСЛОВИЯ И ПОРЯДОК ОРГАНИЗАЦИИ ТЕХНИЧЕСКОЙ ПОДДЕРЖКИ</strong></p><p></p>\n<p>3.1. Техническая поддержка оказывается Исполнителем с момента поступления на расчетный счет денежных средств, указанных в п. 2.1, в полном объеме.</p>\n<p>3.2. Техническая поддержка оказывается Исполнителем на основании запросов, поступивших исключительно от представителей Заказчика.</p>\n<p>3.3. Запросы на техническую поддержку осуществляются по телефону, E-mail или через систему заявок. Срок ответа Исполнителя на заявку Заказчика определяется характером возникающих вопросов, но не более трех рабочих дней. Исполнитель обязуется предпринять действия по разрешению возникших проблем в минимальный возможный срок.</p>\n<p>3.4. Техническая поддержка осуществляется Исполнителем посредством:</p>\n<ul><li>удаленного доступа к оборудованию;</li><li>выдачи рекомендации и технических консультаций по телефону, E-mail, ответами в системе заявок;</li><li>в форме предоставления консультаций.&nbsp;</li></ul>Услуги оказываются Исполнителем в рабочие дни согласно Производственному календарю РФ с 09-00 до 17-00 по местному времени.\n<p>3.5. Исполнитель не несет ответственности за допущенную задержку в оказании услуг по технической поддержке, если она была вызвана:</p>\n<ul><li>неготовностью персонала или технических средств Заказчика к оказанию услуг.</li><li>неисправностью внешних средств связи (телефонные линии, электронная почта, интернет).</li></ul><p>3.6. Оказание дополнительных услуг по технической поддержке осуществляется Исполнителем на основании заказа, поступившего от Заказчика. Заказ Стороны оформляют в виде дополнительного соглашения к настоящему Договору с указанием состава дополнительной услуги, срока исполнения и стоимости.&nbsp;&nbsp;</p><p><br></p><p></p>\n<p><strong>4. ПРОЦЕДУРА ПРИЕМКИ УСЛУГ ПО ТЕХНИЧЕСКОЙ ПОДДЕРЖКЕ</strong></p><p></p>\n<p>4.1. В случае отсутствия замечаний со стороны Заказчика, услуги по технической поддержке считаются выполненными надлежащим образом и приняты Заказчиком. При наличии замечаний, Заказчик в течение 10 рабочих дней с даты оказания услуг по технической поддержке направляет Исполнителю мотивированный отказ от приемки услуг.&nbsp;</p>\n<p>4.2. Исполнитель при получении мотивированного отказа обязан в течение 5 рабочих дней принять меры по устранению не оказанных или некачественно оказанных услуг. Недоработки устраняются Исполнителем своими силами и за свой счет.</p><p></p>\n<p><strong>5. ПРАВА И ОБЯЗАННОСТИ СТОРОН</strong></p><p></p>\n<p>5.1. Исполнитель обязуется качественно и оперативно оказывать услуги по технической поддержке согласно п.1.2 Договора. Услуги по технической поддержке будут предоставляться на квалифицированном профессиональном уровне в полном соответствии с действующими правилами.</p>\n<p>5.2. Исполнитель обязуется при выполнении настоящего Договора принять все возможные меры для сохранения в тайне любой информации, связанной с работой оборудования Заказчика, структурой сети Заказчика, используемым Заказчиком программным обеспечением и прочие сведения, разглашение которых может нанести ущерб Заказчику.</p>\n<p>5.3. Исполнитель вправе не оказывать услуги по технической поддержке Заказчику в случае наличия у него перед Исполнителем задолженности по другим оказанным услугам или договорам. При этом Исполнитель в письменной форме предупреждает об этом Заказчика.</p>\n<p>5.4. Заказчик вправе потребовать от Исполнителя список персонала, имеющего допуск к обслуживанию оборудования и программного обеспечения Заказчика по настоящему Договору.</p>\n<p>5.5. Заказчик вправе в любое время контролировать процесс выполнения услуг по настоящему Договору, без непосредственного вмешательства в работу представителей Исполнителя.</p>\n<p>5.6. Заказчик обязуется обеспечить беспрепятственный доступ персонала Исполнителя к оборудованию и ПО для выполнения работ и услуг по настоящему Договору.</p>\n<p>5.7. Заказчик обязуется при эксплуатации оборудования и ПО, обслуживаемого по настоящему Договору, соблюдать правила и нормы эксплуатации, предусмотренные инструкциями производителя.</p>\n<p>5.8. Заказчик обязуется произвести оплату согласно условиям настоящего Договора.</p><p></p>\n<p><strong>6. ОСОБЫЕ УСЛОВИЯ</strong></p><p></p>\n<p>6.1. В течение всего срока действия Договора, а также после его окончания Исполнитель обязуется сохранять в конфиденциальности любые данные Заказчика.</p><p></p>\n<p><strong>7. УСЛОВИЯ РАСТОРЖЕНИЯ</strong></p><p></p>\n<p>7.1. Договор может быть расторгнут по обоюдному согласию Сторон, о чем составляется соответствующий акт.</p>\n<p>7.2. Любая из Сторон вправе расторгнуть настоящий Договор путем письменного уведомления другой Стороны в случае наступления любого из нижеследующих событий:</p>\n<ul><li>если другая Сторона допустит существенное нарушение своих обязательств по настоящему Договору и после письменного извещения, указывающего на это нарушение, не исправит его в течение определенного времени, указанного в таком извещении.</li></ul>&nbsp;Существенным нарушением обязательств по настоящему Договору считается нарушение Исполнителем сроков оказания услуг по технической поддержке, при котором задержка составит более 30 календарных дней, либо нарушение Заказчиком сроков оплаты, при котором задержка составит более 60 календарных дней:\n<ul><li>если против&nbsp; другой&nbsp; Стороны возбуждается процедура банкротства или подается иск в связи с неплатежеспособностью, и если такое разбирательство не прекращается в течение 60 календарных дней со дня возбуждения</li><li>в&nbsp; случае&nbsp; возникновения&nbsp; форс-мажорных&nbsp; обстоятельств,&nbsp; при&nbsp; которых обстоятельства непреодолимой силы продолжают действовать более 1 месяца.</li></ul><p><br></p>\n<p><strong>8. ОБСТОЯТЕЛЬСТВА НЕПРЕОДОЛИМОЙ СИЛЫ</strong></p><p></p>\n<p>8.1. Стороны освобождаются от ответственности за частичное или полное неисполнение обязательств по Договору, если оно явилось следствием обстоятельств непреодолимой силы, а именно: пожара, наводнения, землетрясения, диверсии, военных действий или изменения законодательства, если эти обстоятельства непосредственно повлияли на исполнение договорных обязательств. При этом срок исполнения обязательств отодвигается соразмерно времени, в течение которого действовали такие обстоятельства.</p>\n<p>8.2. Сторона, которая подвергалась действию непреодолимой силы, должна уведомить об этом другую сторону в течение 10 дней с момента наступления этих обстоятельств, доказав существование обстоятельств непреодолимой силы достоверными документами.</p><p></p>\n<p><strong>9. СРОК ДЕЙСТВИЯ ДОГОВОРА</strong></p><p></p>\n<p>9.1. Договор вступает в силу с даты подписания, и действует до {contract_tildate}.</p>\n<p>9.2. По обоюдному решению сторон настоящий Договор может быть&nbsp;ежегодно пролонгирован сроком на один год. В этом случае одна из Сторон – инициатор действия в течение 30 календарных дней до завершения срока действия Договора уведомляет другую Сторону о своем намерении продлить действие Договора. Если вторая Сторона согласовывает продление срока действия Договора, то Исполнитель выставляет счет на оплату услуг, указанных в п. 1.2.<br>Решение Сторон о продлении срока действия Договора может быть оформлено протоколом переговоров Сторон.</p>\n<p>9.3. В случае, если по истечение 10 рабочих дней с даты окончания срока действия Договора от Заказчика не поступило мотивированной претензии по не оказанной или некачественно оказанной услуге или работе, все обязательства Исполнителя перед Заказчиком считаются выполнены в полном объеме и в установленный срок.</p><p></p>\n<p><strong>10. РАССМОТРЕНИЕ СПОРОВ</strong></p><p></p>\n<p>10.1. За неисполнение или ненадлежащее исполнение своих обязательств по настоящему Договору Стороны несут ответственность в соответствии с действующим Договором.</p>\n<p>10.2. Все споры, возникающие между Сторонами при исполнении настоящего Договора, разрешаются путем переговоров. В случае не достижения согласия между Сторонами спор передается на рассмотрение суда, в соответствии с действующим законодательством РФ.</p><p></p>\n<p><strong>11. ПРОЧИЕ УСЛОВИЯ</strong></p><p></p>\n<p>11.1. В случае, если выполнение настоящего Договора требует закупки дополнительного оборудования, расходных материалов, комплектующих или запасных частей, предназначенных для использования Заказчиком, закупка производится по согласованию Сторон за счет Заказчика</p>\n<p>11.2. Любые изменения и дополнения к Договору должны согласовываться Сторонами и оформляться в виде Дополнительных соглашений к настоящему Договору.</p>\n<p>11.3. Договор заключен в двух экземплярах, имеющих равную юридическую силу, по одному для каждой из Сторон.</p>\n<p>11.4. Контактные данные технической поддержки, указываются на сайте Исполнителя в&nbsp; разделе «Контакты» и могут быть изменены в течение срока действия Договора.</p><p></p>\n<p><strong>12. ЮРИДИЧЕСКИЕ АДРЕСА И РЕКВИЗИТЫ СТОРОН</strong></p>\n<p></p>\n<p><br></p>\n<table>  <tbody><tr>   <td>   <p><span class=\"msonormal0\"><strong>Исполнитель:</strong></span>\n      </p></td><td>\n   </td><td>\n   </td><td>   <p><span class=\"msonormal0\"><strong>Заказчик:</strong></span>\n      </p></td><td>\n  </td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">Наименование:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_contractor}</span>\n      </p></td><td>\n   </td><td>   <p><span class=\"msonormal0\">Наименование:</span>\n      </p></td><td>{contract_customer}\n  </td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">Адрес:</span></p></td><td><span class=\"msonormal0\">{contract_contractor_faddress}</span></td><td>\n   </td><td>   <p><span class=\"msonormal0\">Адрес:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_customer_faddress}</span>\n     </p></td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">Тел.:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_contractor_contact_phone}</span>\n      </p></td><td>\n   </td><td>   <p><span class=\"msonormal0\">Тел.:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_customer_contact_phone}</span>\n     </p></td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">ОГРН:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_contractor_ogrn}</span>\n      </p></td><td>\n   </td><td>   <p><span class=\"msonormal0\">ОГРН:</span>\n      </p></td><td>   <p>{contract_customer_ogrn}<br></p></td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">ИНН:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_contractor_inn}</span>\n      </p></td><td>\n   </td><td>   <p><span class=\"msonormal0\">ИНН:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_customer_inn}</span>\n     </p></td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">КПП:</span>\n      </p></td><td><span class=\"msonormal0\">{contract_contractor_kpp}</span></td><td>\n   </td><td>   <p><span class=\"msonormal0\">КПП:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_customer_kpp}</span>\n     </p></td></tr>    <tr>   <td>   <p><span class=\"msonormal0\">Р/сч:</span></p></td><td>{contract_contractor_schet}</td><td>\n   </td><td>   <p><span class=\"msonormal0\">Р/сч:</span>\n      </p></td><td>{contract_customer_schet}<p><span class=\"msonormal0\"></span>\n     </p></td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">Банк:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_contractor_bank}</span>\n      </p></td><td>\n   </td><td>   <p><span class=\"msonormal0\">Банк:</span>\n      </p></td><td>   <p>{contract_customer_bank}<br></p></td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">БИК:</span></p></td><td>{contract_contractor_bik}</td><td>\n   </td><td>   <p><span class=\"msonormal0\">БИК:</span>\n      </p></td><td>   <p>{contract_customer_bik}<br></p></td></tr>  <tr>   <td>   <p><span class=\"msonormal0\">Кор/сч:</span></p></td><td>{contract_contractor_korschet}</td><td>\n   </td><td>   <p><span class=\"msonormal0\">Кор/сч:</span>\n      </p></td><td>   <p><span class=\"msonormal0\">{contract_customer_korschet}</span>\n     </p></td></tr> </tbody></table>\n<p>&nbsp;\n  </p>\n<p>&nbsp;\n  </p>\n<table>  <tbody><tr>   <td>   <p><span class=\"msonormal0\">От   имени Исполнителя</span></p><p><span class=\"msonormal0\">&nbsp;</span>\n   </p><p><span class=\"msonormal0\">&nbsp;__________&nbsp;{contract_contractor_director} </span>\n      </p></td><td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td><td>   <p><span class=\"msonormal0\">От   имени Заказчика</span>\n   </p><p><span class=\"msonormal0\">&nbsp;</span>\n   </p><p><span class=\"msonormal0\">__________   </span>\n   <span class=\"msonormal0\">&nbsp;{contract_customer_director}</span>\n     </p></td></tr> </tbody></table>','P',4,'Договор','A4',NULL,NULL);
/*!40000 ALTER TABLE `unit_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ustatus`
--

DROP TABLE IF EXISTS `ustatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ustatus` (
                           `id` int(10) NOT NULL AUTO_INCREMENT,
                           `name` varchar(50) DEFAULT NULL,
                           `label` varchar(400) DEFAULT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ustatus`
--

LOCK TABLES `ustatus` WRITE;
/*!40000 ALTER TABLE `ustatus` DISABLE KEYS */;
INSERT INTO `ustatus` VALUES (1,'Используется','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #6ac28e\">Используется</span>'),(2,'В ремонте','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #fcb117\">В ремонте</span>'),(3,'В резерве','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #fcb117\">В резерве</span>'),(4,'На складе','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #58595b\">На складе</span>'),(5,'Списан','<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ffffff; vertical-align: baseline; white-space: nowrap; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25); border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; background-color: #eb5f69\">Списан</span>');
/*!40000 ALTER TABLE `ustatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zcategory`
--

DROP TABLE IF EXISTS `zcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zcategory` (
                             `id` int(11) NOT NULL AUTO_INCREMENT,
                             `name` varchar(100) NOT NULL COMMENT 'Название',
                             `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Активно',
                             `incident` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Инцидент',
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zcategory`
--

LOCK TABLES `zcategory` WRITE;
/*!40000 ALTER TABLE `zcategory` DISABLE KEYS */;
INSERT INTO `zcategory` VALUES (1,'Заявка на обслуживание',1,0),(2,'Инцидент',1,1),(3,'Ремонт оборудования',1,0);
/*!40000 ALTER TABLE `zcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zpriority`
--

DROP TABLE IF EXISTS `zpriority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zpriority` (
                             `id` int(10) NOT NULL AUTO_INCREMENT,
                             `name` varchar(50) DEFAULT NULL,
                             `cost` varchar(50) DEFAULT NULL,
                             `rcost` varchar(50) DEFAULT NULL,
                             `scost` varchar(50) DEFAULT NULL,
                             PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zpriority`
--

LOCK TABLES `zpriority` WRITE;
/*!40000 ALTER TABLE `zpriority` DISABLE KEYS */;
INSERT INTO `zpriority` VALUES (1,'Низкий','+30','+30','+30'),(2,'Средний','+0','+0','+0'),(3,'Высокий','-30','-30','-30'),(4,'Критический','-50','-50','-50');
/*!40000 ALTER TABLE `zpriority` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zstatus`
--

DROP TABLE IF EXISTS `zstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zstatus` (
                           `id` int(10) NOT NULL AUTO_INCREMENT,
                           `name` varchar(50) NOT NULL,
                           `enabled` tinyint(1) NOT NULL DEFAULT '1',
                           `label` varchar(400) NOT NULL,
                           `tag` varchar(100) NOT NULL,
                           `close` tinyint(2) NOT NULL DEFAULT '1',
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
                           `sort_id` int(11) DEFAULT NULL,
                           `is_need_comment` tinyint(1) DEFAULT '0',
                           `is_need_rating` tinyint(1) DEFAULT '0',
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zstatus`
--

LOCK TABLES `zstatus` WRITE;
/*!40000 ALTER TABLE `zstatus` DISABLE KEYS */;
INSERT INTO `zstatus` VALUES (1,'Открыта',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Открыта</span>','#6ac28e',1,1,1,1,0,1,0,0,'SMS или боты заявка открыта','Новая заказчик','default','Новая исполнитель','Уведомление наблюдателя','default','default',0,0,1,'default','default',1,0,0),(2,'Открыта повторно',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #6ac28e; vertical-align: baseline; white-space: nowrap; border: 1px solid #6ac28e; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Открыта повторно</span>','#6ac28e',9,0,0,1,0,0,0,0,'default','default','default','Заявка открыта повторно','default','default','default',0,0,0,'default','default',4,0,0),(3,'Принята в исполнение',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #5692bb; vertical-align: baseline; white-space: nowrap; border: 1px solid #5692bb; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Принята в исполнение</span>','#5692bb',2,1,1,0,0,0,0,0,'SMS или боты заявка принята','Заявка в работе заказчик','default','Заявка в работе исполнитель','Уведомление наблюдателя','default','default',0,0,0,'default','default',2,1,0),(4,'Просрочена реакция',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #fcb117; vertical-align: baseline; white-space: nowrap; border: 1px solid #fcb117; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Просрочена реакция</span>','#fcb117',4,0,0,1,0,0,0,0,'default','default','default','Просрочена реакция исполнитель','Уведомление наблюдателя','default','default',0,0,1,'default','Скоро просрочена реакция',6,0,0),(5,'Просрочено исполнение',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #756994; vertical-align: baseline; white-space: nowrap; border: 1px solid #756994; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Просрочено исполнение</span>','#756994',5,1,0,1,0,0,0,0,'default','Просрочена заявка заказчик','default','Просрочена заявка исполнитель','Уведомление наблюдателя','default','default',0,0,1,'default','Скоро просрочено решение',7,0,0),(6,'Отменена',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #f56954; vertical-align: baseline; white-space: nowrap; border: 1px solid #f56954; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Отменена</span>','#f56954',6,1,0,1,0,0,0,0,'default','Заявка отменена','default','Заявка отменена','Уведомление наблюдателя','default','default',0,0,0,'default','default',5,1,0),(7,'Требует уточнения',0,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #39cccc; vertical-align: baseline; white-space: nowrap; border: 1px solid #39cccc; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Требует уточнения</span>','#39cccc',8,1,0,0,0,0,0,0,'default','default','default','default','Уведомление наблюдателя','default','default',0,1,0,'default','default',8,0,0),(8,'Требует согласования',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #001f3f; vertical-align: baseline; white-space: nowrap; border: 1px solid #001f3f; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Требует согласования</span>','#001f3f',7,0,0,0,0,0,1,0,'default','default','default','default','Уведомление наблюдателя','Заявка требует согласования','default',0,0,0,'default','default',8,0,0),(9,'Согласовано',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #ff851b; vertical-align: baseline; white-space: nowrap; border: 1px solid #ff851b; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Согласовано</span>','#ff851b',0,0,0,1,0,0,0,0,'default','default','default','Заявка согласована','default','default','default',0,0,0,'default','default',9,0,0),(10,'Выполнена',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #d81b60; vertical-align: baseline; white-space: nowrap; border: 1px solid #d81b60; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Выполнена</span>','#d81b60',0,1,0,0,0,0,0,0,'default','Заявка завершена','default','default','default','default','default',0,0,0,'default','default',10,0,0),(11,'Завершена',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #58595b; vertical-align: baseline; white-space: nowrap; border: 1px solid #58595b; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Завершена</span>','#58595b',3,1,1,0,0,1,0,0,'SMS или боты заявка закрыта','Заявка завершена','default','Заявка завершена','Заявка завершена','default','default',0,0,0,'default','default',3,1,0),(12,'Приостановлена',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #248f8f; vertical-align: baseline; white-space: nowrap; border: 1px solid #248f8f; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Приостановлена</span>','#248f8f',10,1,0,1,0,0,0,0,'default','Заявка приостановлена','default','Заявка приостановлена','Заявка приостановлена','default','default',0,1,0,'default','default',11,1,0),(13,'Архив',1,'<span style=\"display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #8fc763; vertical-align: baseline; white-space: nowrap; border: 1px solid #8fc763; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;\">Архив</span>','#8fc763',0,0,0,0,0,0,0,0,'default','default','default','default','default','default','default',1,1,0,'default','default',0,0,0);
/*!40000 ALTER TABLE `zstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zstatus_to_roles`
--

DROP TABLE IF EXISTS `zstatus_to_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zstatus_to_roles` (
                                    `zstatus_id` int(10) NOT NULL,
                                    `roles_id` int(10) NOT NULL,
                                    UNIQUE KEY `ztor` (`zstatus_id`,`roles_id`),
                                    KEY `zstatus_id` (`zstatus_id`),
                                    KEY `roles_id` (`roles_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zstatus_to_roles`
--

LOCK TABLES `zstatus_to_roles` WRITE;
/*!40000 ALTER TABLE `zstatus_to_roles` DISABLE KEYS */;
INSERT INTO `zstatus_to_roles` VALUES (1,1),(1,2),(1,3),(2,1),(2,2),(3,1),(3,3),(4,1),(5,1),(6,1),(6,2),(7,1),(7,3),(8,1),(8,2),(8,3),(9,1),(9,2),(9,3),(10,1),(10,2),(11,1),(11,3),(13,1);
/*!40000 ALTER TABLE `zstatus_to_roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-01-10 11:12:26