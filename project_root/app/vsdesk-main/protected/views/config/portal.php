<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Portal settings'),

);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-sliders fa-xl"> </i><?php echo Yii::t('main-ui', 'Portal settings'); ?></h3>
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
                'id' => 'config-form',
                'enableAjaxValidation' => false,
            ));
            ?>

            <?php echo $form->errorSummary($model13); ?>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $form->toggleButtonRow($model13, 'portalPhonebook'); ?>
                    <?php echo $form->toggleButtonRow($model13, 'portalAllowRegister'); ?>
                    <?php echo $form->toggleButtonRow($model13, 'portalAllowRestore'); ?>
                    <?php echo $form->toggleButtonRow($model13, 'portalAllowNews'); ?>
                    <?php echo $form->toggleButtonRow($model13, 'portalAllowKb'); ?>
                    <?php echo $form->toggleButtonRow($model13, 'portalAllowService'); ?>
                </div>
                <div class="span6">
                    <?php echo $form->toggleButtonRow($model13, 'portalAllowCaptcha'); ?>
                    <?php echo $form->textAreaRow($model13, 'portalCaptchaWords', ['rows' => 6, 'class' => 'span12']); ?>

                </div>

            </div>
        </div>
    </div>
    <div class="box-footer">
        <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div>
