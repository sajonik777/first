<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Telegram bot integration'),

);
?>

<div class="page-header">
    <h3><i class="fa-brands fa-telegram fa-xl"> </i><?php echo Yii::t('main-ui', 'Telegram bot integration'); ?></h3>
</div>
<div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'Getmail-form',
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
       

            <?php echo $form->errorSummary($model10); ?>
            <div class="row-fluid">
            <p>
                <strong> <?php echo Yii::t('main-ui', 'You must provide correct @BotFather toket to use Telegram API.'); ?> </strong>
            </p>
                        <?php echo $form->toggleButtonRow($model10, 'TBotEnabled'); ?>

                        <?php echo $form->labelEx($model10, 'TBotToken'); ?>
                        <?php echo $form->textField($model10, 'TBotToken', array('class' => 'span12')); ?>
                        <?php echo $form->error($model10, 'TBotToken'); ?>

                        <?php echo $form->labelEx($model10, 'TBotURL'); ?>
                        <?php echo $form->textField($model10, 'TBotURL', array('class' => 'span12','value'=> Yii::app()->params['homeUrl'].'/tbot.php')); ?>
                        <?php echo $form->error($model10, 'TBotURL'); ?>

                        <?php
                        echo $form->labelEx($model10, 'TBotCertificate');
                        echo $form->textField($model10, 'TBotCertificate', array('class' => 'span12'));
                        echo $form->error($model10, 'TBotCertificate');
                        ?>

                        <?php
                        echo $form->labelEx($model10, 'TBotMsg');
                        echo $form->textArea($model10, 'TBotMsg', array('class' => 'span6'));
                        echo $form->error($model10, 'TBotMsg');
                        ?>
            </div>
            
    </div>
            <div class="row-fluid">
                <div id="rezult_test">

                </div>
                <div class="box-footer">
                    <?php
                    echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Set Webhook'),
                        CHtml::normalizeUrl(array("config/tbottest")),
                        array(
                            'success' => 'function(data){$("#rezult_test").html(data);}'
                        ),
                        array('class' => 'btn btn-warning'));
                    ?>
                    <?php
                    echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Remove Webhook'),
                        CHtml::normalizeUrl(array("config/tbotremove")),
                        array(
                            'success' => 'function(data){$("#rezult_test").html(data);}'
                        ),
                        array('class' => 'btn btn-danger'));
                    ?>
                    <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
                    
                </div>
            </div>

            <?php $this->endWidget(); ?>
    
</div>
</div>