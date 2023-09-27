<?php


if (isset($fields)) {
  Yii::app()->bootstrap->registerAssetCss('bootstrap-toggle-buttons.css');
  Yii::app()->bootstrap->registerAssetJs('jquery.toggle.buttons.js');
}

Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>

<script>
  function getKnowledgeData($box, string) {
    let loading = false;

    if (!$box) {
      return false;
    }

    if (!string || string === '') {
      $box.innerHTML = '';
    } else {
      if (!loading) {

        loading = true;

        fetch(`/knowledge/module/match?query=${ string }`)
          .then(response => response.json())
          .then(json => {

            if (!json || !json.length) {
              $box.innerHTML = '';
              loading = false;

              return;
            }

            let data = json.map(item => {
              return {
                link: `/knowledge/module/view/id/${ item.id }`,
                title: item.name
              }
            });

            let $html = '<div class="box box-default">' +
              '<div class="box-header with-border">' +
              '<h3 class="box-title">Подходящие записи <a href="/knowledge/module/" target="_blank">Базы знаний</a></h3>' +
              '<div class="box-tools pull-right">' +
              '<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>' +
              '</div>' +
              '</div>' +
              '<div class="box-body">' +
              '</div>' +
              '</div>';

            let list = document.createElement('ul');

            for (let item of data) {
              let point = `<li><a href="${ item.link }" target="_blank">${ item.title }</a></li>`;

              list.innerHTML += point;
            }

            $box.innerHTML = $html;

            $box.querySelector('.box-body').appendChild(list);

            loading = false
          })
          .catch(err => {
            loading = false;
          })
      }
    }

  }
</script>

