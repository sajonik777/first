<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Calls')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main-ui', 'Edit'),
);

	$this->menu=array(
	Yii::app()->user->checkAccess('listCalls') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List Calls'))) : array(NULL),
	);
	?>
<div class="page-header">
<h3><?php echo $model->name;?></h3>
</div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
        'type' =>'pills',
        'items'=> $this->menu,
    )); ?>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>