<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Lists') => array('index'),
    $model->id => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
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
        <h3><?php echo $model->select_name; ?></h3>
    </div>

<?php echo $this->renderPartial('_upform', array('model' => $model)); ?>