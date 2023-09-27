<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Tickets') => array('index'),
    $model->Name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit record'),
);
?>
    <div class="page-header" xmlns="http://www.w3.org/1999/html">
        <h3>
            <?php echo $model->id . ' "' . $model->Name . '"'; ?>
        </h3>
    </div>
    <div class="box">
    <div class="box-body">
<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); ?>

<?php
if (Yii::app()->user->checkAccess('systemUser') OR ($model->CUsers_id == Yii::app()->user->name AND $model->Managers_id !== Yii::app()->user->name AND !Yii::app()->user->checkAccess('systemAdmin'))) {
    $this->renderPartial('_upform', array('model' => $model, 'subs' => $subs, 'subs2' => $subs2, 'fields' => $fields));
} else {
    $this->renderPartial('_adminupform',
        array('model' => $model, 'subs' => $subs, 'subs2' => $subs2, 'fields' => $fields));
}
?>