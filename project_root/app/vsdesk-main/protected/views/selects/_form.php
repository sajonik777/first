<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'fieldsets-form',
            'enableAjaxValidation' => false,
        )); ?>

        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldRow($model, 'select_name', array('class' => 'span6', 'maxlength' => 128)); ?>

        <?php /* echo $form->textFieldRow($model, 'select_value', array('class' => 'span12')); */ ?>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
