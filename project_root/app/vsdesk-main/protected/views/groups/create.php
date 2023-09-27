<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Manage groups') => array('index'),
    Yii::t('main-ui', 'Create'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listGroup') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Groups'))) : array(NULL),
);
?>
    <div class="page-header">
        <h3>Создать группу</h3>
    </div>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>