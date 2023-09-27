<?php

$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('updateCronRequest')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteCronRequest')) {
    $delete = '{delete}';
}
$template = $update . ' ' . $delete;
?>

<?php $dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'cron-req-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            [
                'name' => 'enabled',
                'header' => Yii::t('main-ui', 'Активно'),
                'type' => 'text',
                'filter' => [0 => Yii::t('main-ui', 'Disabled'), 1 => Yii::t('main-ui', 'Enabled')],
                'value' => '$data->enabled ? "' . Yii::t('main-ui', 'Enabled') . '" : "' . Yii::t('main-ui', 'Disabled') . '"'
            ],
            [
                'name' => 'service_id',
                'header' => Yii::t('main-ui', 'Service name'),
                'type' => 'text',
                'filter' => Service::all(),
                'value' => '$data->service->name'
            ],
            [
                'name' => 'CUsers_id',
                'header' => Yii::t('main-ui', 'User'),
                'type' => 'text',
            ],
            [
                'name' => 'Status',
                'header' => Yii::t('main-ui', 'Status'),
                'type' => 'text',
                'filter' => Status::all(),
            ],
            [
                'name' => 'ZayavCategory_id',
                'header' => Yii::t('main-ui', 'Category'),
                'type' => 'text',
                'filter' => Category::model()->all(),
            ],
            [
                'name' => 'Priority',
                'header' => Yii::t('main-ui', 'Priority'),
                'type' => 'text',
                'filter' => Zpriority::all(),
            ],
            [
                'name' => 'Name',
                'header' => Yii::t('main-ui', 'Name'),
                'type' => 'text',
            ],
            [
                'name' => 'Date',
                'header' => Yii::t('main-ui', 'Date start'),
                'type' => 'text',
                'value' => 'date("d.m.Y H:i", strtotime($data->Date))',
            ],
            [
                'name' => 'Date_end',
                'header' => Yii::t('main-ui', 'Date end'),
                'type' => 'text',
                'value' => 'date("d.m.Y H:i", strtotime($data->Date_end))',
            ],
            [
                'name' => 'repeats',
                'header' => Yii::t('main-ui', 'Repeat'),
                'type' => 'text',
                'filter' => CronReq::$allRepeats,
                'value' => 'CronReq::$allRepeats[$data->repeats]',
            ],
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'afterDelete' => '$(function(){
                    $.fn.yiiGridView.update("cron-req-grid");
                    $("#calendar").html(html); 
                })',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
                'buttons' => array(
                    'delete' => array
                    (
                        'label' => Yii::t('main-ui', 'Delete'),
                        'click'=>'function(event){
                            event.preventDefault(); 
                            var checked= $(this).parent().parent().children(":nth-child(1)").text();
                            swal({
                               title: "' . Yii::t('zii','Are you sure you want to delete this item?') . '",
                               type: "warning",
                               showCancelButton: true,
                               confirmButtonColor: "#3085d6",
                               cancelButtonColor: "#d33",
                               confirmButtonText: "'.Yii::t('main-ui', 'Yes').'",
                               cancelButtonText: "'.Yii::t('main-ui', 'No').'",
                           }).then(function (result) {
                               if (result.value) {
                                   $.ajax({
                                       data:{id:checked},
                                       url:"' . CHtml::normalizeUrl(array('cronreq/delete')) . '",
                                       success:function(data){$("#cron-req-grid").yiiGridView("update",{});},
                                   });
                               }
                           });
                       }',
                   ),
                )
            )
        )
    ),
));
$fixed_columns = array_filter(array(
    array(
        'name' => 'id',
        'header' => Yii::t('main-ui', '#'),
        'headerHtmlOptions' => array('width' => 60),
        //'filter' => '',
    )
    )); ?>