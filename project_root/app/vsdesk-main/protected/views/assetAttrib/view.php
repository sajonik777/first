<?php

$this->breadcrumbs = array(
    'Asset Attribs' => array('index'),
    $model->name,
);

$this->menu = array(
    array('label' => 'List AssetAttrib', 'url' => array('index')),
    array('label' => 'Create AssetAttrib', 'url' => array('create')),
    array('label' => 'Update AssetAttrib', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete AssetAttrib', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage AssetAttrib', 'url' => array('admin')),
);
?>
<div class="page-header">
    <h3>View AssetAttrib #<?php echo $model->id; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
            'data' => $model,
            'type' => 'striped bordered condensed',
            'attributes' => array(
                'id',
                'name',
                'asset_id',
                'type',
            ),
        )); ?>
    </div>
</div>
