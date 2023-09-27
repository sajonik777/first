<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Print form templates')=>array('index'),
	$model->name=>array('index'),
	Yii::t('main-ui', 'Edit'),
);

	$this->menu=array(
		Yii::app()->user->checkAccess('listUnitTemplates') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List print templates'))): array(NULL),
	);
	?>
<div class="page-header">
<h3><?php echo $model->name;?></h3>
</div>
<div class="box">
	<div class="box-body">
		<?php $this->widget('bootstrap.widgets.TbMenu', array(
			'type' => 'pills',
			'items' => $this->menu,
		)); ?>
		<?php echo $this->renderPartial('_form', array('model' => $model)); ?>