<div class="box">
  <?php
  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'htmlOptions' => array(
      'enctype' => 'multipart/form-data',
      'onSubmit' => 'document.getElementById("create_btn").disabled=true;',
    ),
    'id' => 'request-form',
    'enableAjaxValidation' => false,

  )); ?>
  <div class="box-body">
    <?php
    echo $form->errorSummary($model); ?>
    <div class="row-fluid">
      <div class="span6">
        <div>
          <nobr>
            <?php
            $criteria = new CDbCriteria;
            $criteria->order = 'fullname ASC';
            echo $form->select2Row($model, 'CUsers_id', [
              'data' => Yii::app()->user->checkAccess('createUser') ? array('0' => '+++ ' . Yii::t('main-ui', 'Create user') . ' +++') + CHtml::listData(CUsers::model()->findAllByAttributes(array('active' => 1), $criteria), 'Username', 'fullname') : CHtml::listData(CUsers::model()->findAllByAttributes(array('active' => 1), $criteria), 'Username', 'fullname'),
              'multiple' => false,
              'id' => 'CUsers_id',
              'prompt' => '',
              'options' => ['width' => '90%'],
              'ajax' => [
                'type' => 'POST',
                'url' => CController::createUrl('Request/SelectAdmObject'),
                'update' => '#cunits',
                'success' => 'function(data) {
                  var doNotSelectServiceCategories = "' . (int)Yii::app()->user->checkAccess('doNotSelectServiceCategories') . '";
                  var csrf = "' . Yii::app()->request->csrfToken . '";
                  var user = $("#CUsers_id").val();
                  $("#cunits").html(data);
                  if (user == 0){
                    $("#addModal").modal({"show":true});
                    $(".select2-chosen").html("");
                    $("#CUsers_id").prop("selectedIndex", null); 
                  } else {
                    var serviceCategoryId = $("#service_category_id").val();
                    
                    if(doNotSelectServiceCategories != 0 || !!serviceCategoryId) {

						var selectedText = "";
						var selectedValue = $("#service").val();
						
						$("#service option").each(function(index, option) {
							var value = $(option).attr("value");
							
							if (value === selectedValue) {
								selectedText = $(option).text();
							} 
						})
						
                        $.ajax({
                          type: "POST",
                          url:  "/request/getservices",
                          data: {"user":user, "YII_CSRF_TOKEN":csrf, "category_id":serviceCategoryId},
                          dataType: "json",
                          cache: false,
                          error: function(e) {
                            console.log(e);
                          },
                          success: function(json) {
                            $("#service").html("");
                            $("#service").append("<option></option>");
                            $.each(json, function(index, value) {
                              $("#service").append(
                                "<option value=\"" + json[index].id + "\">" + json[index].text + "</option>"
                              );
                            });
                            
                            $("#service option").each(function(index, option) {
                            	if ($(option).text() === selectedText) {
                            		$(option).prop("selected", true);
                            	}
                            })
                            
                            $("#service").change();
                          }
                        });
                    }
                   
                  }
                }'
              ]
            ]);
            ?>
            &nbsp;
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
              'icon' => 'fa-solid fa-users fa-xl',
              //'type' => 'primary',
              'htmlOptions' => array(
                'data-toggle' => 'modal',
                'data-target' => '#myModal',
              ),
            ));
            ?>
          </nobr>
        </div>
      </div>
      <?php
      if (Yii::app()->user->checkAccess('doNotSelectServiceCategories')) : ?>
        <div class="span3">
          <?php
          echo CHtml::hiddenField('merged-items', $merged_items); ?>
          <nobr>
            <?php
            echo $form->select2Row($model, 'service_id', [
              'data' => array('0' => '') + Service::model()->all(),
              'multiple' => false,
              'id' => 'service',
              'options' => ['width' => '100%'],
              'ajax' => array(
                'type' => 'POST',
                'dataType' => 'json',
                'url' => CController::createUrl('Request/SelectPriority'),
                'success' => 'function(data) {
                    var id = data.fid;
                    var csrf = data.csrf;
                    $("#Priority").html(data.options);
                    if($("*").is("#watchers")) {
                      $("#watchers").select2().select2("val", data.watcher);
                      $("#watchers").select2({"width":"100%","tokenSeparators":[","]});
                    }
                    if (data.description || data.content){
                      if ($("#Request_Name").val() || $("textarea").val()){
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
                              if (data.description) $("#Request_Name").val(data.description);
                              if (data.content) {
                              if($(".redactor-in-0").length){
                                $(".redactor-in-0").html(data.content);
                              }else{
                                $(".redactor-in-1").html(data.content);
                              }
                              }
                              if (data.content) $("#Content").val(data.content);
                            }
                          });
                      } else {
                        if (data.description) $("#Request_Name").val(data.description);
                        if (data.content) {
                              if($(".redactor-in-0").length){
                                $(".redactor-in-0").html(data.content);
                              }else{
                                $(".redactor-in-1").html(data.content);
                              }
                              }
                        if (data.content) $("#Content").val(data.content);
                      }
                    }
                    
                    $.ajax(
                      {
                        type: "POST",
                        url:  "/request/setfields",
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
                      $.ajax(
                        {
                          type: "POST",
                          url: "/request/selectSLA",
                          data: {
                            "service_id": $("#service").find(":selected").val(),
                            "YII_CSRF_TOKEN": csrf
                          },
                          dataType: "text",
                          cache: false,
                          error: function(e) {
                            console.log("error", e);
                          },
                          success: function(data) {
                            let result = $.parseJSON(data);
                            $("#request_sla").empty();
                        
                            $.each(result, function(i, item) {
                              $("#request_sla").append($("<option>", {
                                value: i,
                                text: item
                              }));
                            });
                
                          }
                        });
                      }',
                    )
            ]); ?>
