<?php


Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data',
        'onSubmit' => 'document.getElementById("create_btn").disabled=true;'
    ),
    'id' => 'additem-form',
    'action' => Yii::app()->createUrl('/companies/send_message', array('id' => $model->id)),
    'enableAjaxValidation' => false,
    )); ?>
    <div class="box">
      <div class="box-header">
        <h3><?php echo Yii::t('main-ui', 'Send Email'); ?></h3>
    </div>
    <div class="box-body">
        <?php
        $comment = new Comments();
        $inGroup = false;
        if (!Yii::app()->user->checkAccess('systemUser') OR Yii::app()->user->checkAccess('systemAdmin')) {
            echo '<div class="row-fluid">';
            echo '<div class="span12">';
            echo '<div class="span6">';
            echo $form->select2Row($comment, 'theme', array(
                'multiple' => false,
                'data' => array('0' => Yii::t('main-ui', 'Select item'))+ReplyTemplates::model()->all(),
                'options' => array(
                    'width' => '100%',
                    'tokenSeparators' => array(','),
                ),
                'ajax' => array(
                    'type' => 'POST',
                    'dataType' => 'json',
                    'url' => CController::createUrl('Companies/SelectTemplate', array('id' => $model->id)),
                    'success' => 'function(data) {
                       var text;
                       text = $("#comment").val();
                       text = text.replace("<p>&#8203;</p>","");
                       $("#Comments_author").val(data.desc);
                       $(".redactor-in-0").html(text+data.content);
                       $("#comment").val(text+data.content);
                   }',
               )
            ));
            echo '</div>';
            echo '<div class="span6">';
            $connection = Yii::app()->db;
            $criteria = new CDbCriteria;
            $criteria->order = ' id DESC';
            $user_sql = 'SELECT * FROM `CUsers` `t` WHERE `t`.`id`=' . Yii::app()->user->id . ' LIMIT 1';
            $user = Yii::app()->user->id ? $connection->createCommand($user_sql)->queryRow() : '';
            if (!Yii::app()->user->isGuest) {
                $username = $user['fullname'];
            } else {
                $username = 'Гость';
            }
            $role_sql = 'SELECT * FROM `roles` `t` WHERE `t`.`value`="' . Yii::app()->user->role . '" LIMIT 1';
            $role_name = $connection->createCommand($role_sql)->queryRow();
            if ($role_name) {
                if (!Yii::app()->user->checkaccess('systemAdmin')) {
                    $criteria->compare('access', $role_name['name'], true);
                }
            }
            $faq = Knowledge::model()->findAll($criteria);
            echo $form->select2Row($comment, 'kbtheme', array(
                'multiple' => false,
                'data' => array('0' => Yii::t('main-ui', 'Select item'))+CHtml::listData($faq, 'id', 'name'),
                'options' => array(
                    'width' => '100%',
                    'tokenSeparators' => array(','),
                ),
                //'prompt' => Yii::t('main-ui', 'Select item'),
                'ajax' => array(
                    'type' => 'POST',
                    'dataType' => 'json',
                    'url' => CController::createUrl('Request/SelectKB'),
                    'success' => 'function(data) {
                        var text;
                        text = $("#comment").val();
                        text = text.replace("<p>&#8203;</p>","");
                        $(".redactor-in-0").html(text+data.content);
                        $("#comment").val(text+data.content);
                    }',
                )
            ));
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<br/>';
        }

      echo '<div class="row-fluid">';
      echo '<p>Тема сообщения</p>';
      echo $form->textField($comment, 'author', array('maxlength' => 100, 'class' => 'span12'));
      echo $form->textAreaRow($comment, 'comment', array('id' => 'comment', 'rows' => 5));
      Yii::app()->clientScript->registerScript('redactor-init2', "
        $(function () {
            $('#comment').redactor({
                lang: 'ru',
                plugins: ['alignment', 'table', 'fullscreen', 'video'],
                linkValidation: false,
                linkSize: 200,
                imageResizable: true,
                imagePosition: true,
                multipleUpload: false,
                imageUpload: '" . $this->createUrl('files/upload2') . "',
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
      echo '</div>
      <br/>';
      ?>

      <?php if (Yii::app()->user->checkAccess('uploadFilesRequest')): ?>
        <?php
        echo '<div class="form-group">
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
        <p class="help-block">' . Yii::t('main-ui', 'Max.') . ' ' . Yii::app()->params->max_file_size . ' Kb</p>
        <div class="MultiFile-list" id="image_wrap"></div>
        </div>';
        ?>
    <?php endif; ?>
</div>
<div class="box-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'id' => 'create_btn',
        'label' => Yii::t('main-ui', 'Add'),
        )); ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('main-ui', 'Cancel'),
        'url' => $this->createUrl('view', array('id' => $model->id)),
        )); ?>
    </div>
</div>
<?php $this->endWidget(); ?>