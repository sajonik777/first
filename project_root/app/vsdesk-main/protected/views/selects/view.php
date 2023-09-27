<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Selects') => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => Yii::t('main-ui', 'List Selects'), 'icon' => 'list', 'url' => array('index')),
);
?>

<div class="page-header">
    <h3><?php echo $model->select_name; ?></h3>
</div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => $this->menu,
)); ?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'select_name',
        'select_value',
    ),
)); ?>
