<?php


Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'templates-form',
    'enableAjaxValidation' => false,
)); ?>
<?php echo $form->errorSummary($model); ?>

<div class="row-fluid">
    <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12')); ?>
    <?php
    echo $form->textAreaRow($model, 'content', array('id' => 'content', 'rows' => 5));
    ?>
    <?php Yii::app()->clientScript->registerScript('redactor-init', "
                     $(function () {
                            $('#content').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'video'],
                            });
                        });
                    ");
    ?>
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