<?php

/**
 * Class VBotForm
 */
class VBotForm extends CFormModel
{
    /**
     * @var integer
     */
    public $enabled;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $webhookUrl;

    /**
     * @var string
     */
    public $msg;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['enabled', 'numerical', 'integerOnly' => true],
            ['token, webhookUrl, msg', 'required'],
            ['enabled, token, webhookUrl, msg', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'enabled' => Yii::t('main-ui', 'Viber channel enabled'),
            'token' => Yii::t('main-ui', 'Viber bot token'),
            'webhookUrl' => Yii::t('main-ui', 'Webhook URL'),
            'msg' => Yii::t('main-ui', 'Greetings message'),
        ];
    }
}
