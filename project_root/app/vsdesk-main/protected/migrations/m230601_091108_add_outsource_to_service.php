<?php

class m230601_091108_add_outsource_to_service extends CDbMigration
{
	public function up()
	{		
		Yii::app()->db->createCommand('ALTER TABLE service add column outsource boolean DEFAULT false')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('alter table service drop column outsource;')->execute();
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