<?php
/** @var $this RequestprocessingrulesController */
/** @var $model RequestProcessingRules */
/** @var $form CActiveForm */
?>

<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'id' => 'requestProcessingRules-form',
            'enableAjaxValidation' => false,
        ]); ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <?php echo $form->textFieldRow($model, 'name', ['class' => 'span12', 'maxlength' => 100]); ?>
        </div>
        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'is_all_match'); ?>
            <?php $this->widget('bootstrap.widgets.TbToggleButton',array(
                'model' => $model,
                'attribute'=>'is_all_match',
                'enabledLabel'=>'Все',
                'disabledLabel'=>'Одно',
            )); ?>
        </div>
        <div class="row-fluid">
            <?php echo $form->toggleButtonRow($model, 'is_apply_to_bots'); ?>
        </div>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', [
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        ]); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
