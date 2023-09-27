<?php

class m200406_080402_templates extends CDbMigration
{
	public function up()
	{
        $table = Yii::app()->db->schema->getTable('unit_templates');
        if(!isset($table->columns['page_format'])) {
            $this->addColumn('unit_templates', 'page_format', 'varchar(50) DEFAULT \'A4\'');
        }
        if(!isset($table->columns['page_width'])) {
            $this->addColumn('unit_templates', 'page_width', 'int(11)');
        }
        if(!isset($table->columns['page_height'])) {
            $this->addColumn('unit_templates', 'page_height', 'int(11)');
        }
        $table2 = Yii::app()->db->schema->getTable('request');
        if(!isset($table2->columns['getmailconfig'])) {
            $this->addColumn('request', 'getmailconfig', 'varchar(50)');
        }

        $table3 = Yii::app()->db->schema->getTable('MailQueue');
        if(!isset($table3->columns['getmailconfig'])) {
            $this->addColumn('MailQueue', 'getmailconfig', 'varchar(50)');
        }

        //$this->dropForeignKey('fk_escalates_manager_id', 'escalates');
        //$this->addForeignKey('fk_escalates_manager_id', 'escalates', 'manager_id', 'CUsers', 'id', 'CASCADE', 'NO ACTION');
        $templates = UnitTemplates::model()->findAll();
        foreach ($templates as $template){
            UnitTemplates::model()->updateByPk($template->id, ['page_format' => 'A4']);
        }
	}

	public function down()
	{
		echo "m200406_080402_templates does not support migration down.\n";
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