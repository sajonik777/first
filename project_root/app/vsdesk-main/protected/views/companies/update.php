<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Companies') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Edit company') . ' "' . $model->name; ?>"</h3>
    </div>
<?php echo $this->renderPartial('_form', array('model' => $model, 'fields' => $fields, 'update' => 1)); ?>