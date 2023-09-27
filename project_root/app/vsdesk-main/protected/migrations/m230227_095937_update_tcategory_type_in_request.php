<?php

class m230227_095937_update_tcategory_type_in_request extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE request MODIFY tcategory varchar(500);')->execute();
	}

	public function down()
	{
		echo "m230227_095937_update_tcategory_type_in_request does not support migration down.\n";
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