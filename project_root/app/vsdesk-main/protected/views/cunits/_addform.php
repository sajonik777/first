<?php

Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>
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
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'cunits-form',
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
            'enableAjaxValidation' => false,
        )); ?>
		<div>
            <?php
            echo $form->errorSummary($model); ?>
		</div>
		<div class="row-fluid">
			<div class="span6">
                <?php
                echo $form->textFieldRow($model, 'name', array('maxlength' => 100, 'class' => 'span12')); ?>
                <?php
                echo $form->dropDownListRow($model, 'type', CunitTypes::All(), array('class' => 'span12')); ?>
                <?php
                echo $form->dropDownListRow($model, 'status', Astatus::All(), array('class' => 'span12')); ?>
                <?php
                echo $form->dropDownListRow($model, 'user', CUsers::ffAll(), array('class' => 'span12')); ?>
                <?php echo $form->dropDownListRow($model, 'company',Companies::All(), array('class' => 'span12','prompt' => Yii::t('main-ui', 'Select item'))); ?>
                <?php
                echo $form->textareaRow($model, 'description', array('class' => 'span12', 'cols' => 6, 'rows' => 8)); ?>
                <?php
                Yii::app()->clientScript->registerScript('redactor-init', "
                          $(function () {
                            $('#Cunits_description').redactor({
                              lang: 'ru',
                              plugins: ['alignment', 'table', 'fullscreen', 'video'],
                              imageResizable: true,
                              linkValidation: false,
                              linkSize: 200,
                              imagePosition: true,
                        });
                    });
                    ");

?>
                </div>
                <div class="span6">
                    <?php echo $form->textFieldRow($model, 'inventory', array('maxlength' => 50, 'class' => 'span12')); ?>
                    <?php //echo $form->textFieldRow($model, 'location', array('maxlength' => 100, 'class' => 'span12')); ?>
                    <label>Дата ввода в эксплуатацию</label>
                    <div class="dtpicker2">
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model' => $model,
                            'attribute' => 'datein',
                            'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                            'defaultOptions' => array(
                                'dateFormat' => 'dd.mm.yy',
                                'showButtonPanel' => true,
                                'changeYear' => true,
                            )
                            )); ?>
                        </div>
                        <label>Дата вывода из эксплуатации</label>
                        <div class="dtpicker2">
                            <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                'model' => $model,
                                'attribute' => 'dateout',
                                'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                                'defaultOptions' => array(
                                    'dateFormat' => 'dd.mm.yy',
                                    'showButtonPanel' => true,
                                    'changeYear' => true,
                                )
                                )); ?>
                            </div>
                            <?php echo $form->labelEx($model, 'warranty_start'); ?>
                <div class="dtpicker2">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'warranty_start',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'defaultOptions' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    )
                )); ?>
                </div>
                <?php echo $form->error($model, 'warranty_start'); ?>
                <?php echo $form->labelEx($model, 'warranty_end'); ?>
                <div class="dtpicker2">
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'model' => $model,
                    'attribute' => 'warranty_end',
                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                    'defaultOptions' => array(
                        'dateFormat' => 'dd.mm.yy',
                        'showButtonPanel' => true,
                        'changeYear' => true,
                    )
                )); ?>
                </div>
                            <!-- <?php echo $form->textFieldRow($model, 'cost', array('readonly' => 'true', 'append' => 'руб.', 'class' => 'span5', 'maxlength' => 50)); ?> -->
                        </div>
                        
                    </div>
                    <div class="row-fluid">
                        <?php
                        $view = NULL;
                        $update = NULL;
                        $delete = NULL;
                        if (Yii::app()->user->checkAccess('viewAsset')) {
                            $view = '{view}';
                        }
                        if (Yii::app()->user->checkAccess('printAsset')) {
                            $print = '{print}';
                        }
                        $delete = '{delete}';
                        $template = $view . ' ' . $print . ' ' . $delete;
                        $config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10), 'sort' => array('attributes' => array('name', 'slabel', 'asset_attrib_name', 'inventory', 'cost')));
                        $rawData = $assets;
                        $dataProvider = new CArrayDataProvider($rawData, $config);
                        ?>
                        <hr/>
                        <h4><?php echo Yii::t('main-ui', 'Unit assets'); ?></h4>
                        <?php
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'label' => Yii::t('main-ui', 'Add asset'),
                            'icon' => 'fa-solid fa-plus',

                'htmlOptions' => array(
                    'data-toggle' => 'modal',
                    'data-target' => '#myModal',
                ),
            ));
            ?>
            <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id' => 'assets-grid',
                'type' => 'striped bordered condensed',
                'summaryText' => '',
                'dataProvider' => $dataProvider,
                'columns' => array(
                    array(
                        'name' => 'slabel',
                        'headerHtmlOptions' => array('width' => 150),
                        'type' => 'html',
                        'header' => Yii::t('main-ui', 'Status'),
                        //'filter' => Astatus::all(),

                    ),
                    array(
                        'name' => 'asset_attrib_name',
                        'header' => Yii::t('main-ui', 'Asset type'),
                    ),
                    array(
                        'name' => 'name',
                        'header' => Yii::t('main-ui', 'Name'),
                    ),
                    array(
                        'name' => 'inventory',
                        'header' => Yii::t('main-ui', 'Inventory #'),
                    ),
                    // array(
                    //     'name' => 'cost',
                    //     'header' => Yii::t('main-ui', 'Cost'),
                    // ),
                    array(
                        'class' => 'bootstrap.widgets.TbButtonColumn'
                    , 'template' => $template
                    , 'deleteButtonUrl' => 'Yii::app()->createUrl("/cunits/delete_asset", array("id"=>$data->id, "mid"=>' . $model->id . '))'
                    , 'viewButtonUrl' => 'Yii::app()->createUrl("/asset/view", array("id"=>$data["id"]))',
                        'buttons' => array
                        (
                            'print' => array
                            (
                                'label' => Yii::t('main-ui', 'Print'),
                                'url' => 'Yii::app()->createUrl("asset/print", array("id"=>$data->id))',
                                'icon' => 'icon-print',
                            ),

                        )
                        //Здесь мы перегружаем страницу для получения пересчитаных результатов
                    , 'afterDelete' => 'function(){
                                        document.location.reload(true)
                                    }'
                    )
                ),
            ));
            ?>
		</div>
		<br>
        <?php
        if (Yii::app()->user->checkAccess('uploadFilesUnit')): ?>
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


