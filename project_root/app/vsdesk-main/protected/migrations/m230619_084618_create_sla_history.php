<?php

class m230619_084618_create_sla_history extends CDbMigration
{
	public function up()
	{
		$this->createTable('sla_history', array(
			'id' => 'pk',
            'sid' => 'int',
            'date' => 'datetime',
			'user' => 'int',
			'user_name' => 'string',
            'action' => 'text',
        ));
		$this->addForeignKey(
			"sla_history_sla_fk",
			"sla_history",
			"sid",
			"sla",
			"id",
			"CASCADE",
			"CASCADE"
		);
		$this->addForeignKey(
			"sla_history_user_fk",
			"sla_history",
			"user",
			"CUsers",
			"id",
			"CASCADE",
			"CASCADE"
		);
	}

	public function down()
	{
		$this->dropForeignKey("sla_history_sla_fk", "sla_history");
		$this->dropForeignKey("sla_history_user_fk", "sla_history");
		$this->dropTable('sla_history');	
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