<?php

$this->breadcrumbs = array(
  Yii::t('main-ui', 'CU types') => array('index'),
  Yii::t('main-ui', 'Manage types'),
);

$this->menu = array(
  Yii::app()->user->checkAccess('createUnitType') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create new type'))) : array(NULL),
);
$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('updateUnitType')) {
  $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteUnitType')) {
  $delete = '{delete}';
}

$template = $update . ' ' . $delete;
?>
<div class="page-header">
  <h3><i class="fa-solid fa-computer fa-xl"> </i><?php echo Yii::t('main-ui', 'CU types'); ?></h3>
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
    <?php $this->widget('bootstrap.widgets.TbGridView', array(
      'id' => 'cunit-types-grid',
      'selectionChanged' => Yii::app()->user->checkAccess('updateUnitType') ? 'function(id){location.href = "' . $this->createUrl('/cunitTypes/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
      'type' => 'striped bordered condensed',
      'dataProvider' => $model->search(),
      'htmlOptions' => array('style' => 'cursor: pointer'),
      'pager' => array(
        'class' => 'CustomPager',
        'displayFirstAndLast' => true,
      ),
      'filter' => $model,
      'columns' => array(
        'name',
        array(
          'class' => 'bootstrap.widgets.TbButtonColumn',
          'template' => $template,
          'header' => Yii::t('main-ui', 'Actions'),
        ),
      ),
      )); ?>
    </div>
  </div>
