<?php

class m230705_063656_create_kb_history extends CDbMigration
{
	public function up()
	{
		$this->createTable('knowledge_history', array(
			'id' => 'pk',
            'kid' => 'int',
            'date' => 'datetime',
			'user' => 'int',
			'user_name' => 'string',
            'action' => 'text',
        ));
		$this->addForeignKey(
			"knowledge_history_brecords_fk",
			"knowledge_history",
			"kid",
			"brecords",
			"id",
			"CASCADE",
			"CASCADE"
		);
		$this->addForeignKey(
			"knowledge_history_user_fk",
			"knowledge_history",
			"user",
			"CUsers",
			"id",
			"CASCADE",
			"CASCADE"
		);
	}

	public function down()
	{
		$this->dropForeignKey("knowledge_history_sla_fk", "knowledge_history");
		$this->dropForeignKey("knowledge_history_user_fk", "knowledge_history");
		$this->dropTable('knowledge_history');	
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