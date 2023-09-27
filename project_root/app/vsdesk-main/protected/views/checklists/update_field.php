<?php

$this->breadcrumbs = [
    Yii::t('main-ui', 'Checklists') => ['index'],
    $model->name => ['index'],
    Yii::t('main-ui', 'Edit'),
];

$this->menu = [
    Yii::app()->user->checkAccess('listChecklists') ? [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Checklists')]
    ] : [null],
];
?>
<br>
<div class="box">
    <div class="box-body">
        <?php
        $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'id' => 'checklists-form',
            'enableAjaxValidation' => false,
        ]); ?>
        <div class="row-fluid">
            <?php
            echo $form->textFieldRow($model, 'name', ['class' => 'span12', 'maxlength' => 100]); ?>
        </div>
    </div>
    <div class="box-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', [
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        ]); ?>
    </div>
    <?php
    $this->endWidget(); ?>
</div>
