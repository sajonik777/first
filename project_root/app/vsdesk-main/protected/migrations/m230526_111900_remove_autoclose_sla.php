<?php

class m230526_111900_remove_autoclose_sla extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('alter table sla drop column autoCloseHours;')->execute();
		Yii::app()->db->createCommand('alter table sla drop column autoCloseStatus;')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('ALTER TABLE sla add column autoCloseStatus int')->execute();
		Yii::app()->db->createCommand('ALTER TABLE sla add column autoCloseHours int NULL')->execute();
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