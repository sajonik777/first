<?php


/* @var $this PortalController */
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Portal'),
);
?>

<?php
if ($_SERVER['REQUEST_URI'] == '/portal/create'): ?>
	<script type="text/javascript">
		$(document).ready(function () {
			location.href = '#create';
		});
	</script>
<?php
endif; ?>

<?php
Yii::app()->bootstrap->registerAssetCss('bootstrap-toggle-buttons.css');
Yii::app()->bootstrap->registerAssetJs('jquery.toggle.buttons.js');

Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>
<div class="page-header">
	<h3><i class="fa-solid fa-house fa-xl"> </i>
        <?php
        if (Yii::app()->params['portalHeader']) {
            echo Yii::app()->params['portalHeader'];
        } else {
            echo Yii::t('main-ui', 'Portal');
        }
        ?>
	</h3>
</div>
<!-- Small boxes (Stat box) -->
<?php
$this->widget('bootstrap.widgets.TbAlert', array(
    'block' => true,
    'fade' => true,
    'closeText' => '×',
)); ?>
<?php
if (Yii::app()->params['portalAllowRegister'] == 1 and Yii::app()->params['portalAllowRestore'] == 0) {
    $span = 4;
} elseif (Yii::app()->params['portalAllowRegister'] == 0 and Yii::app()->params['portalAllowRestore'] == 0) {
    $span = 6;
} elseif ((Yii::app()->params['portalAllowRegister'] == 0 and Yii::app()->params['portalAllowRestore'] == 1)) {
    $span = 4;
} else {
    $span = 3;
}

if (Yii::app()->params['portalAllowNews'] == 1 and Yii::app()->params['portalAllowKb'] == 0) {
    $span2 = 12;
} elseif (Yii::app()->params['portalAllowNews'] == 0 and Yii::app()->params['portalAllowKb'] == 0) {
    $span2 = 6;
} elseif ((Yii::app()->params['portalAllowNews'] == 0 and Yii::app()->params['portalAllowKb'] == 1)) {
    $span2 = 12;
} else {
    $span2 = 6;
}

?>
<div class="row-fluid">
	<div class="span<?php
    echo $span; ?>">
		<!-- small box -->
		<div class="small-box bg-green">
			<div class="inner">
				<h4><?php
                    echo Yii::t('main-ui', 'Create ticket'); ?></h4>
				<p><?php
                    echo Yii::t('main-ui', 'without an account'); ?></p>
			</div>
			<div class="icon">
				<i class="fa-solid fa-plus"></i>
			</div>
			<a href="/portal/#create" class="small-box-footer">
                <?php
                echo Yii::t('main-ui', 'Create ticket'); ?>
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
    <?php
    if (Yii::app()->params['portalAllowRegister'] == 1): ?>
		<div class="span<?php
        echo $span; ?>">
			<!-- small box -->
			<div class="small-box bg-blue">
				<div class="inner">
					<h4><?php
                        echo Yii::t('main-ui', 'Register'); ?></h4>
					<p><?php
                        echo Yii::t('main-ui', 'if you don\'t have an account'); ?></p>
				</div>
				<div class="icon">
					<i class="fa-solid fa-user-plus"></i>
				</div>
				<a href="/site/register" class="small-box-footer">
                    <?php
                    echo Yii::t('main-ui', 'Register'); ?>
					<i class="fas fa-arrow-circle-right"></i>
				</a>
			</div>
		</div>
    <?php
    endif; ?>
	<!-- ./col -->
    <?php
    if (Yii::app()->params['portalAllowRestore'] == 1): ?>
		<div class="span<?php
        echo $span; ?>">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h4><?php
                        echo Yii::t('main-ui', 'Forgot password?'); ?></h4>

					<p><?php
                        echo Yii::t('main-ui', 'Password recovery'); ?></p>
				</div>
				<div class="icon">
					<i class="fa-solid fa-unlock"></i>
				</div>
				<a href="/site/recovery" class="small-box-footer"><?php
                    echo Yii::t('main-ui', 'Recover'); ?>
					<i class="fas fa-arrow-circle-right"></i>
				</a>
			</div>
		</div>
    <?php
    endif; ?>
	<div class="span<?php
    echo $span; ?>">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h4><?php
                    echo Yii::t('main-ui', 'Login'); ?></h4>
				<p><?php
                    echo Yii::t('main-ui', 'if you have an acount'); ?></p>
			</div>
			<div class="icon">
				<i class="fa-solid fa-door-open"></i>
			</div>
			<a href="/site/login" class="small-box-footer"><?php
                echo Yii::t('main-ui', 'Login'); ?>
				<i class="fas fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
</div>
<?php
if (Yii::app()->params['portalText']): ?>
	<div class="row-fluid">
		<div class="box box-default">
			<div class="box-body">
                <?php
                echo Yii::app()->params['portalText']; ?>
			</div>
		</div>
	</div>
