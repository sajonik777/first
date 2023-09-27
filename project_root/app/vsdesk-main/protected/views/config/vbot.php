<?php

/**
 * @var VBotForm $model
 * @var TbActiveForm $form
 */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Viber bot integration'),
];
?>

<div class="page-header">
    <h3><i class="fa-brands fa-viber fa-xl"> </i><?php echo Yii::t('main-ui', 'Viber bot integration'); ?></h3>
</div>
<div class="form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => 'VBotForm-form',
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

                <?php echo $form->labelEx($model, 'token'); ?>
                <?php echo $form->textField($model, 'token', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'token'); ?>

                <?php echo $form->labelEx($model, 'webhookUrl'); ?>
                <?php echo $form->textField($model, 'webhookUrl', ['class' => 'span12','value'=> Yii::app()->params['homeUrl'].'/vbot.php']); ?>
                <?php echo $form->error($model, 'webhookUrl'); ?>

<!--                --><?php
//                echo $form->labelEx($model10, 'TBotCertificate');
//                echo $form->textField($model10, 'TBotCertificate', array('class' => 'span12'));
//                echo $form->error($model10, 'TBotCertificate');
//                ?>
<!---->
                <?php
                echo $form->labelEx($model, 'msg');
                echo $form->textArea($model, 'msg', ['class' => 'span6']);
                echo $form->error($model, 'msg');
                ?>
            </div>

        </div>
        <div class="row-fluid">
            <div id="rezult_test">

            </div>
            <div class="box-footer">
                <?php
                echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Set Webhook'),
                    CHtml::normalizeUrl(["config/vbottest"]),
                    [
                        'success' => 'function(data){$("#rezult_test").html(data);}'
                    ],
                    ['class' => 'btn btn-warning']);
                ?>
                <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), ['class' => 'btn btn-primary']); ?>

            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div>
</div>