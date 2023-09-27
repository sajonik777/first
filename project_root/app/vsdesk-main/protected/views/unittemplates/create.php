<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Print form templates')=>array('index'),
	Yii::t('main-ui', 'Create'),
);

$this->menu=array(
	Yii::app()->user->checkAccess('listUnitTemplates') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List print templates'))): array(NULL),
);
?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Create print template');?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
	'type' => 'pills',
	'items' => $this->menu,
)); ?>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>