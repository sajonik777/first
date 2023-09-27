<?php

/* @var $this SlaController */
/* @var $model Sla */
/* @var $form TbActiveForm */
?>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'sla-form',
            'enableAjaxValidation' => false,
        )); ?>
        <?php echo Yii::t('main-ui', 'Fields marked with <span class="required">*</span> are required'); ?>
        <?php echo $form->errorSummary($model); ?>
        
        <?php echo $form->textFieldRow($model, 'name', array('class' => 'span4', 'maxlength' => 50)); ?>
        <?php echo $form->dropDownListRow($model, 'sla_type', ['sla'=>Yii::t('main-ui', 'SLA'), 'ola'=>Yii::t('main-ui', 'OLA')], array('class' => 'span4', 'maxlength' => 50)); ?>
        <?php echo $form->labelEx($model, 'retimeh'); ?>
        <?php echo $form->textField($model, 'retimeh', array('class' => 'span1', 'size' => 2)); ?> :
        <?php echo $form->textField($model, 'retimem', array('class' => 'span1', 'size' => 2)); ?>
        <?php echo $form->labelEx($model, 'sltimeh'); ?>
        <?php echo $form->textField($model, 'sltimeh', array('class' => 'span1', 'size' => 2)); ?> :
        <?php echo $form->textField($model, 'sltimem', array('class' => 'span1', 'size' => 2)); ?>

        <?php echo $form->labelEx($model, 'ntretimeh'); ?>
        <?php echo $form->textField($model, 'ntretimeh', array('class' => 'span1', 'size' => 2)); ?> :
        <?php echo $form->textField($model, 'ntretimem', array('class' => 'span1', 'size' => 2)); ?>
        <?php echo $form->labelEx($model, 'ntsltimeh'); ?>
        <?php echo $form->textField($model, 'ntsltimeh', array('class' => 'span1', 'size' => 2)); ?> :
        <?php echo $form->textField($model, 'ntsltimem', array('class' => 'span1', 'size' => 2)); ?>



        <label><?php echo Yii::t('main-ui', 'Workhours'); ?>:</label>
        <?php $this->widget(
            'bootstrap.widgets.TbTimePicker',
            array(
                'model' => $model,
                'attribute' => 'wstime',
                'options' => array(
                    'showMeridian' => false,
                ),
                'htmlOptions' => array('class' => 'input-small',))); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbTimePicker',
            array(
                'model' => $model,
                'attribute' => 'wetime',
                'options' => array(
                    'showMeridian' => false,
                ),
                'htmlOptions' => array('class' => 'input-small',))); ?>
            <?php echo $form->textFieldRow($model, 'taxes', array('class' => 'span12', 'maxlength' => 500)); ?>
        <br/>
        <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'button',
                'type'=>'primary',
                'label'=> Yii::t('main-ui', 'Load holidays'),
                'loadingText'=>Yii::t('main-ui', 'Loading...'),
                'htmlOptions'=>array('id'=>'buttonStateful'),

            ));
            ?>
        <div class="row-fluid" style="margin-top: 10px;">
            <?php echo $form->toggleButtonRow($model, 'round_days'); ?>
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
<script>
    $('#buttonStateful').click(function() {
        var btn = $(this);
        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
        btn.button('loading'); // call the loading function
        setTimeout(function() {
            $.ajax({
                type: "GET",
                url: "/sla/loadxml",
                data: {"YII_CSRF_TOKEN": csrf},
                dataType: "text",
                update: "#Sla_taxes",
                cache: false,
                error: function (e) {
                    console.log(e);
                },
                success: function (data) {
                    //console.log(data);
                    if(data == "false"){
                        swal({
                            title: "Произошла ошибка загрузки календаря",
                            type: "error",
                        });
                    } else {
                        $("#Sla_taxes").val(data);
                    }
                    btn.button('reset'); // call the reset function
                }
            });
        }, 3000);

    });
</script>