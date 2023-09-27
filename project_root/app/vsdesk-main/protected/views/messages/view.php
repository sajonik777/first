<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'E-mail templates') => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'List Messages', 'url' => array('index')),
    array('label' => 'Create Messages', 'url' => array('create')),
    array('label' => 'Update Messages', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Messages', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Messages', 'url' => array('admin')),
);
?>
<div class="page-header">
    <h3><?php echo $model->name; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <div>
            <?php echo $model->content; ?>
        </div>
    </div>
</div>
