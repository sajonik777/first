<?php

/**
 * Class WhatsappForm
 */
class WhatsappForm extends CFormModel
{
    /**
     * @var int
     */
    public $enabled;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $apiUrl;

    /**
     * @var string
     */
    public $webhookUrl;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['enabled', 'numerical', 'integerOnly' => true],
            ['services', 'safe'],
            ['enabled, token, apiUrl, webhookUrl', 'required'],
            ['token, apiUrl, webhookUrl', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'enabled' => Yii::t('main-ui', 'Enabled'),
            'token' => Yii::t('main-ui', 'Token'),
            'apiUrl' => Yii::t('main-ui', 'Api Url'),
            'webhookUrl' => Yii::t('main-ui', 'Webhook Url'),
        ];
    }
}
