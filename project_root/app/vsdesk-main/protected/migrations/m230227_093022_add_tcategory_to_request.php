<?php

class m230227_093022_add_tcategory_to_request extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('alter table request add column tcategory varchar(500);')->execute();
	}

	public function down()
	{
		Yii::app()->db->createCommand('alter table request drop column tcategory;')->execute();
		// echo "m230227_093022_add_tcategory_to_request does not support migration down.\n";
		// return false;
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