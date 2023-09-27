<?php

/**
 * @var TeamViewerForm $model
 * @var TbActiveForm $form
 */

$this->breadcrumbs = [
    Yii::t('main-ui', 'TeamViewer integration'),
];
?>

<div class="page-header">
    <h3><i class="fa-solid fa-network-wired fa-xl"> </i><?php echo Yii::t('main-ui', 'TeamViewer integration'); ?></h3>
</div>
<div class="form">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => 'TeamViewer-form',
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

                <?php echo $form->labelEx($model, 'client_id'); ?>
                <?php echo $form->textField($model, 'client_id', ['class' => 'span6']); ?>
                <?php echo $form->error($model, 'client_id'); ?>

                <?php echo $form->labelEx($model, 'client_secret'); ?>
                <?php echo $form->textField($model, 'client_secret', ['class' => 'span6']); ?>
                <?php echo $form->error($model, 'client_secret'); ?>

                <?php
                echo $form->labelEx($model, 'access_token');
                echo $form->textField($model, 'access_token', ['class' => 'span6', 'readonly' => true]);
                echo $form->error($model, 'access_token');
                ?>
            </div>

        </div>
        <div class="row-fluid">
            <div id="rezult_test">

            </div>
            <div class="box-footer">
                <?php echo CHtml::Button(Yii::t('main-ui', 'Get token'), ['onclick' => 'javascript:(openTW())', 'class' => 'btn btn-warning']); ?>
                <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
        <!-- <div class="box-body">
            <h3>Сессии</h3> -->
        <?php
            // $tw = new TeamViewer;
            // $data = $tw->sessionsList(Yii::app()->params['TeamViewerAccessToken']);
            // $dataProvider = new CArrayDataProvider($data['sessions']);
            // $this->widget('bootstrap.widgets.TbGridView', [
            //     'id' => 'sessions-grid',
            //     'dataProvider' => $dataProvider,
            //     'columns' => [
            //         'code',
            //         'state',
            //         [
            //             'name' => 'online',
            //             'value' => '$data["online"] ? "Активен" : "Отключен"',
            //         ],
            //     ],
            // ]);
        ?>
        <!-- </div> -->
    </div>
</div>
<script>
    function openTW() {
        var client_id = $('#TeamViewerForm_client_id').val();
        var client_secret = $('#TeamViewerForm_client_secret').val();
        window.open('<?= CHtml::normalizeUrl(['/config/twtest', 'client_id'=>null]) ?>' + client_id + '&client_secret=' + client_secret,'teamviewer','width=640,height=500');
    }
</script>
