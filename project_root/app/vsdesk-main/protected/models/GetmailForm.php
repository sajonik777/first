<?php

class GetmailForm extends CFormModel
{
    public $getmail_enabled;
    public $getmailserver;
    public $getmailport;
    public $getmailpath;
    public $getmailuser;
    public $getmailpass;
    public $getmaildelete;
    public $getmailitems;
    public $getmailservice;
    public $getmaildisableauth;
    public $getmaildisableconvert;
    public $getmaildisablenl2br;
    public $getmaildisabletrim;
    public $getmaildisablectrim;
    public $getmailclosedtonew;
    public $getmailcopytowatchers;

    public $getmailsmtpauth;
    public $getmailsmsec;
    public $getmailsmdebug;
    public $getmailsmqueue;
    public $getmailsmignoressl;
    public $getmailsmport;
    public $getmailsmhost;
    public $getmailsmusername;
    public $getmailsmpassword;
    public $getmailsmfrom;
    public $getmailsmfromname;

    public
    function rules()
    {
        return array(
            array('getmailsmfromname, getmailsmfrom, getmailsmpassword, getmailsmusername, getmailsmhost, getmailsmport, getmailsmignoressl, getmailsmqueue, getmailsmdebug, getmailsmsec, getmailsmtpauth, getmail_enabled, getmailservice, getmailserver, getmailport, getmailpath, getmailuser, getmailpass, getmaildelete, getmailitems, getmaildisableauth, getmaildisableconvert, getmaildisablenl2br, getmaildisabletrim, getmaildisablectrim, getmailclosedtonew, getmailcopytowatchers', 'required'),
            array('getmailsmfromname, getmailsmfrom, getmailsmpassword, getmailsmusername, getmailsmhost, getmailsmport, getmailsmignoressl, getmailsmqueue, getmailsmdebug, getmailsmsec, getmailsmtpauth, getmailserver, getmailport, getmailpath, getmailuser, getmailpass, getmailitems, getmaildisableauth, getmaildisabletrim, getmaildisablectrim, getmailclosedtonew, getmailcopytowatchers', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'getmail_enabled' => Yii::t('main-ui', 'Email parser enabled'),
            'getmailitems' => Yii::t('main-ui', 'Number of items to proceed'),
            'getmailserver' => Yii::t('main-ui', 'Mail server'),
            'getmailport' => Yii::t('main-ui', 'Mail port'),
            'getmailservice' => Yii::t('main-ui', 'Service'),
            'getmailpath' => Yii::t('main-ui', 'SSL path'),
            'getmailuser' => Yii::t('main-ui', 'Mail username'),
            'getmailpass' => Yii::t('main-ui', 'Mail password'),
            'getmaildelete' => Yii::t('main-ui', 'Delete messages from server?'),
            'getmaildisableauth' => Yii::t('main-ui', 'DISABLE_AUTHENTICATOR'),
            'getmaildisableconvert' => Yii::t('main-ui', 'Disable HTML to Text convertation'),
            'getmaildisablenl2br' => Yii::t('main-ui', 'Disable line wrapping'),
            'getmaildisabletrim' => Yii::t('main-ui', 'Disable trimming of quotes and signatures in ticket'),
            'getmaildisablectrim' => Yii::t('main-ui', 'Disable trimming of quotes and signatures in reply'),
            'getmailclosedtonew' => Yii::t('main-ui', 'Comments to the closed ticket create a new ticket'),
            'getmailcopytowatchers' => Yii::t('main-ui', 'Add users from email carbon copy to watchers if exist in users list'),
            'getmailsmsec' => Yii::t('main-ui', 'SMTP Security'),
            'getmailsmdebug' => Yii::t('main-ui', 'SMTP Debug'),
            'getmailsmhost' => Yii::t('main-ui', 'SMTP Host name'),
            'getmailsmport' => Yii::t('main-ui', 'SMTP Port'),
            'getmailsmqueue' => Yii::t('main-ui', 'Use mail queue for SMTP'),
            'getmailsmignoressl' => Yii::t('main-ui', 'Ignore verify certificate'),
            'getmailsmtpauth' => Yii::t('main-ui', 'SMTP Auth required'),
            'getmailsmusername' => Yii::t('main-ui', 'SMTP Username'),
            'getmailsmpassword' => Yii::t('main-ui', 'SMTP Password'),
            'getmailsmfrom' => Yii::t('main-ui', 'Sender E-Mail'),
            'getmailsmfromname' => Yii::t('main-ui', 'From: filed value'),
        );
    }
}
?>