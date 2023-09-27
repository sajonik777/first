<?php

class m230823_103207_ke_warranty extends CDbMigration
{
	public function up()
	{		
		Yii::app()->db->createCommand('ALTER TABLE cunits add column warranty_start varchar(50) DEFAULT NULL')->execute();
		Yii::app()->db->createCommand('ALTER TABLE cunits add column warranty_end varchar(50) DEFAULT NULL')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('alter table cunits drop column warranty_start;')->execute();
		Yii::app()->db->createCommand('alter table cunits drop column warranty_end;')->execute();
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