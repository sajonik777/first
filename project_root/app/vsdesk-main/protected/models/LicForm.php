<?php

class LicForm extends CFormModel
{
    public $customer;
    public $license_number;
    public $api_key;
    public $update_login;
    public $update_pass;


    public
    function rules()
    {
        return array(
            array('customer, license_number, api_key, update_login, update_pass', 'required'),
            array('customer, license_number, api_key, update_login, update_pass', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'customer' => Yii::t('main-ui', 'Customer'),
            'license_number' => Yii::t('main-ui', 'License number'),
            'api_key' => Yii::t('main-ui', 'API Key'),
            'update_login' => Yii::t('main-ui', 'Update login'),
            'update_pass' => Yii::t('main-ui', 'Update pass'),
        );
    }
}

?>