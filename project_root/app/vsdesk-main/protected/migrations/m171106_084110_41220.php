<?php

class m171106_084110_41220 extends CDbMigration
{
	public function up()
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci';

				$table3 = Yii::app()->db->schema->getTable('fieldsets_fields');
				if(!isset($table3->columns['sid'])) {
						$this->addColumn('fieldsets_fields', 'sid', 'int(11) AFTER `fid`');
				}

				$table7 = Yii::app()->db->schema->getTable('cunits');
				if(!isset($table7->columns['description'])) {
						$this->addColumn('cunits', 'description', 'text');
				}

				$table8 = Yii::app()->db->schema->getTable('asset');
				if(!isset($table8->columns['description'])) {
						$this->addColumn('asset', 'description', 'text');
				}

				$table4 = Yii::app()->db->schema->getTable('asset_attrib');
				if(isset($table4->columns['name'])) {
						$this->alterColumn('asset_attrib', 'name', 'varchar(200)');
				}

				$table5 = Yii::app()->db->schema->getTable('asset_attrib_value');
				if(isset($table5->columns['name'])) {
						$this->alterColumn('asset_attrib_value', 'name', 'varchar(200)');
				}

				$table6 = Yii::app()->db->schema->getTable('asset_values');
				if(isset($table6->columns['value'])) {
						$this->alterColumn('asset_values', 'value', 'varchar(200)');
				}
				if(isset($table6->columns['asset_attrib_name'])) {
						$this->alterColumn('asset_values', 'asset_attrib_name', 'varchar(200)');
				}
		
				$table = Yii::app()->db->schema->getTable('roles_rights');
				if(isset($table->columns['description'])) {
						$this->alterColumn('roles_rights', 'description', 'varchar(100)');
				}
				$table2 = Yii::app()->db->schema->getTable('request');
				if(isset($table2->columns['child'])) {
						$this->alterColumn('request', 'child', 'varchar(500)');
				}
				if(!isset($table2->columns['channel_icon'])) {
						$this->addColumn('request', 'channel_icon', 'varchar(100) after `child`');
				}
				if(!isset($table2->columns['channel'])) {
						$this->addColumn('request', 'channel', 'varchar(100) after `child`');
				}


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
				$statuses = Status::model()->findAll();
				foreach ($statuses as $item){
					Status::model()->updateByPk($item->id, array('label' => '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: '.$item->tag .'; vertical-align: baseline; white-space: nowrap; border: 1px solid '.$item->tag .'; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . $item->name . '</span>'));
				}

				$req = Request::model()->findAll();
				foreach ($req as $value) {
					$status = Status::model()->findByAttributes(array('name' => $value->Status));
					Request::model()->updateByPk($value->id, array('slabel' => '<span style="display: inline-block; padding: 2px 4px; font-size: 11.844px; font-weight: bold; line-height: 14px; color: '.$status->tag .'; vertical-align: baseline; white-space: nowrap; border: 1px solid '.$status->tag .'; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px;">' . $status->name . '</span>'));
					// if($value->ZayavCategory_id == 'E-mail ticket' OR $value->ZayavCategory_id == 'Заявка по e-mail'){
					// 	Request::model()->updateByPk($value->id, array('channel' => 'Email', 'channel_icon' => 'fa-solid fa-envelope'));
					// } elseif($value->ZayavCategory_id == 'Telegram ticket'){
					// 	Request::model()->updateByPk($value->id, array('channel' => 'Telegram', 'channel_icon' => 'fa-brands fa-telegram'));
					// } elseif($value->ZayavCategory_id == 'Portal ticket' OR $value->ZayavCategory_id == 'Заявка с портала'){
					// 	Request::model()->updateByPk($value->id, array('channel' => 'Portal', 'channel_icon' => 'fa-solid fa-house'));
					// } else {
					// 	Request::model()->updateByPk($value->id, array('channel' => 'Manual', 'channel_icon' => 'fa-solid fa-pen-to-square'));
					// }
				}
	}

	public function down()
	{
		echo "m171106_084110_41220 does not support migration down.\n";
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