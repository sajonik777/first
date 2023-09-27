<?php

class m190213_154051_60103 extends CDbMigration
{
	public function up()
	{
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        if (Yii::app()->db->schema->getTable('escalates', true) === null) {
            $this->createTable('escalates', [
                'id' => 'pk',
                'service_id' => 'int(11) NOT NULL',
                'type_id' => 'tinyint(1) NOT NULL',
                'minutes' => 'int(11) DEFAULT 0',
                'manager_id' => 'int(11) DEFAULT NULL',
                'group_id' => 'int(11) DEFAULT NULL',
            ], $tableOptions);

            $this->createIndex('idx_escalates_service_id', 'escalates', 'service_id', false);
            $this->createIndex('idx_escalates_manager_id', 'escalates', 'manager_id', false);
            $this->createIndex('idx_escalates_group_id', 'escalates', 'group_id', false);
            $this->createIndex('idx_escalates_type_id', 'escalates', 'type_id', false);
            $this->addForeignKey('fk_escalates_service_id', 'escalates', 'service_id', 'service', 'id', 'CASCADE', 'NO ACTION');
            $this->addForeignKey('fk_escalates_manager_id', 'escalates', 'manager_id', 'СUsers', 'id', 'CASCADE', 'NO ACTION');
            $this->addForeignKey('fk_escalates_group_id', 'escalates', 'group_id', 'groups', 'id', 'CASCADE', 'NO ACTION');
        }
        if (Yii::app()->db->schema->getTable('request_escalates', true) === null) {
            $this->createTable('request_escalates', [
                'request_id' => 'int(11) NOT NULL',
                'escalate_id' => 'int(11) NOT NULL',
            ], $tableOptions);

            $this->createIndex('idx_request_escalates_request_id', 'request_escalates', 'request_id', false);
            $this->createIndex('idx_request_escalates_escalate_id', 'request_escalates', 'escalate_id', false);
            $this->addPrimaryKey('pk_request_escalates', 'request_escalates', ['request_id', 'escalate_id']);
            $this->addForeignKey('fk_request_escalates_request_id', 'request_escalates', 'request_id', 'request', 'id', 'CASCADE', 'NO ACTION');
            $this->addForeignKey('fk_request_escalates_escalate_id', 'request_escalates', 'escalate_id', 'escalates', 'id', 'CASCADE', 'NO ACTION');
        }

        $table = Yii::app()->db->schema->getTable('request');
        if (!isset($table->columns['paused'])) {
            $this->addColumn('request', 'paused', 'datetime default NULL');
        }
        if (!isset($table->columns['previous_paused_status_id'])) {
            $this->addColumn('request', 'previous_paused_status_id', 'int default NULL');
        }
        if (!isset($table->columns['paused_total_time'])) {
            $this->addColumn('request', 'paused_total_time', 'int DEFAULT 0');
        }

        if (Yii::app()->db->schema->getTable('asset_files', true) === NULL){
            $this->createTable('asset_files', [
                'asset_id' => 'int NOT NULL',
                'file_id' => 'int NOT NULL',
                'PRIMARY KEY(`asset_id`, `file_id`)'
            ], $tableOptions);
        }


        if (Yii::app()->db->schema->getTable('cunits_files', true) === NULL) {
            $this->createTable('cunits_files', [
                'cunits_id' => 'int NOT NULL',
                'file_id' => 'int NOT NULL',
                'PRIMARY KEY(`cunits_id`, `file_id`)'
            ], $tableOptions);
        }

        if (Yii::app()->db->schema->getTable('companies_files', true) === NULL){
            $this->createTable('companies_files', [
                'companies_id' => 'int NOT NULL',
                'file_id' => 'int NOT NULL',
                'PRIMARY KEY(`companies_id`, `file_id`)'
            ], $tableOptions);
        }

        $table2 = Yii::app()->db->schema->getTable('cunits');
        if(!isset($table2->columns['image'])) {
            $this->addColumn('cunits', 'image', 'varchar(500)');
        }

        $table3 = Yii::app()->db->schema->getTable('companies');
        if(!isset($table3->columns['image'])) {
            $this->addColumn('companies', 'image', 'varchar(500)');
        }

        $table4 = Yii::app()->db->schema->getTable('asset');
        if(!isset($table4->columns['image'])) {
            $this->addColumn('asset', 'image', 'varchar(500)');
        }

        $table5 = Yii::app()->db->schema->getTable('request');
        if (!isset($table5->columns['msbot_id'])) {
            $this->addColumn('request', 'msbot_id', 'varchar(255)');
        }

        $table6 = Yii::app()->db->schema->getTable('messages');
        if (!isset($table6->columns['static'])) {
            $this->addColumn('messages', 'static', 'int(1)');
        }

        $this->execute("ALTER TABLE `zstatus` CHANGE `close` `close` TINYINT(2) NOT NULL DEFAULT '1';");
        $exists = Status::model()->findByAttributes(['close' => '10']);
        if(!isset($exists)){
            $this->insert('zstatus', [
                'name' => 'Приостановлена',
                'enabled' => 1,
                'label' => '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: #39cccc; vertical-align: baseline; white-space: nowrap; border: 1px solid #39cccc; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">Приостановлена</span>',
                'tag' => '#39cccc',
                'close' => 10,
                'sms' => 'default',
                'message' => 'default',
                'msms' => 'default',
                'mmessage' => 'default',
                'gmessage' => 'Уведомление наблюдателя',
                'freeze' => 1,
                'mwsms' => 'default',
                'mwmessage' => 'default',
                //'static' => 0,
            ]);
        }
        $exists2 = Messages::model()->findByAttributes(['name' => '{escalate}']);
        if(!isset($exists2)) {
            $this->insert('messages', [
                'name' => '{escalate}',
                'subject' => '[Ticket #{id}] {name}',
                'content' => "<p>Произошла автоматическая эскалация заявки номер <b>{id}</b>&nbsp;<strong>{name}</strong></p><p>Был назначен новый исполнитель <b>{manager_name}</b>&nbsp;</p><p>Посмотреть заявку&nbsp;{url}</p>",
                'static' => 2
            ]);
        }

        if (Yii::app()->db->schema->getTable('MailQueue', true) === null){
            $this->execute('
            CREATE TABLE `MailQueue` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `from` varchar(100) NOT NULL,
              `to` varchar(100) NOT NULL,
              `subject` varchar(100) NOT NULL,
              `body` text,
              `attachs` text,
              `priority` SMALLINT (2) NOT NULL DEFAULT 0,
              `status` TINYINT(1) DEFAULT NULL,
              `createDate` datetime DEFAULT NULL,
              `updateDate` datetime DEFAULT NULL,              
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ');
        }
        $this->execute('
		DROP TRIGGER IF EXISTS `service_request_update`;
		CREATE TRIGGER `service_request_update` AFTER UPDATE ON `service` FOR EACH ROW
		IF(old.`name` <> new.`name`) THEN
		UPDATE `request` SET `request`.`service_name` = new.`name` WHERE `request`.`service_id` = old.`id`;
		UPDATE `problems` SET `problems`.`service` = new.`name` WHERE `problems`.`service` = old.`name`;
		END IF;
		');

        $path = __DIR__ . '/../../protected/config/portal.inc';
        if(!is_file($path)){
            touch($path);
            file_put_contents($path, "YTo3OntzOjE1OiJwb3J0YWxQaG9uZWJvb2siO3M6MToiMCI7czoxOToicG9ydGFsQWxsb3dSZWdpc3RlciI7czoxOiIxIjtzOjE4OiJwb3J0YWxBbGxvd1Jlc3RvcmUiO3M6MToiMSI7czoxNToicG9ydGFsQWxsb3dOZXdzIjtzOjE6IjEiO3M6MTM6InBvcnRhbEFsbG93S2IiO3M6MToiMSI7czoxODoicG9ydGFsQWxsb3dTZXJ2aWNlIjtzOjE6IjAiO3M6MTg6InBvcnRhbEFsbG93Q2FwdGNoYSI7czoxOiIxIjt9");

        }
	}

	public function down()
	{
		echo "m190213_154051_60103 does not support migration down.\n";
		return false;
	}

}