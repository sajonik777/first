<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Attachments'),

);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-paperclip fa-xl"> </i><?php echo Yii::t('main-ui', 'Attachments'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'attach-form',
                'enableAjaxValidation' => false,
            ));
            ?>

            <p>
                <strong> <?php echo Yii::t('main-ui', 'Select the allowed file types for attachments in application.'); ?> </strong>
            </p>
            <?php echo $form->errorSummary($model8); ?>
            <div class="row-fluid">
                        <?php echo $form->labelEx($model8, 'extensions'); ?>
                        <?php echo $form->textField($model8, 'extensions', array('class' => 'span12')); ?>
                        <?php echo $form->error($model8, 'extensions'); ?>

                        <?php echo $form->labelEx($model8, 'duplicate_message'); ?>
                        <?php echo $form->textField($model8, 'duplicate_message', array('class' => 'span12')); ?>
                        <?php echo $form->error($model8, 'duplicate_message'); ?>

                        <?php
                        echo $form->labelEx($model8, 'denied_message');
                        echo $form->textField($model8, 'denied_message', array('class' => 'span12'));
                        echo $form->error($model8, 'denied_message');
                        ?>

                        <?php
                        echo $form->labelEx($model8, 'max_file_size');
                        echo $form->textField($model8, 'max_file_size', array('class' => 'span12'));
                        echo $form->error($model8, 'max_file_size');
                        ?>

                        <?php
                        echo $form->labelEx($model8, 'max_file_msg');
                        echo $form->textField($model8, 'max_file_msg', array('class' => 'span12'));
                        echo $form->error($model8, 'max_file_msg');
                        ?>
            </div>
        </div>
    </div>
            <div class="row-fluid">
                <div class="box-footer">
                    <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>
</div>