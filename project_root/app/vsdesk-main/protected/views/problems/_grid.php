<?php

$view = null;
$update = null;
$delete = null;
if (Yii::app()->user->checkAccess('viewProblem')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateProblem')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteProblem')) {
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
        'gridId' => 'problems-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui',
                'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            array(
                'name' => 'slabel',
                'type' => 'html',
                'header' => Yii::t('main-ui', 'Status'),
                'filter' => Pstatus::model()->all(),
            ),
            array(
                'name' => 'date',
                'headerHtmlOptions' => array('width' => 70),
                'header' => Yii::t('main-ui', 'Created'),
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
                'name' => 'priority',
                'header' => Yii::t('main-ui', 'Priority'),
                'filter' => Zpriority::model()->all(),
            ),
            array(
                'name' => 'category',
                'header' => Yii::t('main-ui', 'Category'),
                'filter' => ProblemCats::all(),
            ),
            array(
                'name' => 'manager',
                'header' => Yii::t('main-ui', 'Manager'),
            ),
            array(
                'name' => 'service',
                'header' => Yii::t('main-ui', 'Service'),
            ),
            array(
                'name' => 'downtime',
                'header' => Yii::t('main-ui', 'Downtime (hh:mm)'),
            ),
            array(
                'name' => 'influence',
                'header' => Yii::t('main-ui', 'Influence'),
            ),
            array(
                'name' => 'creator',
                'header' => Yii::t('main-ui', 'Creator'),
            ),
            array(
                'name' => 'description',
                'header' => Yii::t('main-ui', 'Description'),
                'value' => 'strip_tags($data->description)',
                'filter' => false,
            ),

            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
            )
        )
    ),
));

$fixed_columns = array(
    array(
        'class' => 'CCheckBoxColumn',// Checkboxes
        'selectableRows' => 2,// Allow multiple selections
    ),
    array(
        'name' => 'id',
        'header' => Yii::t('main-ui', '#'),
        'filter' => '',
    ),
    array(
        'name' => 'image',
        'headerHtmlOptions' => array('width' => 10),
        'type' => 'html',
        'header' => CHtml::tag('i class="fa-solid fa-paperclip"'),
        'filter' => '',
        'value' => '($data->image || $data->files) ? CHtml::tag("i class=\"fa-solid fa-paperclip\""): ""',
    ),
);