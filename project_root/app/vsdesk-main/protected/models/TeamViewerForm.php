<?php

/**
 * Class TeamViewerForm
 */
class TeamViewerForm extends CFormModel
{
    /**
     * @var integer
     */
    public $enabled;

    /**
     * @var string
     */
    public $client_id;

    /**
     * @var string
     */
    public $client_secret;

    /**
     * @var string
     */
    public $access_token;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['enabled', 'numerical', 'integerOnly' => true],
            ['client_id, client_secret, access_token', 'required'],
            [
                'enabled, client_id, client_secret, access_token',
                'filter',
                'filter' => [$obj = new CHtmlPurifier(), 'purify']
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'enabled' => Yii::t('main-ui', 'TeamViewer enabled'),
            'client_id' => Yii::t('main-ui', 'Client ID'),
            'client_secret' => Yii::t('main-ui', 'Client secret'),
            'access_token' => Yii::t('main-ui', 'Access token'),
        ];
    }
}
