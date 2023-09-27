<?php

$this->breadcrumbs = array(
    'Управление группами' => array('index'),
    $model->name => array('index'),
    'Редактировать',
);
$this->menu = array(
    Yii::app()->user->checkAccess('listGroup') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Groups'))) : array(NULL),
);
?>

    <div class="page-header">
        <h3>Редактировать группу <?php echo $model->name; ?></h3>
    </div>

<?php echo $this->renderPartial('_upform', array('model' => $model, 'users' => $users)); ?>