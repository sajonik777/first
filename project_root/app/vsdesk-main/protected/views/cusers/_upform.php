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
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'htmlOptions' => ['enctype' => 'multipart/form-data'],
            'id' => 'cusers-form',
            'enableAjaxValidation' => true,
        ]); ?>
        <?php
        /** @var TbActiveForm $form */ ?>
		<!--        --><?php
        //$form->type ?>
		<div>
            <?php
            echo $form->errorSummary($model); ?>
		</div>
		<div class="row-fluid">
			<div class="span6">
				<div class="row-fluid">
                    <?php
                    $url = (bool)$model->photo ? "/media/userphoto/{$model->id}.png" : '/images/no_avatar.png'; ?>
                    <?= CHtml::image($url); ?>
					<!--                --><?php
                    // echo $form->fileFieldRow($model, 'image', ['maxlength' => 50, 'class' => 'span12']);?>
				</div>
				<script>
					function delImage() {
						location.href = '<?= Yii::app()->createUrl('/cusers/delimage', ['id' => $model->id]) ?>';
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
                    <?php
                    if ((bool)$model->photo) { ?>
						<div title="Удалить" class="file_delete" onclick="delImage();">x</div>
                    <?php
                    } ?>
					<div class="file_upload"><?php
                        echo CHtml::activeFileField($model, 'image'); ?></div>
				</div>

			</div>
		</div>
		<div class="row-fluid">
			<div class="span6">
                <?php
                if (Yii::app()->ldap_conf->ad_enabled == 1) {
                    $disabled = false;
                } else {
                    $disabled = false;
                }
                echo $form->textFieldRow($model, 'Username', array('disabled' => $disabled, 'maxlength' => 50, 'class' => 'span12'));
                echo $form->textFieldRow($model, 'fullname', array('disabled' => $disabled, 'maxlength' => 50, 'class' => 'span12'));
                echo $form->passwordFieldRow($model, 'Password', array('disabled' => $disabled, 'maxlength' => 50, 'value' => '', 'class' => 'span12')); ?>
                <?php
                echo $form->textFieldRow($model, 'Email', array('disabled' => $disabled, 'maxlength' => 50, 'class' => 'span12')); ?>
                <?php
                echo $form->textFieldRow($model, 'Phone', array('disabled' => $disabled, 'maxlength' => 50, 'class' => 'span12')); ?>
                <?php
                echo $form->textFieldRow($model, 'intphone', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php
                echo $form->textFieldRow($model, 'mobile', array('maxlength' => 50, 'class' => 'span12')); ?>
                <?php
                echo $form->toggleButtonRow($model, 'active'); ?>
                <?php
                echo $form->toggleButtonRow($model, 'sendmail'); ?>
                <?php
                echo $form->toggleButtonRow($model, 'sendsms'); ?>
			</div>
			<div class="span6">
                <?php
                if (Yii::app()->user->checkAccess('systemManager') and Yii::app()->user->id !== $model->id) {
                    echo $form->dropDownListRow($model, 'role', Roles::uall(), array('class' => 'span12'));
                } elseif (Yii::app()->user->checkAccess('systemManager') and Yii::app()->user->id == $model->id) {
                    echo $form->dropDownListRow($model, 'role', CHTML::listData(Roles::model()->findAllByAttributes(array('value' => $model->role)), 'value', 'name'), array('class' => 'span12'));
                } else {
                    echo $form->dropDownListRow($model, 'role', Roles::All(), array('class' => 'span12'));
                }

                echo '<div id="comorcon_ret">';
                echo '<div class="span12">';
                echo $form->select2Row($model, 'company', array(
                    'multiple' => false,
                    'data' => Companies::all(),
                    'empty' => '',
                    'options' => array(
                        'tokenSeparators' => array(','),
                        'width' => '100%'
                    ),
                    'ajax' => array(
                        'type' => 'POST',//тип запроса
                        'url' => CController::createUrl('Cusers/SelectGroup'),//вызов контроллера c Ajax
                        'update' => '#dep',//id DIV - а в котором надо обновить данные
                    )
                ));
                echo '</div>';
                echo '</div>';
                // echo $form->textFieldRow($model, 'city', array('maxlength' => 50, 'class' => 'span12'));

                echo '<div id="loc" style="margin-top: 100px; margin-bottom: 20px; color: grey;"></div>';
                echo '<div id="dep">';
                //echo $form->dropDownListRow($model, 'department', Depart::all(), array('class' => 'span8'));
                echo $form->dropDownListRow($model, 'department', Depart::call($model->company), array('class' => 'span12'));
                echo '</div>';
                echo $form->textFieldRow($model, 'room', array('maxlength' => 50, 'class' => 'span12'));
                echo $form->labelEx($model, 'umanager');
                $this->widget(
                    'bootstrap.widgets.TbTypeahead',
                    array(
                        'model' => $model,
                        'attribute' => 'umanager',
                        'options' => array(
                            'source' => CUsers::model()->eall(),
                            'items' => 4,
                            'matcher' => <<<ENDL
js:function(item) {
return ~item.toLowerCase().indexOf(this.query.toLowerCase());
}
ENDL
                        ),
                        'htmlOptions' => array(
                            'class' => 'span12'
                        ),
                    )
                );
                echo $form->textFieldRow($model, 'position', array('maxlength' => 50, 'class' => 'span12'));
                echo $form->dropDownListRow($model, 'lang', array_merge($lang, array('en' => 'English')), array('class' => 'span12'));
                ?>
                <?php
                if (Yii::app()->params['TBotEnabled'] == 1) {
                    echo $form->textFieldRow($model, 'tbot', array('maxlength' => 50, 'class' => 'span4'));
                    if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
                        echo $form->toggleButtonRow($model, 'send_tbot');
                    }
                }
                ?>
                <?php
                if (Yii::app()->params['VBotEnabled'] == 1) {
                    echo $form->textFieldRow($model, 'vbot', array('maxlength' => 50, 'class' => 'span4'));
                    if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
                        echo $form->toggleButtonRow($model, 'send_vbot');
                    }
                } ?>
                <?php
                if (Yii::app()->params['WBotEnabled'] == 1) {
                    echo $form->textFieldRow($model, 'wbot', array('maxlength' => 50, 'class' => 'span4'));
                    if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
                        echo $form->toggleButtonRow($model, 'send_wbot');
                    }
                }
                ?>
			</div>
		</div>
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


<script>


$(document).ready(function () {

    const getLocation = ()=>{
        let location = $('#CUsers_company').val();
        // console.log(location.val());
        var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
        $.ajax({
            type: "GET",
            url: "/cusers/getFullAddress",
            data: {"name": location, "YII_CSRF_TOKEN": csrf},
            dataType: "text",
            cache: false,
            // update: "#form",
            error: function (e) {
            console.log(e);
            },
            success: function (data) {
                console.log(data, location);
                $("#loc").html(data);
            }
        });
    }
    getLocation();

    $('#CUsers_company').on('change',()=>{
        getLocation();
    });
});
    
  var id = $("#UnitTemplates_type").val();
  
// });


</script>