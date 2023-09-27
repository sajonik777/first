<?php

use PhpOffice\PhpWord\Writer\Word2007\Part\Rels;

$total = '';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Tickets') => array('index'),
    Yii::t('main-ui', 'Manage tickets'),
);
Yii::app()->bootstrap->registerPackage('select2');
Yii::app()->clientScript->registerScript('search', "
    $('.search-button').click(function(){
        $('.search-form').toggle();
        return false;
    });

    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update('request-grid-full2', {
            data: $(this).serialize()
        });
        return false;
    });
    ");
?>
<div class="page-header">
    <h3><i class="fa-solid fa-ticket fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage tickets'); ?></h3>
</div>

<div class="box">
    <div class="box-header">
        <ul id="yw0" class="nav nav-pills">
            <?php if (Yii::app()->user->checkAccess('createRequest')): ?>
                <li><a href="/request/create"><i class="fa-solid fa-circle-plus fa-xl"
                                                 title="<?php echo Yii::t('main-ui', 'Create ticket'); ?>"></i> </a>
                </li>
            <?php endif; ?>
            <li><a href="javascript:void(0);" class="search-button"><i class="fa-solid fa-magnifying-glass fa-xl"
                                                                       title="<?php echo Yii::t('main-ui', 'Advanced filter'); ?>"></i>
                </a></li>

            <?php if (Yii::app()->user->checkAccess('batchUpdateStatusRequest')): ?>
                <li><a href="javascript:void(0);" id="setstatus"><i class="fa-solid fa-bookmark fa-xl"
                                                                    title="<?php echo Yii::t('main-ui', 'Set status selected'); ?>"></i>
                    </a></li>
            <?php endif; ?>
            <?php if (Yii::app()->user->checkAccess('batchAssignRequest')): ?>
                <li><a href="javascript:void(0);" id="setuser"><i class="fa-solid fa-user fa-xl"
                                                                  title="<?php echo Yii::t('main-ui', 'Assign to user'); ?>"></i>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::app()->user->checkAccess('batchAssignRequest')): ?>
                <li><a href="javascript:void(0);" id="setgroup"><i class="fa-solid fa-users fa-xl"
                                                                   title="<?php echo Yii::t('main-ui',
                                                                       'Assign to group of users'); ?>"></i>
                    </a></li>
            <?php endif; ?>
            <?php if (Yii::app()->user->checkAccess('batchMergeRequest')): ?>
                <li><a href="javascript:void(0);" id="merge"><i class="fa-solid fa-wand-sparkles fa-xl"
                                                                title="<?php echo Yii::t('main-ui', 'Merge selected'); ?>"></i> </a></li>
            <?php endif; ?>
            <?php if (Yii::app()->user->checkAccess('batchUpdateRequest')): ?>
                <?php
                $statusClose = Status::model()->findByAttributes(['close' => 3]);
                $closeNeedComment = (int)$statusClose->is_need_comment;
                $closeNeedRating = (int)$statusClose->is_need_rating;
                ?>
                <li><a href="javascript:void(0);" id="close" data-need_comment="<?= $closeNeedComment ?>"
                       data-need_rating="<?= $closeNeedRating ?>"><i class="fa-solid fa-circle-check fa-xl"
                                                                     title="<?php echo Yii::t('main-ui', 'Close selected'); ?>"></i>
                    </a></li>
            <?php endif; ?>
             <?php if (Yii::app()->user->checkAccess('batchDeleteRequest')): ?>
                 <li><a href="javascript:void(0);" id="delete"><i class="fa-solid fa-trash fa-xl"
                                                                  title="<?php echo Yii::t('main-ui', 'Delete selected'); ?>"></i>
                     </a>
                 </li>
             <?php endif; ?>
            <?php if (Yii::app()->session['requestStopTimer'] == 1): ?>
                <li>
                    <a href="javascript:void(0);"
                       onclick="$.ajax({data:{stoptimer:'stop'}, success: function(){location.reload();}});"
                       id="stop-timer">
                        <i class="fa-solid fa-hourglass-end fa-xl"
                           title="<?php echo Yii::t('main-ui', 'Stop autoupdate timer'); ?>"></i> </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="javascript:void(0);"
                       onclick="$.ajax({data:{stoptimer:'start'}, success: function(){location.reload();}});"
                       id="start-timer">
                        <i class="fa-solid fa-hourglass-start fa-xl"
                           title="<?php echo Yii::t('main-ui', 'Start autoupdate timer'); ?>"></i> </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::app()->session['requestlastactivity'] == 1): ?>
                <li>
                    <a href="javascript:void(0);"
                       onclick="$.ajax({data:{lastactivity:'stop'}, success: function(){location.reload();}});"
                       id="stop-last">
                        <i class="fa-solid fa-arrow-down-9-1 fa-xl"
                           title="<?php echo Yii::t('main-ui', 'Sort by ID'); ?>"></i> </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="javascript:void(0);"
                       onclick="$.ajax({data:{lastactivity:'start'}, success: function(){location.reload();}});"
                       id="start-last">
                        <i class="fa-solid fa-arrow-down-wide-short fa-xl"
                           title="<?php echo Yii::t('main-ui', 'Sort by last activity'); ?>"></i> </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::app()->session['requestFixHeader'] == 1): ?>
                <li>
                    <a href="javascript:void(0);"
                       onclick="$.ajax({data:{fixheader:'stop'}, success: function(){location.reload();}});"
                       id="unfix-header">
                        <i class="fa-solid fa-window-maximize fa-xl"
                           title="<?php echo Yii::t('main-ui', 'Open tickets in the same tab'); ?>"></i> </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="javascript:void(0);"
                       onclick="$.ajax({data:{fixheader:'start'}, success: function(){location.reload();}});"
                       id="fix-header">
                        <i class="fa-solid fa-up-right-from-square fa-xl"
                           title="<?php echo Yii::t('main-ui', 'Open tickets in the new tab'); ?>"></i> </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::app()->session['requestResponsive'] == 1): ?>
                <li>
                    <a href="javascript:void(0);"
                       onclick="$.ajax({data:{responsive:'stop'}, success: function(){location.reload();}});"
                       id="responsivetbl">
                        <i class="fa-solid fa-table-cells-large fa-xl"
                           title="<?php echo Yii::t('main-ui', 'Unset grid responsive'); ?>"></i> </a>
                </li>
            <?php else: ?>
                <li>
                    <a href="javascript:void(0);"
                       onclick="$.ajax({data:{responsive:'start'}, success: function(){location.reload();}});"
                       id="responsivetbl">
                        <i class="fa-regular fa-rectangle-list fa-xl"
                           title="<?php echo Yii::t('main-ui', 'Set grid responsive'); ?>"></i> </a>
                </li>
            <?php endif; ?>
