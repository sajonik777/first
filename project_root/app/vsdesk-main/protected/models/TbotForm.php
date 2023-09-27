<?php

class TbotForm extends CFormModel
{
    public $TBotEnabled;
    public $TBotToken;
    public $TBotURL;
    public $TBotCertificate;
    public $TBotMsg;

    public
    function rules()
    {
        return array(
            array('TBotEnabled', 'numerical', 'integerOnly' => true),
            array('TBotToken, TBotURL', 'required'),
            array('TBotEnabled, TBotToken, TBotURL, TBotCertificate, TBotMsg', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'TBotEnabled' => Yii::t('main-ui', 'Telegram channel enabled'),
            'TBotToken' => Yii::t('main-ui', 'Telegram bot token'),
            'TBotURL' => Yii::t('main-ui', 'Webhook URL'),
            'TBotCertificate' => Yii::t('main-ui', 'Path to SSL Certificate. Only if using selfsigned certificate'),
            'TBotMsg' => Yii::t('main-ui', 'Greetings message'),
        );
    }
    
}

?>
