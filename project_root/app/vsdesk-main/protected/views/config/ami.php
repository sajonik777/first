<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Asterisk integration'),

);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-asterisk fa-xl"> </i><?php echo Yii::t('main-ui', 'Asterisk integration'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'ami-form',
                'enableAjaxValidation' => false,
            ));
            ?>

            <p>
                <strong> <?php echo Yii::t('main-ui', 'You must provide correct values to connect Asterisk Management API.'); ?> </strong>
            </p>
            <?php echo $form->errorSummary($model9); ?>
            <div class="row-fluid">
                        <?php echo $form->toggleButtonRow($model9, 'amiEnabled'); ?>
                        <?php echo $form->toggleButtonRow($model9, 'amiSendPush'); ?>

                        <?php echo $form->labelEx($model9, 'amiHost'); ?>
                        <?php echo $form->textField($model9, 'amiHost', array('class' => 'span6')); ?>
                        <?php echo $form->error($model9, 'amiHost'); ?>

                        <?php echo $form->labelEx($model9, 'amiPort'); ?>
                        <?php echo $form->textField($model9, 'amiPort', array('class' => 'span6')); ?>
                        <?php echo $form->error($model9, 'amiPort'); ?>

                        <?php
                        echo $form->labelEx($model9, 'amiScheme');
                        echo $form->textField($model9, 'amiScheme', array('class' => 'span6'));
                        echo $form->error($model9, 'amiScheme');
                        ?>

                        <?php
                        echo $form->labelEx($model9, 'amiUsername');
                        echo $form->textField($model9, 'amiUsername', array('class' => 'span6'));
                        echo $form->error($model9, 'amiUsername');
                        ?>

                        <?php
                        echo $form->labelEx($model9, 'amiSecret');
                        echo $form->passwordField($model9, 'amiSecret', array('class' => 'span6'));
                        echo $form->error($model9, 'amiSecret');
                        ?>

                        <?php
                        echo $form->labelEx($model9, 'amiConnectTimeout');
                        echo $form->textField($model9, 'amiConnectTimeout', array('class' => 'span6'));
                        echo $form->error($model9, 'amiConnectTimeout');
                        ?>

                        <?php
                        echo $form->labelEx($model9, 'amiReadTimeout');
                        echo $form->textField($model9, 'amiReadTimeout', array('class' => 'span6'));
                        echo $form->error($model9, 'amiReadTimeout');
                        ?>

                        <?php
                        echo $form->labelEx($model9, 'amiContext');
                        echo $form->textField($model9, 'amiContext', array('class' => 'span6'));
                        echo $form->error($model9, 'amiContext');
                        ?>

                        <?php
                        echo $form->labelEx($model9, 'amiChannel');
                        echo $form->textField($model9, 'amiChannel', array('class' => 'span6'));
                        echo $form->error($model9, 'amiChannel');
                        ?>

                        <?php
                        echo $form->labelEx($model9, 'amiRecordPath');
                        echo $form->textField($model9, 'amiRecordPath', ['class' => 'span6']);
                        echo $form->error($model9, 'amiRecordPath');
                        ?>

<!--                        --><?php
//                        echo $form->labelEx($model9, 'amiDBServer');
//                        echo $form->textField($model9, 'amiDBServer', ['class' => 'span6']);
//                        echo $form->error($model9, 'amiDBServer');
//                        ?>
<!---->
<!--                        --><?php
//                        echo $form->labelEx($model9, 'amiDBUser');
//                        echo $form->textField($model9, 'amiDBUser', ['class' => 'span6']);
//                        echo $form->error($model9, 'amiDBUser');
//                        ?>
<!---->
<!--                        --><?php
//                        echo $form->labelEx($model9, 'amiDBPassword');
//                        echo $form->passwordField($model9, 'amiDBPassword', ['class' => 'span6']);
//                        echo $form->error($model9, 'amiDBPassword');
//                        ?>
            </div>
        </div>
    </div>
            <div class="row-fluid">
                <div class="box-footer">
                    <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>
</div>
