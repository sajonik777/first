<?php

class m230623_074651_update_companies extends CDbMigration
{
	public function up()
	{
		Yii::app()->db->createCommand('ALTER TABLE companies add column city int')->execute();
		Yii::app()->db->createCommand('ALTER TABLE companies add column street int')->execute();
		Yii::app()->db->createCommand('ALTER TABLE companies add column building varchar(500)')->execute();
		Yii::app()->db->createCommand('ALTER TABLE companies add column bcorp varchar(500)')->execute();
		Yii::app()->db->createCommand('ALTER TABLE companies add column bblock varchar(500)')->execute();

		$this->addForeignKey(
			"company_city_fk",
			"companies",
			"city",
			"cities",
			"id",
			"CASCADE",
			"CASCADE"
		);

		$this->addForeignKey(
			"company_street_fk",
			"companies",
			"street",
			"streets",
			"id",
			"CASCADE",
			"CASCADE"
		);
	}

	public function down()
	{
		$this->dropForeignKey("company_city_fk", "companies");
		$this->dropForeignKey("company_street_fk", "companies");
		$this->dropColumn('companies', 'city');
		$this->dropColumn('companies', 'street');
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