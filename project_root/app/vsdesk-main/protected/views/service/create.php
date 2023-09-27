<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Service catalog') => array('index'),
    Yii::t('main-ui', 'Create service')
);

$this->menu = array(
    Yii::app()->user->checkAccess('listService') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List services'))): array(NULL),

);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create service'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model, 'escalateNew' => new Escalates())); ?>