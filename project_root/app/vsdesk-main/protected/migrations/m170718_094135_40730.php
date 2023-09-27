<?php

class m170718_094135_40730 extends CDbMigration
{
	public function up()
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

		$table = Yii::app()->db->schema->getTable('request');
		if(isset($table->columns['ZayavCategory_id'])) {
				$this->alterColumn('request', 'ZayavCategory_id', 'varchar(100)');
		}
		$table2 = Yii::app()->db->schema->getTable('service');
		if(isset($table2->columns['watcher'])) {
				$this->alterColumn('service', 'watcher', 'varchar(500)');
		}
		$table3 = Yii::app()->db->schema->getTable('request');
		if(isset($table3->columns['Name'])) {
				$this->alterColumn('request', 'Name', 'varchar(100) DEFAULT NULL');
		}
		if (Yii::app()->db->schema->getTable('banlist', true) === null) {
				$this->createTable('banlist', [
						'id' => 'pk',
						'value' => 'varchar(100) NOT NULL',
				], $tableOptions);
		}

		//re-create triggers
		$this->execute('
		DROP TRIGGER IF EXISTS `update_manager_request`;
		DROP TRIGGER IF EXISTS `update_user_request`;
		DROP TRIGGER IF EXISTS `company_update_users`;
		DROP TRIGGER IF EXISTS `company_update_request`;
		DROP TRIGGER IF EXISTS `service_problems_update`;
		DROP TRIGGER IF EXISTS `service_request_update`;
		DROP TRIGGER IF EXISTS `sla_service_update`;
		');
		$this->execute('
		CREATE TRIGGER `update_manager_request` AFTER UPDATE ON `CUsers` FOR EACH ROW
		IF(old.`fullname` <> new.`fullname`) THEN UPDATE `request` SET `mfullname` = new.`fullname` WHERE `Managers_id` = old.`Username`;
		UPDATE `request` SET `request`.`creator` = new.`fullname` WHERE `request`.`creator` = old.`fullname`;
		UPDATE `cunits` SET `cunits`.`fullname` = new.`fullname` WHERE `cunits`.`fullname` = old.`fullname`;
		UPDATE `asset` SET `asset`.`cusers_fullname` = new.`fullname` WHERE `asset`.`cusers_fullname` = old.`fullname`;
		UPDATE `request` SET `request`.`fullname` = new.`fullname` WHERE `request`.`CUsers_id` = old.`Username`;
		END IF;

		CREATE TRIGGER `company_update_users` AFTER UPDATE ON `companies` FOR EACH ROW
		IF(old.`name` <> new.`name`) THEN  UPDATE `CUsers` SET `CUsers`.`company` = new.`name` WHERE `CUsers`.`company` = old.`name`;
		UPDATE `request` SET `request`.`company` = new.`name` WHERE `request`.`company` = old.`name`;
		UPDATE `cunits` SET `cunits`.`company` = new.`name` WHERE `cunits`.`company` = old.`name`;
		UPDATE `depart` SET `depart`.`company` = new.`name` WHERE `depart`.`company` = old.`name`;
		END IF;

		CREATE TRIGGER `service_request_update` AFTER UPDATE ON `service` FOR EACH ROW
		IF(old.`name` <> new.`name`) THEN
		UPDATE `request` SET `request`.`service_name` = new.`name` WHERE `request`.`service_name` = old.`name`;
		UPDATE `problems` SET `problems`.`service` = new.`name` WHERE `problems`.`service` = old.`name`;
		END IF;

		CREATE TRIGGER `sla_service_update` AFTER UPDATE ON `sla` FOR EACH ROW
		IF(old.`name` <> new.`name`) THEN
		UPDATE `service` SET `service`.`sla` = new.`name` WHERE `service`.`sla` = old.`name`;
		END IF;
	');
	}

	public function down()
	{
		echo "m170718_094139_0730 does not support migration down.\n";
		return false;
	}
}
