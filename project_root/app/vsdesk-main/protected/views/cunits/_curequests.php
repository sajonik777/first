<?php

$config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10));
$rawData = $requests;
$dataProvider = new CArrayDataProvider($rawData, $config);
?>
    <h4><?php echo Yii::t('main-ui', 'Unit requests'); ?></h4>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'problems-grid',
    'type' => 'striped bordered condensed',
    'summaryText' => '',
    'dataProvider' => $dataProvider,
    'columns' => [
        array(
            'name' => 'id',
            'header' => Yii::t('main-ui', '#'),
            'headerHtmlOptions' => array('width' => 60),
            //'resizable' => false
            //'filter' => '',
        ),
        [
            'name' => 'channel',
            'header' =>  Yii::t('main-ui', 'Channel'),
            'headerHtmlOptions' => ['width' => 90],
            'type' => 'raw',
            'filter' => ['Email' => Yii::t('main-ui', 'Email'), 'Manual' => Yii::t('main-ui', 'Manual'),'Planned' => Yii::t('main-ui', 'Planned'),'Portal' => Yii::t('main-ui', 'Portal'),'Telegram' => Yii::t('main-ui', 'Telegram'), 'Viber' => Yii::t('main-ui', 'Viber'), 'Whatsapp' => Yii::t('main-ui', 'Whatsapp'), 'Skype' => Yii::t('main-ui', 'Skype'), 'Slack' => Yii::t('main-ui', 'Slack'), 'Facebook' => Yii::t('main-ui', 'Facebook'), 'Webchat' => Yii::t('main-ui', 'Web chat'), 'Widget' => Yii::t('main-ui', 'Widget')],
            'value' => '$data->channel?Yii::t("main-ui" , "$data->channel") : ""',
        ],
        
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
        ],
        [
            'name' => 'EndTime',
            'header' => Yii::t('main-ui', 'Deadline'),
            'headerHtmlOptions' => ['width' => 70],
    
        ],
        [
            'name' => 'Name',
            //'resizable' => false,
            'header' => Yii::t('main-ui', 'Ticket subject'),
        ],
        [
            'name' => 'fullname',
            'header' => Yii::t('main-ui', 'Customer'),
        ],
        [
            'name' => 'mfullname',
            'header' => Yii::t('main-ui', 'Manager'),
        ],
        [
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => Yii::t('main-ui', 'Actions'),
            'template' => '{view}',
            'buttons' =>
                [
                    'view' =>
                        [
                            'label' => Yii::t('main-ui', 'View'),
                            'url' => 'Yii::app()->createUrl("request/view", array("id"=>$data->id))',
                            'options' => array('target' => '_blank'),
                        ],
                ],
        ]
    ]
));
?>