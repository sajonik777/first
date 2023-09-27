<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Company fields') => array('index'),
    $model->name => array('index'),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listCompany') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('fields'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Company fields'))) : array(NULL),
);
?>
<div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Edit'); ?></h3>
    </div>
<br>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'fieldsets-form',
            'enableAjaxValidation' => false,
        )); ?>
        <div class="row-fluid">
            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 100)); ?>
        </div>
        <div class="row-fluid">
            <?php echo $form->toggleButtonRow($model, 'req'); ?>
        </div>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>