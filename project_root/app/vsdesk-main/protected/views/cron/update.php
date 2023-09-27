<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Manage cron jobs') => array('index'),
    $model->name => array('index'),
    Yii::t('main-ui', 'Edit'),
);
$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'htmlOptions'=>array('title'=> Yii::t('main-ui', 'Cron'))),
);
?>

    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>