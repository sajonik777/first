<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Log analyzer') => array('index'),
    $model->id,
);

?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Log analyzer') . ' #' . $model->id; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
            'data' => $model,
            'type' => 'striped bordered condensed',
            'attributes' => array(
                array(
                    'name' => 'level',
                    'type' => 'html',
                ),
                'category',
                'logtime',
                'message',
            ),
        )); ?>
    </div>
</div>
