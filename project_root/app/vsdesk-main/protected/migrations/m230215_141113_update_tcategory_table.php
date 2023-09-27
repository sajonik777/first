<?php

class m230215_141113_update_tcategory_table extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE tcategory ALTER enabled SET DEFAULT 0;')->execute();
	}

	public function down()
	{
		echo "m230215_141113_update_tcategory_table does not support migration down.\n";
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