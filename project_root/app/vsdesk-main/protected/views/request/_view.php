<?php


Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');
if ($model->closed == '3' and Yii::app()->params['nocomment'] == 1) {
    $no_comment_allowed = 1;
}
?>
<style>
    .redactor-in {
        padding: 24px;
        border: 1px dashed rgba(0, 0, 0, .15);
        border-top: none;
        background: #f6f9fe;
    }
</style>
<?php
if (Yii::app()->user->checkAccess('canEditCommentsRequest')): ?>
    <?php
    Yii::app()->clientScript->registerScript('redactor-init', "
                     function sendSave(id, html) {
                        var csrf = '" . Yii::app()->request->csrfToken . "';
                        $.ajax({
                            type: 'POST',
                            url: '" . Yii::app()->createUrl('comment/inline') . "',
                            data: {'text': html, 'id': id, 'YII_CSRF_TOKEN': csrf},
                            dataType: 'text',
                            cache: false,
                        });
                     }
                     function redactorInit(id) {
                        $('#redactor'+id).redactor({
                            lang: 'ru',
                            imageResizable: true,
                            imagePosition: true,
                            plugins: ['video'],
                            clickToEdit: true,
                            clickToCancel: '#btn-cancel'+id,
                            clickToSave: '#btn-save'+id,
                            callbacks: {
                                clickSave: function(html)
                                {
                                    sendSave(id, html);
                                }
                            }
                        });
                     }
                     $(function () {

                        });
                    ");
    ?>
<?php
endif; ?>
<?php
if (Yii::app()->user->checkAccess('canEditContent')): ?>
    <?php
    Yii::app()->clientScript->registerScript('content-init', "
                     function sendSaveR(html) {
                        var csrf = '" . Yii::app()->request->csrfToken . "';
                        var id = '" . $model->id . "';
                        $.ajax({
                            type: 'POST',
                            url: '" . Yii::app()->createUrl('request/inline') . "',
                            data: {'text': html, 'id': id, 'YII_CSRF_TOKEN': csrf},
                            dataType: 'text',
                            cache: false,
                        });
                     }
                     $(function () {
                        $('#req_content').redactor({
                          lang: 'ru',
                          imageResizable: true,
                          imagePosition: true,
                          plugins: ['alignment', 'table','fullscreen', 'video'],
                          clickToEdit: true,
                          clickToCancel: '#btnc-cancel',
                          clickToSave: '#btnc-save',
                          imageResizable: true,
                          linkValidation: false,
                          linkSize: 200,
                          imagePosition: true,
                          callbacks: {
                            clickSave: function(html)
                            {
                                sendSaveR(html);
                            }
                          }
                        });
                     })
                     $(function () {

                        });
                    ");
    ?>
<?php
endif; ?>
<div class="view">
	<div style="display: inline-block;">
        <?php
        echo $model->slabel; ?>&nbsp;&nbsp;&nbsp;
	</div>
	<div style="display: inline-block;">
        <?php
        // if (!Yii::app()->user->checkAccess('systemManager') OR $model->CUsers_id == Yii::app()->user->name) {
        $this->widget('CStarRating', array(
            'name' => 'star_rating_widget',
            'minRating' => '1',
            'maxRating' => '5',
            'value' => $model->rating, // mark 1...5
            'starWidth' => '20',
            'ratingStepSize' => '1',
            'allowEmpty' => false,
            'readOnly' => Yii::app()->user->checkAccess('systemManager') ? true : false,
            'callback' => '
    function(){
    var csrf = "' . Yii::app()->request->csrfToken . '";
    var id = "' . $model->id . '";
        $.ajax({
            type: "GET",
            url: "' . Yii::app()->createUrl('request/rating') . '",
            data: {"id":id, "star_rating":$(this).val(), "YII_CSRF_TOKEN": csrf},
        }
    )}',
        ));
        //  }
        ?>
	</div>
	<div class="row-fluid">
		<div class="span4">
			<i class='<?php
            echo $model->channel_icon; ?>'>&nbsp;</i><b><?php
                echo Yii::t('main-ui', $model->channel); ?></b>
			<br/>
            <?php
            if (Yii::app()->user->checkAccess('updateLeadRequest') and ($model->lead_time)): ?>
				<span class="fa-regular fa-calendar-days">&nbsp;</span><b><?php
                    echo CHtml::encode($model->getAttributeLabel('leadTimeEx')); ?>:</b>
                <?php
                $this->widget(
                    'bootstrap.widgets.TbEditableField',
                    array(
                        'type' => 'text',
                        'mode' => 'inline',
                        'model' => $model,
                        'attribute' => 'lead_time',
                        // $model->name will be editable
                        'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken)),
                        'url' => $this->createUrl('updStartTime', array('id' => $model->id)),
                        //url for submit data
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    )
                ); ?><br/>
            <?php
            else: ?>
				<span class="fa-regular fa-calendar-days">&nbsp;</span><b><?php
                    echo CHtml::encode($model->getAttributeLabel('leadTimeEx')); ?>:</b>
                <?php
                echo CHtml::encode($model->leadTimeEx); ?>
				<br/>
            <?php
            endif; ?>
			<span class="fa-regular fa-calendar-days">&nbsp;</span><b><?php
                echo CHtml::encode($model->getAttributeLabel('Date')); ?>:</b>
            <?php
            echo CHtml::encode($model->Date); ?>
            <?php
            if ($model->paused): ?>
				<br><span class="fa-regular fa-calendar-days">&nbsp;</span><b><?php
                    echo CHtml::encode($model->getAttributeLabel('paused')); ?>:</b>
                <?php
                echo date('d.m.Y H:i', strtotime($model->paused)); ?>
            <?php
            endif; ?>
			<br/>
            <?php
            if ($model->StartTime): ?>
                <?php
                if (Yii::app()->user->checkAccess('updateDatesRequest')): ?>
					<span class="fa-solid fa-clock"></span><strong> <?php
                        echo Yii::t('main-ui', 'Start Time'); ?>:</strong>
                    <?php
                    $this->widget('editable.EditableField', array(
                        'type' => 'datetime',
                        'model' => $model,
                        'attribute' => 'StartTime',
                        'language' => 'ru',
                        'url' => $this->createUrl('updStartTime', array('id' => $model->id)),
                        'placement' => 'right',
                        'format' => 'dd.mm.yyyy hh:ii', //database datetime format
                        'viewformat' => 'dd.mm.yyyy hh:ii', //format for display
                        'options' => array('disabled' => Yii::app()->user->checkAccess('canEditRequestPlanStart') ? false : true, 'params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken), 'datetimepicker' => array('language' => 'ru', 'weekStart' => 1)),
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    ));
                    ?>
					<br/>
                <?php
                else: ?>
					<span class="fa-solid fa-clock"></span><strong> <?php
                        echo Yii::t('main-ui', 'Start Time'); ?>:</strong>
                    <?php
                    echo $model->StartTime; ?>
					<br/>
                <?php
                endif; ?>
            <?php
            endif; ?>
            <?php
            if ($model->EndTime): ?>
                <?php
                if (Yii::app()->user->checkAccess('updateDatesRequest')): ?>
					<span class="fa-solid fa-clock"></span><strong> <?php
                        echo Yii::t('main-ui', 'End Time'); ?>:</strong>
                    <?php
                    $this->widget('editable.EditableField', array(
                        'type' => 'datetime',
                        'model' => $model,
                        'attribute' => 'EndTime',
                        'language' => 'ru',
                        'url' => $this->createUrl('updStartTime', array('id' => $model->id)),
                        'placement' => 'right',
                        'format' => 'dd.mm.yyyy hh:ii', //database datetime format
                        'viewformat' => 'dd.mm.yyyy hh:ii', //format for display
                        'options' => array('disabled' => Yii::app()->user->checkAccess('canEditRequestPlanEnd') ? false : true, 'params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken), 'datetimepicker' => array('language' => 'ru', 'weekStart' => 1)),
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    ));
                    ?>
					<br/>
                <?php
                else: ?>
					<span class="fa-solid fa-clock"></span><strong> <?php
                        echo Yii::t('main-ui', 'End Time'); ?>:</strong>
                    <?php
                    echo $model->EndTime; ?>
					<br/>
                <?php
                endif; ?>
            <?php
            endif; ?>
            <?php
            if ($model->fStartTime): ?>
                <?php
                if (Yii::app()->user->checkAccess('updateDatesRequest')): ?>
					<span class="fa-solid fa-clock"></span><strong> <?php
                        echo Yii::t('main-ui', 'Fact Start time'); ?>:</strong>
                    <?php
                    $this->widget('editable.EditableField', array(
                        'type' => 'datetime',
                        'model' => $model,
                        'attribute' => 'fStartTime',
                        'language' => 'ru',
                        'url' => $this->createUrl('updStartTime', array('id' => $model->id)),
                        'placement' => 'right',
                        'format' => 'dd.mm.yyyy hh:ii', //database datetime format
                        'viewformat' => 'dd.mm.yyyy hh:ii', //format for display
                        'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken), 'datetimepicker' => array('language' => 'ru', 'weekStart' => 1)),
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    ));
                    ?> <br/>
                <?php
                else: ?>
					<span class="fa-solid fa-clock"></span><strong> <?php
                        echo Yii::t('main-ui', 'Fact Start time'); ?>:</strong>
                    <?php
                    echo $model->fStartTime; ?>
					<br/>
                <?php
                endif; ?>
            <?php
            endif; ?>

            <?php
            if ($model->fEndTime): ?>
                <?php
                if (Yii::app()->user->checkAccess('updateDatesRequest')): ?>
					<span class="fa-solid fa-clock"></span><strong> <?php
                        echo Yii::t('main-ui', 'Fact End Time'); ?>:</strong>
                    <?php
                    $this->widget('editable.EditableField', array(
                        'type' => 'datetime',
                        'model' => $model,
                        'attribute' => 'fEndTime',
                        'language' => 'ru',
                        'url' => $this->createUrl('updStartTime', array('id' => $model->id)),
                        'placement' => 'right',
                        'format' => 'dd.mm.yyyy hh:ii', //database datetime format
                        'viewformat' => 'dd.mm.yyyy hh:ii', //format for display
                        'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken), 'datetimepicker' => array('language' => 'ru', 'weekStart' => 1)),
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    ));
                    ?>
					<br/>
                <?php
                else: ?>
					<span class="fa-solid fa-clock"></span><strong> <?php
                        echo Yii::t('main-ui', 'Fact End Time'); ?>:</strong>
                    <?php
                    echo $model->fEndTime; ?>
					<br/>
                <?php
                endif; ?>
            <?php
            endif; ?>

            <?php
            if (Yii::app()->user->checkAccess('updateRequest')): ?>
				<span class="fa-solid fa-inbox"></span><strong> <?php
                    echo Yii::t('main-ui', 'Category'); ?>:</strong>
                <?php
                $this->widget(
                    'bootstrap.widgets.TbEditableField',
                    array(
                        'type' => 'select',
                        'mode' => 'inline',
                        'emptytext' => Yii::t('main-ui', 'Not set'),
                        'inputclass' => 'input-big',
                        'source' => Category::all(),
                        'model' => $model,
                        'attribute' => 'ZayavCategory_id',
                        'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken)),
                        'url' => $this->createUrl('updCategory', array('id' => $model->id)), //url for submit data
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    )
                ); ?>
				<br>
            <?php
            else: ?>
				<span class="fa-solid fa-inbox"></span><b><?php
                    echo CHtml::encode($model->getAttributeLabel('ZayavCategory_id')); ?>:</b>
                <?php
                echo CHtml::encode($model->ZayavCategory_id); ?>
				<br/>
            <?php
            endif; ?>

			<span class="fa-solid fa-heart-pulse"></span>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('Priority')); ?>:</b>
            <?php
            echo CHtml::encode($model->Priority); ?>
			<br/>
            <?php
            if ($model->service_name): ?>
				<span class="fa-solid fa-layer-group"></span>&nbsp;<b><?php
                    echo CHtml::encode($model->getAttributeLabel('service_name')); ?>:</b>
                <?php
                echo CHtml::encode($model->service_name); ?>
				<br/>
            <?php
            endif; ?>
            <?php
            if (Yii::app()->user->checkAccess('canSetUnitRequest')): ?>
				<span class="fa-solid fa-computer"></span><strong> <?php
                    echo Yii::t('main-ui', 'Configuration units'); ?>:</strong> <?php
                $this->widget(
                    'bootstrap.widgets.TbEditableField',
                    array(
                        'type' => 'select2',
                        'mode' => 'inline',
                        'model' => $model,
                        'emptytext' => Yii::t('main-ui', 'Not set'),
                        'select2' => array(
                            'tags' => Yii::app()->user->checkAccess('unitByUserRequest') ? Cunits::uuall($model->CUsers_id) : Cunits::auall(),
                            'tokenSeparators' => array(','),
                            'width' => '250px'
                        ),
                        'attribute' => 'cunits', // $model->name will be editable
                        'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken)),
                        'url' => $this->createUrl('updUnits', array('id' => $model->id)), //url for submit data
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    )
                ); ?><br/>
            <?php
            endif; ?>
            <?php
            if (Yii::app()->user->checkAccess('canSetUnitRequest')): ?>
				<span class="fa-solid fa-computer"></span><strong> <?php
                    echo Yii::t('main-ui', 'Tcategorization'); ?>:</strong> <?php
                $this->widget(
                    'bootstrap.widgets.TbEditableField',
                    array(
                        'type' => 'select',
                        // 'multiple' => false,
                        'mode' => 'inline',
                        'model' => $model,
                        'emptytext' => Yii::t('main-ui', 'Not set'),
                        'inputclass' => 'input-big',
                        'source' => Tcategory::allTree(),
//                        'select2' => array(
//                            'width' => '300px',
//                        ),
                        'select2' => array(
                            'tags' => Yii::app()->user->checkAccess('unitByUserRequest') ? Tcategory::uuall($model->CUsers_id) : Tcategory::auall(),
                            'tokenSeparators' => array(','),
                            'width' => '250px'
                        ),
                        'attribute' => 'tcategory', // $model->name will be editable
                        'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken)),
                        'url' => $this->createUrl('updTcategory', array('id' => $model->id)), //url for submit data
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    )
                ); ?><br/>
            <?php
            endif; ?>
		</div>

		<div class="span4">

            <?php
            if ($model->getMatchingNames()):

                $stats = RequestMatchingReaction::getRequestStats($model);

                $icons = [
                    0 => '',
                    1 => 'fa-solid fa-thumbs-up',
                    2 => 'fa-solid fa-thumbs-down',
                    3 => 'fa-solid fa-circle-question',
                ];
                $all = [];
                foreach ($stats['matching'] as $key => $matchings) {
                    foreach ($matchings as $matching) {
                        $all[] = "$matching <span class=\"{$icons[$key]}\"></span>";
                    }
                }
                ?>
				<i class="fa-solid fa-thumbs-up"></i>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('matchings')); ?>:</b>
                <?php
                echo implode(', ', $all); ?> &nbsp; &nbsp; <?php
                echo "{$stats['total']['checked']}/{$stats['total']['all']}" ?>
				<br/>
            <?php
            endif; ?>

			<span class="fa-solid fa-user"></span>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('creator')); ?>:</b>
            <?php
            echo CHtml::encode($model->creator); ?>
			<br/>
            <?php
            if (Yii::app()->user->checkAccess('canSetObserversRequest')): ?>
                <?php
                if (Yii::app()->user->checkAccess('systemUser')) {
                    $watchers_request = CUsers::model()->d_all();
                } else {
                    $watchers_request = CUsers::model()->w2all();
                } ?>
				<span class="fa-solid fa-binoculars"></span><strong> <?php
                    echo Yii::t('main-ui', 'Observers'); ?>:</strong> <?php
                $this->widget(
                    'bootstrap.widgets.TbEditableField',
                    array(
                        'type' => 'select2',
                        'mode' => 'inline',
                        'model' => $model,
                        'emptytext' => Yii::t('main-ui', 'Not set'),
                        'select2' => array(
                            'tags' => $watchers_request,
                            'multiple' => 'multiple',
                            'tokenSeparators' => array(','),
                            'width' => '250px'
                        ),
                        'attribute' => 'watchers', // $model->name will be editable
                        'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken)),
                        'url' => $this->createUrl('updWatchers', array('id' => $model->id)), //url for submit data
                        'success' => 'js: function(data) {
                                    location.reload();
                                    }'
                    )
                ); ?><br/>
            <?php
            else: ?>
                <?php
                if ($model->watchers): ?><span class="fa-solid fa-binoculars"></span><strong> <?php
                    echo Yii::t('main-ui',
                        'Observers'); ?>:</strong>
                    <?php
                    echo $model->watchers; ?><br/><?php
                endif; ?>
            <?php
            endif; ?>
            <?php
            if ($model->mfullname): ?>

				<span class="fa-solid fa-person"></span>&nbsp;<b><?php
                    echo CHtml::encode($model->getAttributeLabel('Managers_id')); ?>:</b>
                <?php
                echo CHtml::encode($model->mfullname); ?>
                <?php
                $contractor = CUsers::model()->findByAttributes(['fullname' => $model->mfullname]); ?>
				<a id="gotouserikt" title="<?php
                echo Yii::t('main-ui', 'Go to user IKT'); ?>" target="_blank" href="<?php
                echo Yii::app()->createUrl('cusers/' . $contractor->id); ?>"><i class="fa-solid fa-magnifying-glass"></i></a>
				<br/>
            <?php
            endif; ?>
            <?php
            if (!$model->mfullname): ?>
				<span class="fa-solid fa-folder-tree "></span>&nbsp;<b><?php
                    echo CHtml::encode($model->getAttributeLabel('gfullname')); ?>:</b>
                <?php
                echo CHtml::encode($model->gfullname); ?>
				<br/>
            <?php
            endif; ?>
            <?php
            if (!Yii::app()->user->checkAccess('systemUser')): ?>
                <?php
            if ($model->depart): ?>
				<span class="fa-solid fa-user-group"></span>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('depart')); ?>:</b>
                <?php
                echo CHtml::encode($model->depart); ?>
			<br/>
            <?php
            endif; ?>
            <?php
            if ($model->CUsers_id): ?>
				<span class="fa-solid fa-user"></span><strong> <?php
                echo Yii::t('main-ui', 'User IKT'); ?>:</strong>

                <?php
            if (Yii::app()->user->checkaccess('canChangeUser')): ?>

                <?php
                $manager = CUsers::model()->findByAttributes(['fullname' => $model->fullname]); ?>
                <?php
                echo $model->fullname; ?>
				<a id="gotouserikt" title="<?php
                echo Yii::t('main-ui', 'Go to user IKT'); ?>" target="_blank" href="<?php
                echo Yii::app()->createUrl('cusers/' . $manager->id); ?>"><i class="fa-solid fa-magnifying-glass"></i></a>
				<a id="selectuser" href="javascript:void(0);" onclick="jQuery('#selectModal').modal({'show':true});" title="<?php
                echo Yii::t('main-ui', 'Select user IKT'); ?>"><i class="fa-solid fa-users"></i></a>


            <?php
            endif; ?>
			<br/>
            <?php
            else: ?>
				<span class="fa-solid fa-user"></span><strong> <?php
                echo Yii::t('main-ui', 'Customer'); ?>:</strong>
				<span id="CUsers_fullname"><?php
                    echo $model->fullname; ?></span>
                <?php
            if (Yii::app()->user->checkaccess('canChangeUser')): ?>
				&nbsp;&nbsp;<a id="selectuser" href="javascript:void(0);" onclick="jQuery('#selectModal').modal({'show':true});" title="<?php
            echo Yii::t('main-ui', 'Select user'); ?>"><i class="fa-solid fa-users"></i></a>
            <?php
            endif; ?>
                <?php
            if (Yii::app()->user->checkAccess('createUser')): ?>&nbsp;&nbsp;<a id="adduser" href="javascript:void(0);" onclick="jQuery('#addModal').modal({'show':true});" title="<?php
            echo Yii::t('main-ui', 'Add user'); ?>"><i class="fa-solid fa-user-plus"></i></a><?php
            endif; ?>&nbsp;&nbsp;<?php
                if ((Yii::app()->user->checkAccess('systemAdmin') and $model->channel == 'Email') or (Yii::app()->user->checkAccess('systemManager') and $model->channel == 'Email')): ?><a id="addban" href="javascript:void(0);" title="Ban Email"><i class="fa-solid fa-lock"></i></a><?php
                endif; ?><br/>
            <?php
            endif; ?>
            <?php
            if ($model->phone): ?>
				<span class="fa-solid fa-phone"></span>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('phone')); ?>:</b>
            <?php
            if (Asterisk::isEnabled() && Yii::app()->user->checkAccess('amiCalls')) {
            ?>
				<script>
					function call(phone) {
						var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
						$.ajax({
							type:     'POST',
							url:      '/cusers/call',
							data:     {'number': phone, 'YII_CSRF_TOKEN': csrf},
							dataType: 'text',
							cache:    false,
							error:    function (e) {
								console.log(e);
							},
							success:  function (data) {
								console.log(data);
							}
						});
					}
				</script>
            <?php
            echo $model->phone . ' <a onClick="call(' . $model->phone . ');return false" href="/cusers/call" target="_blank"><i class="fa-solid fa-phone"></i></a>';
            } else {
                echo '<a href="tel:' . $model->phone . '" >' . $model->phone . '</a>';
            }
            ?>
			<br>
            <?php
            endif; ?>
            <?php
            if ($model->room): ?>
				<span class="fa-solid fa-building"></span>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('room')); ?>:</b>
                <?php
                echo CHtml::encode($model->room); ?>
			<br/>
            <?php
            endif; ?>
            <?php
            if ($model->Address): ?>
				<span class="fa-solid fa-location-dot"></span>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('Address')); ?>:</b>
                <?php
                echo CHtml::encode($model->Address); ?>
			<br/>
            <?php
            endif; ?>
            <?php
            if ($model->company): ?>
				<span class="fa-solid fa-briefcase"></span>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('company')); ?>:</b>
                <?php
                echo CHtml::encode($model->company); ?>
			<br/>
            <?php
            endif; ?>
            <?php
            else: ?>
            <?php
            if ($mposition): ?>
				<span class="fa-solid fa-flag"></span>&nbsp;<b><?php
                echo CHtml::encode(Yii::t('main-ui', 'Position')); ?>:</b>
                <?php
                echo $mposition; ?>
			<br/>
            <?php
            endif; ?>
                <?php
            if ($mphone): ?>
				<span class="fa-solid fa-phone"></span>&nbsp;<b><?php
                echo CHtml::encode($model->getAttributeLabel('phone')); ?>:</b>
                <?php
                echo '<a href="tel:' . $mphone . '" >' . $mphone . '</a>'; ?>
			<br/>
            <?php
            endif; ?>
                <?php
            if ($memail): ?>
				<span class="fa-solid fa-envelope"></span>&nbsp;<b><?php
                echo CHtml::encode(Yii::t('main-ui', 'Email')); ?>:</b>
                <?php
                echo '<a href="mailto:' . $memail . '">' . $memail . '</a>'; ?>
			<br/>
            <?php
            endif; ?>
            <?php
            endif; ?>
		</div>
        <?php
        if (isset($model->flds) and Yii::app()->user->checkAccess('canSetFieldsRequest')): ?>
			<div class="span4">
                <?php
                foreach ($model->flds as $field) {
                    if ($field->type == 'textFieldRow' and $field->value) {
                        echo '<span class="fa-regular fa-file"></span>&nbsp;<b>' . $field->name . ': </b>' . $field->value . '<br/>';
                    }
                    if ($field->type == 'date' and $field->value) {
                        echo '<span class="fa-regular fa-calendar-days"></span>&nbsp;<b>' . $field->name . ': </b>' . $field->value . '<br/>';
                    }
                    if ($field->type == 'toggle') {
                        echo '<span class="fa-solid fa-circle-check"></span>&nbsp;<b>' . $field->name . ': </b>' . ($field->value == 1 ? Yii::t('main-ui',
                                'Yes') : Yii::t('main-ui', 'No')) . '<br/>';
                    }
                    if ($field->type == 'select' and $field->value) {
                        echo '<span class="fa-regular fa-file"></span>&nbsp;<b>' . $field->name . ': </b>' . $field->value . '<br/>';
                    }
                } ?>
			</div>
        <?php
        endif; ?>
	</div>
    <?php
    if (Yii::app()->user->checkAccess('canChangeChecklist') and !empty($model->checklistFields)): ?>
		<div class="row-fluid">
			<div class="span12">
                <?php
                $this->widget('application.extensions.checklist.ChecklistWidget', [
                    'request_id' => $model->id
                ]);
                ?>
			</div>
		</div>
    <?php
    endif; ?>
	<hr>
	<div class="row-fluid">
		<div class="span12">
			<div class="with-border">
				<h3><?php
                    echo CHtml::encode($model->getAttributeLabel('Content')); ?>:</h3>
			</div>
			<div class="mailbox-read-message">
                <?php
                if (Yii::app()->user->checkAccess('canEditContent')): ?>
					<div id="req_content"><?php
                        echo $model->Content; ?></div>
					<br>
					<p>
						<button id="btnc-save" class='btn btn-info btn-small' style="display: none;" outline>
                            <?php
                            echo Yii::t('main-ui', 'Save'); ?>
						</button>
						<button id="btnc-cancel" class='btn btn-danger btn-small' style="display: none;" outline>
                            <?php
                            echo Yii::t('main-ui', 'Cancel'); ?>
						</button>
					</p>
                <?php
                else: ?>
                    <?php
                    echo $model->Content; ?>
                <?php
                endif; ?>
			</div>
			<br/>
            <?php
            if ($model->files) {
                FilesShow::show($model->files, 'request', '/uploads', '', 'Request');
            }
            ?>
            <?php
            if ($model->image): ?>
                <?php
                FilesShow::show($files, 'request', '/media/', $model->id, 'Request');
                ?>
            <?php
            endif; ?>

			<div class="comment-form" id="comment-form" style="display:none">
                <?php
                if ($no_comment_allowed !== 1) {
                    $this->renderPartial('_comment', array(
                        'model' => $model,
                    ));
                }
                ?>
				<br/>
			</div><!-- comment-form -->
		</div>
	</div>

    <?php
    $knowledgeResults = Knowledge::model()->searchSame($model->Name, $model->Content);
    $showKnowledge = $model->isNew() && $knowledgeResults && isset($knowledgeResults->data) && count($knowledgeResults->data);
    ?>

    <?php
    if ($showKnowledge): ?>
		<div class="box box-default collapsed-box">
			<div class="box-header with-border">
				<h3 class="box-title">Подходящие записи <a href="/knowledge/module/" target="_blank">Базы знаний</a></h3>
				<div class="box-tools pull-right">
					<button class="btn btn-primary btn-box-tool white" style="color: white" data-widget="collapse">
						<i class="fa fa-plus"></i>
					</button>
				</div>
			</div>
			<div class="box-body">
				<ul>
                    <?php
                    foreach ($knowledgeResults->data as $item): ?>
						<li>
							<a href="/knowledge/module/view/id/<?= $item->id ?>">
                                <?= $item->name ?>
							</a>
						</li>
                    <?php
                    endforeach ?>
				</ul>
			</div>
		</div>
    <?php
    endif; ?>

    <?php
    if (Yii::app()->user->checkAccess('canEditCommentsRequest') and Yii::app()->session['requestStopTimer'] !== 1): ?>
        <?php
        $rd = null;
        if (!empty($model->comms)) {
            foreach ($subs->getData() as $sub) {
                $rd .= "redactorInit(" . $sub->id . "); ";
            }
        }
        ?>
        <?php
        Yii::app()->clientScript->registerScript('redactors-init', "$(function () { " . $rd . " });");
        ?>
    <?php
    endif; ?>
    <?php
    if (!empty($model->comms)): ?>
		<div class="box-footer">
			<!-- row -->
			<div class="row-fluid">
				<div class="col-md-12">
					<!-- The time line -->
					<ul class="timeline">
                        <?php
                        $this->widget('application.extensions.comments.CCommentsList', array(
                            'dataProvider' => $subs,
                            'itemView' => '_comments',
                            'ajaxUpdate' => true,
                            'id' => 'timeline',
                            'pager' => array(
                                'class' => 'CustomPager',
                                'displayFirstAndLast' => true,
                            ),
                            'template' => "{items}\n{pager}",
                        )); ?>
						<li id="clock-remove" style="margin-right: 70%">
							<i class="fa fa-regular fa-clock bg-gray"></i>
						</li>
					</ul>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
    <?php
    endif; ?>
