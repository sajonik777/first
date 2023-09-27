<?php

class m210330_063158_pidindex extends CDbMigration
{
	public function up()
	{
        $idx_company = Yii::app()->db->createCommand('SHOW INDEX FROM request WHERE KEY_NAME = "idx_pid"')->queryRow();
        if($idx_company == 0) {
            $this->createIndex('idx_pid', 'request', 'pid', false);
        }
        $idx_rolename = Yii::app()->db->createCommand('SHOW INDEX FROM CUsers WHERE KEY_NAME = "idx_rolename"')->queryRow();
        if($idx_rolename == 0) {
            $this->createIndex('idx_rolename', 'CUsers', 'role_name', false);
        }
        $idx_active = Yii::app()->db->createCommand('SHOW INDEX FROM CUsers WHERE KEY_NAME = "idx_active"')->queryRow();
        if($idx_active == 0) {
            $this->createIndex('idx_active', 'CUsers', 'active', false);
        }
	}

	public function down()
	{
		echo "m210330_063158_pidindex does not support migration down.\n";
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