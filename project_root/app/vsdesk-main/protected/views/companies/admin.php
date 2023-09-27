<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Companies') => array('index'),
    Yii::t('main-ui', 'Manage'),
);

    $this->menu = array(
        Yii::app()->user->checkAccess('createCompany') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create company'))) : array(NULL),
        Yii::app()->user->checkAccess('fieldsCompany') ? array('icon' => 'fa-solid fa-hammer fa-xl', 'url' => array('fields'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Company fields'))) : array(NULL),
        Yii::app()->user->checkAccess('batchDeleteCompany') ? array('icon' => 'fa-solid fa-trash fa-xl', 'url' => 'javascript:void(0)', 'itemOptions'=>array('title' => Yii::t('main-ui', 'Delete'), 'id' => 'delete')) : array(NULL),
        array('icon' => 'fa-solid fa-gear fa-xl', 'url' => 'javascript:void(0);', 'itemOptions'=>array('title' => Yii::t('main-ui', 'Columns settings'), 'id'=>"companies-grid-ecolumns-dlg-link")),
    );

?>
<div class="page-header">
    <h3><i class="fa-solid fa-building fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage companies'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <?php require_once '_grid.php'; ?>
        <?php $this->widget('FilterGridResizable', array(
            'type' => 'striped bordered condensed',
            'id' => 'companies-grid',
            'redirectRoute' => CHtml::normalizeUrl(''),
            'selectionChanged' => Yii::app()->user->checkAccess('viewCompany') ? 'function(id){location.href = "' . $this->createUrl('/companies') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['compPageCount'] ? Yii::app()->session['compPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'dataProvider' => $model->search(),
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'filter' => $model,
            'columns' => array_merge($fixed_columns, $dialog->columns()),
            'template' => "{summary}\n{items}\n{pager}",
        ));
        ?>
    </div>
</div>
<?php
if (Yii::app()->user->checkAccess('batchDeleteCompany')) {
    Yii::app()->clientScript->registerScript('delete', '
       $("#delete").click(function(){
           var checked=$("#companies-grid").yiiGridView("getChecked","companies-grid_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
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
                            url:"' . CHtml::normalizeUrl(array('companies/batchDelete')) . '",
                            success:function(data){$("#companies-grid").yiiGridView("update",{});},
                            });
                        }
                        });
                    }
                    });
');
}
?>