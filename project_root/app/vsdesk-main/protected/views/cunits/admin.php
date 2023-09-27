<?php

$total = '';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Configuration units') => array('index'),
    Yii::t('main-ui', 'Manage units'),
);
?>

    <div class="page-header">
        <h3><i class="fa-solid fa-computer fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage units'); ?></h3>
    </div>
    <div class="box">
        <div class="box-body table-responsive">
            <ul id="yw0" class="nav nav-pills">
                <?php if (Yii::app()->user->checkAccess('createUnit')): ?>
                    <li><a href="/cunits/create"><i class="fa-solid fa-circle-plus fa-xl"
                                            title="<?php echo Yii::t('main-ui', 'Create unit'); ?>"></i> </a></li>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('exportUnit')): ?>
                    <li><a href="/cunits/export"><i class="fa-solid fa-upload fa-xl"
                                            title="<?php echo Yii::t('main-ui', 'Export to Excel'); ?>"></i> </a></li>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('batchDeleteUnit')): ?>
                <li><a href="javascript:void(0);"><i id="delete" class="fa-solid fa-trash fa-xl" title="<?php echo Yii::t('main-ui', 'Batch delete units'); ?>"></i> </a></li>
            <?php endif; ?>
                <li><a href="javascript:void(0);" id="cunits-grid-ecolumns-dlg-link"><i class="fa-solid fa-gear fa-xl"
                                               title="<?php echo Yii::t('main-ui', 'Columns settings'); ?>"></i> </a>
                </li>
            </ul>

            <?php $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            )); ?>
            <?php require_once '_grid.php'; ?>
            <?php $this->widget('FilterGridResizable', array(
                'id' => 'cunits-grid',
                'redirectRoute' => CHtml::normalizeUrl(''),
                'dataProvider' => $model->search(),
                'htmlOptions' => array('style' => 'cursor: pointer'),
                'selectionChanged' => Yii::app()->user->checkAccess('viewUnit') ? 'function(id){location.href = "' . $this->createUrl('/cunits') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
                'type' => 'striped bordered condensed',
                'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['cunitsPageCount'] ? Yii::app()->session['cunitsPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
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
    </div>
<?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
	$('#newDatepicker').datepicker();
	}
	"); ?>
<?php
if (Yii::app()->user->checkAccess('batchDeleteUnit')) {
    Yii::app()->clientScript->registerScript('delete', '
       $("#delete").click(function(){
           var checked=$("#cunits-grid").yiiGridView("getChecked","cunits-grid_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
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
                            url:"' . CHtml::normalizeUrl(array('cunits/batchDelete')) . '",
                            success:function(data){$("#cunits-grid").yiiGridView("update",{});},
                            });
                        }
                        });
                    }
                    });
');
}
?>