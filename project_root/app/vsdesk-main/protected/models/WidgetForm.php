<?php

class WidgetForm extends CFormModel
{
    public $WidgetEnabled;
    public $WidgetAnimate;
    public $WidgetFiles;
    public $WidgetService;
    public $WidgetColor;
    public $WidgetPosition;
    public $WidgetHeader;
    public $WidgetCode;
    public $WidgetShowPersonal;


    public
    function rules()
    {
        return array(
            array('WidgetEnabled, WidgetShowPersonal, WidgetAnimate, WidgetFiles, WidgetService', 'numerical', 'integerOnly' => true),
            array('WidgetColor, WidgetPosition, WidgetHeader', 'required'),
            array('WidgetColor, WidgetPosition, WidgetHeader, WidgetEnabled, WidgetShowPersonal, WidgetCode, WidgetAnimate, WidgetFiles', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'WidgetEnabled' => Yii::t('main-ui', 'Site widget enabled'),
            'WidgetAnimate' => Yii::t('main-ui', 'Animate widget'),
            'WidgetFiles' => Yii::t('main-ui', 'Allow add files in widget'),
            'WidgetService' => Yii::t('main-ui', 'Allow select service in widget'),
            'WidgetColor' => Yii::t('main-ui', 'Site widget color'),
            'WidgetPosition' => Yii::t('main-ui', 'Site widget position'),
            'WidgetHeader' => Yii::t('main-ui', 'Site widget header'),
            'WidgetCode' => Yii::t('main-ui', 'Site widget code'),
            'WidgetShowPersonal' => Yii::t('main-ui', 'Show accept personal information block'),
        );
    }
}

?>
