<?php

/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Reset';
?>

<div id="mydiv">
    <h5><?php echo Yii::t('main-ui', 'User password reset'); ?></h5>

    <div class="form">
        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'reset-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        )); ?>

        <div class="row-fluid">

            <?php echo $form->passwordField($model, 'password', array('placeholder' => Yii::t('main-ui', 'Here your new password'), 'class'=>'span12')); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>

        <div class="row-fluid">

            <?php echo $form->passwordField($model, 'verifyPassword', array('placeholder' => Yii::t('main-ui', 'Repeat your new password'), 'class'=>'span12')); ?>
            <?php echo $form->error($model, 'verifyPassword'); ?>
        </div>

        <div class="row buttons">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => Yii::t('main-ui', 'Reset'),
                'htmlOptions' => array(
                    'class' => 'btn-block'
                )
            )); ?>

        </div>

        <?php $this->endWidget(); ?>
    </div>
    <!-- form -->

</div>




