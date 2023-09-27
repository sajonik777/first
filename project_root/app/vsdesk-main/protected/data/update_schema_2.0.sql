-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET NAMES utf8;
-- -------------------------------------------
-- -------------------------------------------
-- START UPDATE
-- -------------------------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- reply_templates
--

  CREATE TABLE IF NOT EXISTS `reply_templates` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) DEFAULT NULL,
    `content` text,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE = utf8_general_ci;

-- -------------------------------------------
-- TABLE DATA reply_templates
-- -------------------------------------------
INSERT INTO `reply_templates` (`id`,`name`,`content`) VALUES
  ('1','Недостаточно информации для выполнения заявки','<p>

	 <strong>Уважаемый {fullname}, для выполнения вашей заявки №{id} недостаточно информации, уточните пожалуйста следующее:</strong>

</p>');


--
-- unit_templates
--

CREATE TABLE IF NOT EXISTS `unit_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `format` varchar(1) DEFAULT NULL,
  `type` int(1) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE = utf8_general_ci;


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

--
-- zstatus_to_roles
--

CREATE TABLE IF NOT EXISTS `zstatus_to_roles` (
  `zstatus_id` int(10) NOT NULL,
  `roles_id` int(10) NOT NULL,
  UNIQUE KEY `ztor` (`zstatus_id`,`roles_id`),
  KEY `zstatus_id` (`zstatus_id`),
  KEY `roles_id` (`roles_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_general_ci;

CREATE INDEX roles_id ON zstatus_to_roles (roles_id ASC);
CREATE INDEX zstatus_id ON zstatus_to_roles (zstatus_id ASC);

ALTER TABLE zstatus_to_roles ADD CONSTRAINT ztor UNIQUE (zstatus_id,roles_id);

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
-- TABLE `tbl_columns`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_columns`;
CREATE TABLE IF NOT EXISTS `tbl_columns` (
  `id` varchar(100) NOT NULL,
  `data` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -------------------------------------------
-- TABLE `tbl_migrations`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_migrations`;
CREATE TABLE `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_migrations` (`version`,`apply_time`) VALUES
  ('m160621_080728_service_name_100','1467292342');

INSERT INTO `tbl_migrations` (`version`,`apply_time`) VALUES
  ('m160629_070325_required_field','1467292477');

INSERT INTO `tbl_migrations` (`version`,`apply_time`) VALUES
  ('m160914_074331_30914','1472496373');

INSERT INTO `tbl_migrations` (`version`,`apply_time`) VALUES
  ('m160914_074331_30918','1473970482');

INSERT INTO `tbl_migrations` (`version`,`apply_time`) VALUES
  ('m160919_120214_309182','1473970482');

-- -------------------------------------------
-- TABLE `comments`
-- -------------------------------------------
ALTER TABLE comments
ADD files TEXT NOT NULL AFTER comment;
ALTER TABLE comments
ADD recipients VARCHAR(50) NOT NULL AFTER files;
ALTER TABLE comments
ADD `show` INT(1) NOT NULL AFTER comment;
ALTER TABLE comments
ADD readership VARCHAR(255) NOT NULL;


ALTER TABLE cunits CHANGE COLUMN name name VARCHAR(100) NULL COMMENT '';

ALTER TABLE astatus ADD tag VARCHAR(50) NOT NULL AFTER name;

--
-- CUsers
--

ALTER TABLE CUsers
ADD push_id VARCHAR(50) NOT NULL AFTER Phone;

--
-- depart
--

ALTER TABLE depart
ADD company VARCHAR(100) AFTER name;

--
-- request
--

ALTER TABLE request
ADD pid INT(10) NOT NULL AFTER id;
ALTER TABLE request
ADD timestampStart DATETIME AFTER timestamp;
ALTER TABLE request
ADD timestampfStart DATETIME AFTER timestampStart;
ALTER TABLE request
ADD timestampEnd DATETIME AFTER timestampfStart;
ALTER TABLE request
ADD timestampfEnd DATETIME AFTER timestampEnd;
ALTER TABLE request
ADD matching VARCHAR(50) AFTER watchers;
ALTER TABLE request
ADD correct_timestamp VARCHAR(50) AFTER update_by;
ALTER TABLE request
ADD rating INT(1) AFTER correct_timestamp;
ALTER TABLE request
ADD lead_time TIME AFTER rating;
ALTER TABLE request
ADD leaving INT(1) UNSIGNED DEFAULT 0 AFTER lead_time;
ALTER TABLE request
ADD contractors_id INT(11) AFTER leaving;
ALTER TABLE request
ADD re_leaving INT(1) UNSIGNED DEFAULT 0 AFTER contractors_id;
ALTER TABLE request
ADD groups_id INT(10) UNSIGNED AFTER re_leaving;
ALTER TABLE request
ADD fields_history VARCHAR(1024) NOT NULL AFTER groups_id;
ALTER TABLE request
ADD `key` VARCHAR(32) DEFAULT NULL AFTER fields_history;
ALTER TABLE request
  ADD delayed_start TINYINT(1) unsigned DEFAULT 0;
ALTER TABLE request
  ADD delayed_end TINYINT(1) unsigned DEFAULT 0;
ALTER TABLE request
  ADD timestampClose DATETIME DEFAULT NULL;
ALTER TABLE request
  ADD delayedHours INT(11) unsigned DEFAULT 0;
ALTER TABLE request
  ADD child VARCHAR(50) DEFAULT NULL AFTER pid;

--
-- service
--

ALTER TABLE service CHANGE COLUMN description description VARCHAR(100) NULL COMMENT '';
ALTER TABLE service CHANGE COLUMN name name VARCHAR(100) NULL COMMENT '';
ALTER TABLE service
ADD company_id INT(10) AFTER fieldset;
ALTER TABLE service
ADD company_name VARCHAR(100) AFTER company_id;
ALTER TABLE service
ADD content TEXT NOT NULL AFTER company_name;
ALTER TABLE service
ADD watcher VARCHAR(50) AFTER content;
ALTER TABLE service
ADD matching VARCHAR(50) AFTER watcher;

--
-- sla
--
ALTER TABLE sla
  ADD autoCloseHours INT(1) NOT NULL DEFAULT 0;


--
-- zstatus
--

ALTER TABLE zstatus
ADD notify_matching TINYINT(1) DEFAULT 0 NOT NULL AFTER notify_group;
ALTER TABLE zstatus
ADD notify_matching_sms TINYINT(1) DEFAULT 0 NOT NULL AFTER notify_matching;
ALTER TABLE zstatus
ADD matching_message VARCHAR(50) AFTER gmessage;
ALTER TABLE zstatus
ADD matching_sms VARCHAR(50) AFTER matching_message;


--
-- fieldsets_fields
--

ALTER TABLE fieldsets_fields
  ADD req TINYINT(1) DEFAULT 0 NOT NULL;


-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
