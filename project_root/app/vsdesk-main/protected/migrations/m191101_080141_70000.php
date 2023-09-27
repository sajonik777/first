<?php

class m191101_080141_70000 extends CDbMigration
{
	public function up()
	{
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        if (Yii::app()->db->schema->getTable('service_categories', true) === null) {
            $this->createTable('service_categories', [
                'id' => 'pk',
                'name' => 'varchar(128) NOT NULL',
            ], $tableOptions);

            $this->createIndex('idx_service_categories_name', 'service_categories', 'name', true);
        }

        $table = Yii::app()->db->schema->getTable('CUsers');
        if(!isset($table->columns['tbot'])) {
            $this->addColumn('CUsers', 'tbot', 'varchar(50)');
        }
        if(!isset($table->columns['vbot'])) {
            $this->addColumn('CUsers', 'vbot', 'varchar(50)');
        }
        if(!isset($table->columns['msbot'])) {
            $this->addColumn('CUsers', 'msbot', 'varchar(50)');
        }

        $exists = Messages::model()->findByAttributes(['name' => '{comments}']);
        if(!isset($exists)) {
            $this->insert('messages', [
                'name' => '{comments}',
                'subject' => '[Ticket #{id}] {name}',
                'content' => "<p><strong>Добавлен новый комментарий:</strong></p><p>{date} {author} написал:&nbsp;</p><p>{comment}</p><p>Посмотреть заявку {url}</p><p>{comments_list}</p>",
                'static' => 3
            ]);
        }

        $table2 = Yii::app()->db->schema->getTable('request');
        if(!isset($table2->columns['company_id'])) {
            $this->addColumn('request', 'company_id', 'int(11)');
        }
        if(!isset($table2->columns['depart_id'])) {
            $this->addColumn('request', 'depart_id', 'int(11)');
        }
        if(isset($table2->columns['fullname'])) {
            $this->alterColumn('request', 'fullname', 'varchar(100)');
        }
        if(isset($table2->columns['gfullname'])) {
            $this->alterColumn('request', 'gfullname', 'varchar(100)');
        }
        if(isset($table2->columns['depart'])) {
            $this->alterColumn('request', 'depart', 'varchar(100)');
        }
        if(isset($table2->columns['mfullname'])) {
            $this->alterColumn('request', 'mfullname', 'varchar(100)');
        }
        if (!isset($table2->columns['msbot_id'])) {
            $this->addColumn('request', 'msbot_id', 'varchar(255) default NULL');
        }
        if (!isset($table2->columns['msbot_params'])) {
            $this->addColumn('request', 'msbot_params', 'text');
        }
        if (!isset($table2->columns['service_category_id'])) {
            $this->addColumn('request', 'service_category_id', 'int(11) DEFAULT NULL');
        }
        if (!isset($table2->columns['creator_id'])) {
            $this->addColumn('request', 'creator_id', 'int(11) DEFAULT NULL');
        }

        $table3 = Yii::app()->db->schema->getTable('depart');
        if(!isset($table3->columns['manager_id'])) {
            $this->addColumn('depart', 'manager_id', 'int(11)');
        }
        if(!isset($table3->columns['manager'])) {
            $this->addColumn('depart', 'manager', 'varchar(100)');
        }

        $table4 = Yii::app()->db->schema->getTable('zstatus');
        if (!isset($table4->columns['is_need_comment'])) {
            $this->addColumn('zstatus', 'is_need_comment', 'boolean default 0');
        }
        if (!isset($table4->columns['is_need_rating'])) {
            $this->addColumn('zstatus', 'is_need_rating', 'boolean default 0');
        }

        $table5 = Yii::app()->db->schema->getTable('service');
        if (!isset($table5->columns['category_id'])) {
            $this->addColumn('service', 'category_id', 'int(11) DEFAULT NULL');
            $this->addForeignKey('fk_service_category_id', 'service', 'category_id', 'service_categories', 'id', 'SET NULL', 'NO ACTION');
        }
        if(!isset($table5->columns['autoinwork'])) {
            $this->addColumn('service', 'autoinwork', 'int(1)');
        }


        $idx_Managers_id = Yii::app()->db->createCommand('SHOW INDEX FROM request WHERE KEY_NAME = "idx_Managers_id"')->queryRow();
        if($idx_Managers_id == 0){
            $this->createIndex('idx_Managers_id', 'request', 'Managers_id', false);
        }

        $idx_company = Yii::app()->db->createCommand('SHOW INDEX FROM request WHERE KEY_NAME = "idx_company"')->queryRow();
        if($idx_company == 0) {
            $this->createIndex('idx_company', 'request', 'company', false);
        }

        $idx_Status = Yii::app()->db->createCommand('SHOW INDEX FROM request WHERE KEY_NAME = "idx_Status"')->queryRow();
        if($idx_Status == 0) {
            $this->createIndex('idx_Status', 'request', 'Status', false);
        }

        $idx_timestamp = Yii::app()->db->createCommand('SHOW INDEX FROM request WHERE KEY_NAME = "idx_timestamp"')->queryRow();
        if($idx_timestamp == 0) {
            $this->createIndex('idx_timestamp', 'request', 'timestamp', false);
        }

        $idx_groups_id = Yii::app()->db->createCommand('SHOW INDEX FROM request WHERE KEY_NAME = "idx_groups_id"')->queryRow();
        if($idx_groups_id == 0) {
            $this->createIndex('idx_groups_id', 'request', 'groups_id', false);
        }

        $idx_rating = Yii::app()->db->createCommand('SHOW INDEX FROM request WHERE KEY_NAME = "idx_rating"')->queryRow();
        if($idx_rating == 0) {
            $this->createIndex('idx_rating', 'request', 'rating', false);
        }

        $this->execute('
		DROP TRIGGER IF EXISTS `depart_request_update`;
		CREATE TRIGGER `depart_request_update` AFTER UPDATE ON `depart` FOR EACH ROW
		IF(old.`name` <> new.`name`) THEN
		UPDATE `request` SET `request`.`depart` = new.`name` WHERE `request`.`depart_id` = old.`id`;
		END IF;
		');

	}

	public function down()
	{
		echo "m191101_080141_61111 does not support migration down.\n";
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