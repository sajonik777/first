<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Site widget settings'),

);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-code fa-xl"> </i><?php echo Yii::t('main-ui', 'Site widget settings'); ?></h3>
</div>
<div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'wiget-form',
                'enableAjaxValidation' => false,
            ));
            ?>
<div class="box"> 
    <div class="box-body"> 
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>

            <?php echo $form->errorSummary($model12); ?>
            <div class="row-fluid">
            <p>
            </p>
                        <?php echo $form->toggleButtonRow($model12, 'WidgetEnabled'); ?>
                        <?php echo $form->toggleButtonRow($model12, 'WidgetFiles'); ?>
                        <?php echo $form->toggleButtonRow($model12, 'WidgetAnimate'); ?>
                        <?php echo $form->toggleButtonRow($model12, 'WidgetService'); ?>
                        <br>
                        <small style="color: red"><?php echo Yii::t('main-ui', 'If you change this parameter, you must re-obtain and insert on the website the widget code') ;?></small>
                        <?php echo $form->toggleButtonRow($model12, 'WidgetShowPersonal'); ?>
                        <?php echo $form->colorpickerRow($model12, 'WidgetColor', array('class' => 'span6')); ?>
                        <br>
                        <small style="color: red"><?php echo Yii::t('main-ui', 'If you change this parameter, you must re-obtain and insert on the website the widget code') ;?></small>
                        <?php echo $form->dropDownListRow($model12, 'WidgetPosition', array('left_bottom' => Yii::t('main-ui','Bottom left'), 'right_bottom' => Yii::t('main-ui','Bottom right')), array('class' => 'span6')); ?>
                        <br>
                        <small style="color: red"><?php echo Yii::t('main-ui', 'If you change this parameter, you must re-obtain and insert on the website the widget code') ;?></small>
                        <?php
                        echo $form->labelEx($model12, 'WidgetHeader');
                        echo $form->textField($model12, 'WidgetHeader', array('class' => 'span6'));
                        echo $form->error($model12, 'WidgetHeader');
                        ?>
                <br>
                    <?php
                    echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Get widget code'),
                        CHtml::normalizeUrl(array("config/widgetgen")),
                        array(
                            'success' => 'function(data){
                                    $("#widget_code").show();
                                    $("#WidgetForm_WidgetCode").val(data);
                                    $("#WidCode").text(data);
                            }'
                        ),
                        array('class' => 'btn btn-warning'));
                    ?>

                    <div id="widget_code" style="display: none">
                        <hr>
                    <h5><?php echo Yii::t('main-ui', 'Below the widget code, which you must install on every page of your website before the closing tag &lt;/html&gt;:'); ?></h5>
                        <pre><div id="WidCode"></div></pre>
                        <?php
                        echo CHtml::activeHiddenField($model12,'WidgetCode');
                        ?>
                        </div>
                    </div>
            
    </div>
            <div class="row-fluid">
                <div id="rezult_test">

                </div>
                <div class="box-footer">
                    <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
                    
                </div>
            </div>

            <?php $this->endWidget(); ?>
    
</div>
</div>