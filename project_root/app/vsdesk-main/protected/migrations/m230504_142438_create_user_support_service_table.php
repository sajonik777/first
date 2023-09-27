<?php

class m230504_142438_create_user_support_service_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('service_user_support', array(
            'user_service' => 'int',
            'support_service' => 'int',
        ));
		$this->addForeignKey(
			"user_service_fk",
			"service_user_support",
			"user_service",
			"service",
			"id",
			"CASCADE",
			"CASCADE"
		);
		$this->addForeignKey(
			"support_service_fk",
			"service_user_support",
			"support_service",
			"service",
			"id",
			"CASCADE",
			"CASCADE"
		);
	}

	public function down()
	{
		$this->dropForeignKey("user_service_fk", "service_user_support");
		$this->dropForeignKey("support_service_fk", "service_user_support");
		$this->dropTable('service_user_support');

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