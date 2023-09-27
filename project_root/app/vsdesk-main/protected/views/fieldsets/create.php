<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Fieldsets') => array('index'),
    Yii::t('main-ui', 'Create'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listFieldsets') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Fieldsets'))) : array(NULL),
);
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create Fieldsets'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>