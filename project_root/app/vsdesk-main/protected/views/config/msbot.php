<?php

/**
 * @var MSBotForm $model
 * @var TbActiveForm $form
 */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Microsoft Bot Framework integration'),
];
?>

<div class="page-header">
    <h3><i class="fa-solid fa-face-smile fa-xl"> </i><?php echo Yii::t('main-ui',
            'Microsoft Bot Framework integration'); ?></h3>
</div>
<div class="form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => 'MSBotForm-form',
        'enableAjaxValidation' => false,
    ]);
    ?>
    <div class="box">
        <div class="box-body">
            <?php $this->widget('bootstrap.widgets.TbAlert', [
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            ]); ?>

            <?php echo $form->errorSummary($model); ?>
            <div class="row-fluid">
                <?php echo $form->toggleButtonRow($model, 'enabled'); ?>

                <?php echo $form->labelEx($model, 'appId'); ?>
                <?php echo $form->textField($model, 'appId', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'appId'); ?>

                <?php echo $form->labelEx($model, 'appPassword'); ?>
                <?php echo $form->textField($model, 'appPassword', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'appPassword'); ?>
            </div>

        </div>
        <div class="row-fluid">
            <div id="rezult_test">

            </div>
            <div class="box-footer">
                <?php
                echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Test connect'),
                    CHtml::normalizeUrl(['config/msbottest']),
                    [
                        'success' => 'function(data){$("#rezult_test").html(data);}'
                    ],
                    ['class' => 'btn btn-warning']);
                ?>
                <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>
