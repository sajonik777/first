<?php

$this->breadcrumbs=array(
    Yii::t('main-ui', 'News')=>array('index'),
    Yii::t('main-ui', 'Create new record'),
);

$this->menu=array(
    Yii::app()->user->checkAccess('listNews') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'News'))): array(NULL),
);
?>

<div class="page-header">
<h3><?php echo Yii::t('main-ui', 'Create news'); ?></h3>
</div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>