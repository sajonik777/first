<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Service level') => array('index'),
    Yii::t('main-ui', 'Manage service levels'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('createSla') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Create service level'))) : array(NULL),
);
$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('viewSla')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateSla')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteSla')) {
    $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;
?>
<div class="page-header">
    <h3><i class="fa-solid fa-chart-line fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage service levels'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
            )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
            )); ?>
        <?php $this->widget('FilterGridResizable', array(
            'type' => 'striped bordered condensed',
            'id' => 'sla-grid',
            'selectionChanged' => Yii::app()->user->checkAccess('updateSla')
				? 'function(id){location.href = "' . $this->createUrl('/sla/update') . '/"+$.fn.yiiGridView.getSelection(id);}'
				: 'function(id){location.href = "' . $this->createUrl('/sla/') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'dataProvider' => $model->search(),
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'filter' => $model,
            'columns' => array(
                'name',
                'rhours',
                'shours',
                'wstime',
                'wetime',
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'header' => Yii::t('main-ui', 'Actions'),
                    'template' => $template,
                    'headerHtmlOptions' => array('width' => 70),
                ),
            ),
            )); ?>
        </div>
    </div>
