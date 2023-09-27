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
        'gridId' => 'request-grid-default', //id of related grid
        'storage' => 'session',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="button" id="req_columns_save" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => false,
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'columns' => [
            [
                'name' => 'slabel',
                'header' => Yii::t('main-ui', 'Status'),
            ],
            [
                'name' => 'Date',
                'header' => Yii::t('main-ui', 'Created'),
            ],
            [
                'name' => 'StartTime',
                'header' => Yii::t('main-ui', 'Start Time'),

            ],
            [
                'name' => 'fStartTime',
                'header' => Yii::t('main-ui', 'Fact Start time'),

            ],
            [
                'name' => 'EndTime',
                'header' => Yii::t('main-ui', 'Deadline'),

            ],
            [
                'name' => 'fEndTime',
                'header' => Yii::t('main-ui', 'Fact End Time'),
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
            ],
            [
                'name' => 'ZayavCategory_id',
                'header' => Yii::t('main-ui', 'Category'),
            ],
            [
                'name' => 'Priority',
                'header' => Yii::t('main-ui', 'Priority'),
            ],
            [
                'name' => 'Content',
                'header' => Yii::t('main-ui', 'Content'),
            ],
            [
                'name' => 'rating',
                'header' => Yii::t('main-ui', 'Rating'),
            ],
            [
                'name' => 'channel',
                'header' => Yii::t('main-ui', 'Channel'),
            ],
            [
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
            ]
        ]
    ),
));



