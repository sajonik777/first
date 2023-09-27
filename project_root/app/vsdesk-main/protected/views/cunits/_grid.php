<?php

$view = NULL;
$update = NULL;
$delete = NULL;
$print = NULL;
if (Yii::app()->user->checkAccess('viewUnit')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateUnit')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteUnit')) {
    $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;
$dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'cunits-grid', //id of related grid
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
                'name' => 'type',
                'filter' => CunitTypes::All(),
                'header' => Yii::t('main-ui', 'Unit type'),
                'htmlOptions' => array(
                    'width' => 200,
                ),
            ),
            array(
                'name' => 'name',
                'header' => Yii::t('main-ui', 'Name'),
                'htmlOptions' => array(
                    'width' => 200,
                ),
            ),
            array(
                'name' => 'slabel',
                'header' => Yii::t('main-ui', 'Status'),
                'type' => 'raw',
                'filter' => Ustatus::All(),
                'htmlOptions' => array(
                    'width' => 150,
                ),
            ),
            array(
                'name' => 'company',
                'header' => Yii::t('main-ui', 'Company'),
                'filter' => Companies::All(),
                'htmlOptions' => array(
                    'width' => 150,
                )
            ),
            array(
                'name' => 'fullname',
                'header' => Yii::t('main-ui', 'User'),

            ),
            array(
                'name' => 'dept',
                'header' => Yii::t('main-ui', 'Department'),
                'htmlOptions' => array(
                    'width' => 150,
                )
            ),
            array(
                'name' => 'inventory',
                'header' => Yii::t('main-ui', 'Inventory number'),
                'htmlOptions' => array(
                    'width' => 150,
                )
            ),
            // array(
            //     'name' => 'location',
            //     'header' => Yii::t('main-ui', 'Location'),
            //     'htmlOptions' => array(
            //         'width' => 150,
            //     )
            // ),
            // array(
            //     'name' => 'cost',
            //     'header' => Yii::t('main-ui', 'Cost'),
            //     'htmlOptions' => array(
            //         'width' => 150,
            //     )
            // ),
            array(
                'name' => 'description',
                'header' => Yii::t('main-ui', 'Description'),
                'type' => 'raw',
                'htmlOptions' => array(
                    'width' => 150,
                )
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'headerHtmlOptions' => array('width' => 70),
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
                'buttons' => array(
                    'print' => array
                    (
                        'label' => Yii::t('main-ui', 'Print'),
                        'url' => 'Yii::app()->createUrl("cunits/print", array("id"=>$data->id))',
                        'icon' => 'icon-print',
                        'options' => array('target' => '_blank'),
                    ),
                )
            ),
        ),
    ),
));
$fixed_columns = array_filter(array(
    Yii::app()->user->checkAccess('batchDeleteUnit') ?
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

));