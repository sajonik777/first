<?php

/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Recovery';
?>

<div id="mydiv">
    <h5><?php echo Yii::t('main-ui', 'User password recovery'); ?></h5>

    <div class="form">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'recovery-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        )); ?>

        <div class="row-fluid">

            <?php echo $form->textField($model, 'Username', array('placeholder' => Yii::t('main-ui', 'Here your login'), 'class'=>'span12')); ?>
            <?php echo $form->error($model, 'Username'); ?>
        </div>

        <div class="row-fluid">

            <?php echo $form->textField($model, 'Email', array('placeholder' => Yii::t('main-ui', 'Here your email'), 'class'=>'span12')); ?>
            <?php echo $form->error($model, 'Email'); ?>
        </div>


        <div class="row-fluid">
            <?php if (CCaptcha::checkRequirements() && Yii::app()->user->isGuest): ?>
                <?php $this->widget('CCaptcha', array('clickableImage' => true)) ?>
                <?php echo CHtml::activeTextField($model, 'verifyCode', array('placeholder' => Yii::t('main-ui', 'Verify code'), 'class'=>'span12')); ?>
            <?php endif; ?>
        </div>
        <div class="row buttons">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'default',
                'label' => Yii::t('main-ui', 'Recover'),
                'htmlOptions' => array(
                    'class' => 'btn btn-block'
                )
            )); ?>

        </div>

        <?php $this->endWidget(); ?>
    </div>
    <!-- form -->

</div>




