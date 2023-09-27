<?php

/* @var $this DepartController */
/* @var $model Depart */

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Departments') => array('index'),
    Yii::t('main-ui', 'Create department'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listDepart') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Departments'))) : array(NULL),
);
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create department'); ?></h3>
    </div>
<?php $this->renderPartial('_form', array('model' => $model)); ?>