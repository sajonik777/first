<?php

class m230525_073757_create_sactevesreport extends CDbMigration
{
	public function up()
	{
		$this->createTable('sactivesreport', array(
			'id' => 'pk',
            'dept' => 'string',
            'type' => 'string',
			'count' => 'int',
            'summary' => 'string',
        ));
	}

	public function down()
	{
		$this->dropTable('sactivesreport');
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