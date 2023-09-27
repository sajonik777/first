<?php
/* @var $this TcategoryController */
/* @var $model Tcategory */

$this->breadcrumbs=array(
	'Tcategories'=>array('index'),
	$model->name,
);

$this->menu = array(
    array('label' => Yii::t('main-ui', 'Create Tcategory'), 'icon' => 'list', 'url' => array('index')),
);

// $this->menu=array(
// 	array('label'=>'List Tcategory', 'url'=>array('index')),
// 	array('label'=>'Create Tcategory', 'url'=>array('create')),
// 	array('label'=>'Update Tcategory', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete Tcategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
// 	array('label'=>'Manage Tcategory', 'url'=>array('admin')),
// );
// ?>

<h1>View Tcategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_id',
		'name',
		'enabled',
	),
)); ?>
