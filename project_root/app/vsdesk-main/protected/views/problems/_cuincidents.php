<?php

$config = array('keyField' => 'id', 'pagination' => false);
$rawData = $zayav;
$dataProvider = new CArrayDataProvider($rawData, $config);
if ($zayav[0] != null) {
    echo '<h4>' . Yii::t('main-ui', 'Assigned incidents') . '</h4>';
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
                'name' => 'slabel',
                'type' => 'html',
                'header' => Yii::t('main-ui', 'Status'),
                'headerHtmlOptions' => array('width' => 50),
            ),
            array(
                'name' => 'Priority',
                'header' => Yii::t('main-ui', 'Priority'),
                'headerHtmlOptions' => array('width' => 50),
            ),
            array(
                'name' => 'Name',
                'header' => Yii::t('main-ui', 'Name'),
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => Yii::app()->user->checkAccess('viewRequest') ? '{view}' : NULL,
                'buttons' => array
                (
                    'view' => array
                    (
                        'label' => Yii::t('main-ui', 'View'),
                        'url' => 'Yii::app()->createUrl("request/view", array("id"=>$data->id))',
                    ),
                ),
            ),
        )));
} else {
    echo '<h4>Связанных инцидентов нет.</h4>';
}; ?>