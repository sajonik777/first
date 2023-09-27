<?php

$this->menu = array(
    array('label' => Yii::t('main-ui', 'Export to Excel'), 'icon' => 'fa-solid fa-upload', 'url' => array('exportcomplist', 'sdate' => $sdate, 'edate' => $edate, 'username' => $username)),
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
            'name' => 'lead_time',
            'header' => Yii::t('main-ui', 'Lead time'),
            'headerHtmlOptions' => array('width' => 80),
        ),
        array(
            'name' => 'slabel',
            'type' => 'raw',
            'header' => Yii::t('main-ui', 'Status'),
            'headerHtmlOptions' => array('width' => 50),
        ),
        array(
            'name' => 'company',
            'header' => Yii::t('main-ui', 'Company'),
            'headerHtmlOptions' => array('width' => 90),
        ),
        array(
            'name' => 'Priority',
            'header' => Yii::t('main-ui', 'Priority'),
            'headerHtmlOptions' => array('width' => 50),
        ),
        array(
            'name' => 'cunits',
            'header' => Yii::t('main-ui', 'Configuration unit'),
            'headerHtmlOptions' => array('width' => 250),
        ),
        array(
            'name' => 'Name',
            'header' => Yii::t('main-ui', 'Name'),
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