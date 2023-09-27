<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Fieldsets') => array('index'),
    $model->name => array('index'),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listFieldsets') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Fieldsets'))) : array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>

<?php echo $this->renderPartial('_upform', array('model' => $model, 'model2' => $model2, 'fields' => $fields)); ?>