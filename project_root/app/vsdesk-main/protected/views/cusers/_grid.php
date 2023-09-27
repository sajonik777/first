<?php

$view = null;
$update = null;
$delete = null;
if (Yii::app()->user->checkAccess('viewUser')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateUser')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteUser')) {
    $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;

function getUserPhoto($data)
{
    return '<img alt="asas" class="img-circle" width="35" src="/media/userphoto/' . $data->id . '.png">';
}

$dialog = $this->widget('ext.ecolumns.EColumnsDialog', [
    'options' => [
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ],
    'htmlOptions' => ['style' => 'display: none'], //disable flush of dialog content
    'ecolumns' => [
        'gridId' => 'cusers-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui',
                'Reset') . '" style="float: right">',
        'fixedLeft' => ['CCheckBoxColumn'], //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => [
            [
                'filter' => '',
                'name' => 'photo',
                'headerHtmlOptions' => ['width' => 35],
                'header' => Yii::t('main-ui', 'Photo'),
                'type' => 'raw',
                'value' => '$data->photo == "1" ? getUserPhoto($data) : "&nbsp;"',
            ],
            [
                'name' => 'fullname',
                'header' => Yii::t('main-ui', 'Fullname'),
            ],
            [
                'name' => 'city',
                'header' => Yii::t('main-ui', 'City'),
            ],
            [
                'name' => 'department',
                'header' => Yii::t('main-ui', 'Department'),
//                'filter' => Depart::all(),
            ],
            [
                'name' => 'position',
                'header' => Yii::t('main-ui', 'Position'),
            ],
            [
                'name' => 'Email',
                'header' => Yii::t('main-ui', 'Email'),
            ],
            [
                'name' => 'Phone',
                'header' => Yii::t('main-ui', 'Phone'),
            ],
            [
                'name' => 'intphone',
                'header' => Yii::t('main-ui', 'Internal phone'),
            ],
            [
                'name' => 'mobile',
                'header' => Yii::t('main-ui', 'Mobile'),
            ],
            [
                'name' => 'company',
                'header' => Yii::t('main-ui', 'Company'),
                'filter' => Companies::all(),
            ],
            [
                'name' => 'active',
                'header' => Yii::t('main-ui', 'Active'),
                'value' => '$data->active == "1" ? Yii::t("main-ui", "Yes") : Yii::t("main-ui", "No")',
                'filter' => ['1' => Yii::t('main-ui', 'Yes'), '0' => Yii::t('main-ui', 'No')],
            ],
            [
                'name' => 'Username',
                'header' => Yii::t('main-ui', 'User login'),
            ],
            [
                'name' => 'role_name',
                'header' => Yii::t('main-ui', 'Role'),
                'filter' => Roles::fall(),
            ],
            [
                'name' => 'room',
                'header' => Yii::t('main-ui', 'Room'),
            ],
            [
                'name' => 'umanager',
                'header' => Yii::t('main-ui', 'Department manager'),
            ],
            [
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
            ]
        ]
    ],
]);

$fixed_columns = array_filter([
    Yii::app()->user->checkAccess('batchDeleteUser') ?
        [
            'class' => 'CCheckBoxColumn',// Checkboxes
            'selectableRows' => 2,// Allow multiple selections
            //'resizable' => false
        ] : null,
]);
