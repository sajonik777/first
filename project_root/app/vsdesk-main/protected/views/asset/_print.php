<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'View asset'); ?> <?php echo $model->name; ?></h3>
</div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => $this->menu,
)); ?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type' => 'striped bordered condensed',
    'data' => $model,
    'attributes' => array(
        'name',

    ),
)); ?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'type' => 'striped bordered condensed',
    'data' => $data,
    'attributes' => $data
)); ?>