<?php
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

<div class="modal-header">
	<a class="close" data-dismiss="modal">&times;</a>
	<h4><?php
        echo Yii::t('main-ui', 'Select assets'); ?></h4>
</div>

<div class="modal-body">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'addasset-form',
        'enableAjaxValidation' => false,
        'action' => Yii::app()->createUrl('/cunits/add_asset', array('id' => $model->id)),
    )); ?>


    <?php
    $assets = new Asset('search');

    $criteria = new CDbCriteria();
    $criteria->addCondition('uid IS NULL');
    //$data= MODEL::model()->findAll($criteria);

    //$assets->company = new CDbExpression('NULL');

    $total = '';
    ?>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        //'selectionChanged' => Yii::app()->user->checkAccess('viewAsset') ? 'function(id){location.href = "' . $this->createUrl('/asset') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
        'id' => 'asset-grid',
        'ajaxUrl' => Yii::app()->createUrl('/asset/agrid'),
        //'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['assetPageCount'] ? Yii::app()->session['assetPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
        //'dataProvider' => $assets->search(),
        'dataProvider' => new CActiveDataProvider($assets, array('criteria' => $criteria)),
        'htmlOptions' => array('style' => 'cursor: pointer; overflow-y:scroll;'),
        'filter' => $assets,

        //'afterAjaxUpdate' => 'reinstallDatePicker',
        'columns' => array(
            array(
                'class' => 'CCheckBoxColumn',// Checkboxes
                'selectableRows' => 2,// Allow multiple selections
            ),
            array(
                'name' => 'asset_attrib_name',
                'header' => Yii::t('main-ui', 'Asset type'),
                'filter' => AssetAttrib::all(),
            ),
            array(
                'name' => 'slabel',
                'headerHtmlOptions' => array('width' => 150),
                'type' => 'html',
                'filter' => Astatus::all(),
            ),
            array(
                'name' => 'name',

            ),
            array(
                'name' => 'inventory',
                'header' => Yii::t('main-ui', 'Inventory #'),
            ),

            // array(
            //     'name' => 'cost',
            //     'headerHtmlOptions' => array('width' => 70),
            // ),
        ),
    )); ?>

</div>

<div class="modal-footer">
    <?php
    if (Yii::app()->user->checkAccess('systemManager') or Yii::app()->user->checkAccess('systemAdmin')) {
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Add selected'),
            'type' => 'primary',
            'icon' => 'icon-check',
            'id' => 'close'
        ));
        echo '&nbsp;&nbsp;';
    }
    ?>

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
Yii::app()->clientScript->registerScript('close', '
                         $("#close").click(function(){
                             var checked=$("#asset-grid").yiiGridView("getChecked","asset-grid_c0");
                             var count=checked.length;
                             if(count==0){
                                 alert("No items selected");
                             }
                             if(count>0 && confirm("Do you want to add these "+count+" item(s)"))
                             {
                                 console.log(checked);
                                 $.ajax({
                                     data:{checked:checked},
                                     url:"' . CHtml::normalizeUrl(array('cunits/add_asset', 'id' => $model->id)) . '",
                                     success:function(data){
                                         $("#asset-grid").yiiGridView("update",{});
                                         location.reload();
                                     },
                                 });
                             }
                         });
                         ');

?>
