<?php

class m160918_095313_030918 extends CDbMigration
{
	public function up()
	{
        $table = Yii::app()->db->schema->getTable('request');
        if(!isset($table->columns['child'])) {
            $this->addColumn('request', 'child', 'varchar(50) default NULL');
        }
	}

	public function down()
	{
        $this->dropColumn('request', 'child');
	}

}