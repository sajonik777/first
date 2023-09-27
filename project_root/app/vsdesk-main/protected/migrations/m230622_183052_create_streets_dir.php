<?php

class m230622_183052_create_streets_dir extends CDbMigration
{
	public function up()
	{
		$this->createTable('streets', array(
			'id' => 'pk',
            'cid' => 'int',
			'name' => 'string',
        ));
		$this->addForeignKey(
			"street_city_fk",
			"streets",
			"cid",
			"cities",
			"id",
			"CASCADE",
			"CASCADE"
		);
	}

	public function down()
	{
		$this->dropForeignKey("street_city_fk", "streets");
		$this->dropTable('streets');
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