<?php

class AdForm extends CFormModel
{
    public $type;
    public $ad_enabled;
    public $basedn;
    public $accountSuffix;
    public $domaincontrollers;
    public $adminusername;
    public $adminpassword;
    public $fastAuth;

    public function rules()
    {
        return [

            ['type, ad_enabled, basedn, accountSuffix, domaincontrollers, adminusername, adminpassword, fastAuth', 'required'],
            ['type', 'safe'],
            ['basedn, accountSuffix, domaincontrollers, adminusername, adminpassword', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'ad_enabled' => Yii::t('main-ui', 'Enabled'),
            'basedn' => Yii::t('main-ui', 'Base DN'),
            'accountSuffix' => Yii::t('main-ui', 'Account suffix'),
            'domaincontrollers' => Yii::t('main-ui', 'Domain controller'),
            'adminusername' => Yii::t('main-ui', 'Admin username'),
            'adminpassword' => Yii::t('main-ui', 'Admin password'),
            'fastAuth' => Yii::t('main-ui', 'fastAuth'),
        );
    }
}

?>