<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Lists') => array('index'),
    Yii::t('main-ui', 'Create'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listSelects') ? array(
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => array('index'),
        'itemOptions' => array('title' => Yii::t('main-ui', 'Lists'))
    ) : array(null),
);
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create list'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>