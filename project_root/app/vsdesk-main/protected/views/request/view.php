<?php
if ($model->closed == '3' and Yii::app()->params['nocomment'] == 1) {
    $no_comment_allowed = 1;
}
Yii::app()->clientScript->registerScript('search', "
     $('.search-button').click(function(){
                if(!$('.nav-tabs li:eq(0)').hasClass('active')){
                    var ptab = $('.nav-tabs').find('li.active a').attr('href');
                    $('.nav-tabs a:first').tab('show');
                    swal({
                        title: '" . Yii::t('main-ui', 'Do you want to add a reply?') . "',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '" . Yii::t('main-ui', 'Yes') . "',
                        cancelButtonText: '" . Yii::t('main-ui', 'No') . "',
                      }).then(function (result) {
                        if (result.value) {
                            $('.comment-form').show();
                            location.href = '#comment-form';
                        } else {
                            $('.nav-tabs a[href='+ptab+']').tab('show');
                        }
                      });
                }
                $('.comment-form').show();
                location.href = '#comment-form';
     });
 ");

if (isset($reaction) && !empty($reaction)) {
    Yii::app()->clientScript->registerScript('on_reaction', "
        $(function() {
            if ($('#reaction{$reaction}').length > 0){
                $('#reaction{$reaction}').click();
            }
        });
    ");
}
Yii::app()->clientScript->registerScript('need_comment', "
     $(\"[data-need_comment='1']\").click(function(e){
          e.preventDefault();
          e.stopPropagation();

          let event = $(this).data('event');
          let id = $(this).data('id');
          let reaction = $(this).data('reaction');

          let inputEvent = '<input type=\"hidden\" name=\"event\" value=\"' + event +  '\" />';
          $('#additem-form').append(inputEvent);
            
          let inputId = '<input type=\"hidden\" name=\"id\" value=\"' + id +  '\" />';
          $('#additem-form').append(inputId);
          
          let inputReaction = '<input type=\"hidden\" name=\"reaction\" value=\"' + reaction +  '\" />';
          $('#additem-form').append(inputReaction);

          $('#CommentStatus').hide();

          $('#Status_need_comment').html('<div class=\"alert in alert-block fade alert-error\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\">×</a><strong>Для смены статуса заявки, необходимо добавить комментарий!</strong></div>');
          $('.comment-form').show();
          location.href = '#comment-form';
     });
 ");
Yii::app()->clientScript->registerScript('need_rating', '
     $("#create_btn").click(function(e){
        if ($(".modal-rating").is(":visible")){
        if(!$(".modal-rating .star-rating").hasClass("star-rating-on")){
        e.preventDefault();
        swal(
            "Вам необходимо поставить оценку!",
            "ERROR!",
            "error");
        }
        text = $("#comment").val();
        if(text == ""){
        e.preventDefault();
        swal(
            "Вам необходимо добавить комментарий!",
            "ERROR!",
            "error");
        }
        }
    });
 ');
Yii::app()->clientScript->registerScript('set_rating', "
     $(\"[data-need_rating='1']\").click(function(e){
          e.preventDefault();
          e.stopPropagation();
          $('.modal-rating').show();
     });
 ");
Yii::app()->clientScript->registerScript('hide_rating', "
     $(\"[data-need_rating='0']\").click(function(e){
          e.stopPropagation();
          $('.modal-rating').hide();
     });
 ");
//if (Yii::app()->user->checkAccess('prevnextRequest')) {
//    $nop = Request::model()->getNextOrPrevId($model->id, 'next');
//    $next_id = $nop['next'];
//    $prev_id = $nop['prev'];
//}

$canAccept = false;
$canClose = false;
$role = Roles::model()->findByAttributes(array('value' => strtolower(Yii::app()->user->role)));
$status = CHtml::listData($role->status_rl, 'close', 'close');
foreach ($status as $key => $value) {
    if ($value == 2) {
        $canAccept = true;
    }
    if ($value == 3) {
        $canClose = true;
    }
}

$this->breadcrumbs = [
    Yii::t('main-ui', 'Tickets') => ['index'],
    $model->Name,
];

$this->menu = [
    [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'List tickets')]
    ],

    (Yii::app()->user->checkAccess('updateRequest') and empty($model->pid) and !Yii::app()->user->checkAccess('systemUser')) ? [
        'icon' => 'fa-solid fa-circle-plus fa-xl',
        'url' => ['addchild', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Add child ticket')]
    ] : [null],

    Yii::app()->user->checkAccess('updateRequest') ? [
        'icon' => 'fa-solid fa-pencil fa-xl',
        'url' => ['update', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Edit ticket')]
    ] : [null],

    (Yii::app()->user->checkAccess('canAddCommentsRequest') and $no_comment_allowed !== 1) ? [
        'icon' => 'fa-solid fa-reply fa-xl',
        'url' => 'javascript:void(0);',
        'itemOptions' => [
            'class' => 'search-button',
            'title' => Yii::t('main-ui', 'Add comment'),
        ],
    ] : [null],

    Yii::app()->user->checkAccess('createRequest') ? [
        'icon' => 'fa-solid fa-copy fa-xl',
        'url' => ['copy', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Copy request')]
    ] : [null],

    Yii::app()->user->checkAccess('canAssignRequest') ? [
        'icon' => 'fa-solid fa-user fa-xl',
        'url' => 'javascript:void(0);',
        'itemOptions' => [
            'data-toggle' => 'modal',
            'data-target' => '#myModal3',
            'title' => Yii::t('main-ui', 'Assign to user'),
        ],
    ] : [null],

    Yii::app()->user->checkAccess('canAssignRequest') ? [
        'icon' => 'fa-solid fa-users fa-xl',
        'url' => 'javascript:void(0);',
        'itemOptions' => [
            'data-toggle' => 'modal',
            'data-target' => '#myModal',
            'title' => Yii::t('main-ui', 'Assign to group of users'),
        ],
    ] : [null],

    // [
    //     'icon' => 'fa-solid fa-users fa-xl',
    //     'url' => '#',
    //     'itemOptions' => [
    //         'data-toggle' => 'modal',
    //         'data-target' => '#myModal4',
    //         'title' => Yii::t('main-ui', 'Send SMS to manager'),
    //     ],
    // ],
    // Yii::app()->user->checkAccess('canSmsRequest') ? [
    //     'icon' => 'comments 2x',
    //     'url' => '#',
    //     'itemOptions' => [
    //         'data-toggle' => 'modal',
    //         'data-target' => '#myModal4',
    //         'title' => Yii::t('main-ui', 'Send SMS to manager'),
    //     ],
    // ] : [null],

    Yii::app()->user->checkAccess('canArchiveRequest') ? [
        'icon' => 'archive 2x',
        'url' => ['archive', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Archive request')]
    ] : [null],

    ($canAccept and !isset($model->fStartTime)) ? [
        'icon' => 'fa-solid fa-hand fa-xl',
        'url' => ['injob', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'In job'), 'data-need_comment' => $inJobNeedComment, 'data-need_rating' => $inJobNeedRating, 'data-event' => 'injob', 'data-id' => $model->id]
    ] : [null],

    ($canClose and !isset($model->fEndTime)) ? [
        'icon' => 'fa-solid fa-circle-check fa-xl',
        'url' => ['inclose', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Close ticket'), 'data-need_comment' => $closeNeedComment, 'data-need_rating' => $closeNeedRating, 'data-event' => 'inclose', 'data-id' => $model->id]
    ] : [null],

    (Yii::app()->user->checkAccess('canSuspendRequest') and ($model->previous_paused_status_id == NULL) and !isset($model->fEndTime)) ? [
        'icon' => 'fa-solid fa-circle-pause fa-xl',
        'url' => ['suspend', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Suspend ticket'), 'data-need_comment' => $suspendNeedComment, 'data-need_rating' => $suspendNeedRating, 'data-event' => 'suspend', 'data-id' => $model->id]
    ] : [null],

    (Yii::app()->user->checkAccess('canSuspendRequest') and ($model->previous_paused_status_id !== NULL) and !isset($model->fEndTime)) ? [
        'icon' => 'fa-solid fa-circle-play fa-xl',
        'url' => ['suspend', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Resume ticket')]
    ] : NULL,

    Yii::app()->user->checkAccess('printRequest') ? [
        'icon' => 'fa-solid fa-print fa-xl',
        'url' => '#',
        'itemOptions' => [
            'title' => Yii::t('main-ui', 'Print ticket'),
            'data-toggle' => 'modal',
            'data-target' => '#myModal5'
        ],
        'linkOptions' => ['target' => '_BLANK']
    ] : [NULL],

//    (isset($next_id) AND $next_id !== NULL AND Yii::app()->user->checkAccess('prevnextRequest')) ? [
//        'icon' => 'fa-solid fa-chevron-left fa-xl',
//        'url' => '/request/' . $next_id,
//        'itemOptions' => ['title' => Yii::t('main-ui', 'Previous ticket'), 'disabled' => $next_id ? 0 : 1]
//    ] : NULL,
//
//    (isset($prev_id) AND $prev_id !== NULL AND Yii::app()->user->checkAccess('prevnextRequest')) ? [
//        'icon' => 'fa-solid fa-chevron-right fa-xl',
//        'url' => '/request/' . $prev_id,
//        'itemOptions' => ['title' => Yii::t('main-ui', 'Next ticket'), 'disabled' => $prev_id ? 0 : 1]
//    ] : NULL
];

if ($model->getMatchingIds()) {
    $matchingIds = $model->getMatchingNotCheckedIds();
    $isMatcher = in_array(Yii::app()->user->id, $matchingIds, false);

    if ($isMatcher) {
        $matchingMenu[] = [
            'icon' => 'fa-solid fa-thumbs-up fa-xl',
            'url' => ['request/reaction', 'id' => $model->id, 'reaction' => RequestMatchingReaction::REACTION_AGREED, 'user_id' => NULL],
            'itemOptions' => ['title' => Yii::t('main-ui', 'Approve'), 'id' => 'reaction' . RequestMatchingReaction::REACTION_AGREED,]
        ];

        $matchingMenu[] = [
            'icon' => 'fa-solid fa-thumbs-down fa-xl',
            'url' => ['request/reaction', 'id' => $model->id, 'reaction' => RequestMatchingReaction::REACTION_DENIED, 'user_id' => NULL],
            'itemOptions' => [
                'title' => Yii::t('main-ui', 'Deny'),
                'data-need_comment' => 1,
                'data-event' => 'reaction',
                'data-reaction' => RequestMatchingReaction::REACTION_DENIED,
                'data-id' => $model->id,
                'id' => 'reaction' . RequestMatchingReaction::REACTION_DENIED,
            ]
        ];

        $matchingMenu[] = [
            'icon' => 'fa-regular fa-circle-question fa-xl',
            'url' => ['request/reaction', 'id' => $model->id, 'reaction' => RequestMatchingReaction::REACTION_ADD_INFO, 'user_id' => NULL],
            'itemOptions' => [
                'title' => Yii::t('main-ui', 'Need more information'),
                'data-need_comment' => 1,
                'data-event' => 'reaction',
                'data-reaction' => RequestMatchingReaction::REACTION_ADD_INFO,
                'data-id' => $model->id,
                'id' => 'reaction' . RequestMatchingReaction::REACTION_ADD_INFO,
            ]
        ];

        $this->menu = array_merge($this->menu, $matchingMenu);
    }
}

?>

<div class="page-header">
	<div style="display: inline-block;"><h3>#<?php
            echo $model->id ?> "<?php
            if (!Yii::app()->user->checkAccess('viewMyselfRequest') and !Yii::app()->user->checkAccess('viewMyCompanyRequest')) {
                $this->widget(
                    'bootstrap.widgets.TbEditableField',
                    array(
                        'type' => 'text',
                        'mode' => 'inline',
                        'inputclass' => 'span11',
                        'model' => $model,
                        'attribute' => 'Name', // $model->name will be editable
                        'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken)),
                        'url' => $this->createUrl('updName', array('id' => $model->id)), //url for submit data
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    )
                );
            } else {
                echo $model->Name;
            }
            ?>"</h3>
	</div>
</div>
<div class="box">
	<div class="box-body">
        <?php
        $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php
        $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
		<br/>
        <?php
        $this->widget(
            'bootstrap.widgets.TbTabs',
            [
                'type' => 'tabs', // 'tabs' or 'pills'
                'encodeLabel' => false,
                'tabs' => array_filter([
                    [
                        'label' => Yii::t('main-ui', 'Description'),
                        'content' => $this->renderPartial('_view', [
                            'model' => $model,
                            'files' => $files,
                            'mphone' => $mphone,
                            'memail' => $memail,
                            'mposition' => $mposition,
                            'subs' => $subs
                        ], true),
                        'active' => true
                    ],
                    $unit ? [
                        'label' => Yii::t('main-ui', 'Assigned units'),
                        'content' => $this->renderPartial('_cunits', ['unit' => $unit], true)
                    ] : null,
                    $merged ? [
                        'label' => Yii::t('main-ui', 'Merged items') . ' ' . $model->child,
                        'content' => $this->renderPartial('_merged',
                            ['merged' => $merged, 'pid' => $model->id], true)
                    ] : null,
                    ($user and !Yii::app()->user->checkAccess('systemUser')) ? [
                        'label' => Yii::t('main-ui', 'Customer'),
                        'content' => $this->renderPartial('_user', ['user' => $user], true)
                    ] : null,
                    ($company and !Yii::app()->user->checkAccess('systemUser')) ? [
                        'label' => Yii::t('main-ui', 'Company'),
                        'content' => $this->renderPartial('_company', ['company' => $company, 'contracts' => $contracts], true)
                    ] : null,
                    Yii::app()->user->checkAccess('viewHistoryRequest') ? [
                        'label' => Yii::t('main-ui', 'Ticket history'),
                        'content' => $this->renderPartial('_history', ['history' => $history], true)
                    ] : null,
                    (Yii::app()->user->checkAccess('canStartTWSession') && (bool)Yii::app()->params['TeamViewerEnabled']) ? [
                        'label' => Yii::t('main-ui', 'TeamViewer'),
                        'content' => $this->renderPartial('_teamviewer', ['model' => $model], true)
                    ] : null,
                    (Yii::app()->user->checkAccess('viewCalls') && $call) ? [
                        'label' => Yii::t('main-ui', 'Call'),
                        'content' => $this->renderPartial('_call', ['model' => $call], true)
                    ] : null
                ]),
            ]
        ); ?>

	</div>
</div>
<?php
if (Yii::app()->user->checkAccess('canAssignRequest')): ?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'adduser-form2',
        'enableAjaxValidation' => false,
        'action' => Yii::app()->createUrl('/request/assignGroup', array('id' => $model->id)),
    )); ?>
    <?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4><?php
            echo Yii::t('main-ui', 'Выберите группу исполнителей'); ?></h4>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
            <?php
            $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'groups_id',
                    //'data' => CUsers::all(),
                    'data' => CHtml::listData(Groups::model()->findAll(), 'id', 'name'),
                    'htmlOptions' => array(
                        'class' => 'span12',
                    ),
                )
            ); ?>
		</div>
	</div>
	<div class="modal-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Assign'),
        )); ?>
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Cancel'),
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )); ?>
	</div>
    <?php
    $this->endWidget(); ?>
    <?php
    $this->endWidget(); ?>

    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'adduser-form3',
        'enableAjaxValidation' => false,
        'action' => Yii::app()->createUrl('/request/assign', array('id' => $model->id)),
    )); ?>
    <?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal3')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4><?php
            echo Yii::t('main-ui', 'Выберите исполнителя'); ?></h4>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
            <?php
            $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'users',
                    'data' => CUsers::all(),
                    'htmlOptions' => array(
                        'class' => 'span12',
                    ),
                )
            ); ?>
		</div>
	</div>

	<div class="modal-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Assign'),
        )); ?>
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Cancel'),
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )); ?>
	</div>
    <?php
    $this->endWidget(); ?>
    <?php
    $this->endWidget(); ?>
