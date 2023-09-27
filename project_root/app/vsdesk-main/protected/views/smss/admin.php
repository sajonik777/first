<?php

$total = NULL;
$this->breadcrumbs = array(
  Yii::t('main-ui', 'SMS templates') => array('index'),
  Yii::t('main-ui', 'Manage'),
);

$this->menu = array(
  Yii::app()->user->checkAccess('createSTemplate') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create'))) : array(NULL),
);
$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('viewSTemplate')) {
  $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateSTemplate')) {
  $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteSTemplate')) {
  $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;
?>
<div class="page-header">
  <h3><i class="fa-solid fa-pen-to-square fa-xl"> </i><?php echo Yii::t('main-ui', 'SMS templates'); ?></h3>
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
      'id' => 'messages-grid',
      'selectionChanged' => Yii::app()->user->checkAccess('updateSTemplate') ? 'function(id){location.href = "' . $this->createUrl('/smss/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
      'type' => 'striped bordered condensed',
      'dataProvider' => $model->search(),
      'htmlOptions' => array('style' => 'cursor: pointer'),
      'pager' => array(
        'class' => 'CustomPager',
        'displayFirstAndLast' => true,
      ),
      'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['smessPageCount'] ? Yii::app()->session['smessPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
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
