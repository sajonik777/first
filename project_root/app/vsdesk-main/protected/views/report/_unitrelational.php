<?php

$this->menu = array(
    array('label' => Yii::t('main-ui', 'Export to Excel'), 'icon' => 'fa-solid fa-upload', 'url' => array('exportproblemslist', 'asset' => $asset)),
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
            'name' => 'date',
            'header' => Yii::t('main-ui', 'Created'),
            'headerHtmlOptions' => array('width' => 120),
        ),
        array(
            'name' => 'slabel',
            'type' => 'raw',
            'header' => Yii::t('main-ui', 'Status'),
            'headerHtmlOptions' => array('width' => 50),
        ),
        array(
            'name' => 'assets_names',
            'header' => 'КЕ',

        ),
        array(
            'name' => 'downtime',
            'header' => 'Время простоя',

        ),
        array(
            'name' => 'description',
            'header' => Yii::t('main-ui', 'Description'),
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
                    'url' => 'Yii::app()->createUrl("problems/view", array("id"=>$data->id))',
                ),
            ),
        ),
    ),

));