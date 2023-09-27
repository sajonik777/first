<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'cron-form',
            'enableAjaxValidation' => false,
        )); ?>

        <?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 100)); ?>
    <?php echo $form->textFieldRow($model, 'time', array('class' => 'span12', 'maxlength' => 50)); ?>
    <?php echo $form->textFieldRow($model, 'job', array('class' => 'span12', 'maxlength' => 500)); ?>
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
