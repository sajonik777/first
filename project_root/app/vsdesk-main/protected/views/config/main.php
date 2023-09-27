<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Main settings'),

);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-hammer fa-xl"> </i><?php echo Yii::t('main-ui', 'Main settings'); ?></h3>
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
                    'id' => 'config-form',
                    'enableAjaxValidation' => false,
                ));
                ?>

                <?php echo $form->errorSummary($model); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <h3><?php echo Yii::t('main-ui', 'Settings'); ?></h3>
                        <hr>
                        <?php echo $form->toggleButtonRow($model, 'use_rapid_msg'); ?>
                        <?php echo $form->toggleButtonRow($model, 'allow_register'); ?>
                        <?php echo $form->toggleButtonRow($model, 'allowportal'); ?>
                        <?php echo $form->toggleButtonRow($model, 'allow_select_company'); ?>
                        <?php //echo $form->toggleButtonRow($model, 'useiframe'); ?>

                        <?php echo $form->labelEx($model, 'homeUrl'); ?>
                        <?php echo $form->textField($model, 'homeUrl', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'homeUrl'); ?>

                        <?php echo $form->labelEx($model, 'redirectUrl'); ?>
                        <?php echo $form->textField($model, 'redirectUrl', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'redirectUrl'); ?>

                        <?php echo $form->labelEx($model, 'pageHeader'); ?>
                        <?php echo $form->textField($model, 'pageHeader', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'pageHeader'); ?>

                        <?php echo $form->labelEx($model, 'adminEmail'); ?>
                        <?php echo $form->textField($model, 'adminEmail', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'adminEmail'); ?>

                        <?php echo $form->labelEx($model, 'languages'); ?>
                        <?php echo $form->dropdownlist($model, 'languages', array_merge($list, array('en' => 'English')), array('class' => 'span12')); ?>

                        <?php echo $form->labelEx($model, 'timezone'); ?>
                        <?php echo $form->dropdownlist($model, 'timezone', array('Europe/Moscow' => 'UTC+3/Волгоград, Москва, Санкт-Петербург', 'Europe/Kaliningrad' => 'UTC+2/Калининград', 'Europe/Kiev' => 'UTC+2/Киев, Вильнюс, Рига, Таллин', 'Europe/Minsk' => 'UTC+3/Минск', 'Europe/Samara' => 'UTC+4/Самара', 'Asia/Ashgabat' => 'UTC+5/Ашхабад, Ташкент', 'Asia/Yekaterinburg' => 'UTC+5/Екатеринбург', 'Asia/Omsk' => 'UTC+6/Омск', 'Asia/Krasnoyarsk' => 'UTC+7/Красноярск', 'Asia/Irkutsk' => 'UTC+8/Иркутск', 'Asia/Yakutsk' => 'UTC+9/Якутск', 'Asia/Vladivostok' => 'UTC+10/Владивосток', 'Pacific/Norfolk' => 'UTC+11/Магадан', 'Asia/Anadyr' => 'UTC+12/Анадырь, Петропавловск-Камчатский'), array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'timezone'); ?>

                    </div>
                    <div class="span6">
                        <h3><?php echo Yii::t('main-ui', 'Default SMTP server'); ?></h3>
                        <hr>
                        <?php echo $form->toggleButtonRow($model, 'smtpauth'); ?>
                        <?php echo $form->toggleButtonRow($model, 'smdebug'); ?>
                        <?php echo $form->toggleButtonRow($model, 'smignoressl'); ?>
                        <?php echo $form->toggleButtonRow($model, 'smqueue'); ?>
                        <?php echo $form->labelEx($model, 'smsec'); ?>
                        <?php echo  $form->dropdownlist($model, 'smsec', array(null => 'Без шифрования' ,'ssl' => 'SSL', 'tls' => 'TLS'), array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'smsec'); ?>

                        <?php echo $form->labelEx($model, 'smhost'); ?>
                        <?php echo $form->textField($model, 'smhost', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'smhost'); ?>

                        <?php echo $form->labelEx($model, 'smport'); ?>
                        <?php echo $form->textField($model, 'smport', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'smport'); ?>

                        <?php echo $form->labelEx($model, 'smusername'); ?>
                        <?php echo $form->textField($model, 'smusername', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'smusername'); ?>

                        <?php echo $form->labelEx($model, 'smpassword'); ?>
                        <?php echo $form->passwordField($model, 'smpassword', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'smpassword'); ?>

                        <?php echo $form->labelEx($model, 'smfrom'); ?>
                        <?php echo $form->textField($model, 'smfrom', array('class' => 'span12')); ?>
                        <?php echo $form->error($model, 'smfrom'); ?>

                        <?php echo $form->labelEx($model, 'smfromname'); ?>
                        <?php echo $form->textField($model, 'smfromname', array('class' => 'span12')); ?>

                    </div>

                </div>
            </div>
        </div>
        <div class="box-footer">
            <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div>
