<!--<link rel="stylesheet" href="/js/lobilist/dist/lobilist.min.css">-->
<link rel="stylesheet" href="/css/custom.css">
<!--<script src="/js/lobilist/dist/lobilist.js"></script>-->
<script src="/js/lobilist/lib/jquery/jquery-ui.min.js"></script>
<script src="/js/lobilist/lib/jquery/jquery.ui.touch-punch-improved.js"></script>
<script src="/js/lobilist/lib/highlight/highlight.pack.js"></script>


<?php
/* @var $this DefaultController */

$this->breadcrumbs = array(
    Yii::t('main-ui', 'CRM') => array('/crm'),
    'Сделки',
);
?>
<h1>Сделки</h1>
<div class="row-fluid">
    <div class="box">
        <div class="box-header">
            <ul id="yw0" class="nav nav-pills">
                <li><a href="/crm/leads"><i class="fa-solid fa-list-ul fa-xl"
                                            title="<?php echo Yii::t('main-ui', 'Leads'); ?>"></i>
                    </a></li>
                <?php //if (Yii::app()->user->checkAccess('createRequest')): ?>
                <li><a href="/crm/leads/create"><i class="fa-solid fa-circle-plus fa-xl"
                                                   title="<?php echo Yii::t('main-ui', 'Create lead'); ?>"></i> </a>
                </li>
                <?php // endif; ?>

                <?php if (Yii::app()->user->checkAccess('batchUpdateStatusRequest')): ?>
                    <li><a href="/crm/pipeline"><i class="fa-solid fa-bookmark fa-xl"
                                                   title="<?php echo Yii::t('main-ui', 'Status'); ?>"></i>
                        </a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php
        $criteria = new CDbCriteria();
        $criteria->order = 'sort_id ASC';
        $statuses = Pipeline::model()->findAll($criteria);
        ?>
        <div class="box-body">
            <div class="flow-view flow-content top_bottom_scrollbar">
                <div class="flow-lists" style="width:<?php echo (!isset($statuses) ? 880 : round(count($statuses)*295, 2)); ?>px">
                    <ul style="margin-left: 0px" class="task-list ui-sortable" style="padding-left:15px!important;">
                        <?php
                        foreach ($statuses as $status){
                            $items = array();
                            $criteria2 = new CDbCriteria();
                            Yii::app()->session['LeadsPageCount'] ? Yii::app()->session['LeadsPageCount'] : 30;
                            $criteria2->order = 'sort_id ASC';
                            $requests = Leads::model()->findAllByAttributes(array('status_id' => $status->id), $criteria2);
                            $count = count($requests);
                            $i = 0;
                            foreach ($requests as $request){
                                $i = $i + $request->cost;
                            }
                            echo('
                            <li class="flow-list ui-sortable-handle" id="list_'.$status->id.'">
                            <div class="tasklist well" id="tasklist_'.$status->id.'">
                                <div class="tasklist_detail_'.$status->id.'">
                                    <div class="row-fluid">
                                        <div class="span10">
                                            <h4 style="margin:0;"><i class="fa-solid fa-chevron-right"></i> '.$status->label.' <small>('.$count.')</small>
                                            </h4>
                                           <h5 style="text-align: center">'.number_format($i, 2, ',', ' ').' рублей</h5>
                                        </div>
                                        <div class="span2">
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle"
                                                            data-toggle="dropdown"><i
                                                                class="icon icon-chevron-down"></i></button>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li><a href="/crm/pipeline/update/id/'.$status->id.'">'.Yii::t('main-ui','Edit').'</a></li>
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <ul style="margin-left: 0px"
                                    class="list-tasks column_sortable flow_task_scrollbar ui-sortable"
                                    style="max-height:410px; list-style:none;" id="input-list-1">');
                            foreach ($requests as $request){
                                $i = $i+$request->cost;
                                echo('
                                <li class="flow_task atask" id="task_'.$request->id.'" data-cshared="false"
                                        style="position: relative; opacity: 1; left: 0px; top: 0px; list-style: none">
                                        <div class="row-fluid flow-task-text">
                                            <div class="span10">
                                                <span class="text-left">
                                                    <b><a href="/crm/leads/update/id/'.$request->id.'">'.$request->name.'</a></b>
                                                    <p><small>'.Yii::t('main-ui', 'Customer'). ': '.$request->contact.'</small></p>
                                                    <b><small>'.date('d.m.Y', strtotime($request->created)).'</small></b><br>
                                                    <b>'.number_format($request->cost, 2, ',', ' ').' рублей</b>

                                                </span>
                                            </div>

                                            <div class="span2">
                                                <div class="pull-right">
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-default btn-xs dropdown-toggle"
                                                                data-toggle="dropdown"><i class="icon icon-chevron-down"></i></button>
                                                        <ul class="dropdown-menu pull-right" role="menu">
                                                            <li><a href="/crm/leads/update/id/'.$request->id.'">'.Yii::t('main-ui', 'Edit').'</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="clear: both;"></div>
                                    </li>
                                ');
                            }
                            if(isset($requests) OR empty($requests)){
                                echo('
                                <div style="clear: both;"></div>

                                <ul style="margin-left: 0px" class="list-tasks column_sortable flow_task_scrollbar ui-sortable"
                                    style="max-height:410px; list-style:none;" id="input-list-'.$status->id.'">


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
                        $.post("/crm/pipeline/move", {
                            items: $(this).sortable("serialize"),
                            tasklist_id: b,
                            add_or_remove: add,
                            YII_CSRF_TOKEN: csrf
                        },function (data) {
                            //console.log(data);
                            location.reload();
                        });
                    }
                });
                $(".task-list").sortable({
                    opacity: 0.8, cursor: "move", activate: function (c, a) {
                        a.item.addClass("focus_onmove");
                    }, update: function (c, a) {
                        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
                        $.post("/crm/pipeline/sort", {lists: $(this).sortable("serialize"), YII_CSRF_TOKEN: csrf});
                    }, stop: function (c, a) {
                        a.item.removeClass("focus_onmove");
                    }
                });


            </script>


        </div>
    </div>
</div>

