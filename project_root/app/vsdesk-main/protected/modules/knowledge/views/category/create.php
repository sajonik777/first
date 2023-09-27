<?php

$this->breadcrumbs = array(
	Yii::t('main-ui', 'Knowledgebase cats')=>array('index'),
	Yii::t('main-ui', 'Create')            ,
);

$this->menu = array(
	Yii::app()->user->checkAccess('listKBCat') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Cats list'))): array(NULL),
);
?>
<div class="page-header">
<h3><?php echo Yii::t('main-ui', 'Create new category'); ?></h3>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>