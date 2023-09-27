<?php

class m200618_070613_triggers extends CDbMigration
{
    public function up()
    {
        //re-create triggers
        $this->execute('
            DROP TRIGGER IF EXISTS `update_manager_request`;
            DROP TRIGGER IF EXISTS `update_user_request`;
            DROP TRIGGER IF EXISTS `company_update_users`;
            DROP TRIGGER IF EXISTS `company_update_request`;
            DROP TRIGGER IF EXISTS `service_problems_update`;
            DROP TRIGGER IF EXISTS `service_request_update`;
            DROP TRIGGER IF EXISTS `sla_service_update`;
            DROP TRIGGER IF EXISTS `update_user_name`;
            DROP TRIGGER IF EXISTS `depart_request_update`;
		');

        $this->execute('
            DROP TRIGGER IF EXISTS `depart_request_update`;
            CREATE TRIGGER `depart_request_update` AFTER UPDATE ON `depart` FOR EACH ROW
            IF(old.`name` <> new.`name`) THEN
            UPDATE `request` SET `request`.`depart` = new.`name` WHERE `request`.`depart_id` = old.`id`;
            END IF;
		');

        $this->execute('
			CREATE TRIGGER `update_user_name` BEFORE UPDATE ON `CUsers` FOR EACH ROW            
			IF(old.`Username` <> new.`Username`) THEN UPDATE `request` SET `request`.`CUsers_id` = new.`Username` WHERE `request`.`CUsers_id` = old.`Username`;
			UPDATE `request` SET `request`.`phone` = new.`Phone` WHERE `request`.`CUsers_id` = old.`Username`;
			UPDATE `cunits` SET `cunits`.`user` = new.`Username` WHERE `cunits`.`user` = old.`Username`;
			UPDATE `asset` SET `asset`.`cusers_name` = new.`Username` WHERE `asset`.`cusers_name` = old.`Username`;
			END IF;
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
            UPDATE `request` SET `request`.`service_name` = new.`name` WHERE `request`.`service_id` = old.`id`;
            UPDATE `problems` SET `problems`.`service` = new.`name` WHERE `problems`.`service` = old.`name`;
            END IF;
    
            CREATE TRIGGER `sla_service_update` AFTER UPDATE ON `sla` FOR EACH ROW
            IF(old.`name` <> new.`name`) THEN
            UPDATE `service` SET `service`.`sla` = new.`name` WHERE `service`.`sla` = old.`name`;
            END IF;
	'   );
    }

    public function down()
    {
        echo "m200618_070613_triggers does not support migration down.\n";
        return false;
    }

    /*
    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}