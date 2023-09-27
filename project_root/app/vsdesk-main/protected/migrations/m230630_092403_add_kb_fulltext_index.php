<?php

class m230630_092403_add_kb_fulltext_index extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE brecords ADD FULLTEXT `BRECORDSFULLTEXT` (`name`, `content`);')->execute();

	}

	public function down()
	{
		echo "m230630_092403_add_kb_fulltext_index does not support migration down.\n";
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