</div>
<?php
if (Yii::app()->params['useiframe'] == 1): ?>
	<script language="javascript" type="text/javascript">
		function resizeIframe(obj) {
			obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
		}
	</script>
<?php
endif; ?>
<?php
if (Yii::app()->session['requestStopTimer'] == 1 and !empty($model->comms)) {
    $timeout = (Yii::app()->params->update_grid_timeout) * 1000;
    Yii::app()->clientScript->registerScript('autoupdate-activations-application-grid',
        "setInterval(function(){;$.fn.yiiListView.update('timeline');
        return false;}," . $timeout . ");");
}
?>
<?php
if (Yii::app()->user->checkAccess('createUser') and !Yii::app()->user->checkAccess('systemUser')): ?>
    <?php
    $fuser = new FRegisterForm();
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'adduser-form',
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'action' => Yii::app()->createUrl('/cusers/fastadd', array('call' => NULL, 'ticket' => $model->id,)),
    )); ?>
    <?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'addModal')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4><?php
            echo Yii::t('main-ui', 'Create user'); ?></h4>
	</div>
	<div class="modal-body">
        <?php
        echo $form->errorSummary($fuser); ?>
		<div class="row-fluid">
			<div class="span12">
                <?php
                echo $form->select2Row($model, 'company', array(
                    'multiple' => false,
                    'data' => Companies::all_for_form(),
                    'placeholder' => '--- Выберите местоположение ---',
                    'allowClear' => true,
                    'options' => array(
                        'width' => '100%',
                        'asDropDownList' => true,
                    ),
                    'ajax' => array(
                        'type' => 'POST',//тип запроса
                        'url' => CController::createUrl('Cusers/SelectGroup'),//вызов контроллера c Ajax
                        'update' => '#dep',//id DIV - а в котором надо обновить данные
                    )
                ));
                echo $form->textFieldRow($fuser, 'fullname', array('class' => 'span12', 'onkeyup' => 'translit()')); ?>
			</div>
			<div class="row-fluid">
				<div class="span6"><?php
                    echo $form->textFieldRow($fuser, 'Username', array('class' => 'span12')); ?></div>
				<div class="span6"><?php
                    echo $form->passwordFieldRow($fuser, 'Password', array('class' => 'span12')); ?></div>
			</div>
			<div class="row-fluid">
				<div class="span6"><?php
                    echo $form->textFieldRow($fuser, 'Email', array('class' => 'span12', 'value' => $model->CUsers_id ? NULL : $model->fullname)); ?></div>
				<div class="span6"><?php
                    echo $form->textFieldRow($fuser, 'Phone', array('class' => 'span12')); ?></div>
                <?php
                echo $form->hiddenField($fuser, 'tbot', array('value' => $model->tchat_id)); ?>
                <?php
                echo $form->hiddenField($fuser, 'vbot', array('value' => $model->viber_id)); ?>
                <?php
                echo $form->hiddenField($fuser, 'msbot', array('value' => $model->msbot_id)); ?>

			</div>
		</div>
	</div>

	<div class="modal-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Create'),
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
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'showuser-form',
    'enableAjaxValidation' => false,
)); ?>
<?php
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'selectModal')); ?>
<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4><?php
        echo Yii::t('main-ui', 'Select user'); ?></h4>
