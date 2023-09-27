<!--<link rel="stylesheet" href="/js/lobilist/dist/lobilist.min.css">-->
<!--<script src="/js/lobilist/dist/requests.js"></script>-->
<link rel="stylesheet" href="/css/custom.css">
<script src="/js/lobilist/lib/jquery/jquery-ui.min.js"></script>
<script src="/js/lobilist/lib/jquery/jquery.ui.touch-punch-improved.js"></script>
<script src="/js/lobilist/lib/highlight/highlight.pack.js"></script>

<?php
/* @var $this DefaultController */
$view = null;
$update = null;
$delete = null;

if (Yii::app()->user->checkAccess('viewRequest')) {
    $view = true;
}
if (Yii::app()->user->checkAccess('updateRequest')) {
    $update = true;
}
if (Yii::app()->user->checkAccess('deleteRequest')) {
    $delete = true;
}
$this->breadcrumbs=array(
    Yii::t('main-ui', 'Tickets') => array('/request'),
);
?>
<div class="page-header">
    <h3><i class="fa-solid fa-ticket fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage tickets'); ?></h3>
</div>
<div class="row-fluid">
    <div class="box">
        <div class="box-header">
            <ul id="yw0" class="nav nav-pills">
                <li><a href="/request"><i class="fa-solid fa-list-ul fa-xl"
                                            title="<?php echo Yii::t('main-ui', 'Tickets'); ?>"></i>
                    </a></li>
                <?php if (Yii::app()->user->checkAccess('createRequest')): ?>
                <li><a href="/request/create"><i class="fa-solid fa-circle-plus fa-xl"
                                                   title="<?php echo Yii::t('main-ui', 'Create ticket'); ?>"></i> </a>
                </li>
                <?php  endif; ?>
                <li><a class="carousel-button-left" href="javascript:void(0);"><i class="fa-solid fa-chevron-left fa-xl"
                                            title="<?php echo Yii::t('main-ui', 'Slide left'); ?>"></i>
                    </a></li>
                    <li><a class="carousel-button-right" href="javascript:void(0);"><i class="fa-solid fa-chevron-right fa-xl"
                                                title="<?php echo Yii::t('main-ui', 'Slide right'); ?>"></i>
                        </a></li>
            </ul>
        </div>
        <div class="box-body">
            <?php
            $criteria = new CDbCriteria();
            $criteria->order = 'sort_id ASC';
            $criteria->addNotInCondition('hide', [1 => 1], 'OR');
            $status_array = Status::model()->findAllByAttributes(array('enabled'=>'1'), $criteria);
            $statuses = $status_array;
            ?>
            <div class="flow-view flow-content top_bottom_scrollbar">
                <div class="flow-lists" style="width:<?php echo (!isset($statuses) ? 880 : round(count($statuses)*295, 2)); ?>px">
                    <ul style="margin-left: 0px" class="task-list ui-sortable" style="padding-left:15px!important;">
                        <?php
                        $user = CUsers::model()->findByAttributes(array('Username' => Yii::app()->user->name));
                        $list = array();
                        foreach ($statuses as $status){
                            $criteria = new CDbCriteria();
                            if (Yii::app()->user->checkAccess('viewMyAssignedRequest')) {
                                $criteria->compare('Managers_id', Yii::app()->user->name, true);
                                $criteria->addSearchCondition('CUsers_id', Yii::app()->user->name, false, 'OR', 'LIKE');
                                $criteria_grp = new CDbCriteria;
                                $criteria_grp->addCondition('find_in_set('.Yii::app()->user->id.', users)');
                                //$criteria_grp->compare('users', Yii::app()->user->id, true);
                                $grp = Groups::model()->findAll($criteria_grp);
                                $groups = array();
                                if (isset($grp) AND !empty($grp)) {
                                    foreach ($grp as $grpname) {
                                        $groups[] = $grpname->name;
                                    }
                                    $criteria->addInCondition('gfullname', array($groups), 'OR');
                                    $criteria->addInCondition('mfullname', array(null), 'OR');
                                }
                                $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                                $criteria->compare('pid', 0, false);
                            }
                            if (Yii::app()->user->checkAccess('viewAssignedRequest') AND !Yii::app()->user->checkAccess('viewCompanyRequest')) {
                                $criteria->compare('Managers_id', Yii::app()->user->name, false);
                                $criteria_grp = new CDbCriteria;
                                $criteria_grp->addCondition('find_in_set('.Yii::app()->user->id.', users)');
                                //$criteria_grp->compare('users', Yii::app()->user->id, true);
                                $grp = Groups::model()->findAll($criteria_grp);
                                foreach ($grp as $grpname) {
                                    $criteria->addSearchCondition('gfullname', $grpname->name, true, 'OR', 'LIKE');
                                    $criteria->addInCondition('mfullname', array(null), 'AND');
                                    $criteria->addSearchCondition('Managers_id', Yii::app()->user->name, false, 'OR', 'LIKE');

                                }
                                $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');

                            } else {
                                if (Yii::app()->user->checkAccess('viewCompanyRequest') AND !Yii::app()->user->checkAccess('viewAssignedRequest')) {
                                    $criteria->compare('Managers_id', $model->Managers_id);
                                    $companies = Companies::model()->findAllByAttributes(array('manager' => Yii::app()->user->name));
                                    if ($companies) {
                                        $criteria->compare('company', '000', true);
                                        foreach ($companies as $comps) {
                                            $criteria->addSearchCondition('company', $comps->name, false, 'OR', 'LIKE');
                                            $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                                        }
                                    } else {
                                        $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                                        $criteria->compare('company', '000', true);
                                    }
                                } else {
                                    if (Yii::app()->user->checkAccess('viewCompanyRequest') AND Yii::app()->user->checkAccess('viewAssignedRequest')) {
                                        $companies = Companies::model()->findAllByAttributes(array('manager' => Yii::app()->user->name));
                                        if ($companies) {
                                            $criteria->compare('company', '000', true);
                                            foreach ($companies as $comps) {
                                                $criteria->addSearchCondition('company', $comps->name, false, 'OR', 'LIKE');
                                                $criteria->addSearchCondition('Managers_id', Yii::app()->user->name, false, 'AND', 'LIKE');
                                            }
                                            $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                                            $criteria->addInCondition('Managers_id', array(null), 'OR');
                                        } else {
                                            $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                                            $criteria->compare('company', '000', true);
                                        }
                                    } else {
                                        $criteria->compare('Managers_id', $model->Managers_id);
                                    }
                                }
                            }
                            //Если текущий пользователь Админ или Пользователь, то выводим все или заявки созданные пользователем
                            if (!Yii::app()->user->checkAccess('viewMyselfRequest') AND !Yii::app()->user->checkAccess('viewMyCompanyRequest')) {
                                $criteria->compare('CUsers_id', $model->CUsers_id);
                            } else {
                                if (Yii::app()->user->checkAccess('viewMyCompanyRequest')) {
                                    $company = Companies::model()->findByAttributes(array('name' => $user->company));
                                    if ($company) {
                                        $criteria->addSearchCondition('company', $company->name, false, 'AND', 'LIKE');
                                        $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                                    }

                                } else {
                                    $criteria->compare('CUsers_id', Yii::app()->user->name);
                                    $criteria->addSearchCondition('watchers', $user->fullname, true, 'OR', 'LIKE');
                                }
                            }
                            $criteria->limit = Yii::app()->session['requestPageCount'] ? Yii::app()->session['requestPageCount'] : 30;
                            $criteria->order = 'sort_id DESC, lastactivity DESC';
                            $criteria->compare('Status', array($status->name));
                            $requests = Request::model()->findAll($criteria);
                            $count = count($requests);
                            $i = 0;
                            echo('
                            <li class="flow-list ui-sortable-handle" id="list_'.$status->id.'">
                            <div class="tasklist well" id="tasklist_'.$status->id.'">
                                <div class="tasklist_detail_'.$status->id.'">
                                    <div class="row-fluid">
                                        <div class="span10">
                                            <h4 style="margin:0;><a href="javascript::void(0)">'.$status->label.'</a>
                                            </h4>
                                           
                                        </div>
                                        <div class="span2">
                                            <div class="pull-right">
                                                <i class="fa-solid fa-chevron-right"></i> 
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <ul style="margin-left: 0px"
                                    class="list-tasks column_sortable flow_task_scrollbar ui-sortable"
                                    style="max-height:410px; list-style:none;" id="input-list-1">');
                            foreach ($requests as $request) {
                                if (empty($request->pid)) {
                                    echo('
                                <li class="flow_task atask" id="task_' . $request->id . '" data-cshared="false"
                                        style="position: relative; opacity: 1; left: 0px; top: 0px; list-style: none">
                                        <div class="row-fluid flow-task-text">
                                            <div class="span10">
                                                <span class="text-left">
                                                    <b style="font-size: 12px"><a href="/request/'.$request->id.'">' . $request->Name . '</a></b>');
                                                    if($request->service_id){
                                                        echo('<br><small><b>' . Yii::t('main-ui', 'Service') . '</b>: ' . $request->service_name . '</small>');
                                                    }
                                                    if($request->fullname){
                                                        echo('<br><small><b>' . Yii::t('main-ui', 'Customer') . '</b>: ' . $request->fullname . '</small>');
                                                    }
                                                    if($request->Managers_id){
                                                        echo('<br><small><b>' . Yii::t('main-ui', 'Manager') . '</b>: ' . $request->mfullname . '</small>');
                                                    }
                                                    if($request->Priority){
                                                        echo('<br><small><b>' . Yii::t('main-ui', 'Priority') . '</b>: ' . $request->Priority . '</small>');
                                                    }
                                                    echo('
                                                    <br><small><b>' . Yii::t('main-ui', 'Created') . '</b>: ' . $request->Date . '</small>
                                                    <br><small><b>' . Yii::t('main-ui', 'Deadline') . '</b>: ' . $request->EndTime . '</small>');
                                                    if($request->image || $request->files || $request->Comment || $request->child){
                                                        echo('<hr style="margin: 5px 0px 5px 0px">');
                                                    }
                                                    if($request->image || $request->files){
                                                        echo('<i class="fa-solid fa-paperclip"></i>');
                                                    }
                                                    if($request->Comment){
                                                        echo($request->Comment);
                                                    }
                                                    if($request->child){
                                                        echo($request->child);
                                                    }
                                                echo('</span>
                                            </div>

                                            <div class="span2">
                                                <div class="pull-right">
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-default btn-xs dropdown-toggle"
                                                                data-toggle="dropdown"><i class="icon icon-chevron-down"></i></button>
                                                        <ul class="dropdown-menu pull-right" role="menu">');
                                    if($view){
                                     echo ('<li><a href="/request/' . $request->id . '">' . Yii::t('main-ui', 'View') . '</a></li>');
                                    }
                                    if($update){
                                        echo ('<li><a href="/request/update/' . $request->id . '">' . Yii::t('main-ui', 'Edit') . '</a></li>');
                                    }

                                                        echo('</ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear: both;"></div>
                                    </li>
                                ');
                                }
                            }
                                if (isset($requests) OR empty($requests)) {
                                    echo('
                                <div style="clear: both;"></div>

                                <ul style="margin-left: 0px" class="list-tasks column_sortable flow_task_scrollbar ui-sortable"
                                    style="max-height:410px; list-style:none;" id="input-list-' . $status->id . '">
                                    <div class="empty_column focus_placeholder">Перетащите сюда</div>
                                </ul>
                                ');
                                }

                                echo('</ul>
                            </div>
                        </li>');

                        }

                        ?>
                    </ul>

                </div>
            </div>
            <div id="lobilist-demo"></div>
            <script>
                //$(function(){
                //    $('#lobilist-demo').lobiList({
                //        afterItemReorder: function (item, id) {
                //            var user="<?php //echo Yii::app()->user->name; ?>//";
                //            var csrf = "<?php //echo Yii::app()->request->csrfToken; ?>//";
                //            var checked = $(id).attr('data-id');
                //            var status= item.$el.find('.lobilist-title').attr('id');
                //            $.ajax({
                //                type: "GET",
                //                url: "/request/setstatusone",
                //                data: {'checked': checked, 'user': user, 'status': status, 'YII_CSRF_TOKEN': csrf},
                //                dataType: "text",
                //                cache: false,
                //                success: function() {
                //                    document.location.href = '/request/kanban';
                //                },
                //            });
                //        },
                //        lists: <?php //echo $new; ?>
                //    });
                //    $('.carousel-button-left').click(function() {
                //      var leftPos = $('#lobilist-demo').scrollLeft();
                //      var opt = $(document).width();
                //      $("#lobilist-demo").animate({scrollLeft: leftPos - opt}, 500);
                //    });
                //    $('.carousel-button-right').click(function() {
                //      var opt = $(document).width();
                //      var leftPos = $('#lobilist-demo').scrollLeft();
                //      $("#lobilist-demo").animate({scrollLeft: leftPos + opt}, 500);
                //    });
                //});
            </script>
            <?php if (Yii::app()->params->update_grid == 1) {
                $timeout = (Yii::app()->params->update_grid_timeout) * 1000;
                $id = Yii::app()->db->createCommand('SELECT id FROM request ORDER BY id DESC LIMIT 1')->queryScalar();
                $cid = Yii::app()->db->createCommand('SELECT id FROM comments ORDER BY id DESC LIMIT 1')->queryScalar();
                Yii::app()->clientScript->registerScript('autoupdate-items',
                    "setInterval(function(){
                    $.ajax({
                                type: 'GET',
                                url: '/request/checkupdates',
                                data: {id: ".(int)$id.", cid: ".(int)$cid."},
                                dataType: 'text',
                                cache: false,
                                success: function(data) {
                                    if(data == 'new'){
                                    location.reload();
                                    }
                                },
                            });
                    
                     return false
                     ;}," . $timeout . ");");
            }
            ?>
            <script>
                $(".list-tasks").sortable({
                    opacity: 0.8,
                    cursor: "move",
                    connectWith: ".column_sortable",
                    placeholder: "focus_placeholder",
                    start: function (c, a) {
                        item = a.item;
                        list = a.item.parent();
                        removed_from_id = list.attr("id");
                        add = "remove";
                    },
                    remove: function (c, a) {
                        item = a.item;
                        list = a.item.parent();
                        add = "add";
                    },
                    update: function (c, a) {
                        item = a.item;
                        list = a.item.parent();
                        var b = $(this).closest(".tasklist").attr("id").replace("tasklist_", "");
                        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
                        $.post("/request/move", {
                            items: $(this).sortable("serialize"),
                            tasklist_id: b,
                            add_or_remove: add,
                            YII_CSRF_TOKEN: csrf
                        }, function (data){
                            //console.log(data);
                            if(data == "false" ){
                                swal({
                                    title: "Внимание! Вы не можете устаналвивать данный статус!",
                                    type: "warning",
                                }).then(function (result) {
                                    location.reload();
                                });
                            }
                        });
                        b = $(this).closest(".tasklist").attr("id").replace("tasklist_", "");
                        0 === $("#input-list-" + b + " li").length ? $("#input-list-" + b + " .empty_column").length ? ($("#input-list-" + b + " .empty_column").html("Перетащите сюда"), $("#input-list-" + b + " .empty_column").show()) : $("#input-list-" + b).append('<div class="empty_column focus_placeholder">Перетащите сюда</div>') : ($("#input-list-" + b + " .empty_column").html(""), $("#input-list-" + b + " .empty_column").hide());
                    }
                });
                $(".task-list").sortable({
                    opacity: 0.8, cursor: "move", activate: function (c, a) {
                        a.item.addClass("focus_onmove");
                    }, update: function (c, a) {
                        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
                        $.post("/request/sort", {lists: $(this).sortable("serialize"), YII_CSRF_TOKEN: csrf});
                    }, stop: function (c, a) {
                        a.item.removeClass("focus_onmove");
                    }
                });
                $('.carousel-button-left').click(function() {
                     var leftPos = $('.flow-view').scrollLeft();
                     var opt = $(document).width();
                     $(".flow-view").animate({scrollLeft: leftPos - opt}, 500);
                   });
                $('.carousel-button-right').click(function() {
                     var opt = $(document).width();
                     var leftPos = $('.flow-view').scrollLeft();
                     $(".flow-view").animate({scrollLeft: leftPos + opt}, 500);
                   });


            </script>
        </div>
    </div>
</div>
