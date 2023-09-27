<?php

class m230208_071724_updade_request_table extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('alter table request add column KE_type varchar(100);')->execute();
	}

	public function down()
	{
		echo "m230208_071724_updade_request_table does not support migration down.\n";
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