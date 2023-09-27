<?php

class ConfigForm extends CFormModel
{
    public $use_rapid_msg;
    public $allow_register;
    public $allow_select_company;
    public $homeUrl;
    public $redirectUrl;
    public $pageHeader;
    public $adminEmail;
    public $smsec;
    public $smdebug;
    public $smqueue;
    public $smignoressl;
    public $smport;
    public $smhost;
    public $smtpauth;
    public $smusername;
    public $smpassword;
    public $smfrom;
    public $smfromname;
    public $languages;
    public $getmailserver;
    public $timezone;
    public $useiframe;
    public $allowportal;


    public
    function rules()
    {
        return array(
            array('use_rapid_msg, homeUrl, redirectUrl, adminEmail, smhost, smport, smqueue,pageHeader,smtpauth,smusername,smpassword,smfrom, smsec,smfromname,languages, timezone', 'required'),
            array('smtpauth, allow_register, allow_select_company, useiframe', 'numerical', 'integerOnly' => true),
            array('use_rapid_msg, homeUrl, redirectUrl, adminEmail, smhost, smport, smqueue, smtpauth,smusername,smpassword,smfrom, smsec, smdebug, smignoressl, pageHeader, smfromname,languages, timezone, useiframe, allowportal', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),

        );
    }

    public
    function attributeLabels()
    {
        return array(
            'use_rapid_msg' => Yii::t('main-ui', 'Use popup messages (Decrease performance)'),
            'allow_register' => Yii::t('main-ui', 'Allow user registration'),
            'allow_select_company' => Yii::t('main-ui', 'Allow select a company on register page'),
            'homeUrl' => Yii::t('main-ui', 'Site URL'),
            'redirectUrl' => Yii::t('main-ui', 'URL to redirect after login'),
            'adminEmail' => Yii::t('main-ui', 'Admin E-Mail'),
            'smsec' => Yii::t('main-ui', 'SMTP Security'),
            'smdebug' => Yii::t('main-ui', 'SMTP Debug'),
            'smhost' => Yii::t('main-ui', 'SMTP Host name'),
            'smport' => Yii::t('main-ui', 'SMTP Port'),
            'smqueue' => Yii::t('main-ui', 'Use mail queue for SMTP'),
            'smignoressl' => Yii::t('main-ui', 'Ignore verify certificate'),
            'smtpauth' => Yii::t('main-ui', 'SMTP Auth required'),
            'pageHeader' => Yii::t('main-ui', 'Page header'),
            'smusername' => Yii::t('main-ui', 'SMTP Username'),
            'smpassword' => Yii::t('main-ui', 'SMTP Password'),
            'smfrom' => Yii::t('main-ui', 'Sender E-Mail'),
            'smfromname' => Yii::t('main-ui', 'From: filed value'),
            'languages' => Yii::t('main-ui', 'System language'),
            'getmailserver' => Yii::t('main-ui', 'Mail server'),
            'timezone' => Yii::t('main-ui', 'Timezone'),
            'useiframe' => Yii::t('main-ui', 'Use iframe to display content of tickets'),
            'allowportal' => Yii::t('main-ui', 'Allow guests use Selfcare portal'),

        );
    }
}

?>