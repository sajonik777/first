<?php

$total = '';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Users') => array('index'),
    Yii::t('main-ui', 'Manage users'),
);
$this->menu = array(
    Yii::app()->user->checkAccess('createUser') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create user'))) : array(NULL),
    Yii::app()->user->checkAccess('exportUser') ? array('icon' => 'fa-solid fa-upload fa-xl', 'url' => array('export'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Export to Excel'))) : array(NULL),
    Yii::app()->user->checkAccess('batchDeleteUser') ? array('icon' => 'fa-solid fa-trash fa-xl', 'url' => 'javascript:void(0)', 'itemOptions'=>array('title' => Yii::t('main-ui', 'Delete'), 'id' => 'delete')) : array(NULL),
    array('icon' => 'fa-solid fa-gear fa-xl', 'url' => array('javascript:void(0)'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Columns settings'), 'id'=>"cusers-grid-ecolumns-dlg-link")),
);
?>
<div class="page-header">
    <h3><i class="fa-solid fa-user fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage users'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php if (Yii::app()->user->checkAccess('systemAdmin') OR Yii::app()->user->checkAccess('systemUser')): ?>
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
                'id' => 'cusers-grid',
                'redirectRoute' => CHtml::normalizeUrl(''),
                'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/cusers') . '/"+$.fn.yiiGridView.getSelection(id);}',
                'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['usersPageCount'] ? Yii::app()->session['usersPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
                'dataProvider' => $model->search(),
                'filter' => $model,
                'htmlOptions' => array('style' => 'cursor: pointer'),
                'pager' => array(
                    'class' => 'CustomPager',
                    'displayFirstAndLast' => true,
                ),
                'columns' => array_merge($fixed_columns, $dialog->columns()),
                'template' => "{summary}\n{items}\n{pager}",
            )); ?>
        <?php elseif (Yii::app()->user->checkAccess('systemManager')): ?>
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'items' => $this->menu,
            )); ?>
            <?php $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            )); ?>
            <?php
            ?>
            <?php require_once '_grid.php'; ?>
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'type' => 'striped bordered condensed',
                'id' => 'cusers-grid',
                'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/cusers') . '/"+$.fn.yiiGridView.getSelection(id);}',
                'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['usersPageCount'] ? Yii::app()->session['usersPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
                'dataProvider' => $model->ousearch(),
                'filter' => $model,
                'htmlOptions' => array('style' => 'cursor: pointer'),
                'pager' => array(
                    'class' => 'CustomPager',
                    'displayFirstAndLast' => true,
                ),
                'columns' => array_merge($fixed_columns, $dialog->columns()),
                'template' => "{summary}\n{items}\n{pager}",
            )); ?>
        <?php endif; ?>
    </div>
</div>
<?php
if (Yii::app()->user->checkAccess('batchDeleteUser')) {
    Yii::app()->clientScript->registerScript('delete', '
       $("#delete").click(function(){
           var checked=$("#cusers-grid").yiiGridView("getChecked","cusers-grid_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
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
                            url:"' . CHtml::normalizeUrl(array('cusers/batchDelete')) . '",
                            success:function(data){$("#cusers-grid").yiiGridView("update",{});},
                            });
                        }
                        });
                    }
                    });
');
}
?>