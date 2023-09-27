<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Tickets') => array('index'),
    $model->Name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit record'),
);
$updater = CUsers::model()->findByAttributes(array('Username'=>$model->update_by));
?>
<div class="page-header">
    <h3><?php echo $model->Name; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <h4><?php echo Yii::t('main-ui', 'Данная заявка редактируется исполнителем ') . $updater->fullname; ?></h4>
    </div>
</div>
