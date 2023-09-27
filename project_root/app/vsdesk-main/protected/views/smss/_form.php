<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'messages-form',
    'enableAjaxValidation' => false,
)); ?>
    <?php echo $form->errorSummary($model); ?>
<div class="row-fluid">
    <?php echo $form->textFieldRow($model, 'name', array('maxlength' => 50, 'class' => 'span12')); ?>
    <?php echo $form->textArea($model, 'content', array('rows' => 6, 'cols' => 50, 'maxlength' => 1400, 'class' => 'span12')); ?>
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