<!--            --><?php //if (Yii::app()->user->checkAccess('listRequest')): ?>
<!--                <li><a href="/request/kanban/"><i class="fa-solid fa-chart-bar fa-xl"-->
<!--                                                  title="--><?php //echo Yii::t('main-ui', 'Tickets'); ?><!--"></i> </a>-->
<!--                </li>-->
<!--            --><?php //endif; ?>
            <li><a href="javascript:void(0);" id="request-grid-full2-ecolumns-dlg-link"><i
                            class="fa-solid fa-gear fa-xl"
                            title="<?php echo Yii::t('main-ui', 'Columns settings'); ?>"></i> </a>
            </li>
            <li><a class="carousel-button-left" href="javascript:void(0);"><i class="fa-solid fa-chevron-left fa-xl"
                                                                              title="<?php echo Yii::t('main-ui', 'Slide left'); ?>"></i>
                </a></li>
            <li><a class="carousel-button-right" href="javascript:void(0);"><i
                            class="fa-solid fa-chevron-right fa-xl"
                            title="<?php echo Yii::t('main-ui', 'Slide right'); ?>"></i>
                </a></li>
        </ul>
    </div>
    <div class="box-body table-responsive">
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

        <?php require_once '_gridFullFields.php'; ?>
        <?php $this->widget('FilterGridResizable', array(
            'type' => 'striped bordered condensed',
            'id' => 'request-grid-full2',
            'redirectRoute' => CHtml::normalizeUrl(''),
            //'fixedHeader' => Yii::app()->session['requestFixHeader'] == 1 ? true : false,
            'responsiveTable' => Yii::app()->session['requestResponsive'] == 1 ? true : false,
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('',
                    Yii::app()->session['requestPageCount'] ? Yii::app()->session['requestPageCount'] : 30,
                    Yii::app()->params['selectPageCount'],
                    array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div>' . Yii::t('zii',
                    'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'dataProvider' => $model->search(),
            'selectionChanged' => Yii::app()->session['requestFixHeader'] == 1 ? 'function(id){window.open("' . $this->createUrl('/request') . '/"+$.fn.yiiGridView.getSelection(id), "_blank");}' : 'function(id){location.href = "' . $this->createUrl('/request') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'filter' => $model,
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => array_merge($fixed_columns, $dialog->columns()),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'template' => "{summary}\n{items}\n{pager}",
        ));
        ?>

		<script>
		    document.getElementById('request-grid-full2').classList.add('grid-view-loading')
		</script>

        <?php
        if (Yii::app()->user->checkAccess('batchDeleteRequest')) {
            Yii::app()->clientScript->registerScript('delete', '
                                         $("#delete").click(function(){
                                             var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0"); // _c0 means the checkboxes are located in the first column, change if you put the checkboxes somewhere else
                                             var count=checked.length;
                                             if(count==0){
                                                swal(
                                                    " ' . Yii::t('main-ui', 'No items selected or selected only one item') . '",
                                                    "ERROR!",
                                                    "error");
                                                }
                                                if(count>0){
                                                    swal({
                                                        title: "' . Yii::t('main-ui', 'Do you want to delete') . ' "+count+" ' . Yii::t('main-ui', 'item(s)') . '",
                                                        type: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#3085d6",
                                                        cancelButtonColor: "#d33",
                                                        confirmButtonText: "' . Yii::t('main-ui', 'Yes') . '",
                                                        cancelButtonText: "' . Yii::t('main-ui', 'No') . '",
                                                    }).then(function (result) {
                                                        if (result.value) {
                                                            $.ajax({
                                                                data:{checked:checked},
                                                                url:"' . CHtml::normalizeUrl(array('Request/batchDelete')) . '",
                                                                success:function(data){$("#request-grid-full2").yiiGridView("update",{});},
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                            ');
        }
        ?>
    </div>
</div>
<?php if (Yii::app()->user->checkAccess('batchUpdateRequest')): ?>
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
        'id' => 'request-form',
        'enableAjaxValidation' => false,
        'action' => 'createMerge',
    )); ?>
    <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?php echo Yii::t('main-ui', 'Merge selected'); ?></h4>
    </div>

    <div class="modal-body">
        <div class="row-fluid">
            <label class="required" for="merge-list"><b>Объединить в заявку</b></label>
            <div id="merge-ret">
                <?php echo CHtml::DropDownList('merge-id', 'merge-list', array(""),
                    array('class' => 'span12', 'id' => 'merge-list')); ?>
            </div>
        </div>
        <script>
            function getForm() {
                $.get("merge", function (data) {
                    $("#merge-form").append(data);
                    console.log(data);
                });
            }
        </script>
    </div>
    <div id="merge-form">
        <div class="row-fluid">
            <?php
            //$model = new Request;
            $model = new RequestFullFields();
            $model->preLoadFields();
            $fields = new RequestFields;
            $this->renderpartial('_merge_form_new', array(
                'model' => $model,
                'fields' => $fields,
            ));
            ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <?php $this->endWidget(); ?>

    <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModalSetUser')); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?php echo Yii::t('main-ui', 'Выберите исполнителя'); ?></h4>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <?php $criteria = new CDbCriteria;
            $criteria->order = 'fullname ASC';
            $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'users',
                    'data' => CUsers::all($criteria),
                    'htmlOptions' => array(
                        'class' => 'span12',
                    ),
                )
            ); ?>
        </div>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton',
            array('label' => 'Назначить', 'type' => 'primary', 'id' => 'btnSetUser')); ?>
    </div>
    <?php $this->endWidget(); ?>


    <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModalSetGroup')); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?php echo Yii::t('main-ui', 'Выберите группу исполнителей'); ?></h4>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <?php
            $criteria = new CDbCriteria;
            $criteria->order = 'name ASC';
            $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'groups_id',
                    'data' => CHtml::listData(Groups::model()->findAll($criteria), 'id', 'name'),
                    'htmlOptions' => array(
                        'class' => 'span12',
                    ),
                )
            ); ?>
        </div>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton',
            array('label' => 'Назначить', 'type' => 'primary', 'id' => 'btnSetGroup')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php endif; ?>



<?php $this->beginWidget('bootstrap.widgets.TbModal', ['id' => 'batchCommentModal', 'htmlOptions' => ['style' => 'height:auto;width:auto;']]); ?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('main-ui', 'Комментарий'); ?></h4>
</div>
<div class="modal-body" style="min-height: 80%;">
    <?php $this->renderPartial('_commentBatch', ['model' => $model]); ?>
</div>
<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', ['buttonType' => 'submit', 'label' => Yii::t('main-ui', 'Add comment'), 'type' => 'primary', 'id' => 'btnBatchComment']); ?>
</div>
<?php $this->endWidget(); ?>



<?php if (Yii::app()->user->checkAccess('batchUpdateStatusRequest')): ?>
    <?php $this->beginWidget('bootstrap.widgets.TbModal', ['id' => 'myModalSetStatus']); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?php echo Yii::t('main-ui', 'Set status selected'); ?></h4>
    </div>

    <div class="modal-body">
        <label class="required" for="merge-list"><b>Изменить статус</b></label>
        <div class="row-fluid">
            <?php
            $role = Roles::model()->findByAttributes(['value' => strtolower(Yii::app()->user->role)]);
            echo '<select class="span12" name="Status" id="Status">';
            foreach ($role->status_rl as $status) {
                if ($status->name == 'Согласовано'){

                    if (in_array(Yii::app()->user->getId(), $sogls)){
                        echo "<option data-need_comment='{$status->is_need_comment}' data-need_rating='{$status->is_need_rating}' " . ($status->name != $model->Status ?: 'selected') . " value=\"{$status->name}\">{$status->name}</option>";
                    }
                }else{
                    echo "<option data-need_comment='{$status->is_need_comment}' data-need_rating='{$status->is_need_rating}' " . ($status->name != $model->Status ?: 'selected') . " value=\"{$status->name}\">{$status->name}</option>";
                }
            }
            echo '</select>';
            ?>
            <span id="Status_need_comment"></span>
        </div>
    </div>
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton',
            array('label' => 'Изменить статус', 'type' => 'primary', 'id' => 'btnSetStatus')); ?>
    </div>
    <?php $this->endWidget(); ?>
<?php endif; ?>

<?php
if (Yii::app()->user->checkAccess('batchUpdateRequest')) {
    Yii::app()->clientScript->registerScript('close', '
                                         $("#close").click(function(){
                                             var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0");
                                             var count=checked.length;
                                             if(count==0){
                                                swal(
                                                    " ' . Yii::t('main-ui', 'No items selected or selected only one item') . '",
                                                    "ERROR!",
                                                    "error");
                                                }
                                                if(count>0){
                                                    swal({
                                                        title: "' . Yii::t('main-ui', 'Do you want to close') . ' "+count+" ' . Yii::t('main-ui', 'item(s)') . '",
                                                        type: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#3085d6",
                                                        cancelButtonColor: "#d33",
                                                        confirmButtonText: "' . Yii::t('main-ui', 'Yes') . '",
                                                        cancelButtonText: "' . Yii::t('main-ui', 'No') . '",
                                                    }).then(function (result) {
                                                        if (result.value) {
                                                            if($("#close").data("need_comment") == 1) {
                                                                $("#r_ids").val(checked.join(","));
                                                                $("#url").val("' . CHtml::normalizeUrl(['Request/batchUpdateWithComment']) . '");
                                                                jQuery("#batchCommentModal").modal({"show":true});
                                                                if($("#close").data("need_rating") == 1) {
                                                                    jQuery("#batchCommentModal").find(".modal-rating").show();
                                                                } else {
                                                                    jQuery("#batchCommentModal").find(".modal-rating").hide();
                                                                 }
                                                            } else {
                                                                $.ajax({
                                                                    data:{checked:checked},
                                                                    url:"' . CHtml::normalizeUrl(["Request/batchUpdate"]) . '",
                                                                    success:function(data){$("#request-grid-full2").yiiGridView("update",{});},
                                                                    error:function(e){console.log(e);},
                                                                });
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                            ');

    Yii::app()->clientScript->registerScript('merge', '
                                         $("#merge").click(function(){
                                             var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0");
                                             var count=checked.length;
                                             if(count < 2){
                                                swal(
                                                    " ' . Yii::t('main-ui', 'No items selected or selected only one item') . '",
                                                    "ERROR!",
                                                    "error");
                                                }
                                                if(count>1){
                                                    swal({
                                                        title: "' . Yii::t('main-ui', 'Do you want to merge') . ' "+count+" ' . Yii::t('main-ui', 'item(s)') . '",
                                                        type: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonColor: "#3085d6",
                                                        cancelButtonColor: "#d33",
                                                        confirmButtonText: "' . Yii::t('main-ui', 'Yes') . '",
                                                        cancelButtonText: "' . Yii::t('main-ui', 'No') . '",
                                                    }).then(function (result) {
                                                        if (result.value) {
                                                            $.ajax({
                                                                data:{checked:checked},
                                                                url:"' . CHtml::normalizeUrl(array('Request/mergeList')) . '",
                                                                success:function (e) {
                                                                   $("#merge-ret").html(e);
                                                                   jQuery("#myModal").modal({"show":true});
                                                               },
                                                           });
                                                       }
                                                   });         
                                               }
                                           });');

}
?>
<?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
                                       function reinstallDatePicker(id, data) {
                                         /*$('#newDatepicker').daterangepicker({*/
                                             $('.betweenDatepicker').daterangepicker({
                                                 'format':'DD.MM.YYYY',
                                                 'language':'ru',
                                                 'locale':{
                                                    'fromLabel':'От',
                                                    'toLabel':'До',
                                                    'weekLabel':'Н',
                                                    'customRangeLabel':'Задать даты',
                                                    'firstDay':1,
                                                    'daysOfWeek':['В','П','В','С','Ч','П','С'],
                                                    'monthNames':['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                                                    'applyLabel':'Применить',
                                                    'cancelLabel':'Отмена',
                                                },
                                                'ranges':{
                                                    'Сегодня':[moment(), moment()],
                                                    'Вчера':[moment().subtract('days', 1), moment().subtract('days', 1)],
                                                    'Последние 7 дней':[moment().subtract('days', 6), moment()],
                                                    'Последние 30 дней':[moment().subtract('days', 29), moment()],
                                                    'В этом месяце':[moment().startOf('month'), moment().endOf('month')],
                                                    'В прошлом месяце':[moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                                                },
                                            }, function(){
                                                $(this.element).change();
                                            });
                                            /*$('#fnewDatepicker').datepicker();*/
                                            /*$('#fsnewDatepicker').datepicker();*/
                                            /*$('#fenewDatepicker').datepicker();*/
                                            /*$('#snewDatepicker').datepicker();*/
                                            /*$('.rating-block input').rating({'readOnly':true});*/
    var checkList = document.getElementById('list1');
    if(checkList){
        var items = document.getElementById('items');
    checkList.getElementsByClassName('anchor')[0].onclick = function (evt) {
        if (items.classList.contains('visible')){
            items.classList.remove('visible');
            items.style.display = 'none';
        } else{
            items.classList.add('visible');
            items.style.display = 'block';
        }
    }

    items.onblur = function(evt) {
        items.classList.remove('visible');
    }
    }
                                            if($('#fullname2')) $('#fullname2').select2();
                                            if($('#mfullname2')) $('#mfullname2').select2();
                                            if($('#slabel2')) $('#slabel2').select2();
                                            if($('#delay')) $('#delay').select2();
                                            if($('#Priority2')) $('#Priority2').select2();
                                            if($('#ZayavCategory_id2')) $('#ZayavCategory_id2').select2();
                                            if($('#service2')) $('#service2').select2();
                                            if($('#cunits2')) $('#cunits2').select2();
                                            if($('#groups_id2')) $('#groups_id2').select2();
                                            if($('#company2')) $('#company2').select2();
                                            if($('.rating-block input').length != 0) $('.rating-block input').rating({'readOnly':true});
                                            }
                                            ");
?>
<?php
if (Yii::app()->user->checkAccess('batchUpdateStatusRequest')) {
    Yii::app()->clientScript->registerScript('setstatus', '
                                                    $("#setstatus").click(function(){
                                                        var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0");
                                                        var count=checked.length;
                                                        if(count < 1){
                                                            swal(
                                                                " ' . Yii::t('main-ui', 'No items selected or selected only one item') . '",
                                                                "ERROR!",
                                                                "error");
                                                            } else {
                                                                jQuery("#myModalSetStatus").modal({"show":true});
                                                                
                                                            }
                                                        });
                                                        
                                                        $("#Status").change(function(){
                                                            if($("#Status").find("option:selected").data("need_comment") == 1) {
                                                                $("#Status_need_comment").html("<div class=\"alert in alert-block fade alert-error\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\">×</a><strong>Для смены статуса заявки, необходимо добавить комментарий!</strong></div>");
                                                            } else {
                                                                $("#Status_need_comment").text("");
                                                            }
                                                        });

                                                        $("#btnSetStatus").click(function(){
                                                            var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0");
                                                            var status=$("#Status").val();
                                                            
                                                            $("#r_ids").val(checked.join(","));
                                                            $("#status_batch").val(status);
                                                            $("#url").val("' . CHtml::normalizeUrl(['Request/setStatusWithComment']) . '");
                                                            if($("#Status").find("option:selected").data("need_comment") == 1) {
                                                                jQuery("#myModalSetStatus").modal("toggle");
                                                                jQuery("#batchCommentModal").modal({"show":true});
                                                                if($("#Status").find("option:selected").data("need_rating") == 1) {
                                                                    jQuery("#batchCommentModal").find(".modal-rating").show();
                                                                } else {
                                                                    jQuery("#batchCommentModal").find(".modal-rating").hide();
                                                                }                                                   
                                                            } else {
                                                                var user="' . Yii::app()->user->name . '";
                                                                $.ajax({
                                                                    data:{checked:checked,status:status,user:user},
                                                                    url:"' . CHtml::normalizeUrl(['Request/setStatus']) . '",
                                                                    success:function (e) {
                                                                        jQuery("#myModalSetStatus").modal("toggle");
                                                                        $("#request-grid-full2").yiiGridView("update",{});
                                                                    },
                                                                });
                                                            }
                                                        });
                                                        ');
}
?>

<?php
if (Yii::app()->user->checkAccess('batchUpdateRequest')) {
    Yii::app()->clientScript->registerScript('setusergroup', '
                                                    $("#setuser").click(function(){
                                                        var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0");
                                                        var count=checked.length;
                                                        if(count < 1){
                                                            swal(
                                                                " ' . Yii::t('main-ui', 'No items selected or selected only one item') . '",
                                                                "ERROR!",
                                                                "error");
                                                            } else {
                                                                jQuery("#myModalSetUser").modal({"show":true});
                                                            }
                                                        });

                                                        $("#setgroup").click(function(){
                                                            var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0");
                                                            var count=checked.length;
                                                            if(count < 1){
                                                                swal(
                                                                    " ' . Yii::t('main-ui', 'No items selected or selected only one item') . '",
                                                                    "ERROR!",
                                                                    "error");
                                                                } else {
                                                                    jQuery("#myModalSetGroup").modal({"show":true});
                                                                }
                                                            });

                                                            $("#btnSetUser").click(function(){
                                                                var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0");
                                                                var user=$("#users").val();
                                                                $.ajax({
                                                                    data:{checked:checked,user:user},
                                                                    url:"' . CHtml::normalizeUrl(array('Request/setUser')) . '",
                                                                    success:function (e) {
                                                                        jQuery("#myModalSetUser").modal("toggle");
                                                                        $("#request-grid-full2").yiiGridView("update",{});
                                                                    },
                                                                });
                                                            });

                                                            $("#btnSetGroup").click(function(){
                                                                var checked=$("#request-grid-full2").yiiGridView("getChecked","request-grid-full2_c0");
                                                                var group=$("#groups_id").val();
                                                                $.ajax({
                                                                    data:{checked:checked,group:group},
                                                                    url:"' . CHtml::normalizeUrl(array('Request/setGroup')) . '",
                                                                    success:function (e) {
                                                                        jQuery("#myModalSetGroup").modal("toggle");
                                                                        $("#request-grid-full2").yiiGridView("update",{});
                                                                    },
                                                                });
                                                            });
                                                            
                                                            $("#btnBatchComment").click(function(e){
                                                            if($("#batchCommentModal .star-rating").hasClass("star-rating-on")){
                                                            text = $("#comment").val();
                                                                if(text == ""){
                                                                e.preventDefault();
                                                                swal(
                                                                    "Вам необходимо добавить комментарий!",
                                                                    "ERROR!",
                                                                    "error");
                                                                } else {
                                                                e.preventDefault();
                                                                $("#btnBatchComment").prop("disabled", true);
                                                                jQuery.ajax({
                                                                        \'type\':\'POST\',
                                                                        \'url\': $("#url").val(),
                                                                        \'cache\':false,
                                                                        \'data\':$("#batchCommentForm").serialize(),
                                                                        \'success\':function(html){ 
                                                                            jQuery("#batchCommentModal").modal("toggle");  
                                                                            $("#request-grid-full2").yiiGridView("update",{});
                                                                        }
                                                                    });
                                                                } 
                                                            } else {
                                                            if ($("#batchCommentModal .modal-rating").is(":visible")){
                                                            swal(
                                                                "Вам необходимо поставить оценку!",
                                                                "ERROR!",
                                                                "error");
                                                            } else {
                                                            text = $("#comment").val();
                                                                if(text == ""){
                                                                e.preventDefault();
                                                                swal(
                                                                    "Вам необходимо добавить комментарий!",
                                                                    "ERROR!",
                                                                    "error");
                                                                } else {
                                                                e.preventDefault();
                                                                $("#btnBatchComment").prop("disabled", true);
                                                                jQuery.ajax({
                                                                        \'type\':\'POST\',
                                                                        \'url\': $("#url").val(),
                                                                        \'cache\':false,
                                                                        \'data\':$("#batchCommentForm").serialize(),
                                                                        \'success\':function(html){ 
                                                                            jQuery("#batchCommentModal").modal("toggle"); 
                                                                            $("#request-grid-full2").yiiGridView("update",{});
                                                                        }
                                                                    });
                                                                }
                                                            }
                                                            }
                                                                           
                                                            });
                                                    ');
}
?>
<?php if (Yii::app()->session['requestStopTimer'] == 1) {
    $timeout = (Yii::app()->params->update_grid_timeout) * 1000;
    Yii::app()->clientScript->registerScript('autoupdate-activations-application-grid',
        "setInterval(function(){;$.fn.yiiGridView.update('request-grid-full2');
        return false;}," . $timeout . ");");
}
?>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            var event = jQuery.Event("keydown");
            event.keyCode = 13;
            $('#RequestFullFields_id').trigger(event);
            return false;
        }, 1000);
    });

    $(function () {
        $('.carousel-button-left').click(function () {
            var leftPos = $('.box-body').scrollLeft();
            var opt = $(document).width();
            $(".box-body").animate({scrollLeft: leftPos - opt}, 500);
        });
        $('.carousel-button-right').click(function () {
            var opt = $(document).width();
            var leftPos = $('.box-body').scrollLeft();
            $(".box-body").animate({scrollLeft: leftPos + opt}, 500);
        });
    });
</script>