<?php
endif; ?>
<?php
// if (Yii::app()->user->checkAccess('canSmsRequest')): 
?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'senditem-form',
        'action' => Yii::app()->createUrl('/request/sendsms', array('id' => $model->id)),
    )); ?>
    <?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal4')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4><?php
            echo Yii::t('main-ui', 'Send SMS to manager'); ?></h4>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
            <?php
            $comment = new Comments();
            echo $form->textAreaRow($comment, 'comment', array('rows' => 6, 'cols' => 50, 'class' => 'span12'));
            ?>
		</div>
	</div>

	<div class="modal-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Send'),
        )); ?>
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Cancel'),
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )); ?>
	</div>
    <?php
    $this->endWidget(); ?>
    <?php
    $this->endWidget(); ?>
<?php
// endif; 
?>
<?php
if (Yii::app()->user->checkAccess('printRequest')): ?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'adduser-form4',
        'enableAjaxValidation' => false,
        'action' => Yii::app()->createUrl('/request/printform', array('id' => $model->id)),
    )); ?>
    <?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal5')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4><?php
            echo Yii::t('main-ui', 'Select print form template'); ?></h4>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
            <?php
            $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'template_id',
                    'data' => CHtml::listData(UnitTemplates::model()->findAllByAttributes(array('type' => 3)), 'id', 'name'),
                    'htmlOptions' => array(
                        'class' => 'span12',
                    ),
                )
            ); ?>
		</div>
	</div>

	<div class="modal-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Print'),
        )); ?>

        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Cancel'),
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )); ?>
	</div>
    <?php
    $this->endWidget(); ?>
    <?php
    $this->endWidget(); ?>
<?php
endif; ?>
