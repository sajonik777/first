<?php

class m230607_134114_update_asset extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('alter table asset add column warranty_start date NULL;')->execute();
		Yii::app()->db->createCommand('alter table asset add column warranty_end date NULL;')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('alter table asset drop column warranty_start;')->execute();
		Yii::app()->db->createCommand('alter table asset drop column warranty_end;')->execute();
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