<?php

$config2 = array('keyField' => 'id', 'pagination' => false);
$rawData2 = $units;
$dataProvider2 = new CArrayDataProvider($rawData2, $config2);
if ($units) {
    if ($units[0] != null) {
        echo '<h4>' . Yii::t('main-ui', 'Configuration units') . '</h4>';
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
                    'name' => 'inventory',
                    'header' => Yii::t('main-ui', 'Inventory number'),
                    'headerHtmlOptions' => array('width' => 100),
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
                            'url' => 'Yii::app()->createUrl("cunits/view", array("id"=>$data->id))',
                        ),
                    ),
                ),
            )));
    } else {
        echo '<h4>Связанных КЕ нет.</h4>';
    }
} else {
    echo '<h4>Связанных КЕ нет.</h4>';
}; ?>