</div>
<div class="span3">

            <label for="request_sla"><?php echo Yii::t('main-ui', 'SLA') ?></label>
            <select class="span12" id="request_sla" name="Request[sla]">
            </select>


            
            &nbsp;
            <?php
            /*
                                   $this->widget('bootstrap.widgets.TbButton', array(
                                   'icon' => 'folder-open',
                                   'htmlOptions' => array(
                                   'data-toggle' => 'modal',
                                   'data-target' => '#myModal2',
                                 ),
                               ));
                               */ ?>
          </nobr>
        </div>
      <?php
      else : ?>
        <?php
        if (Yii::app()->user->checkAccess('doNotSelectServiceCategories')) {
          $sdata = ['0' => ''] + Service::model()->all();
        } else {
          $sdata = ['0' => ''] + Service::model()->all();
        }
        ?>
        <div class="span3">
          <?php
          echo $form->select2Row($model, 'service_category_id', [
            'data' => CHtml::listData(ServiceCategories::model()->findAll(), 'id', 'name'),
            'multiple' => false,
            'id' => 'service_category_id',
            'prompt' => '',
            'options' => ['width' => '100%']
          ]); ?>
        </div>
        <div class="span3">
          <?php
          echo CHtml::hiddenField('merged-items', $merged_items); ?>
          <nobr>
            <?php
            echo $form->select2Row($model, 'service_id', [
              'data' => $sdata,
              'multiple' => false,
              'id' => 'service',
              'options' => ['width' => '100%'],
              'ajax' => array(
                'type' => 'POST',
                'dataType' => 'json',
                'url' => CController::createUrl('Request/SelectPriority'),
                'success' => 'function(data) {
                var id = data.fid;
                var csrf = data.csrf;
                $("#Priority").html(data.options);
                if($("*").is("#watchers")) {
                  $("#watchers").select2().select2("val", data.watcher);
                  $("#watchers").select2({"width":"100%","tokenSeparators":[","]});
                }
                if (data.description || data.content){
                  if ($("#Request_Name").val() || $("textarea").val()){
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
                          if (data.description) $("#Request_Name").val(data.description);
                          if (data.content) {
                          if ($(".redactor-in-0").length) {
                            $(".redactor-in-0").html(data.content);
                           }else{
                            $(".redactor-in-1").html(data.content);
                           }
                          }
                          if (data.content) $("#Content").val(data.content);
                        }
                      });
                  } else {
                    if (data.description) $("#Request_Name").val(data.description);
                    if (data.content) {
                          if ($(".redactor-in-0").length) {
                            $(".redactor-in-0").html(data.content);
                           }else{
                            $(".redactor-in-1").html(data.content);
                           }
                          }
                    if (data.content) $("#Content").val(data.content);
                  }
                }
                $.ajax({
                  type: "POST",
                  url:  "/request/setfields",
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
              )
            ]); ?>
            &nbsp;
            <?php
            /*
                                   $this->widget('bootstrap.widgets.TbButton', array(
                                   'icon' => 'folder-open',
                                   'htmlOptions' => array(
                                   'data-toggle' => 'modal',
                                   'data-target' => '#myModal2',
                                 ),
                               ));
                               */ ?>
          </nobr>
        </div>
      <?php
      endif; ?>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <div class="span6">
          <?php
          $role = Roles::model()->findByAttributes(array('value' => strtolower(Yii::app()->user->role)));
          $list_data = CHtml::listData($role->status_rl, 'name', 'name');
          echo $form->dropDownListRow($model, 'Status', $list_data, array('class' => 'span12'));
          ?>
        </div>

        <div class="span3">
          <?php
          echo $form->dropDownListRow(
            $model,
            'ZayavCategory_id',
            Category::model()->all(),
            array('class' => 'span12')
          ); ?>
        </div>
        <div class="span3">
          <?php
          echo $form->dropDownListRow(
            $model,
            'Priority',
            Zpriority::model()->all(),
            array('id' => 'Priority', 'class' => 'span12')
          ); ?>
        </div>
      </div>
      <?php
      if (Yii::app()->user->checkAccess('canSetFieldsRequest') and !Yii::app()->user->checkAccess('downfieldsRequest')) {
        echo '<div id="fields" class="row-fluid" style="display: none"></div>';
      } ?>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <?php
        echo $form->textFieldRow($model, 'Name', array('maxlength' => 100, 'class' => 'span12')); ?>

        <div id="RequestThemeList"></div>

        <script>
          let $requestTheme = document.querySelector('#Request_Name');
          let $requestThemeHolder = document.querySelector('#RequestThemeList');

          if ($requestTheme) {
            $requestTheme.addEventListener('input', event => {
              getKnowledgeData($requestThemeHolder, $requestTheme.value)
            })
          }
        </script>

        <?php
        echo $form->textAreaRow($model, 'Content', array('id' => 'Content', 'rows' => 5));
        ?>
        <?php
        Yii::app()->clientScript->registerScript('redactor-init', "
      function addField(id) {
        if(id){
          $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"Request[files][]\">');
        }
      }
      $(function () {
        $('#Content').redactor({
          lang: 'ru',
          plugins: ['alignment', 'table','fullscreen', 'video'],
          linkValidation: false,
          linkSize: 200,
          imageResizable: true,
          imagePosition: true,
          multipleUpload: false,
          imageUpload: '" . $this->createUrl('files/upload2') . "',
          imageData: {'YII_CSRF_TOKEN': '" . Yii::app()->request->csrfToken . "'},
          fileData: {'YII_CSRF_TOKEN': '" . Yii::app()->request->csrfToken . "'},
            callbacks: {
				keyup: function(e) {
					let box = document.querySelector('#RequestContentList');
					let value = e.target.innerText.trim();
					getKnowledgeData(box, value);
				},
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
        <small><?php
                echo Yii::t('main-ui', 'You can make screenshot by PrintScreen button and paste by Ctrl+V.'); ?> </small>
        <?php
        if (Yii::app()->user->checkAccess('canSetFieldsRequest') and Yii::app()->user->checkAccess('downfieldsRequest')) {
          echo '<div id="fields" class="row-fluid" style="display: none"></div>';
        } ?>
        <div id="screens"></div>
        <br />

        <div id="RequestContentList"></div>

        <?php
        if (Yii::app()->user->checkAccess('canSetObserversRequest')) : ?>
          <?php
          echo $form->select2Row($model, 'watchers', array(
            'id' => 'watchers',
            'data' => CUsers::model()->wall(),
            'multiple' => 'multiple',
            'options' => array(
              'width' => '100%',
              'tokenSeparators' => array(','),
            ),
          ));
          ?>
        <?php
        endif; ?>
        <?php
        if (Yii::app()->user->checkAccess('canSetUnitRequest')) : ?>
          <?php
          echo $form->select2Row($model, 'cunits', array(
            'data' => Cunits::model()->mall(),
            'multiple' => 'multiple',
            'id' => 'cunits',
            'options' => array(
              'width' => '100%',
              'tokenSeparators' => array(','),
            ),
          ));
          ?>
        <?php
        endif; ?>

        <br />
        <?php
        if (Yii::app()->user->checkAccess('uploadFilesRequest')) : ?>
          <?php
          if ($model->image == null) {
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
                var fileSize= e.files[0].size;
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
          <p class="help-block">' . Yii::t(
              'main-ui',
              'Max.'
            ) . ' ' . Yii::app()->params->max_file_size . ' Kb</p>
          <div class="MultiFile-list" id="image_wrap"></div>
          </div>';
          }
          ?>
        <?php
        endif; ?>
      </div>
    </div>
  </div>
  <div class="box-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
      'buttonType' => 'submit',
      'type' => 'primary',
      'id' => 'create_btn',
      'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
    )); ?>
  </div>
  <?php
  $this->endWidget(); ?>
</div>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
  'id' => 'showuser-form',
  'enableAjaxValidation' => false,
)); ?>
<?php
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>
<div class="modal-header">
  <a class="close" data-dismiss="modal">&times;</a>
  <h4><?php
      echo Yii::t('main-ui', 'Select user'); ?></h4>
</div>
<div class="modal-body">
  <?php
  $assets = new CUsers('search');
  $criteria = new CDbCriteria();
  $total = '';
  ?>
  <?php
  $model2 = new CUsers('search');
  $this->renderPartial('_ugrid', array(
    'model' => $model2,
  ));
  ?>
</div>

<div class="modal-footer">
  <?php
  $this->widget('bootstrap.widgets.TbButton', array(
    'label' => Yii::t('main-ui', 'Cancel'),
    'url' => '#',
    'type' => 'primary',
    'htmlOptions' => array('data-dismiss' => 'modal'),
  )); ?>

</div>
<?php
$this->endWidget(); ?>
<?php
$this->endWidget(); ?>
<?php
$fuser = new FRegisterForm();
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
  'id' => 'adduser-form',
  'enableAjaxValidation' => true,
  'clientOptions' => array(
    'validateOnSubmit' => true,
  ),
  'action' => Yii::app()->createUrl('/cusers/fastadd', array('call' => NULL, 'ticket' => NULL,)),
)); ?>
<?php
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'addModal')); ?>
<div class="modal-header">
  <a class="close" data-dismiss="modal">&times;</a>
  <h4><?php
      echo Yii::t('main-ui', 'Create user'); ?></h4>
