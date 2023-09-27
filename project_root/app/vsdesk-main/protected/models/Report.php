<?php

class Report extends CFormModel
{
    public $sdate;
    public $edate;
    public $company;
    public $contractor;
    public $service;
    public $type;


    public
    function rules()
    {
        return array(
            array('sdate, edate', 'required'),
            array('sdate, edate, company, service, type', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),

        );
    }

    public
    function attributeLabels()
    {
        return array(
            'sdate' => Yii::t('main-ui', 'Site URL'),
            'edate' => Yii::t('main-ui', 'Admin E-Mail'),
            'company' => Yii::t('main-ui', 'Company'),
            'contractor' => Yii::t('main-ui', 'Contractor'),
            'service' => Yii::t('main-ui', 'Service'),
            'type' => Yii::t('main-ui', 'Select report type'),
        );
    }
}

?>