<?php

Yii::import('application.vendors.adLDAP.adLDAP');

class LdapComponent extends adLDAP {

    public $ad_enabled;
    public $baseDn;
    public $accountSuffix;
    public $domainControllers;
    public $adminUsername;
    public $adminPassword;

    public function __construct() {

    }

    public function init() {
        return parent::__construct();
    }
}
?>