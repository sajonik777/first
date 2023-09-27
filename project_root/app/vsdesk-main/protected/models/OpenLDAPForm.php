<?php

class OpenLDAPForm extends CFormModel
{
    public $type;
    public $ad_enabled;
    public $host;
    public $account;
    public $password;
    public $baseDN;
    public $usersDN;
    public $groupsDN;
    public $accountSuffix;

    public function rules()
    {
        return [
            ['type, ad_enabled, host, account, password, baseDN, usersDN, groupsDN, accountSuffix', 'required'],
            ['type', 'safe'],
            [
                'host, account, password, baseDN, usersDN, groupsDN, accountSuffix',
                'filter',
                'filter' => [$obj = new CHtmlPurifier(), 'purify']
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ad_enabled' => Yii::t('main-ui', 'Enabled'),
            'host' => Yii::t('main-ui', 'Domain controller'),
            'account' => Yii::t('main-ui', 'Admin username'),
            'password' => Yii::t('main-ui', 'Admin password'),
            'baseDN' => Yii::t('main-ui', 'Base DN'),
            'usersDN' => Yii::t('main-ui', 'Users DN'),
            'groupsDN' => Yii::t('main-ui', 'Groups DN'),
            'accountSuffix' => Yii::t('main-ui', 'Account suffix'),
        ];
    }
}
