<?php

$this->breadcrumbs=array(
    Yii::t('main-ui', 'Manage backups')=>array('/backup'),
    Yii::t('main-ui', 'Upload'),
);?>

<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Upload'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <div class="box-header">
            <h4><?php echo Yii::t('main-ui', 'Select a backup file in ZIP format');?></h4>
        </div>
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'upload-form',
                'enableAjaxValidation' => true,
                'htmlOptions'=>array('enctype'=>'multipart/form-data'),
            ));
            ?>
            <div class="form-group">
                <?php
                echo '
                                <div class="btn btn-default btn-file">
                                  <i class="fa-solid fa-paperclip"></i> ' . Yii::t('main-ui', 'Select a backup file');

                echo $form->fileField($model,'upload_file');
                echo $form->error($model,'upload_file');
                echo '</div>
                            <div class="MultiFile-list" id="upload_file_wrap"></div>
                          ';
                ?>
            </div><!-- row -->
    </div>
            <div class="box-footer">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'info',
                    'label' =>  Yii::t('main-ui', 'Upload'),
                )); ?>
            </div>
    <?php $this->endWidget(); ?>
    </div>