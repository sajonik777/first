<?php

class m180424_083445_50505 extends CDbMigration
{
	public function up()
	{
		$table = Yii::app()->db->schema->getTable('comments');
		if(!isset($table->columns['channel'])) {
			$this->addColumn('comments', 'channel', 'varchar(100)');
		}
		$table2 = Yii::app()->db->schema->getTable('request');
		if(!isset($table2->columns['viber_id'])) {
			$this->addColumn('request', 'viber_id', 'varchar(100)');
		}
	}

	public function down()
	{
		echo "m180424_083445_50505 does not support migration down.\n";
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