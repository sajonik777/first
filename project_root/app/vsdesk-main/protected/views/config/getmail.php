<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Email configurations') => array('config/getmail'),
    Yii::t('main-ui', 'Mail configuration'),

);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-envelope fa-xl"> </i><?php echo Yii::t('main-ui', 'Mail configuration') ." - ". $model2->getmailuser; ?></h3>
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


            <?php echo $form->errorSummary($model2); ?>
            <div class="row-fluid">
                <div class="span3">
                    <h3><?php echo Yii::t('main-ui', 'Settings'); ?></h3>
                    <hr>
                    <?php echo $form->toggleButtonRow($model2, 'getmail_enabled'); ?>
                    <?php echo $form->toggleButtonRow($model2, 'getmaildisableconvert'); ?>
                    <?php echo $form->toggleButtonRow($model2, 'getmaildisablenl2br'); ?>
                    <?php echo $form->toggleButtonRow($model2, 'getmaildisabletrim'); ?>
                    <?php echo $form->toggleButtonRow($model2, 'getmaildisablectrim'); ?>
                    <?php echo $form->toggleButtonRow($model2, 'getmailclosedtonew'); ?>
                    <?php echo $form->toggleButtonRow($model2, 'getmailcopytowatchers'); ?>
                </div>
                <div class="span9">
                    <div class="span6">
                        <h3><?php echo Yii::t('main-ui', 'Outgoing mail'); ?></h3>
                        <hr>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="span6">
                                    <?php echo $form->toggleButtonRow($model2, 'getmailsmtpauth'); ?>
                                    <?php //echo $form->toggleButtonRow($model2, 'getmailsmqueue'); ?>
                                </div>
                                <div class="span6">
                                    <?php echo $form->toggleButtonRow($model2, 'getmailsmdebug'); ?>
                                    <?php echo $form->toggleButtonRow($model2, 'getmailsmignoressl'); ?>

                                </div>
                            </div>
                        </div>
                        <?php echo $form->labelEx($model2, 'getmailsmsec'); ?>
                        <?php echo  $form->dropdownlist($model2, 'getmailsmsec', array(null => 'Без шифрования' ,'ssl' => 'SSL', 'tls' => 'TLS'), array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailsmsec'); ?>

                        <?php echo $form->labelEx($model2, 'getmailsmhost'); ?>
                        <?php echo $form->textField($model2, 'getmailsmhost', array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailsmhost'); ?>

                        <?php echo $form->labelEx($model2, 'getmailsmport'); ?>
                        <?php echo $form->textField($model2, 'getmailsmport', array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailsmport'); ?>

                        <?php echo $form->labelEx($model2, 'getmailsmusername'); ?>
                        <?php echo $form->textField($model2, 'getmailsmusername', array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailsmusername'); ?>

                        <?php echo $form->labelEx($model2, 'getmailsmpassword'); ?>
                        <?php echo $form->passwordField($model2, 'getmailsmpassword', array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailsmpassword'); ?>

                        <?php echo $form->labelEx($model2, 'getmailsmfrom'); ?>
                        <?php echo $form->textField($model2, 'getmailsmfrom', array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailsmfrom'); ?>

                        <?php echo $form->labelEx($model2, 'getmailsmfromname'); ?>
                        <?php echo $form->textField($model2, 'getmailsmfromname', array('class' => 'span12')); ?>

                    </div>
                    <div class="span6">
                        <h3><?php echo Yii::t('main-ui', 'Incoming mail'); ?></h3>
                        <hr>
                        <?php echo $form->toggleButtonRow($model2, 'getmaildelete'); ?>
                        <?php echo $form->toggleButtonRow($model2, 'getmaildisableauth'); ?>
                        <?php echo $form->dropDownListRow($model2, 'getmailservice', Service::model()->sall(), array('class' => 'span12')); ?>
                        <?php echo $form->labelEx($model2, 'getmailserver'); ?>
                        <?php echo $form->textField($model2, 'getmailserver', array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailserver'); ?>

                        <?php echo $form->labelEx($model2, 'getmailport'); ?>
                        <?php echo $form->textField($model2, 'getmailport', array('class' => 'span12', 'placeholder' => '993')); ?>
                        <?php echo $form->error($model2, 'getmailport'); ?>

                        <?php echo $form->dropDownListRow($model2, 'getmailpath', array('/imap' => 'IMAP без шифрования', '/imap/ssl' => 'IMAP SSL', '/imap/notls' => 'IMAP NOTLS', '/imap/tls' =>  'IMAP TLS','/imap/ssl/novalidate-cert' => 'IMAP SSL no validate certiificate', '/pop3' => 'POP3 без шифрования', '/pop3/ssl' => 'POP3 SSL', '/pop3/notls' => 'POP3 NOTLS', '/pop3/tls' => 'POP3 TLS', '/pop3/ssl/novalidate-cert' => 'POP3 SSL no validate certificate'), array('class' => 'span12')); ?>

                        <?php echo $form->labelEx($model2, 'getmailuser'); ?>
                        <?php echo $form->textField($model2, 'getmailuser', array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailuser'); ?>

                        <?php echo $form->labelEx($model2, 'getmailpass'); ?>
                        <?php echo $form->passwordField($model2, 'getmailpass', array('class' => 'span12')); ?>
                        <?php echo $form->error($model2, 'getmailpass'); ?>

                        <?php echo $form->labelEx($model2, 'getmailitems'); ?>
                        <?php echo $form->textField($model2, 'getmailitems', array('class' => 'span12', 'placeholder' => '20')); ?>
                        <?php echo $form->error($model2, 'getmailitems'); ?>
                    </div>

                </div>
            </div>
            </div>

            <div class="row-fluid">
                <div id="rezult_test">

                </div>
                <div class="box-footer">
                    <?php
                    echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Test connect'),
                        CHtml::normalizeUrl(array("config/getmailtest")),
                        array(
                            'success' => 'function(data){$("#rezult_test").html(data);}'
                        ),
                        array('class' => 'btn btn-warning'));
                    ?>
                    <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
                    <br>
                    <?php if (isset($file) and $file != 'getmail.inc') {
                        echo CHtml::linkButton(Yii::t('main-ui', 'Delete'), array('class' => 'btn btn-danger', 'href' => '/config/getmaildelete/?file=' . $file));
                        } ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>
    </div>
</div>
