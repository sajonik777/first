<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Companies') => array('index'),
    Yii::t('main-ui', 'Create'),
);
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Create company'); ?></h3>
    </div>

<?php echo $this->renderPartial('_form', array('model' => $model, 'fields' => $fields)); ?>