<?php

$this->breadcrumbs = array(
  Yii::t('main-ui', 'Asset types') => array('index'),
  Yii::t('main-ui', 'Manage types'),
);

$this->menu = array(
  Yii::app()->user->checkAccess('createAssetType') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create new type'))) : array(NULL),
);

$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('updateAssetType')) {
  $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteAssetType')) {
  $delete = '{delete}';
}

$template = $update . ' ' . $delete;
?>
<div class="page-header">
  <h3><i class="fa-solid fa-desktop fa-xl"> </i><?php echo Yii::t('main-ui', 'Asset types'); ?></h3>
</div>
<div class="box">
  <div class="box-body">
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
      'id' => 'asset-attrib-grid',
      'selectionChanged' => Yii::app()->user->checkAccess('createAssetType') ? 'function(id){location.href = "' . $this->createUrl('/assetAttrib/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
      'type' => 'striped bordered condensed',
      'dataProvider' => $model->search(),
      'htmlOptions' => array('style' => 'cursor: pointer'),
      'filter' => $model,
      'pager' => array(
        'class' => 'CustomPager',
        'displayFirstAndLast' => true,
      ),
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
