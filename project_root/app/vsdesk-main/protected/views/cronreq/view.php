<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Cron Requests') => array('index'),
    $model->Name,
);

$this->menu = array(
    array('label' => Yii::t('main-ui', 'List requests'), 'icon' => 'list', 'url' => array('index')),
);
?>

<div class="page-header">
    <h3><?php echo $model->Name; ?></h3>
</div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => $this->menu,
)); ?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'service_id',
        'CUsers_id',
        'Status',
        'ZayavCategory_id',
        'Priority',
        'Name',
        'Content',
        'watchers',
        'cunits',
        'Date',
        'repeats',
    ),
)); ?>
