<?php

class m230803_055408_add_sla_to_request extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE request add column sla int')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('ALTER TABLE request drop column sla ')->execute();
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