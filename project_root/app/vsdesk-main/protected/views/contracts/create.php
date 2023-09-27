<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Contracts') => array('index'),
    Yii::t('main-ui', 'Create'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listContracts') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions' => array('title' => Yii::t('main-ui', 'List Contracts'))) : array(NULL),
);
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create contract'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>