</div>
<div class="modal-body">
  <?php
  echo $form->errorSummary($fuser); ?>
  <div class="row-fluid">
    <div class="span12">
      <?php
      echo $form->labelEx($fuser, 'company');
      $this->widget(
        'bootstrap.widgets.TbTypeahead',
        array(
          'model' => $fuser,
          'attribute' => 'company',
          'options' => array(
            'source' => Companies::model()->eall(),
            'items' => 4,
            'matcher' => <<<ENDL
js:function(item) {
return ~item.toLowerCase().indexOf(this.query.toLowerCase());
}
ENDL
          ),
          'htmlOptions' => array(
            'class' => 'span12',

          ),
        )
      );
      echo $form->textFieldRow($fuser, 'fullname', array('class' => 'span12', 'onkeyup' => 'translit()')); ?>
    </div>
    <div class="row-fluid">
      <div class="span6"><?php
                          echo $form->textFieldRow($fuser, 'Username', array('class' => 'span12')); ?></div>
      <div class="span6"><?php
                          echo $form->passwordFieldRow($fuser, 'Password', array('class' => 'span12')); ?></div>
    </div>
    <div class="row-fluid">
      <div class="span6"><?php
                          echo $form->textFieldRow($fuser, 'Email', array('class' => 'span12', 'value' => $model->CUsers_id ? NULL : $model->fullname)); ?></div>
      <div class="span6"><?php
                          echo $form->textFieldRow($fuser, 'Phone', array('class' => 'span12')); ?></div>
    </div>
  </div>
