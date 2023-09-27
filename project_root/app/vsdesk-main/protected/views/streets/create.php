<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Streets') => array('index'),
    Yii::t('main-ui', 'Create new street'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listStreets') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Streets'))) : array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Crete street'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>