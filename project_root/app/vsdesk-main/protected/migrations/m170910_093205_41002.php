<?php

class m170910_093205_41002 extends CDbMigration
{
	public function up()
	{
		$tableOptions = 'ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';
			$table = Yii::app()->db->schema->getTable('messages');
			if(!isset($table->columns['static'])) {
					$this->addColumn('messages', 'static', 'int(1) DEFAULT "0"');
					$this->insert('messages', array(
						'name' => '{registration}',
						'subject' => 'Регистрация в системе Univef сервис деск',
						'content' => '<h3>Успешная регистрация в системе технической поддержки продукта Univef.</h3>
<p>Добрый день! Вы успешно зарегистрировались на портале&nbsp;технической поддержки Univef, теперь вы можете:</p>
<ul><li>оставлять заявки на поддержку и обслуживание;</li><li>видеть последние новости компании;</li><li>получать самостоятельную помощь из опубликованных записей Базы знаний;</li></ul>
<p>Ваш логин в системе: <strong>{login}</strong></p>
<p>Ваш пароль в системе:<strong> {password}</strong></p>
<p>Перейдите на портал технической поддержки и начните работу!</p>',
						'static' => 1
					));
			}
	}

	public function down()
	{
		echo "m170910_093205_40930 does not support migration down.\n";
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