<?php
endif; ?>
<div class="row-fluid">
    <?php
    if (Yii::app()->params['portalAllowNews'] == 1): ?>
		<div class="span<?php
        echo $span2; ?>">
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title"><?php
                        echo Yii::t('main-ui', 'Latest news and alerts'); ?></h3>
				</div>
				<div class="box-body">
                    <?php
                    $this->widget('bootstrap.widgets.TbGridView', array(
                        'type' => 'striped bordered condensed',
                        'id' => 'news-grid',
                        'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/news/module/view/id') . '/"+$.fn.yiiGridView.getSelection(id);}',
                        'dataProvider' => $news->searchmain(),
                        'htmlOptions' => array('style' => 'cursor: pointer'),
                        'summaryText' => '',
                        'columns' => array(
                            array(
                                'name' => 'date',
                                'headerHtmlOptions' => array('width' => 100),
                                'header' => Yii::t('main-ui', 'Created'),
                            ),
                            array(
                                'name' => 'author',
                                'headerHtmlOptions' => array('width' => 50),
                                'header' => Yii::t('main-ui', 'Author'),
                            ),
                            array(
                                'name' => 'name',
                                //'headerHtmlOptions'=> array('width'=>120),
                                'header' => Yii::t('main-ui', 'Name'),
                            ),

                            array(
                                'class' => 'bootstrap.widgets.TbButtonColumn',
                                //'headerHtmlOptions'=> array('width'=>50),
                                'template' => '{view}',
                                'buttons' => array
                                (
                                    'view' => array
                                    (
                                        'label' => Yii::t('main-ui', 'View'),
                                        'url' => 'Yii::app()->createUrl("news/module/view", array("id"=>$data->id))',
                                    ),
                                ),
                            )
                        ))); ?>
					<span class="fa-solid fa-angles-right">   </span><a
							href="/news/"><?php
                        echo Yii::t('main-ui', 'View all'); ?></a>
				</div>
			</div>
		</div>
    <?php
    endif; ?>
    <?php
    if (Yii::app()->params['portalAllowKb'] == 1): ?>
		<div class="span<?php
        echo $span2; ?>">
			<div class="box box-default">
				<div class="box-header with-border">
					<h3 class="box-title"><?php
                        echo Yii::t('main-ui', 'Latest knowledgebase records'); ?></h3>
				</div>

				<div class="box-body">
                    <?php
                    $config = array('keyField' => 'id', 'pagination' => false);
                    $rawData = $faq;
                    $dataProvider = new CArrayDataProvider($rawData, $config);
                    $this->widget('bootstrap.widgets.TbGridView', array(
                        'type' => 'striped bordered condensed',
                        'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/knowledge/module/view/id') . '/"+$.fn.yiiGridView.getSelection(id);}',
                        'id' => 'faq-grid',
                        'dataProvider' => $dataProvider,
                        'htmlOptions' => array('style' => 'cursor: pointer'),
                        'summaryText' => '',
                        'columns' => array(
                            array(
                                'name' => 'image',
                                'headerHtmlOptions' => array('width' => 10),
                                'type' => 'html',
                                'header' => CHtml::tag('i', array('class' => 'icon-paper-clip'), null),
                                'filter' => '',
                                'value' => '$data->image ? CHtml::tag("i", array("class" =>"icon-paper-clip"), null) : ""',
                            ),
                            array(
                                'name' => 'created',
                                'headerHtmlOptions' => array('width' => 100),
                                'header' => Yii::t('main-ui', 'Created'),
                            ),
                            array(
                                'name' => 'author',
                                'headerHtmlOptions' => array('width' => 50),
                                'header' => Yii::t('main-ui', 'Author'),
                            ),
                            array(
                                'name' => 'name',
                                //'headerHtmlOptions'=> array('width'=>120),
                                'header' => Yii::t('main-ui', 'Name'),
                            ),

                            array(
                                'class' => 'bootstrap.widgets.TbButtonColumn',
                                //'headerHtmlOptions'=> array('width'=>50),
                                'template' => '{view}',
                                'buttons' => array
                                (
                                    'view' => array
                                    (
                                        'label' => Yii::t('main-ui', 'View'),
                                        'url' => 'Yii::app()->createUrl("knowledge/module/view", array("id"=>$data->id))',
                                    ),
                                ),
                            )
                        ))); ?>
					<span class="fa-solid fa-angles-right"></span> <a
							href="/knowledge/"><?php
                        echo Yii::t('main-ui', 'View all'); ?></a>
				</div>
			</div>
		</div>
    <?php
    endif; ?>
