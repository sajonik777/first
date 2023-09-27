<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Departments') => array('index'),
    $model->name => array('index'),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listDepart') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Departments'))) : array(NULL),

);?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Edit') . ' ' . $model->name; ?></h3>
    </div>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>