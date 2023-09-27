<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'roles-form',
            'enableAjaxValidation' => false,
        )); ?>
        <div class="row-fluid">
            <?php echo $form->errorSummary($model); ?>
            <div class="span12">
                <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 50)); ?>
                <?php echo $form->textFieldRow($model, 'value', array('class' => 'span12', 'maxlength' => 50)); ?>
                <?php echo $form->dropDownListRow($model, 'copyfrom', Roles::model()->idall(), array('class' => 'span12')); ?>
            </div>
        </div>
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