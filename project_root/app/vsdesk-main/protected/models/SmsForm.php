<?php

class SmsForm extends CFormModel
{
    public $api_id;
    public $smsuser;
    public $smspassword;
    public $smsformat;
    public $smssender;

    public
    function rules()
    {
        return array(
            array('smsuser, smspassword, api_id, smsformat, smssender', 'required'),
            array('smsuser, smspassword, api_id, smsformat, smssender', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'api_id' => 'Ваш API ID',
            'smsuser' => Yii::t('main-ui', 'SMS username'),
            'smspassword' => Yii::t('main-ui', 'SMS password'),
            'smsformat' => Yii::t('main-ui', 'SMS format'),
            'smssender' => Yii::t('main-ui', 'SMS sender'),
        );
    }
}

?>