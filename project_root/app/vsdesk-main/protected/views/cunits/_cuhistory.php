<?php

$config = array('keyField' => 'id', 'sort' => array('defaultOrder' => 'id DESC'), 'pagination' => array('pageSize' => 10));
$rawData = $history;
$dataProvider = new CArrayDataProvider($rawData, $config);
?>
    <h4><?php echo Yii::t('main-ui', 'Unit history'); ?></h4>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'history-grid',
    'type' => 'striped bordered condensed',
    'summaryText' => '',
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'date',
            'header' => Yii::t('main-ui', 'Changed'),
            'headerHtmlOptions' => array('width' => 100),
        ),
        array(
            'name' => 'user',
            'header' => Yii::t('main-ui', 'Username'),
            'headerHtmlOptions' => array('width' => 70),
        ),
        array(
            'name' => 'action',
            'type' => 'html',
            'header' => Yii::t('main-ui', 'Content'),
        ),
    ),
));
?>