<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Pipelines')=>array('index'),
	$model->name,
);

$this->menu=array(
        Yii::app()->user->checkAccess('listPipeline') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List Pipeline'))) : array(NULL),
);
?>
<div class="page-header">
<h3><?php echo $model->name;?></h3>
</div>
<div class="box">
    <div class="box-body">
<?php $this->widget('bootstrap.widgets.TbMenu', array(
        'type' =>'pills',
        'items'=> $this->menu,
    )); ?>
<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'id',
		'name',
		'label',
		'send_email',
		'email_template',
		'send_sms',
		'sms_template',
		'create_task',
		'task_deadline',
		'task_description',
		'close_deal',
		'cancel_deal',
),
)); ?>
</div>
    </div>