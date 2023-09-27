<?php

$view = NULL;
$update = NULL;
$delete = NULL;
$filter_types = Contracts::getTypes();
if (Yii::app()->user->checkAccess('viewContracts')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateContracts')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteContracts')) {
    $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;
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
        'gridId' => 'contracts-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            [
                'name' => 'number',
                'header' => Yii::t('main-ui', 'Contract number'),
            ],
            [
                'name' => 'name',
                'header' => Yii::t('main-ui', 'Name'),
            ],
            [
                'name' => 'type',
                'header' => Yii::t('main-ui', 'Type'),
                'filter' => $filter_types
            ],
            [
                'name' => 'date_view',
                'header' => Yii::t('main-ui', 'Start of contract'),
                'filter' => '<div class="dtpicker">' . $this->widget('bootstrap.widgets.TbDateRangePicker', [
                        'model' => $model,
                        'attribute' => 'date_view',
                        'callback' => 'js:function(){$(this.element).change();}',
                        'options' => [
                            'format' => 'DD.MM.YYYY',
                            'language' => 'ru',
                            'ranges' => [
                                'Сегодня' => 'js:[moment(), moment()]',
                                'Вчера' => 'js:[moment().subtract("days", 1), moment().subtract("days", 1)]',
                                'Последние 7 дней' => 'js:[moment().subtract("days", 6), moment()]',
                                'Последние 30 дней' => 'js:[moment().subtract("days", 29), moment()]',
                                'В этом месяце' => 'js:[moment().startOf("month"), moment().endOf("month")]',
                                'В прошлом месяце' => 'js:[moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")]',
                            ],
                            'locale' => [
                                'fromLabel' => 'От',
                                'toLabel' => 'До',
                                'weekLabel' => 'Н',
                                'customRangeLabel' => 'Задать даты',
                                'applyLabel' => 'Применить',
                                'cancelLabel' => 'Отмена',
                                'firstDay' => 1,
                            ],
                        ],

                        'htmlOptions' => [
                            'id' => 'newDatepicker',
                            'class' => 'betweenDatepicker',
                        ],

                    ],
                        true) . '</div>',
            ],
            [
                'name' => 'tildate_view',
                'header' => Yii::t('main-ui', 'Contract termination'),
                'filter' => '<div class="dtpicker">' . $this->widget('bootstrap.widgets.TbDateRangePicker', [
                        'model' => $model,
                        'attribute' => 'tildate_view',
                        'callback' => 'js:function(){$(this.element).change();}',
                        'options' => [
                            'format' => 'DD.MM.YYYY',
                            'language' => 'ru',
                            'ranges' => [
                                'Сегодня' => 'js:[moment(), moment()]',
                                'Вчера' => 'js:[moment().subtract("days", 1), moment().subtract("days", 1)]',
                                'Последние 7 дней' => 'js:[moment().subtract("days", 6), moment()]',
                                'Последние 30 дней' => 'js:[moment().subtract("days", 29), moment()]',
                                'В этом месяце' => 'js:[moment().startOf("month"), moment().endOf("month")]',
                                'В прошлом месяце' => 'js:[moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")]',
                            ],
                            'locale' => [
                                'fromLabel' => 'От',
                                'toLabel' => 'До',
                                'weekLabel' => 'Н',
                                'customRangeLabel' => 'Задать даты',
                                'applyLabel' => 'Применить',
                                'cancelLabel' => 'Отмена',
                                'firstDay' => 1,
                            ],
                        ],

                        'htmlOptions' => [
                            'id' => 'new2Datepicker',
                            'class' => 'betweenDatepicker',
                        ],

                    ],
                        true) . '</div>',
            ],
            [
                'name' => 'customer_name',
                'header' => Yii::t('main-ui', 'Customer'),
            ],
            [
                'name' => 'company_name',
                'header' => Yii::t('main-ui', 'Contractor'),
            ],
            [
                'name' => 'cost',
                'header' => Yii::t('main-ui', 'Cost'),
            ],
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
            )
        )
    ),
));
$fixed_columns = array_filter(array(
//    array(
//        'name' => 'id',
//        'header' => Yii::t('main-ui', '#'),
//        'headerHtmlOptions' => array('width' => 60),
//        //'filter' => '',
//    ),
    array(
        'name'             =>'expired',
        'headerHtmlOptions'=> array('width'=>10),
        'type'             =>'raw',
        'filter'           => '',
        'header' => CHtml::tag('i', array('class' => "fa-solid fa-circle-exclamation"), null),
        'value' => '$data->expired ? CHtml::tag("i", array("class"=>"fa-solid fa-circle-exclamation", "style" => "color: red"), null) : ""',
    ),
    array(
        'name'             =>'image',
        'headerHtmlOptions'=> array('width'=>10),
        'type'             =>'raw',
        'filter'           => '',
        'header' => CHtml::tag('i', array('class' => "fa-solid fa-paperclip"), null),
        'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"fa-solid fa-paperclip"), null) : ""',
    ),
)); ?>