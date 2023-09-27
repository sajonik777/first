<?php

/**
 * @var WhatsappForm $model
 * @var TbActiveForm $form
 */

$this->breadcrumbs = [
    Yii::t('main-ui', 'WhatsApp integration'),
];
?>

<div class="page-header">
    <h3><i class="fa-solid fa-face-smile fa-xl"> </i>
        <?php echo Yii::t('main-ui', 'WhatsApp integration'); ?>
    </h3>
</div>
<div class="form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => 'MSBotForm-form',
        'enableAjaxValidation' => false,
    ]);
    ?>
    <div class="box">

                <div>
            <?php

            try {
                require_once __DIR__ . '../../../vendors/whatsapp/chatapi.class.php';

                $api = new ChatApi($model->token, $model->apiUrl);
                $webhookArr = $api->getWebhook();
                if ($webhookArr && $webhookArr['webhookUrl']) {
                    $model->webhookUrl = $webhookArr['webhookUrl'];
                }

            } catch (Throwable $e) {

            }

            ?>
        </div>

        <div class="box-body">
            <?php $this->widget('bootstrap.widgets.TbAlert', [
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            ]); ?>

            <?php echo $form->errorSummary($model); ?>
            <div class="row-fluid">
                <?php echo $form->toggleButtonRow($model, 'enabled'); ?>

                <?php echo $form->labelEx($model, 'token'); ?>
                <?php echo $form->textField($model, 'token', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'token'); ?>

                <?php echo $form->labelEx($model, 'apiUrl'); ?>
                <?php echo $form->textField($model, 'apiUrl', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'apiUrl'); ?>

                <?php echo $form->labelEx($model, 'webhookUrl'); ?>
                <?php echo $form->textField($model, 'webhookUrl', ['class' => 'span12']); ?>
                <?php echo $form->error($model, 'webhookUrl'); ?>
            </div>

        </div>
        <div class="row-fluid">
            <div class="box-footer">
                <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>

    </div>

    <?php $this->endWidget(); ?>
</div>
