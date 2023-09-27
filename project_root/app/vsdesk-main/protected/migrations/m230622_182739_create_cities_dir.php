<?php

class m230622_182739_create_cities_dir extends CDbMigration
{
	public function up()
	{
		$this->createTable('cities', array(
			'id' => 'pk',
            'name' => 'string',
        ));
	}

	public function down()
	{
		$this->drop_table('cities');
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