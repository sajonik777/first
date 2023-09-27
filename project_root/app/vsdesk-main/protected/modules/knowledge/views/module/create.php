<?php

$this->breadcrumbs = array(
	Yii::t('main-ui', 'Knowledgebase')=>array('index'),
	Yii::t('main-ui', 'Create new record'),
);

$this->menu = array(
	array('label'=>Yii::t('main-ui', 'List records'),'icon' =>'list' ,'url'  =>array('index')),
);
?>
<div class="page-header">
<h3><?php echo Yii::t('main-ui', 'Create new record'); ?></h3>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

