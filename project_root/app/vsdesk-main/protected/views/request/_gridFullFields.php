<?php

$view = null;
$update = null;
$delete = null;

if (Yii::app()->user->checkAccess('viewRequest')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateRequest')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteRequest')) {
    $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;

include_once '_dropdownCheckList.php';
$clmns = [
    [
        'name' => 'slabel',
        'type' => 'raw',
        'header' => Yii::t('main-ui', 'Status'),
        'filter'=>$checklistFullFields,
//        'filter' => $this->widget('bootstrap.widgets.TbSelect2', [
//            'model' => $model,
//            'attribute' => 'slabel',
//            'data' => Status::all(),
//            'htmlOptions' => [
//                'multiple' => 'multiple',
//                'id' => 'slabel2',
//                'style' => 'width: 150px',
//            ],
//        ], true),
    ],
    [
        'name' => 'Date',
        'headerHtmlOptions' => ['width' => 120],
        'header' => Yii::t('main-ui', 'Created'),
        'filter' => '<div class="dtpicker">' . $this->widget('bootstrap.widgets.TbDateRangePicker', [
                'model' => $model,
                'attribute' => 'Date',
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
        'name' => 'StartTime',
        'header' => Yii::t('main-ui', 'Start Time'),
        'headerHtmlOptions' => ['width' => 70],
        'filter' => '<div class="dtpicker">' . $this->widget('bootstrap.widgets.TbDateRangePicker', [
                'model' => $model,
                'attribute' => 'StartTime',
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
                    'id' => 'snewDatepicker',
                    'class' => 'betweenDatepicker',
                ],
            ],
                true) . '</div>',

    ],
    [
        'name' => 'fStartTime',
        'header' => Yii::t('main-ui', 'Fact Start time'),
        'headerHtmlOptions' => ['width' => 70],
        'filter' => '<div class="dtpicker">' . $this->widget('bootstrap.widgets.TbDateRangePicker', [
                'model' => $model,
                'attribute' => 'fStartTime',
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
                    'id' => 'fsnewDatepicker',
                    'class' => 'betweenDatepicker',
                ],
            ],
                true) . '</div>',

    ],
    [
        'name' => 'EndTime',
        'header' => Yii::t('main-ui', 'Deadline'),
        'headerHtmlOptions' => ['width' => 70],
        'filter' => '<div class="dtpicker">' . $this->widget('bootstrap.widgets.TbDateRangePicker', [
                'model' => $model,
                'attribute' => 'EndTime',
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
                    'id' => 'fnewDatepicker',
                    'class' => 'betweenDatepicker',
                ],
            ],
                true) . '</div>',

    ],
    [
        'name' => 'fEndTime',
        'header' => Yii::t('main-ui', 'Fact End Time'),
        'headerHtmlOptions' => ['width' => 70],
        //'headerHtmlOptions'=> array('width'=>90),
        'filter' => '<div class="dtpicker">' . $this->widget('bootstrap.widgets.TbDateRangePicker', [
                'model' => $model,
                'attribute' => 'fEndTime',
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
                    'id' => 'fenewDatepicker',
                    'class' => 'betweenDatepicker',
                ],
            ],
                true) . '</div>',
    ],
    [
        'name' => 'lead_time',
        'header' => Yii::t('main-ui', 'Time worked'),
    ],
    [
        'name' => 'Name',
        //'resizable' => false,
        'header' => Yii::t('main-ui', 'Ticket subject'),
    ],
    [
        'name' => 'phone',
        'header' => Yii::t('main-ui', 'Phone'),
    ],
    [
        'name' => 'room',
        'header' => Yii::t('main-ui', 'Room'),
    ],
    [
        'name' => 'Address',
        'header' => Yii::t('main-ui', 'Address'),
    ],
    [
        'name' => 'company',
        'header' => Yii::t('main-ui', 'Company'),
    ],
    [
        'name' => 'depart',
        'header' => Yii::t('main-ui', 'Department'),
    ],
    [
        'name' => 'creator',
        'header' => Yii::t('main-ui', 'Creator'),
    ],
    [
        'name' => 'fullname',
        'header' => Yii::t('main-ui', 'Customer'),
    ],
    [
        'name' => 'cunits',
        'header' => Yii::t('main-ui', 'Units'),
    ],
    [
        'name' => 'service_name',
        'header' => Yii::t('main-ui', 'Service'),
    ],
    [
        'name' => 'mfullname',
        'header' => Yii::t('main-ui', 'Manager'),
    ],
    [
        'name' => 'groups_id',
        'value' => '$data->groups_rl ? $data->groups_rl->name : NULL',
        'header' => Yii::t('main-ui', 'Group'),
        'filter' => CHtml::listData(Groups::model()->findAll(), 'id', 'name'),
    ],
    [
        'name' => 'ZayavCategory_id',
        'header' => Yii::t('main-ui', 'Category'),
        'filter' => Category::model()->All(),
    ],
    [
        'name' => 'KE_type',
        'header' => Yii::t('main-ui', 'Category KE'),
        'filter' => CunitTypes::model()->All(),
    ],
    
    [
        'name' => 'Priority',
        'header' => Yii::t('main-ui', 'Priority'),
        'filter' => Zpriority::model()->all(),
    ],
    [
        'name' => 'Content',
        'header' => Yii::t('main-ui', 'Content'),
        'value' => 'strip_tags($data->Content)',
        'filter' => false,
    ],
    [
        'name' => 'rating',
        'header' => Yii::t('main-ui', 'Rating'),
        'type' => 'raw',
        'value' => '$this->grid->controller->widget("CStarRating", array(
                        "name" => $data->id,
                        "id" => $data->id,
                        "minRating" => "1",
                        "maxRating" => "5",
                        "ratingStepSize" => "1",
                        "starWidth" => "10",
                        "value" => $data->rating,
                        "readOnly" => true,
                    ), true)',
        'headerHtmlOptions' => array('width' => 100),
        'htmlOptions' => array('class' => 'rating-block'),
        'filter' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
        'sortable' => false,
    ],
    [
        'name' => 'channel',
        'header' =>  Yii::t('main-ui', 'Channel'),
        'headerHtmlOptions' => ['width' => 90],
        'type' => 'raw',
        'filter' => ['Email' => Yii::t('main-ui', 'Email'), 'Manual' => Yii::t('main-ui', 'Manual'),'Planned' => Yii::t('main-ui', 'Planned'),'Portal' => Yii::t('main-ui', 'Portal'),'Telegram' => Yii::t('main-ui', 'Telegram'), 'Viber' => Yii::t('main-ui', 'Viber'), 'Whatsapp' => Yii::t('main-ui', 'Whatsapp'), 'Skype' => Yii::t('main-ui', 'Skype'), 'Slack' => Yii::t('main-ui', 'Slack'), 'Facebook' => Yii::t('main-ui', 'Facebook'), 'Webchat' => Yii::t('main-ui', 'Web chat'), 'Widget' => Yii::t('main-ui', 'Widget')],
        'value' => '$data->channel?Yii::t("main-ui" , "$data->channel") : ""',
    ],
    [
        'class' => 'bootstrap.widgets.TbButtonColumn',
        'header' => Yii::t('main-ui', 'Actions'),
        'template' => $template,
        'buttons' =>
            [
                'view' =>
                    [
                        'label' => Yii::t('main-ui', 'View'),
                        'url' => 'Yii::app()->createUrl("request/view", array("id"=>$data->id))',
                    ],
                'update' =>
                    [
                        'label' => Yii::t('main-ui', 'Edit'),
                        'url' => 'Yii::app()->createUrl("request/update", array("id"=>$data->id))',
                    ],
            ],
    ]
];
;
$fields_colums = [];
$fieldsets_fields = Yii::app()->db->createCommand('SELECT id, `name`, `type` FROM fieldsets_fields')->queryAll();
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
$clmns = array_merge($clmns, $fields_colums);

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
        'gridId' => 'request-grid-full2', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui',
                'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        //'model' => $model->search(), //model is used to get attribute labels
        'columns' => $clmns
    ),
));
$responsive = false;
if (isset(Yii::app()->session['requestResponsive']) AND Yii::app()->session['requestResponsive'] == 1) {
    $responsive = true;
}
$fixed_columns = array_filter(array(
    Yii::app()->session['requestStopTimer'] !== 1 ?
        array(
            'class' => 'CCheckBoxColumn',// Checkboxes
            'selectableRows' => 2,// Allow multiple selections
            //'resizable' => false
        ) : null,
    array(
        'name' => 'id',
        'header' => Yii::t('main-ui', '#'),
        'headerHtmlOptions' => array('width' => 60),
        //'resizable' => false
        //'filter' => '',
    ),
    array(
        'name' => 'image',
        'headerHtmlOptions' => array('width' => 10),
        'type' => 'raw',
        'header' => (!$responsive) ? CHtml::tag('i', array('class' => "fa-solid fa-paperclip"), null) : Yii::t('main-ui', 'Attachment'),
        'filter' => '',
        'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"fa-solid fa-paperclip"), null) : ""',
        //'resizable' => false
    ),
//    array(
//        'name' => 'update_by',
//        'headerHtmlOptions' => array('width' => 10),
//        'type' => 'raw',
//        'header' => (!$responsive) ? CHtml::tag('i', array('class' => "fa-solid fa-lock"), null) : Yii::t('main-ui', 'Locked'),
//        'filter' => '',
//        'value' => '$data->update_by?CHtml::tag("i", array("class"=>"fa-solid fa-lock"), null) : ""',
//        //'resizable' => false
//    ),
    array(
        'name' => 'Comment',
        'type' => 'raw',
        'header' => (!$responsive) ? CHtml::tag('i', array('class' => "fa-solid fa-comment"), null) : Yii::t('main-ui', 'Comment'),
        'headerHtmlOptions' => array('width' => 10),
        'filter' => '',
        //'resizable' => false
    ),
    array(
        'name' => 'child',
        'headerHtmlOptions' => array('width' => 10),
        'type' => 'raw',
        'header' => (!$responsive) ? CHtml::tag('i', array('class' => "fa-solid fa-briefcase"), null) : Yii::t('main-ui', 'Joint'),
        'filter' => '',
        //'resizable' => false
    ),
    array(
        'name' => 'channel_image',
        'header' => (!$responsive) ? CHtml::tag('i', array('class' => "fa-solid fa-at"), null) : Yii::t('main-ui', 'Channel'),
        'headerHtmlOptions' => array('width' => 10),
        'type' => 'raw',
        'filter' => false,
        //'resizable' => false,
        //'filter' => array('Email' => Yii::t('main-ui', 'Email'), 'Manual' => Yii::t('main-ui', 'Manual'),'Planned' => Yii::t('main-ui', 'Planned'),'Portal' => Yii::t('main-ui', 'Portal'),'Telegram' => Yii::t('main-ui', 'Telegram'), 'Widget' => Yii::t('main-ui', 'Widget')),
        'value' => '$data->channel_icon?CHtml::tag("i", array("class"=>"$data->channel_icon"), null) : ""',
    ),
));
