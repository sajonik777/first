<?php

class SlackForm extends CFormModel
{
    public $SlackEnabled;
    public $SlackUsername;
    public $SlackWebhookURL;
    public $SlackIconURL;
    public $SlackEmojii;
    public $SlackTemplate;


    public
    function rules()
    {
        return array(
            array('SlackEnabled', 'numerical', 'integerOnly' => true),
            array('SlackUsername, SlackWebhookURL, SlackTemplate', 'required'),
            array('SlackEnabled, SlackUsername, SlackWebhookURL, SlackIconURL, SlackEmojii, SlackTemplate', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'SlackEnabled' => Yii::t('main-ui', 'Slack messages enabled'),
            'SlackUsername' => Yii::t('main-ui', 'Slack sender username'),
            'SlackWebhookURL' => Yii::t('main-ui', 'Slack Webhook URL'),
            'SlackIconURL' => Yii::t('main-ui', 'Slack sender icon URL'),
            'SlackTemplate' => Yii::t('main-ui', 'Template'),
            'SlackEmojii' => Yii::t('main-ui', 'Slack sender emoji instead of icon'),
        );
    }
}

?>
