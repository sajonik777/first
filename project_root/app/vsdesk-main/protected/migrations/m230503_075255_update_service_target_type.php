<?php

class m230503_075255_update_service_target_type extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE service add column target_type varchar(500)')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('alter table service drop column target_type;')->execute();
		// echo "m230503_075255_update_service_target_type does not support migration down.\n";
		// return false;
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