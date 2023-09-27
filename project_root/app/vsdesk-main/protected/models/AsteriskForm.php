<?php

/**
 * Class AsteriskForm
 */
class AsteriskForm extends CFormModel
{
    public $amiHost;
    public $amiPort;
    public $amiScheme;
    public $amiUsername;
    public $amiSecret;
    public $amiConnectTimeout;
    public $amiReadTimeout;
    public $amiSendPush;
    public $amiEnabled;
    public $amiContext;
    public $amiChannel;
    public $amiRecordPath;
//    public $amiDBServer;
//    public $amiDBUser;
//    public $amiDBPassword;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['amiSendPush, amiEnabled', 'numerical', 'integerOnly' => true],
            ['amiHost, amiPort, amiScheme, amiUsername, amiSecret, amiConnectTimeout, amiReadTimeout', 'required'],
            [
                'amiHost, amiPort, amiScheme, amiUsername, amiSecret, amiConnectTimeout, amiReadTimeout, amiSendPush, amiContext, amiChannel, amiRecordPath',
                'filter',
                'filter' => [$obj = new CHtmlPurifier(), 'purify']
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'amiEnabled' => Yii::t('main-ui', 'Asterisk integration enabled'),
            'amiSendPush' => Yii::t('main-ui', 'Send google push notifications'),
            'amiHost' => Yii::t('main-ui', 'Asterisk management API host'),
            'amiPort' => Yii::t('main-ui', 'Asterisk management API port'),
            'amiScheme' => Yii::t('main-ui', 'Asterisk management API connection scheme (tcp:// most popular)'),
            'amiUsername' => Yii::t('main-ui', 'Asterisk management API username'),
            'amiSecret' => Yii::t('main-ui', 'Asterisk management API secret'),
            'amiConnectTimeout' => Yii::t('main-ui', 'Asterisk management API connection timeout'),
            'amiReadTimeout' => Yii::t('main-ui', 'Asterisk management API read timeout'),
            'amiContext' => Yii::t('main-ui', 'Asterisk context'),
            'amiChannel' => Yii::t('main-ui', 'Asterisk channel'),
            'amiRecordPath' => Yii::t('main-ui', 'Asterisk record path'),
//            'amiDBServer' => Yii::t('main-ui', 'Asterisk DB server DSN string'),
//            'amiDBUser' => Yii::t('main-ui', 'Asterisk DB user'),
//            'amiDBPassword' => Yii::t('main-ui', 'Asterisk DB password'),
        ];
    }
}
