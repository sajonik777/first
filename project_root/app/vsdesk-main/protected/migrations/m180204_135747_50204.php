<?php

class m180204_135747_50204 extends CDbMigration
{
	public function up()
	{
		$table = Yii::app()->db->schema->getTable('request_fields');
				if(!isset($table->columns['fid'])) {
						$this->addColumn('request_fields', 'fid', 'int(11)');
				}
	}

	public function down()
	{
		echo "m180204_135747_50204 does not support migration down.\n";
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