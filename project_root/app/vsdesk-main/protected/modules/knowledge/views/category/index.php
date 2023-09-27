<?php

$update = NULL;
$delete = NULL;
if(Yii::app()->user->checkAccess('updateKBCat')){
  $update = '{update}';  
}
if(Yii::app()->user->checkAccess('deleteKBCat')){
  $delete = '{delete}';  
}
$template = $update.' '.$delete; 
$this->breadcrumbs = array(
	Yii::t('main-ui', 'Knowledgebase cats')=>array('index'),
	Yii::t('main-ui', 'Manage cats')       ,
);
$this->menu = array(
  Yii::app()->user->checkAccess('createKBCat') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create new category'))) : array(NULL),
);

?>
<div class="page-header">
  <h3><?php echo Yii::t('main-ui', 'Knowledgebase cats'); ?></h3>
</div>
<div class="box">
  <div class="box-body">
    <?php $this->widget('bootstrap.widgets.TbMenu', array(
      'type' =>'pills',
      'items'=> $this->menu,
      )); ?>
    <?php $this->widget('bootstrap.widgets.TbAlert', array(
      'block'    =>true,
      'fade'     =>true,
      'closeText'=>'×',
      )); ?>

    <?php
    $this->widget('bootstrap.widgets.TbGridView',array(
      'id'          =>'bcats-grid',
      'type'        => 'striped bordered condensed',
      'htmlOptions' => array('style'=>'cursor: pointer'),
      'selectionChanged'=>Yii::app()->user->checkAccess('updateKBCat')?'function(id){location.href = "'.$this->createUrl('/knowledge/category/update/id').'/"+$.fn.yiiGridView.getSelection(id);}':NULL,
      'dataProvider'=>$model->search(),
      'filter'      =>$model,
      'pager' => array(
        'class' => 'CustomPager',
        'displayFirstAndLast' => true,
      ),
      'columns' => array(
        'name',
        array(
          'class'      =>'bootstrap.widgets.TbButtonColumn',
          'template'   => $template,
          'header'=> Yii::t('main-ui', 'Actions'),
        ),
      ),
    ));
    ?>
  </div>
</div>
