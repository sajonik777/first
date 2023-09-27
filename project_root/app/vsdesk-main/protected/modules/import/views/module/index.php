<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Import from CSV') => array('index'),
);

?>
<div class="page-header">
    <h3><i class="fa-solid fa-file-csv fa-xl"> </i><?php echo Yii::t('main-ui', 'Import from CSV'); ?> </h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php echo CHtml::form($this->createUrl('upload'), 'post', array('enctype' => 'multipart/form-data', 'id'=>'csv-form')); ?>
        <h4><?php echo Yii::t('main-ui', 'Select model to import:'); ?></h4>
        <?php
        $asset_attribs = AssetAttrib::All();
        ?>
        <div class="row-fluid">
            <?php echo CHtml::DropDownList('model', 'model',
                array(Yii::t('main-ui', 'Assets') => $asset_attribs, 'Cunits' => Yii::t('main-ui', 'Units'), 'CUsers' => Yii::t('main-ui', 'Users'), 'Companies' => Yii::t('main-ui', 'Companies')),
                array(
                    'name' => 'model',
                    'prompt' => Yii::t('main-ui', 'Select item'),
                    'class' => 'span12',
                    'ajax' => array(
                        'type' => 'POST',
                        'url' => CController::createUrl('/import/module/getfields'),
                        'update' => '#model_name',
                    )));
            ?>
            <p><?php echo Yii::t('main-ui', 'To import from CSV file saved in UTF-8 and contains only the fields with values without gaps and headings, select the file with a separator'); ?>
                <strong>;</strong></p>
            <div id="model_name">
            </div>
            <br/>
            <?php
            echo '
                            <div class="form-group">
                                <div class="btn btn-default btn-file">
                                  <i class="fa-solid fa-paperclip"></i> ' . Yii::t('main-ui', 'Select a CSV file');
            $this->widget('CMultiFileUpload', array(
                'name' => 'files',
                'accept' => 'csv',
                'htmlOptions' => [
                    'multiple' => true
                ],
				'options' => [
                    'list' => '#files_wrap',
				],
                'denied' => Yii::app()->params->denied_message,
                'max' => 1,
            ));
            echo '</div>
                            <div class="MultiFile-list" id="files_wrap"></div>
                          </div>';
            ?>
        </div>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Import from CSV'),
        )); ?>
    </div>
    <?php echo CHtml::endForm(); ?>
</div>
