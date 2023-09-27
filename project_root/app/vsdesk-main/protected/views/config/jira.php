<?php

$this->breadcrumbs = [
    Yii::t('main-ui', 'Jira integration'),
];
?>

<div class="page-header">
    <h3><i class="fa-brands fa-jira fa-xl"></i><?php echo Yii::t('main-ui', 'Jira integration'); ?></h3>
</div>

<div class="form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => 'VBotForm-form',
        'enableAjaxValidation' => false,
    ]);
    ?>
    <div class="box">
        <div class="box-body">
            <?php $this->widget('bootstrap.widgets.TbAlert', [
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            ]); ?>

            <?php echo $form->errorSummary($model); ?>
            <div class="row-fluid">
                <?php echo $form->toggleButtonRow($model, 'enabled'); ?>

                <?php echo $form->labelEx($model, 'domen'); ?>
                <?php echo $form->textField($model, 'domen', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'domen'); ?>

                <?php echo $form->labelEx($model, 'user'); ?>
                <?php echo $form->textField($model, 'user', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'user'); ?>

                <?php echo $form->labelEx($model, 'password'); ?>
                <?php echo $form->passwordField($model, 'password', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'password'); ?>

                <?php echo $form->labelEx($model, 'project'); ?>
                <?php echo $form->textField($model, 'project', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'project'); ?>

                <?php echo $form->labelEx($model, 'issuetype'); ?>
                <?php echo $form->textField($model, 'issuetype', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'issuetype'); ?>

                <?php echo $form->select2Row($model, 'services', [
                    'id' => 'services',
                    'data' => Service::all(),
                    'multiple' => 'multiple',
                    'options' => [
                        'width' => '100%',
                        'tokenSeparators' => [','],
                    ],
                ]);
                ?>
            </div>

        </div>
        <div class="row-fluid">
            <div class="box-footer">
                <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
