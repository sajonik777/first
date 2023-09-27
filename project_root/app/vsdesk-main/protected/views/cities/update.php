<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Cities') => array('index'),
    Yii::t('main-ui', 'Edit city'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listCities') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Cities'))) : array(NULL),
);
?>

    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>