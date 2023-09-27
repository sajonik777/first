<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Roles management') => array('index'),
    Yii::t('main-ui', 'Manage'),
);

$this->menu = array(
    array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'htmlOptions' => array('title'=> Yii::t('main-ui', 'Create role'))),
    array('icon' => 'fa-solid fa-rotate fa-xl', 'url' => array('reload'), 'htmlOptions' => array('title'=> Yii::t('main-ui', 'Reload'))),
);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-user-lock fa-xl"> </i><?php echo Yii::t('main-ui', 'Roles management'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'roles-grid',
            'dataProvider' => $model->search(),
            'type' => 'striped bordered condensed',
            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/roles/update') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'columns' => array(
                'name',
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{update} {delete}',
                ),
            ),
        )); ?>
    </div>
</div>
