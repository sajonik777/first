<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Service level') => array('index'),
    Yii::t('main-ui', 'Create service level'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listSla') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List service levels'))): array(NULL),

);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create service level'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>