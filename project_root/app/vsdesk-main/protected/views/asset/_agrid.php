<?php
$total = '';


$criteria = new CDbCriteria();
$criteria->compare('asset_attrib_id', $model->asset_attrib_name);
$criteria->compare('slabel', $model->slabel, true);
$criteria->compare('name', $model->name, true);
$criteria->compare('inventory', $model->inventory, true);
$criteria->compare('cost', $model->cost, true);
$criteria->addCondition('uid IS NULL');


$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'id' => 'asset-grid',
    'ajaxUrl' => Yii::app()->createUrl('/asset/agrid'),
    'dataProvider' => new CActiveDataProvider($model, array('criteria' => $criteria)),
    'htmlOptions' => array('style' => 'cursor: pointer'),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',// Checkboxes
            'selectableRows' => 2,// Allow multiple selections
        ),
        array(
            'name' => 'asset_attrib_name',
            'header' => Yii::t('main-ui', 'Asset type'),
            'filter' => AssetAttrib::all(),
        ),
        array(
            'name' => 'slabel',
            'headerHtmlOptions' => array('width' => 150),
            'type' => 'html',
            'filter' => Astatus::all(),
        ),
        array(
            'name' => 'name',

        ),
        array(
            'name' => 'inventory',
            'header' => Yii::t('main-ui', 'Inventory #'),
        ),

        array(
            'name' => 'cost',
            'headerHtmlOptions' => array('width' => 70),
        ),
    ),
)); ?>