<?php

Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');
$kb_model = Knowledge::model();
?>
<div class="box">
	<div class="box-body">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'brecords-form',
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'enableAjaxValidation' => false,
        )); ?>

		<div class="row-fluid">
            <?php
            echo $form->errorSummary($kb_model); ?>

            <?php
            echo $form->dropDownListRow($kb_model, 'parent_id', Yii::app()->user->checkAccess('systemAdmin') ? Categories::model()->findAllByPkToArray(5) : Categories::model()->findAllByPkToArray(5), array('class' => 'span12')); ?>

            <?php
            echo $form->textFieldRow($kb_model, 'name', array('class' => 'span12', 'maxlength' => 100)); ?>

            <?php
            echo $form->textAreaRow($kb_model, 'content', array('id' => 'kb_content', 'rows' => 5));
            ?>
            <?php
            Yii::app()->clientScript->registerScript('redactor-init', "
                     function addField(id) {
                        if(id){
                            $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"Knowledge[files][]\">');
                        }
                     }
                     $(function () {
                            $('#content').redactor({
                                lang: 'ru',
                                plugins: ['alignment', 'table', 'fullscreen', 'video'],
                                imageResizable: true,
                                imagePosition: true,
                                linkValidation: false,
                                imageUpload: '" . $this->createUrl('/files/upload2') . "',
                                imageData: {'YII_CSRF_TOKEN': '" . Yii::app()->request->csrfToken . "'},
                                fileData: {'YII_CSRF_TOKEN': '" . Yii::app()->request->csrfToken . "'},
                                callbacks: {
                                    image: {
                                        uploaded: function (image, response) {
                                            addField(response['file-0'].id);
                                        },
                                        uploadError: function (response) {
                                            swal(response.message, 'ERROR!', 'error');
                                        }
                                    },
                                    file: {
                                        uploaded: function (file, response) {
                                            addField(response['file-0'].id);
                                        },
                                        uploadError: function (response) {
                                            swal(response.message, 'ERROR!', 'error');
                                        }
                                    }
                                } 
                            });
                        });
                    ");
            ?>
            <?php
            /* echo $form->redactorRow($kb_model, 'content', array(
                           'rows' => 5,
                           'options' => array(
                               'fileUpload' => Yii::app()->user->checkAccess('uploadFilesKB') ? $this->createUrl('/site/fileUpload') : false,
                               'imageUpload' => Yii::app()->user->checkAccess('uploadFilesKB') ? $this->createUrl('/site/imageUpload') : false,
                               'uploadImageFields' => [
                                   'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken,
                               ],
                               'uploadFileFields' => [
                                   'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken,
                               ],
                               'lang' => 'ru',
                               'plugins' => array('fullscreen', 'fontsize', 'fontfamily', 'table', 'video'),
                           )
                       )); */ ?>
			<br/>
            <?php
            if (Yii::app()->user->checkAccess('uploadFilesKB')): ?>
                <?php
                if ($kb_model->image == null) {
                    echo '
                            <div class="form-group">
                                <div class="btn btn-default btn-file">
                                  <i class="fa-solid fa-paperclip"></i> ' . Yii::t('main-ui', 'Upload files');
                    $this->widget('CMultiFileUpload', array(
                        'name' => 'image',
                        'accept' => Yii::app()->params->extensions,
                        'duplicate' => Yii::app()->params->duplicate_message,
                        'denied' => Yii::app()->params->denied_message,
                        'options' => [
                            'onFileSelect' => 'function(e ,v ,m){
                                        var fileSize=$("#image")[0].files[0].size;
                                        if(fileSize>' . (Yii::app()->params->max_file_size * 1024) . '){
                                        alert("' . Yii::app()->params->max_file_msg . '");
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
	</div>
	<div class="row-fluid">
		<div class="box-footer">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $kb_model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
            )); ?>
		</div>
	</div>
    <?php
    $this->endWidget(); ?>

</div>


<script>
	$(document).ready(function () {
		$('#Knowledge_name').val($('#Service_description').val());
		$('#kb_content').val($('.redactor-in:eq(0)').text());
		console.log(123);
		$('#Service_description').on('change', () => {
			console.log($('#Service_description').val());
			$('#Knowledge_name').val($('#Service_description').val());
		});

		$('.redactor-in').on('DOMSubtreeModified', () => {
			console.log('123123');
			console.log($('.redactor-in:eq(0').text());
			$('#kb_content').val($('.redactor-in:eq(0)').text());

			console.log($('#kb_content').text());
		});
	});

</script>
