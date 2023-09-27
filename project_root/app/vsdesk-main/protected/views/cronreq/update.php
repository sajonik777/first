<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Cron Requests') => array('index'),
    $model->Name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
);
?>
    <div class="page-header">
        <h3><?php echo $model->Name; ?></h3>
    </div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => $this->menu,
)); ?>

<?php echo $this->renderPartial('_upform', array('model' => $model)); ?>