<?php

class m160919_120214_309182 extends CDbMigration
{
	public function up()
	{
		$all = Request::model()->findAll();
		foreach($all as $one){
			$childs = Request::model()->countByAttributes(array('pid' => $one->id));
			$ch_label = '<span class="lb-danger">' . (int)$childs . '</span>';
			if ((int)$childs > 0)
				Request::model()->updateByPk($one->id, array('child' => $ch_label));
		}
	}

	public function down()
	{
		echo "m160919_120214_309182 does not support migration down.\n";
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