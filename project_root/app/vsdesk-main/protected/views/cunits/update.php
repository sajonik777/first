<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Configuration units') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Update unit'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listUnit') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List units'))): array(NULL),
);
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Update unit'); ?></h3>
    </div>

<?php echo $this->renderPartial('_addform', array('model' => $model, 'assets' => $assets)); ?>