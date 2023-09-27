<div class="box">
    <div class="box-body">
        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'items' => $this->menu,
            )); ?>
            <?php $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            )); ?>
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'htmlOptions' => ['enctype'=>'multipart/form-data'],
                'id' => 'cusers-form',
                'enableAjaxValidation' => true,
            )); ?>
            <div>
                <?php echo $form->errorSummary($model); ?>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <div class="row-fluid">
                        <?php $url = (bool)$model->photo ? "/media/userphoto/{$model->id}.png" : '/images/no_avatar.png'; ?>
                        <?= CHtml::image($url); ?>
                        <!--                --><?php // echo $form->fileFieldRow($model, 'image', ['maxlength' => 50, 'class' => 'span12']);?>
                    </div>
                    <script>
                        function delImage() {
                            location.href='<?= Yii::app()->createUrl('/cusers/delimage', ['id'=>$model->id]) ?>';
                        }
                    </script>
                    <style>
                        .file_delete {
                            float: left;
                            position: relative;
                            overflow: hidden;
                            background: #fff;
                            border: 1px solid #ccc;
                            font-size: 14px;
                            line-height: 1;
                            text-align: center;
                            border-radius: 5px;
                            padding: 1px;
                            margin-top: 5px;
                            width: 18px;
                            cursor: pointer;
                        }

                        .file_upload input[type=file] {
                            position: relative;
                            opacity: inherit;
                            min-width: 0;
                            font-size: 12px;
                        }
                    </style>
                    <div class="row-fluid">
                        <?php if((bool)$model->photo){ ?>
                            <div title="Удалить" class="file_delete" onclick="delImage();">x</div>
                        <?php } ?>
                        <div class="file_upload"><?php  echo CHtml::activeFileField($model, 'image');?></div>
                    </div>

                </div>
            </div>
            <div class="row-fluid">
                <div class="span6">
                    <?php
                    if (Yii::app()->ldap_conf->ad_enabled == 1) {
                        $disabled = true;
                    } else {
                        $disabled = false;
                    }
                    echo $form->textFieldRow($model, 'Username', array('disabled' => 'true', 'maxlength' => 50, 'class' => 'span12'));
                    echo $form->textFieldRow($model, 'fullname', array('disabled' => $disabled, 'maxlength' => 50, 'class' => 'span12'));
                    echo $form->passwordFieldRow($model, 'Password', array('disabled' => $disabled, 'maxlength' => 50, 'value' => '', 'class' => 'span12'));
                    echo $form->textFieldRow($model, 'Email', array('disabled' => $disabled, 'maxlength' => 50, 'class' => 'span12'));
                    echo $form->textFieldRow($model, 'Phone', array('disabled' => $disabled, 'maxlength' => 50, 'class' => 'span12'));
                    echo $form->textFieldRow($model, 'intphone', array('maxlength' => 50, 'class' => 'span12'));
                    echo $form->textFieldRow($model, 'mobile', array('maxlength' => 50, 'class' => 'span12'));
                    echo $form->dropDownListRow($model, 'lang', array_merge($lang, array('en' => 'English')), array('class' => 'span12'));
                    ?>
                </div>
            </div>
        </div>
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