</div>
<div class="row-fluid">
	<!-- quick email widget -->
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data',
            'onSubmit' => 'document.getElementById("create_btn").disabled=true;'
        ),
        'id' => 'request-form',
        'action' => Yii::app()->createUrl('/portal/create'),
        'enableAjaxValidation' => false,
    )); ?>
	<div class="box box-info">
		<div class="box-header">
			<i class="fa-solid fa-ticket fa-xl"></i>
			<h3 class="box-title"><?php
                echo Yii::t('main-ui', 'Create ticket'); ?></h3>
		</div>
		<div id="create" class="box-body">
            <?php
            echo $form->errorSummary($model); ?>
			<div class="row-fluid">
                <?php
                echo $form->textFieldRow($model, 'depart', array('maxlength' => 100, 'class' => 'span12', 'placeholder' => Yii::t('main-ui', 'Here your email'))); ?>
			</div>
            <?php
            if (Yii::app()->params['portalAllowService']): ?>
				<div class="row-fluid">
                    <?php
                    $services = Service::getAllShared();
                    foreach ($services as $key => $value) {
                        if (!isset($allServices[$key])) {
                            $allServices[$key] = $value;
                        }
                    }
                    asort($allServices);
                    echo $form->select2Row($model, 'service_id', [
                        'data' => $allServices,
                        'multiple' => false,
                        'id' => 'service',
                        'options' => ['width' => '100%'],
                        'empty' => '',
                        'ajax' => [
                            'type' => 'POST',
                            'dataType' => 'json',
                            'url' => CController::createUrl('Portal/SelectService'),
                            'success' => 'function(data) {
                var id = data.fid;
                var csrf = data.csrf;
                if (data.description || data.content){
                  if ($("#PortalRequest_Name").val() || $("textarea").val()){
                      swal({
                        title: "Внимание! Будут заменены введеные данные в поля Наименование и Содержание на шаблонные. Заменить значения?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "' . Yii::t('main-ui', 'Yes') . '",
                        cancelButtonText: "' . Yii::t('main-ui', 'No') . '",
                      }).then(function (result) {
                        if (result.value) {
                          if (data.description) $("#PortalRequest_Name").val(data.description);
                          if (data.content) $(".redactor-in-0").html(data.content);
                          if (data.content) $("textarea").val(data.content);
                        }
                      });
                  } else {
                    if (data.description) $("#PortalRequest_Name").val(data.description);
                    if (data.content) $(".redactor-in-0").html(data.content);
                    if (data.content) $("textarea").val(data.content);
                  }
                }
                $.ajax({
                  type: "POST",
                  url:  "/Portal/SetFields",
                  data: {"id":id, "YII_CSRF_TOKEN":csrf},
                  dataType: "text",
                  cache: false,
                  update: "#fields",
                  error: function(e) {
                    console.log(e);
                  },
                  success: function(data) {
                    $("#fields").html(data);
                    if (data){
                      $("#fields").show();
                    }else{
                      $("#fields").hide();
                    }
                  }
                });
              }',
                        ]
                    ]); ?>
				</div>
            <?php
            endif; ?>
			<div class="row-fluid">
                <?php
                echo $form->textFieldRow($model, 'Name', array('maxlength' => 100, 'class' => 'span12', 'placeholder' => Yii::t('main-ui', 'Name'))); ?>
			</div>

			<div class="row-fluid">
                <?php
                echo $form->textAreaRow($model, 'Content', array('id' => 'Content', 'rows' => 5));
                echo '<div id="fields" class="row-fluid" style="display: none"></div>';
                ?>
                <?php
                Yii::app()->clientScript->registerScript('redactor-init', "
                     function addField(id) {
                        if(id){
                            $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"PortalRequest[files][]\">');
                        }
                     }
                     $(function () {
                            $('#Content').redactor({
                                lang: 'ru',
                                plugins: ['fullscreen', 'video'],
                                linkValidation: false,
                                linkSize: 200,
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
			<br/>
			<div class="row-fluid">
                <?php
                if (!Yii::app()->user->checkAccess('uploadFilesRequest')): ?>
                    <?php
                    if ($model->image == null) {
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
            <?php
            if (Yii::app()->params['portalAllowCaptcha'] == 1): ?>
				<div class="row-fluid">
					<div class="span3">
                        <?php
                        if (CCaptcha::checkRequirements() && Yii::app()->user->isGuest): ?>
                            <?php
                            $this->widget('CCaptcha', array('clickableImage' => true)) ?>
							<br/>
                            <?php
                            echo CHtml::activeTextField($model, 'verifyCode', array('placeholder' => Yii::t('main-ui', 'Verify code'), 'class' => 'span12')); ?>
                            <?php
                            echo $form->error($model, 'verifyCode'); ?>
                        <?php
                        endif; ?>
					</div>
				</div>
            <?php
            endif; ?>
		</div>
		<div class="box-footer clearfix">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'id' => 'create_btn',
                'type' => 'primary',
                'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
            )); ?>
		</div>
	</div>
    <?php
    $this->endWidget(); ?>
</div>
<script>
	$(document).ready(function () {
		var id   = $('#service').val();
		var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
		$.ajax({
			type:     'POST',
			url:      '/portal/setfields2',
			data:     {'id': id, 'YII_CSRF_TOKEN': csrf},
			dataType: 'text',
			cache:    false,
			update:   '#fields',
			error:    function (e) {
				console.log(e);
			},
			success:  function (data) {
				$('#fields').css({'display': 'block'});
				$('#fields').html(data);
			}
		});
	});
</script>