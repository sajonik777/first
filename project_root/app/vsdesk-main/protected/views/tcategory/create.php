<?php
/* @var $this TcategoryController */
/* @var $model Tcategory */

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Tcategories')=>array('index'),
	Yii::t('main-ui', 'Create'),
);

$this->menu=array(
	array('label'=>Yii::t('main-ui', 'List Tcategory'), 'url'=>array('index')),
	array('label'=>Yii::t('main-ui', 'Manage Tcategory'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main-ui', 'Create Tcategory') ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>