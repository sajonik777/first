<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Tickets') => array('index'),
    Yii::t('main-ui', 'Create ticket'),
);

$this->menu = array(
    array('label' => Yii::t('main-ui', 'List tickets'), 'icon' => 'list', 'url' => array('index')),
);
?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Create ticket'); ?></h3>
</div>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); ?>
<?php
if (!Yii::app()->user->checkAccess('systemUser')) {
    if(Yii::app()->user->checkAccess('canSelectDeadline')){
        if(Yii::app()->user->checkAccess('cantSelectCustomer')){
            $this->renderPartial('_form2', array('model' => $model, 'fields' => $fields, 'copy' => $copy, 'merged_items' => $merged_items));
        } else {
            $this->renderPartial('_adminform2', array('model' => $model, 'fields' => $fields, 'copy' => $copy, 'merged_items' => $merged_items));
        }
    } else {
        if(Yii::app()->user->checkAccess('cantSelectCustomer')){
            $this->renderPartial('_form', array('model' => $model, 'fields' => $fields, 'copy' => $copy, 'merged_items' => $merged_items));
        } else {
            $this->renderPartial('_adminform', array('model' => $model, 'fields' => $fields, 'copy' => $copy, 'merged_items' => $merged_items));
        }
    }
} else {
    if (Yii::app()->user->checkAccess('liteformRequest')) {
        $this->renderPartial('_liteform', array('model' => $model));
    } else {
        if(Yii::app()->user->checkAccess('canSelectDeadline')){
            $this->renderPartial('_form2', array('model' => $model, 'fields' => $fields, 'copy' => $copy, 'merged_items' => $merged_items));
        } else {
            $this->renderPartial('_form', array('model' => $model, 'fields' => $fields, 'copy' => $copy, 'merged_items' => $merged_items));
        }
    }

}
?>

