<?php


$this->breadcrumbs = array(
    Yii::t('main-ui', 'Appearance'),

);
$themes = array('blue' => Yii::t('main-ui','Blue'), 'blue-light' => Yii::t('main-ui','Light-blue'), 'black' => Yii::t('main-ui','Gray'), 'black-light' => Yii::t('main-ui','Light-gray'),'green' => Yii::t('main-ui','Green'), 'green-light' => Yii::t('main-ui','Light-green'), 'purple' => Yii::t('main-ui','Purple'), 'purple-light' => Yii::t('main-ui','Light-purple'), 'red' => Yii::t('main-ui','Red'), 'red-light' => Yii::t('main-ui','Light-red'), 'yellow' => Yii::t('main-ui','Yellow'), 'yellow-light' => Yii::t('main-ui','Light-yellow'));

Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>

<div class="page-header">
    <h3><i class="fa-solid fa-shirt fa-xl"> </i><?php echo Yii::t('main-ui', 'Appearance'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'app-form',
                'enableAjaxValidation' => false,
            ));
            ?>

            <?php echo $form->errorSummary($model6); ?>
            <div class="row-fluid">
                    <?php echo $form->toggleButtonRow($model6, 'fixedPanel'); ?>
                    <?php echo $form->toggleButtonRow($model6, 'showBtn'); ?>

                    <?php echo $form->labelEx($model6, 'theme'); ?>
                    <?php echo $form->dropdownlist($model6, 'theme', $themes, array('class' => 'span12')); ?>
                    <?php echo $form->error($model6, 'theme'); ?>

                    <?php echo $form->labelEx($model6, 'brandName'); ?>
                    <?php echo $form->textField($model6, 'brandName', array('class' => 'span12')); ?>
                    <?php echo $form->error($model6, 'brandName'); ?>

                    <?php echo $form->labelEx($model6, 'loginText'); ?>
                    <?php echo $form->textField($model6, 'loginText', array('class' => 'span12')); ?>
                    <?php echo $form->error($model6, 'loginText'); ?>

                    <?php
                    echo $form->labelEx($model6, 'mainLogo');
                    echo $form->textField($model6, 'mainLogo', array('class' => 'span12'));
                    echo $form->error($model6, 'mainLogo');
                    ?>

                    <?php
                    echo $form->labelEx($model6, 'smallLogo');
                    echo $form->textField($model6, 'smallLogo', array('class' => 'span12'));
                    echo $form->error($model6, 'smallLogo');
                    ?>

                    <?php
                    echo $form->labelEx($model6, 'portalHeader');
                    echo $form->textField($model6, 'portalHeader', array('class' => 'span12'));
                    echo $form->error($model6, 'portalHeader');
                    ?>

                    <?php
                    echo $form->textAreaRow($model6, 'portalText', array('id' => 'Content', 'rows' => 5));
                    ?>
                    <?php Yii::app()->clientScript->registerScript('redactor-init', "
                     function addField(id) {
                        if(id){
                            $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"Request[files][]\">');
                        }
                     }
                     $(function () {
                            $('#Content').redactor({
                                lang: 'ru',
                                plugins: ['fullscreen', 'video'],
                                imageResizable: true,
                                imagePosition: true,
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
                    ?>

            </div>
        </div>
    </div>
            <div class="row-fluid">
                <div class="box-footer">
                    <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>
</div>
