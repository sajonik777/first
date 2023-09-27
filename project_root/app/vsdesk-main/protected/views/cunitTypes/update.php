<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'CU types') => array('index'),
    Yii::t('main-ui', 'Edit type'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listUnitType') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'CU types'))) : array(NULL),
);
?>

    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>