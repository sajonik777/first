<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'LDAP integration'),

);

$this->menu = array(// array('label' => Yii::t('main-ui', 'Импортировать пользователей из AD'), 'icon' => 'icon-group', 'url' => array('adusersimport')),
);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-folder-tree fa-xl"> </i><?php echo Yii::t('main-ui', 'LDAP integration'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
    <div class="row-fluid">
        <div class="span6">
            <?php
            $new = false;
            if ('openldap' === $ldap_model->type) {
                $oldap = 1;
            } elseif ('ad' === $model3->type) {
                $oldap = 0;
            } else {
                $oldap = 1;
                $new = true;
            }
            if($new) {
                $this->widget(
                    'bootstrap.widgets.TbToggleButton',
                    [
                        'name' => 'type',
                        'value' => $oldap,
                        'onChange' => 'function(){$("#ad").toggle();$("#openldap").toggle();}',
                        'enabledLabel' => 'LDAP',
                        'disabledLabel' => 'AD',
                    ]
                );
            }
            ?>
        </div>
    </div>
    </div>

<div class="form" id="ad" style="display: <?= !$oldap ? 'block' : 'none' ?>">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'ad-form',
                'enableAjaxValidation' => false,
            ));
            ?>
    <input type="hidden" name="AdForm[type]" value="ad" >
<div>
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
            <p style="color:red">
                <strong> <?php echo Yii::t('main-ui', 'Be careful, including authorization, incorrect settings can lead to broken authorization.'); ?> </strong>
            </p>
            <?php echo $form->errorSummary($model3); ?>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $form->toggleButtonRow($model3, 'ad_enabled'); ?>

                    <?php echo $form->labelEx($model3, 'basedn'); ?>
                    <?php echo $form->textField($model3, 'basedn', array('class' => 'span12')); ?>
                    <?php echo $form->error($model3, 'basedn'); ?>

                    <?php echo $form->labelEx($model3, 'accountSuffix'); ?>
                    <?php echo $form->textField($model3, 'accountSuffix', array('class' => 'span12')); ?>
                    <?php echo $form->error($model3, 'accountSuffix'); ?>

                    <?php echo $form->toggleButtonRow($model3, 'fastAuth'); ?>
                </div>

                <div class="span6">
                    <?php echo $form->labelEx($model3, 'domaincontrollers'); ?>
                    <?php echo $form->textField($model3, 'domaincontrollers', array('class' => 'span12')); ?>
                    <?php echo $form->error($model3, 'domaincontrollers'); ?>

                    <?php echo $form->labelEx($model3, 'adminusername'); ?>
                    <?php echo $form->textField($model3, 'adminusername', array('class' => 'span12')); ?>
                    <?php echo $form->error($model3, 'adminusername'); ?>

                    <?php echo $form->labelEx($model3, 'adminpassword'); ?>
                    <?php echo $form->passwordField($model3, 'adminpassword', array('class' => 'span12')); ?>
                    <?php echo $form->error($model3, 'adminpassword'); ?>
                </div>
            </div>
        </div>

            <div class="row-fluid">
                <div id="rezult_test">

                </div>
            </div>
            <div class="box-footer">
                <?php
                echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Test connect'),
                    CHtml::normalizeUrl(array("config/adtest")),
                    array(
                        'success' => 'function(data){$("#rezult_test").html(data);}'
                    ),
                    array('class' => 'btn btn-warning'));
                ?>
                <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
                <br>
                <?php if(isset($file) and $file != 'ad.inc') echo CHtml::linkButton(Yii::t('main-ui', 'Delete'), array('class' => 'btn btn-danger', 'href' => '/config/addelete/?file=' . $file)); ?>
            </div>

            <?php $this->endWidget(); ?>
        </div>
</div>

<div class="form" id="openldap" style="display: <?= $oldap ? 'block' : 'none' ?>">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'ldap-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <input type="hidden" name="OpenLDAPForm[type]" value="openldap" >
    <div>
        <div class="box-body">
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'items' => $this->menu,
            )); ?>
            <?php $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            )); ?>
            <p style="color:red">
                <strong> <?php echo Yii::t('main-ui', 'Be careful, including authorization, incorrect settings can lead to broken authorization.'); ?> </strong>
            </p>
            <?php echo $form->errorSummary($ldap_model); ?>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $form->toggleButtonRow($ldap_model, 'ad_enabled'); ?>

                    <?php echo $form->labelEx($ldap_model, 'account'); ?>
                    <?php echo $form->textField($ldap_model, 'account', ['class' => 'span12']); ?>
                    <?php echo $form->error($ldap_model, 'account'); ?>

                    <?php echo $form->labelEx($ldap_model, 'password'); ?>
                    <?php echo $form->passwordField($ldap_model, 'password', ['class' => 'span12']); ?>
                    <?php echo $form->error($ldap_model, 'password'); ?>

                    <?php echo $form->labelEx($ldap_model, 'accountSuffix'); ?>
                    <?php echo $form->textField($ldap_model, 'accountSuffix', array('class' => 'span12')); ?>
                    <?php echo $form->error($ldap_model, 'accountSuffix'); ?>
                </div>

                <div class="span6">
                    <?php echo $form->labelEx($ldap_model, 'host'); ?>
                    <?php echo $form->textField($ldap_model, 'host', ['class' => 'span12']); ?>
                    <?php echo $form->error($ldap_model, 'host'); ?>

                    <?php echo $form->labelEx($ldap_model, 'baseDN'); ?>
                    <?php echo $form->textField($ldap_model, 'baseDN', ['class' => 'span12']); ?>
                    <?php echo $form->error($ldap_model, 'baseDN'); ?>

                    <?php echo $form->labelEx($ldap_model, 'usersDN'); ?>
                    <?php echo $form->textField($ldap_model, 'usersDN', ['class' => 'span12']); ?>
                    <?php echo $form->error($ldap_model, 'usersDN'); ?>

                    <?php echo $form->labelEx($ldap_model, 'groupsDN'); ?>
                    <?php echo $form->textField($ldap_model, 'groupsDN', ['class' => 'span12']); ?>
                    <?php echo $form->error($ldap_model, 'groupsDN'); ?>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <div id="rezult_test2">

            </div>
        </div>
        <div class="box-footer">
            <?php
            echo CHtml::ajaxSubmitButton(Yii::t('main-ui', 'Test connect'),
                CHtml::normalizeUrl(array("config/ldaptest")),
                array(
                    'success' => 'function(data){$("#rezult_test2").html(data);}'
                ),
                array('class' => 'btn btn-warning'));
            ?>
            <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
            <br>
            <?php if(isset($file) and $file != 'ad.inc') echo CHtml::linkButton(Yii::t('main-ui', 'Delete'), array('class' => 'btn btn-danger', 'href' => '/config/addelete/?file=' . $file)); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
</div>