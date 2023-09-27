<?php

$config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10));
$rawData = $problems;
$dataProvider = new CArrayDataProvider($rawData, $config);
?>
    <h4><?php echo Yii::t('main-ui', 'Unit problems'); ?></h4>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'problems-grid',
    'type' => 'striped bordered condensed',
    'summaryText' => '',
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
            'type' => 'html',
            'header' => Yii::t('main-ui', 'Status'),
            'headerHtmlOptions' => array('width' => 50),
        ),
        array(
            'name' => 'assets_names',
            'header' => Yii::t('main-ui', 'Configuration unit'),

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
?>