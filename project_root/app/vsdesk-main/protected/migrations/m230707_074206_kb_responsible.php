<?php

class m230707_074206_kb_responsible extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE brecords add column responsible_id int')->execute();
		$this->addForeignKey(
			"brecords_responsible_user_fk",
			"brecords",
			"responsible_id",
			"CUsers",
			"id",
			"CASCADE",
			"CASCADE"
		);

	}

	public function down()
	{
		$this->dropForeignKey("brecords_responsible_user_fk", "brecords");
		$this->dropColumn('brecords', 'responsible_id');
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