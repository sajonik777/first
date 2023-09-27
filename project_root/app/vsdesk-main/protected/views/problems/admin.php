<?php

$total = '';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Problems') => array('index'),
    Yii::t('main-ui', 'Manage problems'),
);

Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });
    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update('problems-grid', {
            data: $(this).serialize()
        });
        return false;
    });
    ");

    ?>
    <div class="page-header">
        <h3><i class="fa-solid fa-triangle-exclamation fa-xl"></i><?php echo Yii::t('main-ui', 'Manage problems'); ?></h3>
    </div>
    <div class="box">
        <div class="box-body table-responsive">
            <ul id="yw0" class="nav nav-pills">
                <?php if (Yii::app()->user->checkAccess('createProblem')): ?>
                    <li><a href="/problems/create"><i class="fa-solid fa-circle-plus fa-xl"
                        title="<?php echo Yii::t('main-ui', 'Problem by incident'); ?>"></i>
                    </a></li>
                    <li><a href="/problems/createh"><i class="fa-solid fa-circle-plus fa-xl"
                       title="<?php echo Yii::t('main-ui', 'Problem by asset'); ?>"></i>
                   </a></li>
               <?php endif; ?>
               <li><a href="#" class="search-button"><i
                class="fa-solid fa-magnifying-glass fa-xl" title="<?php echo Yii::t('main-ui', 'Advanced filter'); ?>"></i></a>
            </li>
            <?php if (Yii::app()->user->checkAccess('batchUpdateProblem')): ?>
                <li><a href="#" id="close"><i class="fa-solid fa-power-off fa-xl"
                  title="<?php echo Yii::t('main-ui', 'Mark as solved'); ?>"></i> </a>
              </li>
          <?php endif; ?>
          <?php if (Yii::app()->user->checkAccess('batchDeleteProblem')): ?>
            <li><a href="#" id="delete"><i class="fa-solid fa-trash fa-xl"
             title="<?php echo Yii::t('main-ui', 'Delete selected'); ?>"></i> </a>
         </li>
     <?php endif; ?>
     <li><a href="javascript:void(0);" id="problems-grid-ecolumns-dlg-link"><i class="fa-solid fa-gear fa-xl"
         title="<?php echo Yii::t('main-ui', 'Columns settings'); ?>"></i> </a>
     </li>
 </ul>
 <?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
    )); ?>

    <div class="search-form" style="display:none">
        <?php $this->renderPartial('_search', array(
            'model' => $model,
            )); ?>
            <br/>
        </div><!-- search-form -->
        <?php require_once '_grid.php'; ?>
        <?php $this->widget('FilterGridResizable', array(
            'type' => 'striped bordered condensed',
            'id' => 'problems-grid',
            'redirectRoute' => CHtml::normalizeUrl(''),
            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/problems') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['problemsPageCount'] ? Yii::app()->session['problemsPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'dataProvider' => $model->search(),
            'filter' => $model,
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => array_merge($fixed_columns, $dialog->columns()),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'template' => "{summary}\n{items}\n{pager}",
            )); ?>
        </div>
    </div>

    <?php
    if (Yii::app()->user->checkAccess('batchDeleteProblem')) {
        Yii::app()->clientScript->registerScript('delete', '
            $("#delete").click(function(){
                var checked=$("#problems-grid").yiiGridView("getChecked","problems-grid_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
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
                                url:"' . CHtml::normalizeUrl(array('Problems/batchDelete')) . '",
                                success:function(data){$("#problems-grid").yiiGridView("update",{});},
                            });
                        }
                    });

                }
            });
            ');
        } ?>
        <?php if (Yii::app()->user->checkAccess('batchUpdateProblem')) {
            Yii::app()->clientScript->registerScript('close', '
                $("#close").click(function(){
                    var checked=$("#problems-grid").yiiGridView("getChecked","problems-grid_c0");
                    var count=checked.length;
                    if(count==0){
                        swal(
                            " '. Yii::t('main-ui', 'No items selected or selected only one item') . '",
                            "ERROR!",
                            "error");
                        }
                        if(count>0){
                            swal({
                                title: "' . Yii::t('main-ui', 'Do you want to close') . ' "+count+" ' . Yii::t('main-ui','item(s)') . '",
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
                                        url:"' . CHtml::normalizeUrl(array('Problems/batchUpdate')) . '",
                                        success:function(data){$("#problems-grid").yiiGridView("update",{});},
                                    });
                                }
                            });
                        }
                    });
                    ');
        }
        Yii::app()->clientScript->registerScript('re-install-date-picker', "
           function reinstallDatePicker(id, data) {
               $('#newDatepicker').datepicker();
           }
           ");
           ?>
