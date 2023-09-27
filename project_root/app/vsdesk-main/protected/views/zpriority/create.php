<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Incident priority') => array('index'),
    Yii::t('main-ui', 'Create priority'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listPriority') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List priorities'))): array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create priority'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>