<?php

$this->breadcrumbs = array(
  Yii::t('main-ui', 'CU types') => array('index'),
  Yii::t('main-ui', 'Manage types'),
);

$this->menu = array(
  Yii::app()->user->checkAccess('createCities') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create new type'))) : array(NULL),
);
$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('updateCities')) {
  $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteCities')) {
  $delete = '{delete}';
}

$template = $update . ' ' . $delete;
?>
<div class="page-header">
  <h3><i class="fa-solid fa-computer fa-xl"> </i><?php echo Yii::t('main-ui', 'Cities'); ?></h3>
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
      'id' => 'cities-grid',
      'selectionChanged' => Yii::app()->user->checkAccess('updateCity') ? 'function(id){location.href = "' . $this->createUrl('/cities/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
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
