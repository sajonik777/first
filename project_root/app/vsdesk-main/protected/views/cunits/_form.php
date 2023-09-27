<?php

Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>
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
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'cunits-form',
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'enableAjaxValidation' => false,
        )); ?>

		<div>
            <?php
            echo $form->errorSummary($model); ?>
		</div>
		<div class="row-fluid">
			<div class="span6">
                <?php
                echo $form->textFieldRow($model, 'name', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php
                echo $form->dropDownListRow($model, 'type', CunitTypes::All(), array('class' => 'span12','prompt' => '')); ?>
                <?php
                echo $form->dropDownListRow($model, 'status', Astatus::All(), array('class' => 'span12','prompt' => '')); ?>
                <?php
                echo $form->dropDownListRow($model, 'dept', Depart::all(), array(
                    'prompt' => '',
                    'class' => 'span12',
                    'ajax' => array(
                        'type' => 'POST',//тип запроса
                        'url' => CController::createUrl('Cunits/SelectDepart'),//вызов контроллера c Ajax
                        'update' => '#Cunits_user',//id DIV - а в котором надо обновить данные
                    ))); ?>
                <?php
                //echo $form->dropDownListRow($model, 'user', CUsers::ffall(), array('id' => 'Cunits_user', 'class' => 'span12'));  ?>
                <?php
                echo $form->select2Row($model, 'user', [
                    'data' => CUsers::ffall(),
                    'multiple' => false,
                    'asDropDownList' => true,
                    'id' => 'Cunits_user',
                    'prompt' => '',
                    'options' => ['width' => '100%'],
                ]);

                ?>
                <?php
                echo $form->textareaRow($model, 'description', array('class' => 'span12', 'cols' => 6, 'rows' => 8)); ?>
                <?php
                Yii::app()->clientScript->registerScript('redactor-init', "
                          $(function () {
                            $('#Cunits_description').redactor({
                              lang: 'ru',
                              plugins: ['alignment', 'table','fullscreen', 'video'],
                              imageResizable: true,
                              linkValidation: false,
                              linkSize: 200,
                              imagePosition: true,
                        });
                    });
                    ");
                ?>

            </div>
            <div class="span6">
                <?php echo $form->textFieldRow($model, 'inventory', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php //echo $form->textFieldRow($model, 'location', array('maxlength' => 100, 'class' => 'span12')); ?>
                <?php echo $form->labelEx($model, 'datein'); ?>
                <div class="dtpicker2">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'datein',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'defaultOptions' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    )
                )); ?>
                </div>
                <?php echo $form->error($model, 'dateout'); ?>
                <?php echo $form->labelEx($model, 'dateout'); ?>
                <div class="dtpicker2">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'dateout',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'defaultOptions' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    )
                )); ?>
                </div>
                <?php echo $form->labelEx($model, 'warranty_start'); ?>
                <div class="dtpicker2">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'warranty_start',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'defaultOptions' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    )
                )); ?>
                </div>
                <?php echo $form->error($model, 'warranty_start'); ?>
                <?php echo $form->labelEx($model, 'warranty_end'); ?>
                <div class="dtpicker2">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'warranty_end',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'defaultOptions' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    )
                )); ?>
                </div>
                <?php echo $form->error($model, 'dateout'); ?>
                <div id="cost_div">
                    <!-- <?php echo $form->textFieldRow($model, 'cost', array('disabled' => 'true', 'append' => 'руб.', 'class' => 'span5', 'maxlength' => 100)); ?> -->
                </div>
            
            
            <!-- <div class="row-fluid"> -->
                
			<p>Добавьте активы из списка:</p>

            <?php
            $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'assets',
                    'data' => Asset::aall(),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                    ),
                    'options' => array('width' => '100%'),
                )
            ); ?>
            </div>
</div>
		<!-- </div> -->
                <?php
                echo $form->error($model, 'dateout'); ?>
				<!-- <div id="cost_div">
                    <?php
                    // echo $form->textFieldRow($model, 'cost', array('disabled' => 'true', 'append' => 'руб.', 'class' => 'span5', 'maxlength' => 100)); ?>
				</div> -->
			<!-- </div> -->
		<!-- </div> -->
		<!-- <div class="row-fluid">
			<p>Добавьте активы из списка:</p>

            <?php
            $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'assets',
                    'data' => Asset::aall(),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                    ),
                    'options' => array('width' => '100%'),
                )
            ); ?>

		</div> -->
		<br>
        <?php
        if (Yii::app()->user->checkAccess('uploadFilesUnit')): ?>
            <?php
            if ($model->image == null) {
                echo '

                            <div class="form-group">
                                <div class="btn btn-default btn-file">
                                  <i class="fa-solid fa-paperclip"></i> ' . Yii::t('main-ui', 'Upload files');
                $this->widget('CMultiFileUpload', array(
                    'name' => 'image',
                    'accept' => Yii::app()->params->extensions,
                    'duplicate' => Yii::app()->params->duplicate_message,
                    'denied' => Yii::app()->params->denied_message,
                    'htmlOptions' => [
                        'multiple' => true
                    ],
                    'options' => [
                        'list' => '#image_wrap',
                        'onFileSelect' => 'function(e ,v ,m){
                                        var fileSize=e.files[0].size;
                                        if(fileSize>' . (Yii::app()->params->max_file_size * 1024) . '){
                                            swal(
                                                                  "' . Yii::app()->params->max_file_msg . '",
                                                                  "ERROR!",
                                                                  "error");     
                                                              return false;
                                    }
                                    }'
                    ],
                ));
                echo '</div>
                            <p class="help-block">' . Yii::t('main-ui',
                        'Max.') . ' ' . Yii::app()->params->max_file_size . ' Kb</p>
                            <div class="MultiFile-list" id="image_wrap"></div>
                          </div>';

            }
            ?>
        <?php
        endif; ?>
	</div>
	<div class="row-fluid">

		<div class="box-footer">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
            )); ?>
		</div>

        <?php
        $this->endWidget(); ?>
	</div>
</div>