<?php

$this->breadcrumbs = array(
  Yii::t('main-ui', 'Streets') => array('index'),
  Yii::t('main-ui', 'Manage streets'),
);

$this->menu = array(
  Yii::app()->user->checkAccess('createStreets') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create new street'))) : array(NULL),
);
$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('updateStreets')) {
  $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteStreets')) {
  $delete = '{delete}';
}

$template = $update . ' ' . $delete;
?>
<div class="page-header">
  <h3><i class="fa-solid fa-computer fa-xl"> </i><?php echo Yii::t('main-ui', 'Streets'); ?></h3>
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
      'id' => 'streets-grid',
      'selectionChanged' => Yii::app()->user->checkAccess('updateStreet') ? 'function(id){location.href = "' . $this->createUrl('/streets/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
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
        'city.name:text:'.Yii::t('main-ui', 'City'),
        // 'name:text:'.Yii::t('main-ui', 'Services'),
        array(
          'class' => 'bootstrap.widgets.TbButtonColumn',
          'template' => $template,
          'header' => Yii::t('main-ui', 'Actions'),
        ),
      ),
      )); ?>
    </div>
  </div>
