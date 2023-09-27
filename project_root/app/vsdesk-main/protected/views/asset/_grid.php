<?php

$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('viewAsset')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateAsset')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteAsset')) {
    $delete = '{delete}';
}

$template = $view . ' ' . $update . ' ' . $print . ' ' . $delete;
$dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'assets-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            array(
                'name' => 'date',
                'headerHtmlOptions' => array('width' => 120),
                'header' => Yii::t('main-ui', 'Date'),
                'filter' => '<div class="dtpicker">'.$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'date',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'htmlOptions' => array(
                        'id' => 'newDatepicker',
                    ),
                    'defaultOptions' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    ),

                ),
                true).'</div>',
            ),
            array(
                'name' => 'asset_attrib_name',
                'header' => Yii::t('main-ui', 'Asset type'),
                'headerHtmlOptions' => array('width' => 200),
                'filter' => AssetAttrib::types(),
            ),
            array(
                'name' => 'name',
                'header' => Yii::t('main-ui', 'Name'),

            ),
            array(
                'name' => 'cusers_fullname',
                'header' => Yii::t('main-ui', 'Fullname'),
                'headerHtmlOptions' => array('width' => 150),
                'filter' => CUsers::ufall(),
            ),
            array(
                'name' => 'cusers_dept',
                'header' => Yii::t('main-ui', 'Department'),
                'headerHtmlOptions' => array('width' => 150),
                'filter' => Depart::all(),
            ),

            array(
                'name' => 'slabel',
                'header' => Yii::t('main-ui', 'Status'),
                'headerHtmlOptions' => array('width' => 150),
                'type' => 'raw',
                'filter' => Astatus::all(),

            ),
            array(
                'name' => 'location',
                'header' => Yii::t('main-ui', 'Location'),
                'headerHtmlOptions' => array('width' => 150),
            ),
            array(
                'name' => 'inventory',
                'header' => Yii::t('main-ui', 'Inventory number'),
                'headerHtmlOptions' => array('width' => 150),
            ),
            array(
                'name' => 'cost',
                'header' => Yii::t('main-ui', 'Cost'),
                'headerHtmlOptions' => array('width' => 70),
            ),
            array(
                'name' => 'description',
                'header' => Yii::t('main-ui', 'Description'),
                'type' => 'raw',
                'headerHtmlOptions' => array('width' => 150),
            ),

            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'headerHtmlOptions' => array('width' => 70),
                'template' => $template,
                'buttons' => array
                (
                    'view' => array
                    (
                        'label' => Yii::t('main-ui', 'View'),
                        'url' => 'Yii::app()->createUrl("asset/view", array("id"=>$data->id))',
                    ),
                    'update' => array
                    (
                        'label' => Yii::t('main-ui', 'Edit'),
                        'url' => 'Yii::app()->createUrl("asset/update", array("id"=>$data->id))',
                    ),
                ),
            )
        )
    ),
));
$fixed_columns = array_filter(array(
    Yii::app()->user->checkAccess('batchDeleteAsset') ?
    array(
            'class' => 'CCheckBoxColumn',// Checkboxes
            'selectableRows' => 2,// Allow multiple selections
            //'resizable' => false
        ) : null,
    array(
        'name' => 'id',
        'header' => Yii::t('main-ui', '#'),
        'headerHtmlOptions' => array('width' => 30),
        'filter' => '',
    ),
    array(
        'name'             =>'image',
        'headerHtmlOptions'=> array('width'=>10),
        'type'             =>'raw',
        'filter'           => '',
        'header' => CHtml::tag('i', array('class' => "fa-solid fa-paperclip"), null),
        'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"fa-solid fa-paperclip"), null) : ""',
    ),
    array(
        'name' => 'uid',
        'type' => 'raw',
        'header' => CHtml::tag('i', array('class'=>"fa-solid fa-computer"), null),
        'headerHtmlOptions' => array('width' => 30),
        'value' => '$data->uid?CHtml::tag("a", array("href"=>"/cunits/$data->uid"), CHtml::tag("i", array("class"=>"fa-solid fa-computer"), false)): ""',
        'filter' => '',
    ),


));
