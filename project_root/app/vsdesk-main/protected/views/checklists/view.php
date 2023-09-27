<?php
/* @var $this ChecklistsController */
/* @var $model Checklists */

$this->breadcrumbs=array(
	'Checklists'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Checklists', 'url'=>array('index')),
	array('label'=>'Create Checklists', 'url'=>array('create')),
	array('label'=>'Update Checklists', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Checklists', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Checklists', 'url'=>array('admin')),
);
?>

<h1>View Checklists #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