</div>

<div class="modal-footer">
  <?php
  $this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => Yii::t('main-ui', 'Create'),
  )); ?>
  <?php
  $this->widget('bootstrap.widgets.TbButton', array(
    'label' => Yii::t('main-ui', 'Cancel'),
    'url' => '#',
    'htmlOptions' => array('data-dismiss' => 'modal'),
  )); ?>
</div>
<?php
$this->endWidget(); ?>
<?php
$this->endWidget(); ?>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
  if($('#company22')) $('#company22').select2();
}
");
?>
<script>
  $(document).ready(function() {
    var id = $('#service').val();
    var serviceCategoryId = $('#service_category_id').val();
    var user = $('#CUsers_id').val();
    var copy = "<?php echo $copy; ?>";
    var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
    $.ajax({
      type: 'POST',
      url: '/request/setfields2',
      data: {
        'id': id,
        'YII_CSRF_TOKEN': csrf
      },
      dataType: 'text',
      cache: false,
      update: '#fields',
      error: function(e) {
        console.log(e);
      },
      success: function(data) {
        $('#fields').css({
          'display': 'block'
        });
        $('#fields').html(data);
        if (copy != true && user) {
          $.ajax({
            type: 'POST',
            url: '/request/getservices',
            data: {
              'user': user,
              'YII_CSRF_TOKEN': csrf,
              'category_id': serviceCategoryId
            },
            dataType: 'json',
            cache: false,
            error: function(e) {
              console.log(e);
            },
            success: function(json) {
              $('#service').html('');
              $('#service').append('<option></option>');
              $.each(json, function(index, value) {
                $('#service').append(
                  '<option value="' + json[index].id + '">' + json[index].text + '</option>'
                );
              });
              $('#service').val(id);
            }
          });
        }
      }
    });

    const getSla = () => {
      // console.log("a;sdhklahkflhka");
      let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
      // console.log(s);

      $.ajax({
        type: "GET",
        url: "/request/selectSLA",
        data: {
          "service_id": $("#service").find(":selected").val(),
          "YII_CSRF_TOKEN": csrf
        },
        dataType: "text",
        cache: false,
        error: function(e) {
          console.log('error', e);
        },
        success: function(data) {
          let result = $.parseJSON(data);
          $('#request_sla').empty();
          $.each(result[0], function(i, item) {
            $('#request_sla').append($('<option>', {
              value: i,
              text: item,
              selected: i == result[1] ? true : false
            }));
          });

        }
      });
    }

    getSla();

    console.log("$('#service').select2();");
    $("#service").on("select2:select", (s) => {
      let csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
      console.log(s);
      $.ajax({
        type: "GET",
        url: "/request/selectSLA",
        data: {
          "service_id": s.target.value,
          "YII_CSRF_TOKEN": csrf
        },
        dataType: "text",
        cache: false,
        error: function(e) {
          console.log('error', e);
        },
        success: function(data) {
          let result = $.parseJSON(data);
          $('#request_sla').empty();
          $('#request_sla').append($('<option>', {
            value: "",
            text: "--- Выберите улицу ---",
          }));
          // <option disabled selected value> -- select an option -- </option>
          $.each(result, function(i, item) {
            $('#request_sla').append($('<option>', {
              value: i,
              text: item
            }));
          });

        }
      });
    });



  });

  function translit() {
    // Символ, на который будут заменяться все спецсимволы
    var space = '_';
    // Берем значение из нужного поля и переводим в нижний регистр
    var text = $('#FRegisterForm_fullname').val().toLowerCase();

    // Массив для транслитерации
    var transl = {
      'а': 'a',
      'б': 'b',
      'в': 'v',
      'г': 'g',
      'д': 'd',
      'е': 'e',
      'ё': 'e',
      'ж': 'zh',
      'з': 'z',
      'и': 'i',
      'й': 'j',
      'к': 'k',
      'л': 'l',
      'м': 'm',
      'н': 'n',
      'о': 'o',
      'п': 'p',
      'р': 'r',
      'с': 's',
      'т': 't',
      'у': 'u',
      'ф': 'f',
      'х': 'h',
      'ц': 'c',
      'ч': 'ch',
      'ш': 'sh',
      'щ': 'sh',
      'ъ': space,
      'ы': 'y',
      'ь': space,
      'э': 'e',
      'ю': 'yu',
      'я': 'ya',
      ' ': space,
      '_': space,
      '`': space,
      '~': space,
      '!': space,
      '@': space,
      '#': space,
      '$': space,
      '%': space,
      '^': space,
      '&': space,
      '*': space,
      '(': space,
      ')': space,
      '-': space,
      '\=': space,
      '+': space,
      '[': space,
      ']': space,
      '\\': space,
      '|': space,
      '/': space,
      '.': space,
      ',': space,
      '{': space,
      '}': space,
      '\'': space,
      '"': space,
      ';': space,
      ':': space,
      '?': space,
      '<': space,
      '>': space,
      '№': space
    };

    var result = '';
    var curent_sim = '';

    for (i = 0; i < text.length; i++) {
      // Если символ найден в массиве то меняем его
      if (transl[text[i]] != undefined) {
        if (curent_sim != transl[text[i]] || curent_sim != space) {
          result += transl[text[i]];
          curent_sim = transl[text[i]];
        }
      }
      // Если нет, то оставляем так как есть
      else {
        result += text[i];
        curent_sim = text[i];
      }
    }

    result = TrimStr(result);

    // Выводим результат
    $('#FRegisterForm_Username').val(result);

  }

  function TrimStr(s) {
    s = s.replace(/^-/, '');
    return s.replace(/-$/, '');
  }

  // Выполняем транслитерацию при вводе текста в поле
  $(function() {
    $('#name').keyup(function() {
      translit();
      return false;
    });
  });
  $('#service_category_id').change(function() {
    var serviceCategoryId = $('#service_category_id').val();
    var user = $('#CUsers_id').val();
    var csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
    $.ajax({
      type: 'POST',
      url: '/request/getservices',
      data: {
        'user': user,
        'YII_CSRF_TOKEN': csrf,
        'category_id': serviceCategoryId
      },
      dataType: 'json',
      cache: false,
      error: function(e) {
        console.log(e);
      },
      success: function(json) {
        $('#service').html('');
        $('#service').append('<option></option>');
        $.each(json, function(index, value) {
          $('#service').append(
            '<option value="' + json[index].id + '">' + json[index].text + '</option>'
          );
        });
        $('#service').change();
      }
    });



  });
  // });
</script>
