<?php

class m210528_173227_create_request_processing_rules extends CDbMigration
{
	public function up()
	{
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        $table = Yii::app()->db->schema->getTable('request');
            if(isset($table->columns['service_name'])) {
                $this->alterColumn('request', 'service_name', 'varchar(250)');
            }

        if (null === Yii::app()->db->schema->getTable('request_processing_rules', true)) {
            $this->createTable('request_processing_rules', [
                'id' => 'pk',
                'name' => 'VARCHAR(500) NOT NULL',
                'is_all_match' => 'TINYINT(1) NOT NULL DEFAULT 0',
                'is_apply_to_bots' => 'TINYINT(1) NOT NULL DEFAULT 0',
                'creator_id' => 'INT(11) NOT NULL',
                'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            ], $tableOptions);

            $this->addForeignKey('request_processing_rules_creator_id_fk', 'request_processing_rules', 'creator_id', 'CUsers', 'id', 'CASCADE', 'NO ACTION');
        }

        if (null === Yii::app()->db->schema->getTable('request_processing_rule_conditions', true)) {
            $this->createTable('request_processing_rule_conditions', [
                'id' => 'pk',
                'request_processing_rule_id' => 'INT(11) NOT NULL',
                'val' => 'VARCHAR(255) NOT NULL',
                'target' => 'TINYINT UNSIGNED NOT NULL',
                'condition' => 'TINYINT UNSIGNED NOT NULL',
            ], $tableOptions);

            $this->addForeignKey('request_processing_rule_conditions_request_processing_rule_id_fk', 'request_processing_rule_conditions', 'request_processing_rule_id', 'request_processing_rules', 'id', 'CASCADE', 'NO ACTION');
        }

        if (null === Yii::app()->db->schema->getTable('request_processing_rule_actions', true)) {
            $this->createTable('request_processing_rule_actions', [
                'id' => 'pk',
                'request_processing_rule_id' => 'INT(11) NOT NULL',
                'target' => 'TINYINT UNSIGNED NOT NULL',
                'val' => 'VARCHAR(255) NOT NULL',
            ], $tableOptions);

            $this->addForeignKey('request_processing_rule_actions_request_processing_rule_id_fk', 'request_processing_rule_actions', 'request_processing_rule_id', 'request_processing_rules', 'id', 'CASCADE', 'NO ACTION');
        }
	}

	public function down()
	{
		echo "m210528_173227_create_request_processing_rules does not support migration down.\n";
		return false;
	}
}
