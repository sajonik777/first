<?php

class m230801_064203_add_type_to_sla extends CDbMigration
{
	public function up()
	{

		Yii::app()->db->createCommand('ALTER TABLE sla add column sla_type varchar(255)')->execute();

	}

	public function down()
	{
		Yii::app()->db->createCommand('ALTER TABLE sla drop column sla_type ')->execute();	}

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