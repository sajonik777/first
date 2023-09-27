<?php

class m180320_093226_50404 extends CDbMigration
{
	public function up()
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        if (Yii::app()->db->schema->getTable('company_fieldset', true) === null) {
            $this->createTable('company_fieldset', [
                  'id' => 'pk',
				  'fid' => 'int(11) DEFAULT NULL',
				  'sid' => 'int(11) DEFAULT NULL',
				  'name' => 'varchar(100) DEFAULT NULL',
				  'type' => 'varchar(100) DEFAULT NULL',
				  'req' => 'tinyint(1) NOT NULL DEFAULT 0',
				  'value' => 'text',
				  'select_id' => 'int(11) DEFAULT NULL',
            ], $tableOptions);
            $this->createIndex('fid', 'company_fieldset', 'fid', false);
            $this->createIndex('idx_company_fields_name', 'company_fieldset', 'name', false);
        }

        if (Yii::app()->db->schema->getTable('company_fields', true) === null) {
            $this->createTable('company_fields', [
                'id' => 'pk',
				'rid' => 'int(11) DEFAULT NULL',
				'name' => 'varchar(64) NOT NULL',
				'type' => "enum('toggle','date','textFieldRow','select') NOT NULL",
				'value' => 'varchar(64) NOT NULL',
				'fid' => 'int(11) DEFAULT NULL',
            ], $tableOptions);
            $this->createIndex('rid', 'company_fields', 'rid', false);
            $this->createIndex('type', 'company_fields', 'type', false);
            $this->createIndex('value', 'company_fields', 'value', false);
            $this->addForeignKey('fk-company-id', 'company_fields','rid','companies','id','CASCADE','NO ACTION');
        }
	}

	public function down()
	{
		echo "m180320_093226_50404 does not support migration down.\n";
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