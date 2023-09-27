<?php

class m161212_073006_31212 extends CDbMigration
{
	public function up()
	{
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        $table = Yii::app()->db->schema->getTable('astatus');
        if(!isset($table->columns['tag'])) {
            $this->addColumn('astatus', 'tag', 'varchar(50) default NULL');
        }

        $this->alterColumn('companies', 'uraddress', 'varchar(200)');
        $this->alterColumn('companies', 'faddress', 'varchar(200)');

        if (Yii::app()->db->schema->getTable('files', true) === NULL){
            $this->createTable('files', [
                'id' => 'pk',
                'name' => 'varchar(128) NOT NULL',
                'file_name' => 'varchar(32) NOT NULL',
                'created_at' => 'TIMESTAMP NOT NULL',
            ], $tableOptions);

            $this->createIndex('idx_uq_file_name', 'files', 'file_name', true);
        }


        if (Yii::app()->db->schema->getTable('request_files', true) === NULL){
            $this->createTable('request_files', [
                'request_id' => 'int NOT NULL',
                'file_id' => 'int NOT NULL',
                'PRIMARY KEY(`request_id`, `file_id`)'
            ], $tableOptions);
        }

        if (Yii::app()->db->schema->getTable('comment_files', true) === NULL){
            $this->createTable('comment_files', [
                'comment_id' => 'int NOT NULL',
                'file_id' => 'int NOT NULL',
                'PRIMARY KEY(`comment_id`, `file_id`)'
            ], $tableOptions);
        }

        if (Yii::app()->db->schema->getTable('knowledge_files', true) === NULL){
            $this->createTable('knowledge_files', [
                'knowledge_id' => 'int NOT NULL',
                'file_id' => 'int NOT NULL',
                'PRIMARY KEY(`knowledge_id`, `file_id`)'
            ], $tableOptions);
        }

        if (Yii::app()->db->schema->getTable('problem_files', true) === NULL){
            $this->createTable('problem_files', [
                'problem_id' => 'int NOT NULL',
                'file_id' => 'int NOT NULL',
                'PRIMARY KEY(`problem_id`, `file_id`)'
            ], $tableOptions);
        }

	}

	public function down()
	{
		echo "m161212_073006_31212 does not support migration down.\n";
		return false;
	}
}