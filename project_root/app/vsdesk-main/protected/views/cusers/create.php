<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Users') => array('index'),
    Yii::t('main-ui', 'Create user'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listUser') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List users'))) : array(NULL),
);
?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Create user'); ?></h3>
</div>
<?php echo $this->renderPartial('_form', array('model' => $model, 'lang' => $lang)); ?>
