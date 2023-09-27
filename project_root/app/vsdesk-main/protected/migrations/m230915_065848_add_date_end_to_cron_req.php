<?php

class m230915_065848_add_date_end_to_cron_req extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE cron_req add column Date_end datetime')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('ALTER TABLE cron_req drop column Date_end ')->execute();
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