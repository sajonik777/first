<?php

class m230703_090345_add_requests_fulltext_index extends CDbMigration
{
	public function up()
	{
        Yii::app()->db->createCommand('ALTER TABLE request ADD FULLTEXT REQUESTSFULLTEXT (`Name`, `Content`);')->execute();
	}

	public function down()
	{
		echo "m230703_090345_add_requests_fulltext_index does not support migration down.\n";
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