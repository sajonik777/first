<?php

class m230918_124133_add_sla_to_cronreq extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE cron_req add column sla int')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('ALTER TABLE cron_req drop column sla ')->execute();
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