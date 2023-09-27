<?php

class PortalForm extends CFormModel
{
    public $portalPhonebook;
    public $portalAllowRegister;
    public $portalAllowRestore;
    public $portalAllowNews;
    public $portalAllowKb;
    public $portalAllowService;
    public $portalAllowCaptcha;
    public $portalCaptchaWords;



    public
    function rules()
    {
        return array(
            //array('portalPhonebook, portalAllowRegister, portalAllowRestore, portalAllowNews, portalAllowKb, portalAllowService, portalAllowCaptcha', 'required'),
            array('portalPhonebook, portalAllowRegister, portalAllowRestore, portalAllowNews, portalAllowKb, portalAllowService, portalAllowCaptcha', 'numerical', 'integerOnly' => true),
            array('portalPhonebook, portalAllowRegister, portalAllowRestore, portalAllowNews, portalAllowKb, portalAllowService, portalAllowCaptcha, portalCaptchaWords', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'portalPhonebook' => Yii::t('main-ui', 'Enable phonebook on portal'),
            'portalAllowRegister' => Yii::t('main-ui', 'Allow registration from portal'),
            'portalAllowRestore' => Yii::t('main-ui', 'Allow restore password from portal'),
            'portalAllowNews' => Yii::t('main-ui', 'Show last news on portal'),
            'portalAllowKb' => Yii::t('main-ui', 'Show last knowledgebase records on portal'),
            'portalAllowService' => Yii::t('main-ui', 'Allow select service on portal'),
            'portalAllowCaptcha' => Yii::t('main-ui', 'Allow use captcha on portal'),
            'portalCaptchaWords' => Yii::t('main-ui', 'Words of captcha on portal and widget'),
        );
    }
}

?>