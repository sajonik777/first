<?php

$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('viewCompany')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateCompany')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteCompany')) {
    $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;
$fields_colums = [];
$fieldsets_fields = Yii::app()->db->createCommand('SELECT id, `name`, `type` FROM company_fieldset')->queryAll();
foreach ($fieldsets_fields as $field) {
    $filter = NULL;
    if($field['type'] == 'toggle'){
        $filter = array('1' => 'Да');
    }
    $fields_colums[] = [
        'name' => 'ff_id_' . $field['id'],
        'header' => $field['name'],
        'value' => '$data->ff_id_'.$field['id'],
        'filter' => $filter
    ];
}
$columns = array(
            array(
                'name' => 'name',
                'header' => Yii::t('main-ui', 'Name'),
            ),
            array(
                'name' => 'director',
                'header' => Yii::t('main-ui', 'CIOOIT'),
            ),
            array(
                'name' => 'uraddress',
                'header' => Yii::t('main-ui', 'Legal address'),
            ),
            array(
                'name' => 'faddress',
                'header' => Yii::t('main-ui', 'Actual address'),
            ),
            array(
                'name' => 'inn',
                'header' => Yii::t('main-ui', 'INN (Russia only)'),
            ),
            array(
                'name' => 'kpp',
                'header' => Yii::t('main-ui', 'KPP (Russia only)'),
            ),
            array(
                'name' => 'ogrn',
                'header' => Yii::t('main-ui', 'ORGN (Russia only)'),
            ),
            array(
                'name' => 'bik',
                'header' => Yii::t('main-ui', 'BIK (Russia only)'),
            ),
            array(
                'name' => 'korschet',
                'header' => Yii::t('main-ui', 'Cor. account (Russia only)'),
            ),
            array(
                'name' => 'schet',
                'header' => Yii::t('main-ui', 'Account (Russia only)'),
            ),
            array(
                'name' => 'phone',
                'header' => Yii::t('main-ui', 'Phone'),
            ),
            array(
                'name' => 'email',
                'header' => Yii::t('main-ui', 'Email'),
            ),
            array(
                'name' => 'contact_name',
                'header' => Yii::t('main-ui', 'Contact name'),
            ),
            array(
                'name' => 'manager',
                'header' => Yii::t('main-ui', 'Manager of company'),
            ),array(
                'name' => 'add1',
                'header' => Yii::t('main-ui', 'Additional field'),
                'type' => 'html',
            ),array(
                'name' => 'add2',
                'header' => Yii::t('main-ui', 'Additional field2'),
                'type' => 'html',
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
            )
        );
$dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'zIndex' => 10000,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'companies-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array_merge($columns, $fields_colums),
),
));
$fixed_columns = array_filter(array(
        Yii::app()->user->checkAccess('batchDeleteCompany') ?
        array(
                'class' => 'CCheckBoxColumn',// Checkboxes
                'selectableRows' => 2,// Allow multiple selections
                //'resizable' => false
            ) : null,
        array(
            'name'             =>'image',
            'headerHtmlOptions'=> array('width'=>10),
            'type'             =>'raw',
            'filter'           => '',
            'header' => CHtml::tag('i', array('class' => "fa-solid fa-paperclip"), null),
            'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"fa-solid fa-paperclip"), null) : ""',
        ),
    ));