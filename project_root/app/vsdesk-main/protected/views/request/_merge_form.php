<?php

if (Yii::app()->request->isAjaxRequest) {
    Yii::app()->getClientScript()->scriptMap = array(
        'jquery.js' => false,
        'jquery.min.js' => false,
        'jquery-ui.min.js' => false,
        'jquery.ba-bbq.js'=>false,
        'jquery.yiigridview.js'=>false,
        'jquery.toggle.buttons.js'=>false,
        'jquery.multifile.js'=>false,
        'redactor.min.js'=>false,
        'select2.min.js'=>false,
    );
}
?>
<div class="row">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
        'id' => 'request-form',
        'enableAjaxValidation' => false,
        'action' => 'createMerge',
    )); ?>
    <?php echo CHtml::hiddenField('merge-all', isset($_GET['all']) ? $_GET['all'] : null); ?>
    <?php echo CHtml::hiddenField('merge-id', isset($_GET['id']) ? $_GET['id'] : 0); ?>
    <div class="span11">
        <?php echo $form->textFieldRow($model, 'Name', array('maxlength' => 50, 'class' => 'span12', 'disabled'=>$model->Name ? 'disabled' : false)); ?>
    </div>
    <div class="span11">
        <div class="span4">
            <?php echo $form->dropDownListRow($model, Yii::app()->params['t_filter'], Yii::app()->params['t_filter'] == 'company' ? Companies::model()->all() : Depart::model()->all(), array(
                'class' => 'span12 custom1',
                'disabled'=>$model->Name ? 'disabled' : false,
                'prompt' => Yii::t('main-ui', 'Select item'),
                'ajax' => array(
                    'type' => 'POST',//тип запроса
                    'url' => CController::createUrl('Request/SelectFObject'),//вызов контроллера c Ajax
                    'update' => '#CUsers_id',//id DIV - а в котором надо обновить данные
                )
            )); ?>


        </div>
        <div class="span4">
            <?php echo $form->dropDownListRow($model, 'CUsers_id', CUsers::model()->ffall(), array(
                'class' => 'span12',
                'disabled'=>$model->CUsers_id ? 'disabled' : false,
                'prompt' => Yii::t('main-ui', 'Select item'),
                'id' => 'CUsers_id',
                'ajax' => array(
                    'type' => 'POST',//тип запроса
                    'url' => CController::createUrl('Request/SelectAdmObject'),//вызов контроллера c Ajax
                    'update' => '#cunits',//id DIV - а в котором надо обновить данные
                )
            )); ?>

        </div>
        <div class="span4">
            <?php echo $form->dropDownListRow($model, 'ZayavCategory_id', Category::model()->all(), array('class' => 'span12', 'disabled'=>$model->ZayavCategory_id ? 'disabled' : false)); ?>
        </div>
    </div>
    <div class="span11">
        <div class="span4">
            <?php
            $role = Roles::model()->findByAttributes(array('value'=>strtolower(Yii::app()->user->role)));
            $list_data = CHtml::listData($role->status_rl, 'name', 'name');
            echo $form->dropDownListRow($model, 'Status', $list_data, array('class' => 'span12', 'disabled'=>$model->Status ? 'disabled' : false));
            ?>
        </div>
        <div class="span4">
            <?php echo $form->dropDownListRow($model, 'service_id', Service::model()->all(), array(
                'id' => 'service',
                'class' => 'span12',
                'disabled'=>$model->service_id ? 'disabled' : false,
                'ajax' => array(
                    'type' => 'POST',
                    'dataType' => 'json',
                    'url' => CController::createUrl('Request/SelectPriority'),
                    'success' => 'function(data) {
									var id = data.fid;
									var csrf = data.csrf;
									$("#Priority").html(data.options);
									$.ajax({
										type: "POST",
										url:  "/request/setfields",
										data: {"id":id, "YII_CSRF_TOKEN":csrf},
										dataType: "text",
										cache: false,
										update: "#fields",
										error: function(e) {
											console.log(e);
										},
										success: function(data) {
											$("#fields").html(data);
										}
									});
						}',
                ))); ?>

        </div>
        <div class="span4">
            <?php echo $form->dropDownListRow($model, 'Priority', Zpriority::model()->all(), array('id' => 'Priority', 'class' => 'span12', 'disabled'=>$model->Priority ? 'disabled' : false)); ?>
        </div>
    </div>
    <?php if (Yii::app()->user->checkAccess('canSetFieldsRequest')) {
        echo '<div id="fields" class="span10">
					</div>';
    } ?>
    <div class="span11">
        <?php echo $form->redactorRow($model, 'Content', array('class' => 'span4', 'rows' => 5, 'options' => array('disabled'=>'disabled'))); ?>
        <?php if (Yii::app()->user->checkAccess('canSetObserversRequest')): ?>
            <br/><br/><br/>
            <?php echo $form->select2Row($model, 'watchers', array(
                'data' => CUsers::model()->wall(),
                'multiple' => 'multiple',
                'disabled'=>$model->Name ? 'disabled' : false,
                'options' => array(

                    'width' => '100%',
                    'tokenSeparators' => array(','),
                ),
            ));
            ?>
        <?php endif; ?>
        <?php if (Yii::app()->user->checkAccess('canSetUnitRequest')): ?>
            <?php echo $form->select2Row($model, 'cunits', array(
                'data' => Cunits::model()->all(),
                'multiple' => 'multiple',
                'disabled'=>$model->Name ? 'disabled' : false,
                'id' => 'cunits',
                'options' => array(
                    'width' => '100%',
                    'tokenSeparators' => array(','),
                ),
            ));
            ?>
        <?php endif; ?>
        <br/>
        <?php if (Yii::app()->user->checkAccess('uploadFilesRequest')): ?>
            <?php if ($model->image == NULL) {
                echo '<p>' . Yii::t('main-ui', 'Upload files') . ':</p>';
                $this->widget('CMultiFileUpload', array(
                    'name' => 'image',
                    'accept' => Yii::app()->params->extensions,
                    'duplicate' => Yii::app()->params->duplicate_message,
                    'denied' => Yii::app()->params->denied_message,
                    'options' => array(
                        'onFileSelect' => 'function(e ,v ,m){
													var fileSize=e.files[0].size;
													if(fileSize>' . (Yii::app()->params->max_file_size * 1024) . '){
														swal(
                                                                  "'. Yii::app()->params->max_file_msg . '",
                                                                  "ERROR!",
                                                                  "error");     
                                                              return false;
													}
												}'

                    ),
                ));
            } else {
                echo '<br/><br/><br/>';
            }
            ?>
        <?php endif; ?>
        <div class="form-actions">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $model->isNewRecord ? Yii::t('main-ui', 'Merge') : Yii::t('main-ui', 'Save'),
            )); ?>
        </div>

        <?php $this->endWidget(); ?>

    </div>
</div>
<script>
    $(document).ready(function () {
        var id = $("#service").val();
        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
        $.ajax({
            type: "POST",
            url: "/request/setfields2",
            data: {"id": id, "YII_CSRF_TOKEN": csrf},
            dataType: "text",
            cache: false,
            update: "#fields",
            error: function (e) {
                console.log(e);
            },
            success: function (data) {
                $("#fields").html(data);
            }
        });
    });
    jQuery('body').on('change','.custom1',function(){
        jQuery.ajax({
            'type':'POST',
            'url':'/Request/SelectFObject2',
            'cache':false,
            'data':jQuery(this).parents("form").serialize(),
            'success':function(html){jQuery("#service").html(html)}
        });
        return false;
    });
</script>