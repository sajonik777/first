<?php

class m170322_083228_40420 extends CDbMigration
{
    public function up()
    {
        $this->execute('
        DROP TRIGGER IF EXISTS `update_manager_request`;
        DROP TRIGGER IF EXISTS `update_user_request`;
        DROP TRIGGER IF EXISTS `company_update_users`;
        DROP TRIGGER IF EXISTS `company_update_request`;
        DROP TRIGGER IF EXISTS `service_request_update`;
        DROP TRIGGER IF EXISTS `service_problems_update`;
        ');
        $this->execute('
	      CREATE TRIGGER `update_manager_request` BEFORE UPDATE ON `CUsers` FOR EACH ROW IF(old.`fullname` <> new.`fullname`) THEN UPDATE `request` SET `mfullname` = new.`fullname` WHERE `Managers_id` = old.`Username`; END IF;
        CREATE TRIGGER `update_user_request` AFTER UPDATE ON `CUsers` FOR EACH ROW IF(old.`fullname` <> new.`fullname`) THEN  UPDATE `request` SET `fullname` = new.`fullname` WHERE `CUsers_id` = old.`Username`; END IF;
        CREATE TRIGGER `company_update_users` BEFORE UPDATE ON `companies` FOR EACH ROW IF(old.`name` <> new.`name`) THEN  UPDATE `CUsers` SET `company` = new.`name` WHERE `company` = old.`name`; END IF;
        CREATE TRIGGER `company_update_request` AFTER UPDATE ON `companies` FOR EACH ROW IF(old.`name` <> new.`name`) THEN UPDATE `request` SET `request`.`company` = new.`name` WHERE `request`.`company` = old.`name`; END IF;
        CREATE TRIGGER `service_request_update` BEFORE UPDATE ON `service` FOR EACH ROW IF(old.`name` <> new.`name`) THEN UPDATE `request` SET `request`.`service_name` = new.`name` WHERE `request`.`service_name` = old.`name`; END IF;
        CREATE TRIGGER `service_problems_update` AFTER UPDATE ON `service` FOR EACH ROW IF(old.`name` <> new.`name`) THEN UPDATE `problems` SET `problems`.`service` = new.`name` WHERE `problems`.`service` = old.`name`; END IF;
	    ');
        $requests = Request::model()->findAll();
        foreach ($requests as $request){
            $username = CUsers::model()->findByAttributes(array('Username' => $request->CUsers_id));
            if (isset($username) AND !empty($username)){
                Request::model()->updateByPk($request->id, array('company' => $username->company));
            }
            $service = Service::model()->findByPk($request->service_id);
            if (isset($service) AND !empty($service)){
                Request::model()->updateByPk($request->id, array('service_name' => $service->name));
            }
        }
    }

    public function down()
    {
        $this->execute('
        DROP TRIGGER IF EXISTS `update_manager_request`;
        DROP TRIGGER IF EXISTS `update_user_request`;
        DROP TRIGGER IF EXISTS `company_update_users`;
        DROP TRIGGER IF EXISTS `company_update_request`;
        DROP TRIGGER IF EXISTS `service_request_update`;
        DROP TRIGGER IF EXISTS `service_problems_update`;
        ');
    }
}
