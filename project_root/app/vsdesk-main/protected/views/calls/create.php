<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Calls')=>array('index'),
	Yii::t('main-ui', 'Create'),
);

$this->menu=array(
Yii::app()->user->checkAccess('listCalls') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List Calls'))) : array(NULL),
);
?>

<div class="page-header">
<h3><?php echo Yii::t('main-ui', 'Create Calls');?></h3>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>