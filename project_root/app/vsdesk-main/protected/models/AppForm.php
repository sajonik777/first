<?php

class AppForm extends CFormModel
{
    public $fixedPanel;
    public $showBtn;
    public $brandName;
    public $loginText;
    public $mainLogo;
    public $smallLogo;
    public $theme;
    public $portalHeader;
    public $portalText;


    public
    function rules()
    {
        return array(
            array('brandName, loginText, mainLogo, smallLogo', 'required'),
            array('brandName, loginText, mainLogo, smallLogo, fixedPanel, showBtn, theme, portalHeader, portalText', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'fixedPanel' => Yii::t('main-ui', 'Fixed top and side panels'),
            'showBtn' => Yii::t('main-ui', 'Show scroll buttons in grid lists'),
            'brandName' => Yii::t('main-ui', 'System name'),
            'loginText' => Yii::t('main-ui', 'Login text'),
            'mainLogo' => Yii::t('main-ui', 'Main login logo file'),
            'smallLogo' => Yii::t('main-ui', 'Small panel logo file (20x20)'),
            'theme' => Yii::t('main-ui', 'Color theme'),
            'portalHeader' => Yii::t('main-ui', 'Portal header text'),
            'portalText' => Yii::t('main-ui', 'Portal greetings text'),
        );
    }
}

?>
