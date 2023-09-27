<?php
?>
<div class="row-fluid">
<div class="span10">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $unit,
    'type' => 'striped bordered condensed',
    'attributes' => array(
        'name',
        'type',
        'slabel:raw',
        'fullname',
        'company',
        'inventory',
        'location',
        'date',
        'datein',
        'dateout',
        'cost',
    ),
)); ?>
<a class="btn btn-info" href="/cunits/<?php echo $unit->id; ?>"><?php echo Yii::t('main-ui', 'View'); ?></a>
    </div>
<div class="span2">
    <h4><a href="<?php echo Yii::app()->params['homeUrl'];?>/uploads/unit<?php echo $unit->id;?>.png" target="_blank"><?php echo Yii::t('main-ui', 'QR Code to print form') ;?></a></h4>
    <?php $this->widget('application.extensions.qrcode.QRCodeGenerator',array(
        'data' => Yii::app()->params['homeUrl'].'/cunits/'.$unit->id,
        'filePath' => Yii::app()->getBasePath().'/../uploads',
        'filename' => "unit".$unit->id.".png",
        'subfolderVar' => false,
        'matrixPointSize' => 5,
        'displayImage'=>true,
        'errorCorrectionLevel'=>'L',
        'matrixPointSize'=>6, // 1 to 10 only
    )) ?>
</div>
</div>