<?php

class m201226_154846_matchings extends CDbMigration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        $table = Yii::app()->db->schema->getTable('request_fields');
        $table2 = Yii::app()->db->schema->getTable('service');

        if(isset($table->columns['value'])) {
            $this->alterColumn('request_fields', 'value', 'varchar(500)');
        }

        if(!isset($table2->columns['matchings'])) {
            $this->addColumn('service', 'matchings', 'varchar(200) NULL DEFAULT NULL');
        }

        if (null === Yii::app()->db->schema->getTable('request_matching_reaction', true)) {
            $this->createTable('request_matching_reaction', [
                'id' => 'pk',
                'request_id' => 'int(11) NOT NULL',
                'iteration' => 'int(11) NOT NULL DEFAULT 0',
                'user_id' => 'int(11) NOT NULL',
                'checked' => 'TINYINT(1) NOT NULL DEFAULT 0',
                'reaction_time' => 'DATETIME NULL DEFAULT NULL',
            ], $tableOptions);

            $this->addForeignKey('request_matching_reaction_request_id_fk', 'request_matching_reaction', 'request_id', 'request', 'id', 'CASCADE', 'NO ACTION');

            $this->addForeignKey('request_matching_reaction_user_id_fk', 'request_matching_reaction', 'user_id', 'CUsers', 'id', 'CASCADE', 'NO ACTION');

            $this->createIndex('request_matching_reaction_checked_idx', 'request_matching_reaction', 'checked');

            $this->createIndex('request_matching_reaction_reaction_time_idx', 'request_matching_reaction', 'reaction_time');

            $this->createIndex('request_matching_reaction_iteration_idx', 'request_matching_reaction', 'iteration');

            $this->createIndex('request_matching_reaction_request_id_iteration_idx', 'request_matching_reaction', 'request_id, iteration');

            $this->createIndex('request_matching_reaction_iteration_uniq', 'request_matching_reaction', 'request_id, iteration, user_id', true);
        }

    }

    public function down()
    {
    }
}
