<?php

class m171009_104738_41130 extends CDbMigration
{
	public function up()
	{
		$table = Yii::app()->db->schema->getTable('CUsers');
		if(!isset($table->columns['active'])) {
				$this->addColumn('CUsers', 'active', 'int(1) NOT NULL DEFAULT "1"');
		}

		$table2 = Yii::app()->db->schema->getTable('request');
		if(!isset($table2->columns['tchat_id'])) {
				$this->addColumn('request', 'tchat_id', 'varchar(100) DEFAULT NULL');
		}

		$this->execute('
			DROP TRIGGER IF EXISTS `update_user_name`;
		');

		$this->execute('
			CREATE TRIGGER `update_user_name` BEFORE UPDATE ON `CUsers` FOR EACH ROW            
			IF(old.`Username` <> new.`Username`) THEN UPDATE `request` SET `request`.`CUsers_id` = new.`Username` WHERE `request`.`CUsers_id` = old.`Username`;
			UPDATE `request` SET `request`.`phone` = new.`Phone` WHERE `request`.`CUsers_id` = old.`Username`;
			UPDATE `cunits` SET `cunits`.`user` = new.`Username` WHERE `cunits`.`user` = old.`Username`;
			UPDATE `asset` SET `asset`.`cusers_name` = new.`Username` WHERE `asset`.`cusers_name` = old.`Username`;
			END IF;
		');

		$command = Yii::app()->db->createCommand('show index from fieldsets_fields');
		$userIndexes = $command->queryAll(); 
		foreach($userIndexes as $index){
			if($index['Key_name'] == 'idx_uq_fieldsets_fields_name'){
				$this->dropIndex('idx_uq_fieldsets_fields_name', 'fieldsets_fields');
				$this->createIndex('idx_fieldsets_fields_name', 'fieldsets_fields', 'name', false);
			} elseif ($index['Key_name'] == 'idx_fieldsets_fields_name'){
				$this->dropIndex('idx_fieldsets_fields_name', 'fieldsets_fields');
				$this->createIndex('idx_fieldsets_fields_name', 'fieldsets_fields', 'name', false);
			}
		}
	}

	public function down()
	{
		echo "m171009_104738_41130 does not support migration down.\n";
		return false;
	}

}