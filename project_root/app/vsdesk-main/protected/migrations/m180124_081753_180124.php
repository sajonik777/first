<?php

class m180124_081753_180124 extends CDbMigration
{
	public function up()
	{
		$table = Yii::app()->db->schema->getTable('fieldsets_fields');
				if(isset($table->columns['value'])) {
						$this->alterColumn('fieldsets_fields', 'value', 'text');
				}
	}

	public function down()
	{
		echo "m180124_081753_180124 does not support migration down.\n";
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