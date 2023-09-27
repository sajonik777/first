<div class="box">
    <div class="box-body">
        <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'calls-form',
	'enableAjaxValidation'=>false,
)); ?>

        <?php echo $form->errorSummary($model); ?>

                    <?php echo $form->textFieldRow($model,'rid',array('class'=>'span5')); ?>

                        <?php echo $form->textFieldRow($model,'uniqid',array('class'=>'span5','maxlength'=>50)); ?>

                        <?php echo $form->textFieldRow($model,'duniqid',array('class'=>'span5','maxlength'=>50)); ?>

                        <?php echo $form->textFieldRow($model,'date',array('class'=>'span5')); ?>

                        <?php echo $form->textFieldRow($model,'adate',array('class'=>'span5')); ?>

                        <?php echo $form->textFieldRow($model,'edate',array('class'=>'span5')); ?>

                        <?php echo $form->textFieldRow($model,'dialer',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'dialer_name',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'dr_number',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'dr_company',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'dialed',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'dialed_name',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'dd_number',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'slabel',array('class'=>'span5','maxlength'=>200)); ?>

                        <?php echo $form->textFieldRow($model,'shown',array('class'=>'span5')); ?>

                </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'      =>'info',
			'label'     =>$model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
		)); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>