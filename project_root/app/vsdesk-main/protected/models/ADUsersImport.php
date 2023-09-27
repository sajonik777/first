<?php

class ADUsersImport extends CFormModel {

    public $notemailusers;
    public $defaultcompany;
    public $defaulttype;
    public $defaultrole;

    public function attributeLabels()
    {
        return array(
            'notemailusers' => Yii::t('main-ui', 'Импортировать пользователей без email'),
            'defaultrole' => Yii::t('main-ui', 'Роль по умолчанию'),
            'defaultcompany' => Yii::t('main-ui', 'Компания по умолчанию'),
            'defaulttype' => Yii::t('main-ui', 'Тип по умолчанию'),
        );
    }
}