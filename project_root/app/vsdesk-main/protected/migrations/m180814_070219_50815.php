<?php

class m180814_070219_50815 extends CDbMigration
{
	public function up()
	{
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        $this->execute('
			DROP TRIGGER IF EXISTS `update_user_name`;
		');

        $this->execute('
			CREATE TRIGGER `update_user_name` BEFORE UPDATE ON `CUsers` FOR EACH ROW            
			IF(old.`Username` <> new.`Username`) THEN UPDATE `request` SET `request`.`CUsers_id` = new.`Username` WHERE `request`.`CUsers_id` = old.`Username`;
			UPDATE `request` SET `request`.`phone` = new.`Phone` WHERE `request`.`CUsers_id` = old.`Username`;
			UPDATE `cunits` SET `cunits`.`user` = new.`Username` WHERE `cunits`.`user` = old.`Username`;
			UPDATE `asset` SET `asset`.`cusers_name` = new.`Username` WHERE `asset`.`cusers_name` = old.`Username`;
			END IF;
		');

        if (null === Yii::app()->db->schema->getTable('teamviewer_sessions', true)) {
            $this->createTable('teamviewer_sessions', [
                'id' => 'pk',
                'request_id' => 'int(11) NOT NULL',
                'code' => 'varchar(32) NOT NULL',
                'supporter_link' => 'varchar(64) NOT NULL',
                'end_customer_link' => 'varchar(64) NOT NULL',
                'valid_until' => 'DATETIME NOT NULL',
            ], $tableOptions);
            $this->createIndex('idx_teamviewer_sessions_request_id', 'teamviewer_sessions', 'request_id', true);
            $this->addForeignKey('fk_teamviewer_sessions_request', 'teamviewer_sessions', 'request_id', 'request', 'id', 'CASCADE', 'NO ACTION');
        }

        //ad photo
        $table = Yii::app()->db->schema->getTable('CUsers');
        if(!isset($table->columns['photo'])) {
            $this->addColumn('CUsers', 'photo', 'int(1) DEFAULT "0"');
        }
        if(!isset($table->columns['city'])) {
            $this->addColumn('CUsers', 'city', 'varchar(50) DEFAULT NULL');
        }
        if(!isset($table->columns['mobile'])) {
            $this->addColumn('CUsers', 'mobile', 'varchar(50) DEFAULT NULL');
        }

        $table2 = Yii::app()->db->schema->getTable('request');
        if(isset($table2->columns['company'])) {
            $this->alterColumn('request', 'company', 'varchar(100)');
        }
        if(isset($table2->columns['Address'])) {
            $this->alterColumn('request', 'Address', 'varchar(200)');
        }

        $path = __DIR__ . '/../../media/userphoto/';
        if(!is_dir($path)){
            mkdir(__DIR__ . '/../../media/userphoto/');
        }

        $table3 = Yii::app()->db->schema->getTable('request');
        if (!isset($table3->columns['gr_id'])) {
            $this->addColumn('request', 'gr_id', 'int(10) DEFAULT NULL');
            $this->createIndex('idx_gr_id', 'request', 'gr_id', false);
        }

        $table4 = Yii::app()->db->schema->getTable('groups');
        if (!isset($table4->columns['send'])) {
            $this->addColumn('groups', 'send', 'int(1) DEFAULT "0"');
            $this->createIndex('idx_send', 'groups', 'send', false);
        }

        $table5 = Yii::app()->db->schema->getTable('sla');
        if (!isset($table5->columns['autoCloseStatus'])) {
            $this->addColumn('sla', 'autoCloseStatus', 'int DEFAULT "0"');
            $this->createIndex('idx_autoCloseStatus', 'sla', 'autoCloseStatus', false);
        }

        $this->execute("ALTER TABLE `CUsers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
	}

	public function down()
	{
		echo "m180814_070219_50815 does not support migration down.\n";
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