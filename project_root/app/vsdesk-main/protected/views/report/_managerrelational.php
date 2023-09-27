<?php

$this->menu = array(
    array('label' => Yii::t('main-ui', 'Export to Excel'), 'icon' => 'fa-solid fa-upload', 'url' => array('exportmanagerlist', 'sdate' => $sdate, 'edate' => $edate, 'username' => $username, 'company' => $company, 'type' => $type)),
);
?>
<?php
if ($gridDataProvider)
    $this->widget('bootstrap.widgets.TbMenu', array(
        'type' => 'pills',
        'items' => $this->menu,
    )); ?>
<?php
$config = array('keyField' => 'id', 'pagination' => false);
$rawData = $gridDataProvider;
$dataProvider = new CArrayDataProvider($rawData, $config);

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type' => 'striped bordered',
    'template' => "{items}",
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'id',
            'header' => Yii::t('main-ui', '#'),
            'headerHtmlOptions' => array('width' => 20),
        ),
        array(
            'name' => 'Date',
            'header' => Yii::t('main-ui', 'Created'),
            'headerHtmlOptions' => array('width' => 120),
        ),
        array(
            'name' => 'EndTime',
            'header' => Yii::t('main-ui', 'Deadline'),
            'headerHtmlOptions' => array('width' => 120),
        ),
        array(
            'name' => 'lead_time',
            'header' => Yii::t('main-ui', 'Lead time'),
            'headerHtmlOptions' => array('width' => 120),
        ),
        [
            'name' => 'rating',
            'header' => Yii::t('main-ui', 'Rating'),
            'type' => 'raw',
        ],
        array(
            'name' => 'slabel',
            'type' => 'raw',
            'header' => Yii::t('main-ui', 'Status'),
            'headerHtmlOptions' => array('width' => 50),
        ),
        array(
            'name' => 'Name',
            'header' => Yii::t('main-ui', 'Name'),
        ),
        array(
            'name' => 'Priority',
            'header' => Yii::t('main-ui', 'Priority'),
        ),
        array(
            'name' => 'fullname',
            'header' => Yii::t('main-ui', 'Customer'),

        ),
        array(
            'name' => 'company',
            'header' => Yii::t('main-ui', 'Company'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => Yii::t('main-ui', 'Actions'),
            'template' => '{view}',
            'buttons' => array
            (
                'view' => array
                (
                    'label' => Yii::t('main-ui', 'View'),
                    'url' => 'Yii::app()->createUrl("request/view", array("id"=>$data->id))',
                ),
            ),
        ),
    ),

));