<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'astatus-form',
	'enableAjaxValidation'=>false,
)); ?>

<div class="box">
    <div class="box-body">
        <div class="row-fluid">
        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldRow($model,'name',array('class'=>'span12','maxlength'=>50)); ?>

        <?php echo $form->colorpickerRow($model, 'tag', array('class' => 'span12')); ?>
            </div>
    </div>
    <div class="box-footer">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'submit',
                'type'      =>'info',
                'label'     =>$model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
            )); ?>
    </div>
</div>

<?php $this->endWidget(); ?>
