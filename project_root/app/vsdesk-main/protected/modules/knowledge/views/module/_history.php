<?php

$config = array('keyField' => 'id', 'sort' => array('defaultOrder' => 'id DESC'), 'pagination' => array('pageSize' => 10));
// $rawData = $history;
$dataProvider = new CArrayDataProvider($history, $config);
?>
<h4><?php echo Yii::t('main-ui', 'Knowledge history'); ?></h4>
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
            'headerHtmlOptions' => array('width' => 150),
        ),
        array(
            'name' => 'user_name',
            'header' => Yii::t('main-ui', 'Username'),
            'headerHtmlOptions' => array('width' => 200),
        ),
        array(
            'name' => 'action',
            'type' => 'html',
            'header' => Yii::t('main-ui', 'Content'),
        ),
    ),
));
?>
