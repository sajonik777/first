<?php

/* @var $this StatusController */
/* @var $model Status */
/* @var $messages Messages[] */
/* @var $smss Smss[] */
?>

<?php
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Statuses') => array('index'),
    Yii::t('main-ui', 'Create'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listStatus') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Status'))): array(NULL),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model, 'messages' => $messages, 'smss' => $smss)); ?>