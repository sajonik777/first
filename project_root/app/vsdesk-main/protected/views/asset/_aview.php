<?php

echo '<div class="row-fluid">'; ?>
<div class="span10">
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'type' => 'striped bordered condensed',
        'data' => $model,
        'attributes' => array(
            'asset_attrib_name',
            'name',
            'status',
            'location',
            'inventory',
            'cost',
            'description:raw',
            array(               // related city displayed as a link
                'label'=>Yii::t('main-ui', 'Warranty Start'),
                'type'=>'raw',
                'value'=>date_format(new DateTime($model->warranty_start),'d.m.Y'),
            ),
            array(               // related city displayed as a link
                'label'=>Yii::t('main-ui', 'Warranty End'),
                'type'=>'raw',
                'value'=>date_format(new DateTime($model->warranty_end),'d.m.Y'),
            ),
            // 'warranty_start',
            // 'warranty_end',

        ),
    )); ?>
    <?php if ($data): ?>
        <?php $this->widget('bootstrap.widgets.TbDetailView', array(
            'type' => 'striped bordered condensed',
            'data' => $data,
            'attributes' => $data
        )); ?>
    <?php endif; ?>
    <?php echo '</div>
        <div class="span2">'; ?>
    <h4><a href="<?php echo Yii::app()->params['homeUrl']; ?>/uploads/asset<?php echo $model->id; ?>.png"
           target="_blank"><?php echo Yii::t('main-ui', 'QR Code to print active form'); ?></a></h4>
    <?php $this->widget('application.extensions.qrcode.QRCodeGenerator', array(
        'data' => Yii::app()->params['homeUrl'] . '/asset/' . $model->id,
        'filePath' => Yii::app()->getBasePath() . '/../uploads',
        'filename' => "asset" . $model->id . ".png",
        'subfolderVar' => false,
        'matrixPointSize' => 5,
        'displayImage' => true,
        'errorCorrectionLevel' => 'L',
        'matrixPointSize' => 6, // 1 to 10 only
    )) ?>
</div>
</div>