<?php

$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('viewCUsers')) {
$view = '{view}';
}
$template = $view;
function getUserPhoto($data)
{
    return '<img alt="asas" class="img-circle" width="35" src="/media/userphoto/' . $data->id . '.png">';
}
?>

<?php $dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'phonebook-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->psearch(), //model is used to get attribute labels
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
                'name' => 'company',
                'header' => Yii::t('main-ui', 'Company'),
                'filter' => Companies::all(),
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
                'name' => 'mobile',
                'header' => Yii::t('main-ui', 'Mobile'),
            ],
            [
                'name' => 'intphone',
                'header' => Yii::t('main-ui', 'Internal phone'),
            ],
            [
                'name' => 'room',
                'header' => Yii::t('main-ui', 'Room'),
            ],
            [
                'name' => 'umanager',
                'header' => Yii::t('main-ui', 'Department manager'),
            ],
        ]
    ),
));
$fixed_columns = array_filter(array(
)); ?>