</div>
<div class="modal-body">
    <?php
    $assets = new CUsers('search');
    $criteria = new CDbCriteria();
    $total = '';
    ?>
    <?php
    $model2 = new CUsers('search');
    $this->renderPartial('_ugrid2', array(
        'model' => $model2,
        'reqmodel' => $model->id,
    ));
    ?>
</div>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('main-ui', 'Cancel'),
        'url' => '#',
        'type' => 'primary',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )); ?>

</div>
<?php
$this->endWidget(); ?>
<?php
$this->endWidget(); ?>
<?php
Yii::app()->clientScript->registerScript('addban', '
$("#addban").click(function(){
var checked = $("#CUsers_fullname").text();
if(confirm("' . Yii::t('main-ui', 'Do you want to add email ') . '"+checked+"' . Yii::t('main-ui', ' to banlist?') . '"))
{
   $.ajax({
       data:{checked:checked},
       url:"' . CHtml::normalizeUrl(array('request/addban')) . '",
       success:function(data){location.reload();},
   });
}
});
');
?>
<script>
	function translit() {
// Символ, на который будут заменяться все спецсимволы
		var space = '_';
// Берем значение из нужного поля и переводим в нижний регистр
		var text  = $('#FRegisterForm_fullname').val().toLowerCase();

// Массив для транслитерации
		var transl = {
			'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
			'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
			'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
			'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': space, 'ы': 'y', 'ь': space, 'э': 'e', 'ю': 'yu', 'я': 'ya',
			' ': space, '_': space, '`': space, '~': space, '!': space, '@': space,
			'#': space, '$': space, '%': space, '^': space, '&': space, '*': space,
			'(': space, ')': space, '-': space, '\=': space, '+': space, '[': space,
			']': space, '\\': space, '|': space, '/': space, '.': space, ',': space,
			'{': space, '}': space, '\'': space, '"': space, ';': space, ':': space,
			'?': space, '<': space, '>': space, '№': space
		}

		var result     = '';
		var curent_sim = '';

		for (i = 0; i < text.length; i++) {
			// Если символ найден в массиве то меняем его
			if (transl[text[i]] != undefined) {
				if (curent_sim != transl[text[i]] || curent_sim != space) {
					result += transl[text[i]];
					curent_sim = transl[text[i]];
				}
			}
			// Если нет, то оставляем так как есть
			else {
				result += text[i];
				curent_sim = text[i];
			}
		}

		result = TrimStr(result);

// Выводим результат
		$('#FRegisterForm_Username').val(result);

	}

	function TrimStr(s) {
		s = s.replace(/^-/, '');
		return s.replace(/-$/, '');
	}

	// Выполняем транслитерацию при вводе текста в поле
	$(function () {
		$('#name').keyup(function () {
			translit();
			return false;
		});
	});
</script>
<script>
	function onReply(id) {
		var text = $('#redactor' + id).html();
		if ($('.comment-form').is(':visible') == false) $('.comment-form').toggle();
		if ($('.redactor-in-1').length) {
			$('.redactor-in-1').append('<blockquote>' + text + '</blockquote>');
		} else {
			$('.redactor-in-0').append('<blockquote>' + text + '</blockquote>');
		}
		$('textarea').append('<blockquote>' + text + '</blockquote>');
		location.href = '#comment-form';
	};

	function onDelete(id) {
		swal({
			title:              "<?php echo Yii::t('zii', 'Are you sure you want to delete this item?'); ?>",
			type:               'warning',
			showCancelButton:   true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor:  '#d33',
			confirmButtonText:  "<?php echo Yii::t('main-ui', 'Yes'); ?>",
			cancelButtonText:   "<?php echo Yii::t('main-ui', 'No'); ?>",
		}).then(function (result) {
			if (result.value) {
				$.ajax({
					url:     '/request/deletesub/' + id,
					success: function () {
						$('#comment' + id).addClass('loading');
						$('#delete_btn' + id).hide();
						$('#date' + id).hide();
						$('#comment' + id).hide();
					}
				});
			}
		});
	};

	function onRead(id) {
		$.ajax({
			url:     '/comment/read/' + id,
			success: function () {
				$('#read_btn' + id).hide();
				$('#read' + id).toggleClass('bg-red bg-blue');
			}
		});
	};
</script>
