<?php

$dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'request-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui',
                'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->searchmain(), //model is used to get attribute labels
        'columns' => array(
            array(
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
                'filter' => false,
                'sortable' => false,
            ),
            array(
                'name' => 'slabel',
                'type' => 'raw',
                'header' => Yii::t('main-ui', 'Status'),
                //'headerHtmlOptions'=> array('width'=>50),
            ),
            array(
                'name' => 'Date',
                'header' => Yii::t('main-ui', 'Created'),
                //'headerHtmlOptions'=> array('width'=>90),
            ),
            array(
                'name' => 'StartTime',
                'header' => Yii::t('main-ui', 'Start Time'),
                //'headerHtmlOptions'=> array('width'=>90),
            ),
            array(
                'name' => 'fStartTime',
                'header' => Yii::t('main-ui', 'Fact Start time'),
                //'headerHtmlOptions'=> array('width'=>90),
            ),
            array(
                'name' => 'EndTime',
                'header' => Yii::t('main-ui', 'Deadline'),
                //'headerHtmlOptions'=> array('width'=>90),
            ),
            array(
                'name' => 'fEndTime',
                'header' => Yii::t('main-ui', 'Fact End Time'),
                //'headerHtmlOptions'=> array('width'=>90),
            ),
            array(
                'name' => 'Name',
                'header' => Yii::t('main-ui', 'Name'),
                //'headerHtmlOptions'=> array('width'=>400),
            ),
            array(
                'name' => 'lead_time',
                'header' => Yii::t('main-ui', 'Time worked'),
                //'headerHtmlOptions'=> array('width'=>90),
            ),
            array(
                'name' => 'phone',
                'header' => Yii::t('main-ui', 'Phone'),
                //'headerHtmlOptions'=> array('width'=>400),
            ),
            array(
                'name' => 'room',
                'header' => Yii::t('main-ui', 'Room'),
                //'headerHtmlOptions'=> array('width'=>400),
            ),
            array(
                'name' => 'Address',
                'header' => Yii::t('main-ui', 'Address'),
                //'headerHtmlOptions'=> array('width'=>400),
            ),
            array(
                'name' => 'company',
                'header' => Yii::t('main-ui', 'Company'),
                //'headerHtmlOptions'=> array('width'=>400),
            ),
            array(
                'name' => 'creator',
                'header' => Yii::t('main-ui', 'Creator'),
            ),
            array(
                'name' => 'fullname',
                //'headerHtmlOptions'=> array('width'=>150),
                'header' => Yii::t('main-ui', 'Customer'),
            ),
            array(
                'name' => 'mfullname',
                'header' => Yii::t('main-ui', 'Manager'),
            ),
            array(
                'name' => 'cunits',
                //'headerHtmlOptions'=> array('width'=>150),
                'header' => Yii::t('main-ui', 'Units'),
            ),
            array(
                'name' => 'service_name',
                //'headerHtmlOptions'=> array('width'=>150),
                'header' => Yii::t('main-ui', 'Service'),
            ),
            array(
                'name' => 'groups_id',
                'value' => '$data->groups_rl ? $data->groups_rl->name : NULL',
                'header' => Yii::t('main-ui', 'Group'),
                'filter' => CHtml::listData(Groups::model()->findAll(), 'id', 'name'),
            ),
            array(
                'name' => 'ZayavCategory_id',
                //'headerHtmlOptions'=> array('width'=>120),
                'header' => Yii::t('main-ui', 'Category'),
            ),
            array(
                'name' => 'Priority',
                //'headerHtmlOptions'=> array('width'=>80),
                'header' => Yii::t('main-ui', 'Priority'),
            ),
            array(
                'name' => 'Content',
                'header' => Yii::t('main-ui', 'Content'),
                'value' => 'strip_tags($data->Content)',
                'filter' => false,
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                //'headerHtmlOptions'=> array('width'=>50),
                'template' => '{view} {update}',
                'buttons' => array
                (
                    'view' => array
                    (
                        'label' => Yii::t('main-ui', 'View'),
                        'url' => 'Yii::app()->createUrl("request/view", array("id"=>$data->id))',
                    ),
                    'update' => array
                    (
                        'label' => Yii::t('main-ui', 'Edit'),
                        'url' => 'Yii::app()->createUrl("request/update", array("id"=>$data->id))',
                    ),
                ),
            )
        )
    ),
));
$fixed_columns = array(
    array(
        'name' => 'id',
        'header' => '№',
    ),
    array(
        'name' => 'image',
        'headerHtmlOptions' => array('width' => 10),
        'type' => 'raw',
        'header' => CHtml::tag('i', array('class'=>"fa-solid fa-paperclip"), null),
        'filter' => '',
        'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"fa-solid fa-paperclip"), null) : ""',
    ),
//    array(
//        'name' => 'update_by',
//        'headerHtmlOptions' => array('width' => 10),
//        'type' => 'raw',
//        'header' => CHtml::tag('i', array('class'=>"fa-solid fa-lock"), null),
//        'filter' => '',
//        'value' => '$data->update_by?CHtml::tag("i", array("class"=>"fa-solid fa-lock"), null) : ""',
//    ),
    array(
        'name' => 'Comment',
        'type' => 'raw',
        'header' => CHtml::tag('i', array('class'=>"fa-solid fa-comment"), null),
        'headerHtmlOptions' => array('width' => 10),
        'filter' => '',
    ),
    array(
        'name' => 'child',
        'headerHtmlOptions' => array('width' => 10),
        'type' => 'raw',
        'header' => CHtml::tag('i', array('class'=>"fa-solid fa-briefcase"), null),
        'filter' => '',
    ),
        array(
        'name' => 'channel_icon',
        'header' => (!$responsive) ? CHtml::tag('i', array('class' => "fa-solid fa-at"), null) : Yii::t('main-ui', 'Channel'),
        'headerHtmlOptions' => array('width' => 10),
        'type' => 'raw',
        'filter' => '',
        'value' => '$data->channel_icon?CHtml::tag("i", array("class"=>"$data->channel_icon"), null) : ""',
    ),
);
