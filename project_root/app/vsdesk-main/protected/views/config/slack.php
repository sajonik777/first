<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Slack integration'),

);
?>

<div class="page-header">
    <h3><i class="fa-brands fa-slack fa-xl"></i><?php echo Yii::t('main-ui', 'Slack integration'); ?></h3>
</div>
<div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'slack-form',
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

            <?php echo $form->errorSummary($model11); ?>
            <div class="row-fluid">
            <p>
                <strong> <?php echo Yii::t('main-ui', 'You must provide correct Slack Incoming Webhook URL.'); ?> </strong>
            </p>
                        <?php echo $form->toggleButtonRow($model11, 'SlackEnabled'); ?>

                        <?php echo $form->labelEx($model11, 'SlackUsername'); ?>
                        <?php echo $form->textField($model11, 'SlackUsername', array('class' => 'span12')); ?>
                        <?php echo $form->error($model11, 'SlackUsername'); ?>

                        <?php echo $form->labelEx($model11, 'SlackWebhookURL'); ?>
                        <?php echo $form->textField($model11, 'SlackWebhookURL', array('class' => 'span12')); ?>
                        <?php echo $form->error($model11, 'SlackWebhookURL'); ?>

                        <?php
                        echo $form->labelEx($model11, 'SlackIconURL');
                        echo $form->textField($model11, 'SlackIconURL', array('class' => 'span12'));
                        echo $form->error($model11, 'SlackIconURL');
                        ?>

                        <?php
                        echo $form->labelEx($model11, 'SlackEmojii');
                        echo $form->textField($model11, 'SlackEmojii', array('class' => 'span6'));
                        echo $form->error($model11, 'SlackEmojii');
                        ?>
                        <hr>
        <h5><?php echo Yii::t('main-ui', 'You can create your template using HTML tags and following tags:'); ?></h5>
        <p><?php echo Yii::t('main-ui', 'Tags are case sensitive'); ?></p>
        <div class="row-fluid">
            <div class="span6">
                <ul>
                    <li><b>{id}</b> <?php echo Yii::t('main-ui', 'Ticket #'); ?></li>
                    <li><b>{name}</b> <?php echo Yii::t('main-ui', 'Ticket name'); ?></li>
                    <li><b>{status}</b> <?php echo Yii::t('main-ui', 'Ticket status'); ?></li>
                    <li><b>{fullname}</b> <?php echo Yii::t('main-ui', 'Customer fullname'); ?></li>
                    <li><b>{watchers}</b> <?php echo Yii::t('main-ui', 'Observers'); ?></li>
                    <li><b>{manager_name}</b> <?php echo Yii::t('main-ui', 'Manager name'); ?> </li>
                    <li><b>{manager_phone}</b> <?php echo Yii::t('main-ui', 'Manager phone'); ?></li>
                    <li><b>{manager_email}</b>  <?php echo Yii::t('main-ui', 'Manager e-mail'); ?></li>
                    <li><b>{category}</b> <?php echo Yii::t('main-ui', 'Ticket category'); ?></li>
                    <li><b>{priority}</b> <?php echo Yii::t('main-ui', 'Ticket priority'); ?></li>
                    <li><b>{created}</b> <?php echo Yii::t('main-ui', 'Ticket created'); ?></li>
                </ul>
            </div>
            <div class="span6">
                <ul>
                    <li><b>{unit}</b> <?php echo Yii::t('main-ui', 'Configuration Unit'); ?></li>
                    <li><b>{StartTime}</b> <?php echo Yii::t('main-ui', 'Start Time'); ?></li>
                    <li><b>{fStartTime}</b> <?php echo Yii::t('main-ui', 'Fact Start time'); ?></li>
                    <li><b>{EndTime}</b> <?php echo Yii::t('main-ui', 'End Time'); ?></li>
                    <li><b>{fEndTime}</b> <?php echo Yii::t('main-ui', 'Fact End Time'); ?></li>
                    <li><b>{service_name}</b> <?php echo Yii::t('main-ui', 'Service name'); ?></li>
                    <li><b>{room}</b> <?php echo Yii::t('main-ui', 'Room'); ?></li>
                    <li><b>{company}</b> <?php echo Yii::t('main-ui', 'Company'); ?></li>
                    <li><b>{address}</b> <?php echo Yii::t('main-ui', 'Address'); ?></li>
                    <li><b>{content}</b> <?php echo Yii::t('main-ui', 'Content'); ?></li>
                </ul>
            </div>
        </div>
                        <?php
                        echo $form->labelEx($model11, 'SlackTemplate');
                        echo $form->textArea($model11, 'SlackTemplate', array('class' => 'span12', 'cols' => '12', 'rows' => '6'));
                        echo $form->error($model11, 'SlackTemplate');
                        ?>
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