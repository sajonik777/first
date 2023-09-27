<?php

$this->breadcrumbs = array(
  Yii::t('main-ui', 'Lists') => array('index'),
  Yii::t('main-ui', 'Manage'),
);
$this->menu = array(
  Yii::app()->user->checkAccess('createSelects') ? array(
    'icon' => 'fa-solid fa-circle-plus fa-xl',
    'url' => array('create'),
    'itemOptions' => array('title' => Yii::t('main-ui', 'Create list'))
  ) : array(null),
);
$update = null;
$delete = null;
$total = null;
if (Yii::app()->user->checkAccess('updateFieldsets')) {
  $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteFieldsets')) {
  $delete = '{delete}';
}
$template = $update . ' ' . $delete;
?>
<div class="page-header">
  <h3><i class="fa-solid fa-square-caret-down fa-xl"> </i><?php echo Yii::t('main-ui', 'Lists'); ?></h3>
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
    <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
      'type' => 'striped bordered condensed',
      'id' => 'selects-grid',
      'dataProvider' => $model->search(),
      'filter' => $model,
      'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('',
        Yii::app()->session['fieldsetsPageCount'] ? Yii::app()->session['fieldsetsPageCount'] : 30,
        Yii::app()->params['selectPageCount'],
        array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii',
        'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
        'selectionChanged' => Yii::app()->user->checkAccess('updateFieldsets') ? 'function(id){location.href = "' . $this->createUrl('/selects/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : null,
        'htmlOptions' => array('style' => 'cursor: pointer'),
        'pager' => array(
          'class' => 'CustomPager',
          'displayFirstAndLast' => true,
        ),
        'columns' => array(
          'select_name',
          array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => Yii::t('main-ui', 'Actions'),
            'template' => $template,
          ),
        ),
        )); ?>
      </div>
    </div>