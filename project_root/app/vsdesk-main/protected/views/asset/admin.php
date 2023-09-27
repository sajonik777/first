<?php

$total = '';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Assets') => array('index'),
    Yii::t('main-ui', 'Manage assets'),
);
?>
    <div class="page-header">
        <h3><i class="fa-solid fa-desktop fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage assets'); ?></h3>
    </div>
<div class="box">
    <div class="box-body table-responsive">
        <ul id="yw0" class="nav nav-pills">
            <?php if (Yii::app()->user->checkAccess('createAsset')): ?>
                <li><a href="/asset/create"><i class="fa-solid fa-circle-plus fa-xl" title="<?php echo Yii::t('main-ui', 'Create asset'); ?>"></i> </a></li>
            <?php endif; ?>
            <?php if (Yii::app()->user->checkAccess('exportAsset')): ?>
                <li><a href="/asset/export"><i class="fa-solid fa-upload fa-xl" title="<?php echo Yii::t('main-ui', 'Export to Excel'); ?>"></i> </a></li>
            <?php endif; ?>
            <?php if (Yii::app()->user->checkAccess('batchDeleteAsset')): ?>
                <li><a href="javascript:void(0);"><i id="delete" class="fa-solid fa-trash fa-xl" title="<?php echo Yii::t('main-ui', 'Batch delete assets'); ?>"></i> </a></li>
            <?php endif; ?>
            <li><a href="javascript:void(0);" id="assets-grid-ecolumns-dlg-link"><i class="fa-solid fa-gear fa-xl"
                                           title="<?php echo Yii::t('main-ui', 'Columns settings'); ?>"></i> </a>
            </li>
        </ul>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <div id="#printarea">
            <?php require_once '_grid.php'; ?>
            <?php $this->widget('FilterGridResizable', array(
                'type' => 'striped bordered condensed',
                'redirectRoute' => CHtml::normalizeUrl(''),
                'selectionChanged' => Yii::app()->user->checkAccess('viewAsset') ? 'function(id){location.href = "' . $this->createUrl('/asset') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
                'id' => 'assets-grid',
                'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['assetPageCount'] ? Yii::app()->session['assetPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
                'dataProvider' => $model->search(),
                'htmlOptions' => array('style' => 'cursor: pointer'),
                'filter' => $model,
                'afterAjaxUpdate' => 'reinstallDatePicker',
                'columns' => array_merge($fixed_columns, $dialog->columns()),
                'pager' => array(
                    'class' => 'CustomPager',
                    'displayFirstAndLast' => true,
                ),
                'template' => "{summary}\n{items}\n{pager}",
            )); ?>
        </div>
        <?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
	$('#newDatepicker').datepicker();
	}
	"); ?>
    </div>
</div>
<?php
if (Yii::app()->user->checkAccess('batchDeleteAsset')) {
    Yii::app()->clientScript->registerScript('delete', '
       $("#delete").click(function(){
           var checked=$("#assets-grid").yiiGridView("getChecked","assets-grid_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
           var count=checked.length;
           if(count==0){
            swal(
            " '. Yii::t('main-ui', 'No items selected or selected only one item') . '",
            "ERROR!",
            "error");
        }
        if(count>0){
            swal({
                title: "' . Yii::t('main-ui', 'Do you want to delete') . ' "+count+" ' . Yii::t('main-ui','item(s)') . '",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "'.Yii::t('main-ui', 'Yes').'",
                cancelButtonText: "'.Yii::t('main-ui', 'No').'",
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            data:{checked:checked},
                            url:"' . CHtml::normalizeUrl(array('asset/batchDelete')) . '",
                            success:function(data){$("#assets-grid").yiiGridView("update",{});},
                            });
                        }
                        });
                    }
                    });
');
}
?>