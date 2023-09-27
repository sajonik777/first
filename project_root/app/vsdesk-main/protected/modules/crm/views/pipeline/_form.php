<?php
Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');
?>
<div class="box">
    <div class="box-body">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'pipeline-form',
            'enableAjaxValidation' => false,
        )); ?>
        <div class="row-fluid">
            <?php echo $form->errorSummary($model); ?>
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 200)); ?>
                <div class="input-append">
                    <?php echo $form->colorpickerRow(
                        $model, 'tag', array('class' => 'span12')); ?>
                    <span class="add-on"><i class="icon icon-eyedropper"></i></span>
                </div>
            </div>
            <div class="span6">
                <?php echo $form->toggleButtonRow($model, 'close_deal'); ?>
                <?php echo $form->toggleButtonRow($model, 'cancel_deal'); ?>
            </div>
        </div>
        <hr>
        <div class="row-fluid">
        <?php echo $form->toggleButtonRow($model, 'send_email'); ?>
        <?php echo $form->textAreaRow($model, 'email_template', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>
        <?php Yii::app()->clientScript->registerScript('redactor-init1', "
                     $(function () {
                            $('#Pipeline_email_template').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'fullscreen', 'video'],
                                imageResizable: true,
                                imagePosition: true, 
                            });
                        });
                    ");

?>
        </div>
        <hr>
        <div class="row-fluid">
        <?php echo $form->toggleButtonRow($model, 'send_sms'); ?>
        <?php echo $form->textAreaRow($model, 'sms_template', array('rows' => 6, 'cols' => 50, 'class' => 'span12')); ?>
        </div>
        <hr>
        <div class="row-fluid">
        <?php echo $form->toggleButtonRow($model, 'create_task'); ?>
        <br>
         <label><?php echo Yii::t('main-ui', 'Start date'); ?>:</label>
         <div class="dtpicker"><?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'attribute' => 'task_deadline',
            'language' => 'ru',
            'options' => array(
                'dateFormat' => 'dd.mm.yy',
                'changeYear' => true,
            ),
            'htmlOptions' => array(
                'style' => 'height:20px;'
            ),
        )); ?></div>
        <?php echo $form->textAreaRow($model, 'task_description', array('rows' => 6, 'cols' => 50, 'class' => 'span8')); ?>
        <?php Yii::app()->clientScript->registerScript('redactor-init3', "
                     $(function () {
                            $('#Pipeline_task_description').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table','fullscreen', 'video', 'source', 'crm', 'iconic'],
                                imageResizable: true,
                                imagePosition: true, 
                            });
                        });
                    ");
        ?>
        </div>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'info',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>