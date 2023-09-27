<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Service level') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listSla') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List service levels'))): array(NULL),

);
?>
    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>


<!-- Yii::app()->user->checkAccess('viewHistoryProblem') ? array('label' => Yii::t('main-ui', 'SLA history'), 'content' => $this->renderPartial('_history', array('history' => $history), true)) : NULL, -->
