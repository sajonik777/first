<?php

class m230601_153751_add_kb_category extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('INSERT INTO `bcats` (`name`, `access`) VALUES ("Описание услуги", NULL);')->execute();
	}

	public function down()
	{
		echo "m230601_153751_add_kb_category does not support migration down.\n";
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