<?php

class m230609_092601_create_service_history extends CDbMigration
{
	public function up()
	{
		$this->createTable('service_history', array(
			'id' => 'pk',
            'sid' => 'int',
            'date' => 'datetime',
			'user' => 'int',
			'user_name' => 'string',
            'action' => 'text',
        ));
		$this->addForeignKey(
			"service_fk",
			"service_history",
			"sid",
			"service",
			"id",
			"CASCADE",
			"CASCADE"
		);
		$this->addForeignKey(
			"user_fk",
			"service_history",
			"user",
			"CUsers",
			"id",
			"CASCADE",
			"CASCADE"
		);
	}

	public function down()
	{
		$this->dropForeignKey("service_fk", "service_history");
		$this->dropForeignKey("user_fk", "service_history");
		$this->dropTable('service_history');	
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