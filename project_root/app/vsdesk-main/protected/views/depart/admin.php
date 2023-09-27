<?php

/* @var $this DepartController */
/* @var $model Depart */
$total = '';
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('updateDepart')) {
  $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteDepart')) {
  $delete = '{delete}';
}
$template = $update . ' ' . $delete;
$this->breadcrumbs = array(
  Yii::t('main-ui', 'Departments') => array('index'),
  Yii::t('main-ui', 'Manage departments'),
);

$this->menu = array(
  Yii::app()->user->checkAccess('createDepart') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create department'))) : array(NULL),
);
?>
<div class="page-header">
  <h3><i class="fa-solid fa-users fa-xl"> </i><?php echo Yii::t('main-ui', 'Departments'); ?></h3>
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
      'id' => 'depart-grid',
      'type' => 'striped bordered condensed',
      'selectionChanged' => Yii::app()->user->checkAccess('updateDepart') ? 'function(id){location.href = "' . $this->createUrl('/depart/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
      'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['deptPageCount'] ? Yii::app()->session['deptPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
      'dataProvider' => $model->search(),
      'htmlOptions' => array('style' => 'cursor: pointer'),
      'filter' => $model,
      'pager' => array(
        'class' => 'CustomPager',
        'displayFirstAndLast' => true,
      ),
      'columns' => array(
        'name',
        'company',
        'manager',
        array(
          'class' => 'bootstrap.widgets.TbButtonColumn',
          'template' => $template,
          'header' => Yii::t('main-ui', 'Actions'),
        ),
      ),
      )); ?>
    </div>
  </div>
