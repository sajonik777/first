<?php

class RequestForm extends CFormModel
{
    public $enabled;
    public $zdpriority;
    public $zdcategory;
    public $zdsla;
    public $zdtype;
    public $zdmanager;
    public $update_grid;
    public $update_grid_timeout;
    public $grid_items;
    public $t_filter;
    public $monopoly;
    public $kbcategory;
    public $autoaccept;
    public $autoarch;
    public $nocomment;
    public $autoarchdays;
    public $req_columns_default;

    public
    function rules()
    {
        return array(
            array('zdpriority, zdcategory, zdsla, zdmanager, zdtype, t_filter', 'required'),
            array('update_grid, monopoly, autoaccept, autoarch, nocomment', 'numerical', 'integerOnly' => true),
            array('zdpriority, enabled, zdcategory, update_grid_timeout, grid_items, kbcategory, autoarchdays, nocomment, req_columns_default', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
        );
    }

    public
    function attributeLabels()
    {
        return array(
            'enabled' => Yii::t('main-ui', 'Enable simple ticket form'),
            'zdpriority' => Yii::t('main-ui', 'Priority'),
            'zdcategory' => Yii::t('main-ui', 'Category'),
            'kbcategory' => Yii::t('main-ui', 'Knowledgebase category'),
            'zdsla' => Yii::t('main-ui', 'SLA'),
            'zdtype' => Yii::t('main-ui', 'Manager type'),
            'zdmanager' => Yii::t('main-ui', 'Manager'),
            'update_grid' => Yii::t('main-ui', 'Autoupdate ticket list'),
            'update_grid_timeout' => Yii::t('main-ui', 'Autoupdate timeout (sec.)'),
            'grid_items' => Yii::t('main-ui', 'Items on panel'),
            't_filter' => Yii::t('main-ui', 'Filter users in ticket'),
            'monopoly' => Yii::t('main-ui', 'Allow to block ticket assigned to group'),
            'autoaccept' => Yii::t('main-ui', 'Automatically take ticket when adding a comment'),
            'autoarch' => Yii::t('main-ui', 'Automatically archive tickets'),
            'autoarchdays' => Yii::t('main-ui', 'Automatically archive tickets after (days)'),
            'nocomment' => Yii::t('main-ui', 'Deny adding comments to a closed ticket'),
            'req_columns_default' => Yii::t('main-ui', 'Request columns default appear'),
        );
    }
}

?>