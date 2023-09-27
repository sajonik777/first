<?php

$app = Yii::app();
$this->breadcrumbs = array(
  Yii::t('main-ui', 'Ticket Priority') => array('index'),
  Yii::t('main-ui', 'Manage priorities'),
);

$this->menu = array(
  $app->user->checkAccess('createPriority') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Create priority'))) : array(NULL),
);
$update = NULL;
$delete = NULL;
if ($app->user->checkAccess('updatePriority')) {
  $update = '{update}';
}
if ($app->user->checkAccess('deletePriority')) {
  $delete = '{delete}';
}
$template = $update . ' ' . $delete;
?>
<div class="page-header">
  <h3><i class="fa-solid fa-clock fa-xl"> </i><?php echo Yii::t('main-ui', 'Ticket Priority'); ?></h3>
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
      <div class="row-fluid">
        <?php $this->widget('bootstrap.widgets.TbGridView', array(
          'type' => 'striped bordered condensed',
          'id' => 'zpriority-grid',
          'selectionChanged' => Yii::app()->user->checkAccess('updatePriority') ? 'function(id){location.href = "' . $this->createUrl('/zpriority/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
          'dataProvider' => $model->search(),
          'htmlOptions' => array('style' => 'cursor: pointer'),
          'pager' => array(
            'class' => 'CustomPager',
            'displayFirstAndLast' => true,
          ),
          'columns' => array(
            array(
              'name' => 'name'),
            array(
              'name' => 'rcost',
              'headerHtmlOptions' => array('width' => 120)),
            array(
              'name' => 'scost',
              'headerHtmlOptions' => array('width' => 120)),
            array(
              'class' => 'bootstrap.widgets.TbButtonColumn',
              'template' => $template,
              'header' => Yii::t('main-ui', 'Actions'),
            ),
          ),
          )); ?>
        </div>
      </div>
    </div>
