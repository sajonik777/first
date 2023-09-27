<?php

/**
 * Class m200808_144222_checklists
 */
class m200808_144222_checklists extends CDbMigration
{
    /**
     * @return bool|void
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

        if (null === Yii::app()->db->schema->getTable('checklists', true)) {
            $this->createTable('checklists', [
                'id' => 'pk',
                'name' => 'varchar(64) NOT NULL',
            ], $tableOptions);
            $this->createIndex('checklists_name_uidx', 'checklists', 'name', true);
        }

        if (null === Yii::app()->db->schema->getTable('checklist_fields', true)) {
            $this->createTable('checklist_fields', [
                'id' => 'pk',
                'checklist_id' => 'int(11) NOT NULL',
                'name' => 'varchar(64) NOT NULL',
                'sorting' => 'int(11) NOT NULL DEFAULT 1',
            ], $tableOptions);
            $this->createIndex('checklist_fields_checklist_id_name_uidx', 'checklist_fields', ['checklist_id', 'name'],
                true);
            $this->addForeignKey('checklists_checklist_id_fk', 'checklist_fields', 'checklist_id', 'checklists', 'id',
                'CASCADE', 'NO ACTION');
        }

        $table2 = Yii::app()->db->schema->getTable('service');
        if(!isset($table2->columns['checklist_id'])) {
            $this->addColumn('service', 'checklist_id', 'int(11)  NULL DEFAULT NULL');
            $this->addForeignKey('service_checklist_id_fk', 'service', 'checklist_id', 'checklists', 'id', 'CASCADE', 'NO ACTION');
        }

        if (null === Yii::app()->db->schema->getTable('request_checklist_fields', true)) {
            $this->createTable('request_checklist_fields', [
                'id' => 'pk',
                'request_id' => 'int(11) NOT NULL',
                'checklist_field_id' => 'int(11) NOT NULL',
                'checked' => 'TINYINT(1) NOT NULL DEFAULT 0',
                'sorting' => 'int(11) NOT NULL DEFAULT 1',
                'checked_user_id' => 'int(11) NULL DEFAULT NULL',
                'checked_time' => 'DATETIME NULL DEFAULT NULL',
            ], $tableOptions);
            $this->addForeignKey('request_checklist_fields_request_id_fk', 'request_checklist_fields', 'request_id',
                'request', 'id', 'CASCADE', 'NO ACTION');
            $this->addForeignKey('request_checklist_fields_checklist_field_id_fk', 'request_checklist_fields',
                'checklist_field_id', 'checklist_fields', 'id', 'CASCADE', 'NO ACTION');
            $this->addForeignKey('request_checklist_fields_checked_user_id_fk', 'request_checklist_fields',
                'checked_user_id', 'CUsers', 'id', 'CASCADE', 'NO ACTION');
        }

        $exists = Messages::model()->findByAttributes(['name' => '{escalate_group}']);
        if(!isset($exists)) {
            $this->insert('messages', [
                'name' => '{escalate_group}',
                'subject' => '[Ticket #{id}] {name}',
                'content' => "<p>Произошла эскалация заявки номер <b>{id}</b>&nbsp;<strong>{name}</strong></p><p>Была назначена группа исполнителей <b>{groupname}</b>&nbsp;</p><p>Посмотреть заявку&nbsp;{url}</p>",
                'static' => 4
            ]);
        }
    }

    /**
     * @return bool
     */
    public function down()
    {
        echo "m200808_144222_checklists does not support migration down.\n";
        return true;
    }
}
