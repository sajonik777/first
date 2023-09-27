<?php

$config2 = array('keyField' => 'id', 'pagination' => false);
$rawData2 = $unit;
$dataProvider2 = new CArrayDataProvider($rawData2, $config2);
echo '<h4>' . Yii::t('main-ui', 'Assigned units') . '</h4>';
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type' => 'striped bordered',
    'template' => "{items}",
    'dataProvider' => $dataProvider2,
    'columns' => array(
        array(
            'name' => 'name',
            'header' => Yii::t('main-ui', 'Name'),
            'headerHtmlOptions' => array('width' => 120),
        ),
        array(
            'name' => 'type',
            'header' => Yii::t('main-ui', 'Type'),
            'headerHtmlOptions' => array('width' => 120),
        ),
        array(
            'name' => 'slabel',
            'type' => 'html',
            'header' => Yii::t('main-ui', 'Status'),
            'headerHtmlOptions' => array('width' => 50),
        ),

        array(
            'name' => 'fullname',
            'header' => Yii::t('main-ui', 'Username'),
            'headerHtmlOptions' => array('width' => 150),
        ),

        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => Yii::t('main-ui', 'Actions'),
            'template' => Yii::app()->user->checkAccess('viewUnit') ? '{view}' : NULL,
            'buttons' => array
            (
                'view' => array
                (
                    'label' => Yii::t('main-ui', 'View'),
                    'url' => 'Yii::app()->createUrl("cunits/view", array("id"=>$data->id))',
                    'options' => array("target" => "_blank"),
                ),
            ),
        ),
    ))); ?>