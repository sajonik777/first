<?php

/**
 * Class MSBotForm
 */
class MSBotForm extends CFormModel
{
    /**
     * @var int
     */
    public $enabled;

    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $appPassword;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['enabled', 'numerical', 'integerOnly' => true],
            ['services', 'safe'],
            ['enabled, appId, appPassword', 'required'],
            ['appId, appPassword', 'filter', 'filter' => [$obj = new CHtmlPurifier(), 'purify']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'enabled' => Yii::t('main-ui', 'Enabled'),
            'appId' => Yii::t('main-ui', 'App Id'),
            'appPassword' => Yii::t('main-ui', 'App Password'),
        ];
    }
}
