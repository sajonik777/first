<div class="row-fluid">
<div class="span10">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'type' => 'striped bordered condensed',
    'attributes' => array(
        'name',
        'type',
        'slabel:raw',
        'fullname',
        'company',
        'inventory',
        // 'location',
        'date',
        'datein',
        'dateout',
        'warranty_start',
        'warranty_end',
        // 'cost',
        'description:raw',
    ),
)); ?>
    </div>
<div class="span2">
    <h4><a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'];?>/uploads/unit<?php echo $model->id;?>.png" target="_blank"><?php echo Yii::t('main-ui', 'QR Code to print form') ;?></a></h4>
    <?php $this->widget('application.extensions.qrcode.QRCodeGenerator',array(
        'data' => 'http://' . $_SERVER['HTTP_HOST'].'/cunits/'.$model->id,
        // 'data' => Yii::app()->params['homeUrl'].'/cunits/'.$model->id,
        'filePath' => Yii::app()->getBasePath().'/../uploads',
        'filename' => "unit".$model->id.".png",
        'subfolderVar' => false,
        'matrixPointSize' => 5,
        'displayImage'=>true,
        'errorCorrectionLevel'=>'L',
        'matrixPointSize'=>6, // 1 to 10 only
    )) ?>
</div>
</div>
<?php
$view = NULL;
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('viewAsset')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('printAsset')) {
    $delete = '{print}';
}

$template = $view . ' ' . $delete;
$config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10),);
$rawData = $assets;
$dataProvider = new CArrayDataProvider($rawData, $config);
?>
<h4><?php echo Yii::t('main-ui', 'Unit assets'); ?></h4>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'assets-grid',
    'type' => 'striped bordered condensed',
    'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/asset') . '/"+$.fn.yiiGridView.getSelection(id);}',
    'summaryText' => '',
    'dataProvider' => $dataProvider,
    'columns' => array(
        /*array(
            'name' => 'asset_attrib_name',
            'header' => Yii::t('main-ui', 'Asset type'),
        ),*/
        array(
            'name' => 'name',
            'header' => Yii::t('main-ui', 'Name'),
        ),
        array(
            'name' => 'slabel',
            'type' => 'raw',
            'header' => Yii::t('main-ui', 'Status'),
        ),
        array(
            'name' => 'inventory',
            'header' => Yii::t('main-ui', 'Inventory #'),
        ),
        // array(
        //     'name' => 'cost',
        //     'header' => Yii::t('main-ui', 'Cost'),
        // ),

        array(
            'class' => 'bootstrap.widgets.TbButtonColumn', 'viewButtonUrl' => 'Yii::app()->createUrl("/asset/view", array("id"=>$data->id))',
            'buttons' => array
            (
                'print' => array
                (
                    'label' => Yii::t('main-ui', 'Print'),
                    'url' => 'Yii::app()->createUrl("asset/print", array("id"=>$data->id))',
                    'icon' => 'icon-print',
                    'options' => array('target' => '_blank'),
                ),

            )
        )
    ),
));
?>
