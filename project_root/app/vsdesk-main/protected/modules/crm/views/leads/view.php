<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Leads')=>array('index'),
	$model->name,
);

$this->menu=array(
        Yii::app()->user->checkAccess('listLeads') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List Leads'))) : array(NULL),
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
		'company_id',
		'company',
		'contact_id',
		'contact',
		'contact_phone',
		'contact_email',
		'contact_position',
		'created',
		'changed',
		'closed',
		'creator',
		'changer',
		'manager_id',
		'manager',
		'status_id',
		'status',
		'cost',
		'tag',
		'description',
),
)); ?>
</div>
    </div>