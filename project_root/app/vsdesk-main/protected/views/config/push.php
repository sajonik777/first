<?php

$this->breadcrumbs = [
    Yii::t('main-ui', 'Push notification'),
];
?>

<div class="page-header">
    <h3><i class="fa-regular fa-comment-dots fa-xl"> </i><?= Yii::t('main-ui', 'Push notification'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <div class="form">
            <?php
            /** @var TbActiveForm $form */
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'push-form',
                'enableAjaxValidation' => false,
            ));
            ?>
            <div class="row-fluid">
                <div class="span12">
                    <?php echo CHtml::label('API KEY', 'api_key'); ?>
                    <?php echo CHtml::textField('api_key', $api_key, array('class'=>'span12')); ?>

                    <?php echo CHtml::label('Google API config script', 'script_config'); ?>
                    <?php echo CHtml::textArea('script_config', $script_config, array('class'=>'span12', 'rows' => '9')); ?>
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
