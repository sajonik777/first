<?php

class m170115_182307_40116 extends CDbMigration
{
	public function up()
	{
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';
        if (Yii::app()->db->schema->getTable('selects', true) === null) {
            $this->createTable('selects', [
                'id' => 'pk',
                'select_name' => 'varchar(128) NOT NULL',
                'select_value' => 'text NULL',
            ], $tableOptions);
          //  $this->createIndex('idx_uq_select_name', 'selects', 'select_name', true);
        }

        $table = Yii::app()->db->schema->getTable('fieldsets_fields');
        if(!isset($table->columns['select_id'])) {
            $this->addColumn('fieldsets_fields', 'select_id', 'int');
        }

        $this->alterColumn('companies', 'add1', 'text');
        $this->alterColumn('companies', 'add2', 'text');

        $table = Yii::app()->db->schema->getTable('service');
        if(isset($table->columns['company_id'])) {
            $this->dropColumn('service', 'company_id');
        }
        if(isset($table->columns['company_name'])) {
            $this->dropColumn('service', 'company_name');
        }
        if(!isset($table->columns['shared'])) {
            $this->addColumn('service', 'shared', 'bool default false');
        }


        if (Yii::app()->db->schema->getTable('company_services', true) === null) {
            $this->createTable('company_services', array(
                'company_id' => 'int NOT NULL',
                'service_id' => 'int NOT NULL',
                'PRIMARY KEY(`company_id`, `service_id`)'
            ), $tableOptions);
        }

        if (Yii::app()->db->schema->getTable('depart_services', true) === null) {
            $this->createTable('depart_services', array(
                'depart_id' => 'int NOT NULL',
                'service_id' => 'int NOT NULL',
                'PRIMARY KEY(`depart_id`, `service_id`)'
            ), $tableOptions);
        }

        //$this->createIndex('idx_uq_fieldsets_fields_name', 'fieldsets_fields', 'name', true);
	}

	public function down()
	{
		echo "m170115_182307_40116 does not support migration down.\n";
		return false;
	}

}
