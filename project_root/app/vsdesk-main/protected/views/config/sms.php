<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'SMS gate'),
);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-comment-sms fa-xl"> </i><?php echo Yii::t('main-ui', 'SMS gate'); ?></h3>
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
                'id' => 'sms-form',
                'enableAjaxValidation' => false,
            ));
            ?>
            <p>
                <strong><?php echo Yii::t('main-ui', 'The system works with SMS notification service SMSC.RU, for account register at http://smsc.ru and enter your registration information. Service availability depends entirely on the owners service! Find out the cost of each SMS possible on site service.'); ?></strong>
            </p>
            <p><strong><?php echo Yii::t('main-ui','Your ballance:');?> <?php echo Yii::app()->sms->get_balance()?Yii::app()->sms->get_balance():'<span style="color:red">ERROR</span>';?> <?php echo Yii::t('main-ui','rub.');?></strong></p>
            <?php echo $form->errorSummary($model4); ?>
            <div class="row-fluid">
                <div class="span6">
                        <?php echo $form->labelEx($model4, 'smsuser'); ?>
                        <?php echo $form->textField($model4, 'smsuser', array('class' => 'span12')); ?>
                        <?php echo $form->error($model4, 'smsuser'); ?>

                        <?php echo $form->labelEx($model4, 'smspassword'); ?>
                        <?php echo $form->passwordField($model4, 'smspassword', array('class' => 'span12')); ?>
                        <?php echo $form->error($model4, 'smspassword'); ?>

                        <?php echo $form->labelEx($model4, 'smsformat'); ?>
                        <?php echo $form->dropdownList($model4, 'smsformat', array('0' => 'SMS', '10' => 'Viber'),array('class' => 'span12')); ?>
                        <?php echo $form->error($model4, 'smsformat'); ?>

                        <?php echo $form->labelEx($model4, 'smssender'); ?>
                        <?php echo $form->textField($model4, 'smssender', array('class' => 'span12')); ?>
                        <?php echo $form->error($model4, 'smssender'); ?>
                </div>
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