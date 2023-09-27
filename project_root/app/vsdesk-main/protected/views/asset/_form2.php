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
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'items' => $this->menu,
            )); ?>
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'asset-form',
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
                'enableAjaxValidation' => false,
            )); ?>
        <div class="row-fluid">
            <div class="span6">
                <h4><b><?php echo Yii::t('main-ui', 'Asset type'); ?></b> <?php echo $model->asset_attrib_name; ?></h4>
                <?php echo $form->errorSummary($model); ?>
                <?php echo $form->dropDownListRow($model, 'status', Astatus::All(), array('class' => 'span12')); ?>
                <?php echo $form->hiddenField($model, 'id', array('value' => $model->id)); ?>
                <?php echo $form->textFieldRow($model, 'name', array('size' => '10', 'maxlength' => 50, 'class' => 'span12')); ?>
                <?php echo $form->dropDownListRow($model, 'location',Companies::All(), array('class' => 'span12','prompt' => Yii::t('main-ui', 'Select item'))); ?>
                <?php echo $form->textFieldRow($model, 'inventory', array('size' => '10', 'maxlength' => 50, 'class' => 'span12')); ?>
                <label><i class="fa-solid fa-calendar-days"></i> <?php echo Yii::t('main-ui', 'Warranty Start'); ?>:</label>
                <?php 
                
                // $this->widget('bootstrap.widgets.TbDatePicker', array(
                    $this->widget('editable.EditableField', array(
                    'name' => 'warranty_start',
                    'type'        => 'date',
                    // 'language' => 'ru',
                    'url' => Yii::app()->createUrl('asset/updWarranty/', array('id' => $model->id)),
                    'model' => $model,
                    'attribute' => 'warranty_start',
                    'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken), 'datetimepicker' => array('language' => 'ru', 'weekStart' => 1)),

                    'format' => 'dd.mm.yyyy',
                    'success' => 'js: function(data) {
                        location.reload();
                        }'
                )); ?>
                <label><i class="fa-solid fa-calendar-days"></i> <?php echo Yii::t('main-ui', 'Warranty End'); ?>:</label>
                <?php 
                // $this->widget('bootstrap.widgets.TbDatePicker', array(
                    $this->widget('editable.EditableField', array(
                    'type'        => 'date',
                    // 'language' => 'ru',
                    'url' => Yii::app()->createUrl('asset/updWarranty', array('id' => $model->id)),
                    'model' => $model,
                    'attribute' => 'warranty_end',
                    'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken), 'datetimepicker' => array('language' => 'ru', 'weekStart' => 1)),

                    'format' => 'dd.mm.yyyy',
                    'success' => 'js: function(data) {
                        location.reload();
                        }'
                )); ?>
                <?php echo $form->textFieldRow($model, 'cost', array('append' => Yii::t('main-ui', '.usd'), 'class' => 'span6', 'maxlength' => 50, 'class' => 'span12')); ?>
                
                <?php echo $form->textareaRow($model, 'description', array('class' => 'span12', 'cols' => 6, 'rows' => 8)); ?>
                <?php Yii::app()->clientScript->registerScript('redactor-init', "
                          $(function () {
                            $('#Asset_description').redactor({
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
                <div id="data">
                    <?php if ($model_s): ?>
                        <h4><?php echo Yii::t('main-ui', 'Enter form data'); ?></h4>
                    <?php endif; ?>
                    <?php $i = 0; ?>
                    <?php
                    foreach ($model_s as $model_s): ?>
                        <?php $i = $i + 1; ?>
                        <?php echo '<label>' . $model_s->asset_attrib_name . '</label>'; ?>
                        <?php echo '<input type="text" name="Asset[' . $model_s->id . ']" id="Asset[' . $model_s->id . ']" size="10" maxlength="200" class="span12" value="' . $model_s->value . '">'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            </div>
        <br>
        <?php if (Yii::app()->user->checkAccess('uploadFilesAsset')): ?>
            <?php if ($model->image == null) {
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
                                                                  "'. Yii::app()->params->max_file_msg . '",
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
        <?php endif; ?>
    </div>
            <div class="row-fluid">
                <div class="box-footer">
                    <?php $this->widget('bootstrap.widgets.TbButton', array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
                    )); ?>
                </div>
                <?php $this->endWidget(); ?>
            </div>
</div>

<script>
$(document).ready(function () {
    $(".redactor-toolbar").css("z-index", "1");
});
</script>