<?php

class m230215_134938_craete_tcategory_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('tcategory', array(
            'id' => 'pk',
            'parent_id' => 'int',
            'name' => 'string NOT NULL',
            'enabled' => 'bool',
        ));
	}

	public function down()
	{
		echo "m230215_134938_craete_tcategory_table does not support migration down.\n";
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