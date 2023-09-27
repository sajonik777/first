<?php

class m190319_120022_60610 extends CDbMigration
{
	public function up()
	{
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        if (Yii::app()->db->schema->getTable('contracts', true) === null) {
            $this->createTable('contracts', [
                'id' => 'pk',
                'number' => 'varchar(100) DEFAULT NULL',
                'name' => 'varchar(100) DEFAULT NULL',
                'type' => 'varchar(100) DEFAULT NULL',
                'date' => 'date DEFAULT NULL',
                'date_view' => 'varchar(20) DEFAULT NULL',
                'customer_id' => 'int(11) DEFAULT NULL',
                'customer_name' => 'varchar(100) DEFAULT NULL',
                'company_id' => 'int(11) DEFAULT NULL',
                'company_name' => 'varchar(100) DEFAULT NULL',
                'tildate' => 'date DEFAULT NULL',
                'tildate_view' => 'varchar(20) DEFAULT NULL',
                'cost' => 'varchar(100) DEFAULT NULL',
                'stopservice' => 'int(1) DEFAULT NULL',
                'image' => 'text NULL',
            ], $tableOptions);
        }

        $table = Yii::app()->db->schema->getTable('companies');
        if (!isset($table->columns['head_positon'])) {
            $this->addColumn('companies', 'head_positon', 'varchar(100) default NULL');
        }
        if (!isset($table->columns['head_name_writeable'])) {
            $this->addColumn('companies', 'head_name_writeable', 'varchar(100) default NULL');
        }
        if (!isset($table->columns['bank'])) {
            $this->addColumn('companies', 'bank', 'varchar(100) default NULL');
        }

        $table2 = Yii::app()->db->schema->getTable('request');
        if (!isset($table2->columns['reopened'])) {
            $this->addColumn('request', 'reopened', 'int(1) default NULL');
        }
        if (!isset($table2->columns['canceled'])) {
            $this->addColumn('request', 'canceled', 'int(1) default NULL');
        }
        if (!isset($table2->columns['delayed'])) {
            $this->addColumn('request', 'delayed', 'int(1) default NULL');
        }
        if (!isset($table2->columns['waspaused'])) {
            $this->addColumn('request', 'waspaused', 'int(1) default NULL');
        }
        if (!isset($table2->columns['wasautoclosed'])) {
            $this->addColumn('request', 'wasautoclosed', 'int(1) default NULL');
        }
        if (!isset($table2->columns['wasescalated'])) {
            $this->addColumn('request', 'wasescalated', 'int(1) default NULL');
        }
        if (!isset($table2->columns['msbot_id'])) {
            $this->addColumn('request', 'msbot_id', 'varchar(255) default NULL');
        }
        if (!isset($table2->columns['msbot_params'])) {
            $this->addColumn('request', 'msbot_params', 'text');
        }
        if (!isset($table2->columns['sort_id'])) {
            $this->addColumn('request', 'sort_id', 'int(11) default NULL');
        }

        $table3 = Yii::app()->db->schema->getTable('zstatus');
        if (!isset($table3->columns['sort_id'])) {
            $this->addColumn('zstatus', 'sort_id', 'int(11) default NULL');
        }

//        $table4 = Yii::app()->db->schema->getTable('leads');
//        if (!isset($table4->columns['sort_id'])) {
//            $this->addColumn('leads', 'sort_id', 'int(11) default NULL');
//        }
//
//        $table5 = Yii::app()->db->schema->getTable('pipeline');
//        if (!isset($table5->columns['sort_id'])) {
//            $this->addColumn('pipeline', 'sort_id', 'int(11) default NULL');
//        }

        if (Yii::app()->db->schema->getTable('contracts_files', true) === NULL){
            $this->createTable('contracts_files', [
                'contracts_id' => 'int NOT NULL',
                'file_id' => 'int NOT NULL',
                'PRIMARY KEY(`contracts_id`, `file_id`)'
            ], $tableOptions);
        }
	}

	public function down()
	{
		echo "m190319_120022_companies does not support migration down.\n";
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