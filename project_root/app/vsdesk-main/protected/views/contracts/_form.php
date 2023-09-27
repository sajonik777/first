<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'contracts-form',
            'enableAjaxValidation' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        )); ?>
        <div class="row-fluid">
            <?php echo $form->errorSummary($model); ?>
            <div class="span6">
                <div class="row-fluid">
                    <div class="span3"><?php echo $form->textFieldRow($model, 'number', array('prepend' => '№', 'class' => 'span12', 'maxlength' => 100)); ?></div>
                    <div class="span9"><?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 100)); ?></div>
                </div>
                <?php echo $form->select2Row($model, 'customer_name', array(
                    'multiple' => false,
                    'data' => Companies::all(),
                    'empty' => '',
                    'options' => array(
                        'tokenSeparators' => array(','),
                        'width' => '100%'
                    ),
                )); ?>
                <?php echo $form->select2Row($model, 'company_name', array(
                    'multiple' => false,
                    'data' => Companies::all(),
                    'empty' => '',
                    'options' => array(
                        'tokenSeparators' => array(','),
                        'width' => '100%'
                    ),
                )); ?>
                <?php
                echo $form->labelEx($model, 'type');
                $this->widget(
                    'bootstrap.widgets.TbTypeahead',
                    array(
                        'model' => $model,
                        'attribute' => 'type',
                        'options' => array(
                            'source' => Contracts::getTypes2(),
                            'items' => 10,
                            'matcher' => <<<ENDL
js:function(item) {
    return ~item.toLowerCase().indexOf(this.query.toLowerCase());
}
ENDL
                        ),
                        'htmlOptions' => array(
                            'class' => 'span12', 'placeholder' => Yii::t('main-ui', 'Start typing...')
                        ),
                    )
                );
                ?>
            </div>
            <div class="span6">
                <?php echo $form->toggleButtonRow($model, 'stopservice'); ?>
                <?php echo $form->labelEx($model, 'date_view'); ?>
                <div class="dtpicker2">
                    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'date_view',
                        'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                        'defaultOptions' => array(
                            'dateFormat' => 'dd.mm.yy',
                            'showButtonPanel' => true,
                            'changeYear' => true,
                        )
                    )); ?>
                </div>
                    <?php echo $form->labelEx($model, 'tildate_view'); ?>
                    <div class="dtpicker2">
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model' => $model,
                            'attribute' => 'tildate_view',
                            'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                            'defaultOptions' => array(
                                'dateFormat' => 'dd.mm.yy',
                                'showButtonPanel' => true,
                                'changeYear' => true,
                            )
                        )); ?>
                    </div>
                <?php echo $form->textFieldRow($model, 'cost', array('append' => 'руб.', 'class' => 'span5', 'maxlength' => 50)); ?>
            </div>
        </div>
        <br>
        <?php if (Yii::app()->user->checkAccess('uploadFilesContracts')): ?>
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
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>