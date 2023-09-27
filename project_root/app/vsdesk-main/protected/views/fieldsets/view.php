<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Fieldsets') => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => Yii::t('main-ui', 'List Fieldsets'), 'icon' => 'list', 'url' => array('index')),
);
?>

<div class="page-header">
    <h3><?php echo $model->name; ?></h3>
</div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => $this->menu,
)); ?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'name',
    ),
)); ?>
