<?php

$total = '';
$view = NULL;
$update = NULL;
$delete = NULL;
if(Yii::app()->user->checkAccess('viewKB') OR Yii::app()->user->isGuest){
    $view = '{view}';
}
if(Yii::app()->user->checkAccess('updateKB')){
    $update = '{update}';
}
if(Yii::app()->user->checkAccess('deleteKB')){
    $delete = '{delete}';
}
$template = $view.' '.$update.' '.$delete;
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Knowledgebase') => array('index'),
    Yii::t('main-ui', 'Manage'),
);
$this->menu = array(
    Yii::app()->user->checkAccess('createKB') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create new record'))) : array(NULL),
);
?>
<div class="page-header">
    <h3><i class="fa-solid fa-book fa-xl">  </i><?php echo Yii::t('main-ui', 'Knowledgebase'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
            )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
            )); ?>
            <?php if(Yii::app()->user->isGuest):?>
                <?php
                $config = array('keyField' => 'id','pagination' => array(
                    'pageSize' => (int)Yii::app()->session['knowPageCount'] ? Yii::app()->session['knowPageCount'] : 30,
                ),
                    'sort' => array('defaultOrder' => 'bcat_name ASC'));
                $rawData = $model;
                $dataProvider = new CArrayDataProvider($rawData, $config);
                $this->widget('bootstrap.widgets.TbGroupGridView', array(
                    'id' => 'brecords-grid',
                    'dataProvider' => $dataProvider,
                    'type' => 'striped bordered condensed',
                    'selectionChanged'=>'function(id){location.href = "'.$this->createUrl('/knowledge/module/view/id').'/"+$.fn.yiiGridView.getSelection(id);}',
                    'summaryText' => '<div class="items_col2"> '. Yii::t('main-ui', 'Items: ').''. CHtml::dropDownList('',Yii::app()->session['knowPageCount'] ? Yii::app()->session['knowPageCount'] : 30,Yii::app()->params['selectPageCount'],array('onchange'=>"document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")).'</div> '.Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total),
                    'mergeColumns' => array('bcat_name'),
                    'htmlOptions' => array('style'=>'cursor: pointer'),
                    'afterAjaxUpdate' => 'reinstallDatePicker',
                    'columns' => array(
                        array(
                            'name' => 'bcat_name',
                            'header' => Yii::t('main-ui', 'Category'),
                            'headerHtmlOptions' => array('width' => 300),
                            'filter' => Categories::model()->call(),

                        ),
                        array(
                            'name'             =>'image',
                            'headerHtmlOptions'=> array('width'=>10),
                            'type'             =>'raw',
                            'filter'           => '',
                            'header' => CHtml::tag('i', array('class' => "fa-solid fa-paperclip"), null),
                            'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"fa-solid fa-paperclip"), null) : ""',
                        ),
                        array(
                            'name' => 'author',
                            'header' => Yii::t('main-ui', 'Author'),
                            'headerHtmlOptions' => array('width' => 300),

                        ),
                        array(
                            'name' => 'created',
                            'header' => Yii::t('main-ui', 'Created'),
                            'headerHtmlOptions' => array('width' => 120),

                        ),
                        array(
                            'name' => 'name',
                            'header' => Yii::t('main-ui', 'Name'),

                        ),

                        array(
                            'class' => 'bootstrap.widgets.TbButtonColumn',
                            'headerHtmlOptions' => array('width' => 70),
                            'template'         => '{view}',
                            'buttons'                    =>array
                            (
                                'view'     => array
                                (
                                    'label'=>Yii::t('main-ui','View'),
                                    'url'  =>'Yii::app()->createUrl("/knowledge/module/view/", array("id"=>$data->id))',
                                ),),
                        ),
                    ),
                    )); ?>
                <?php else: ?>
                    <?php $this->widget('bootstrap.widgets.TbGroupGridView', array(
                        'id' => 'brecords-grid',
                        'dataProvider' => $model->search(),
                        'htmlOptions' => array('style'=>'cursor: pointer'),
                        'type' => 'striped bordered condensed',
                        'selectionChanged'=>Yii::app()->user->checkAccess('viewKB')?'function(id){location.href = "'.$this->createUrl('/knowledge/module/view/id').'/"+$.fn.yiiGridView.getSelection(id);}':NULL,
                        'summaryText' => '<div class="items_col2"> '. Yii::t('main-ui', 'Items: ').''. CHtml::dropDownList('',Yii::app()->session['knowPageCount'] ? Yii::app()->session['knowPageCount'] : 30,Yii::app()->params['selectPageCount'],array('onchange'=>"document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")).'</div> '.Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total),
                        'filter' => $model,
                        'afterAjaxUpdate' => 'reinstallDatePicker',
                        'mergeColumns' => array('bcat_name'),
                        'pager' => array(
                            'class' => 'CustomPager',
                            'displayFirstAndLast' => true,
                        ),
                        'columns' => array(
                            array(
                                'name' => 'bcat_name',
                                'headerHtmlOptions' => array('width' => 300),
                                'filter' => Categories::model()->call(),

                            ),
                            array(
                                'name'             =>'image',
                                'headerHtmlOptions'=> array('width'=>10),
                                'type'             =>'raw',
                                'filter'           => '',
                                'header' => CHtml::tag('i', array('class' => "fa-solid fa-paperclip"), null),
                                'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"fa-solid fa-paperclip"), null) : ""',
                            ),
                            array(
                                'name' => 'author',
                                'headerHtmlOptions' => array('width' => 300),

                            ),
                            array(
                                'name' => 'created',
                                'headerHtmlOptions' => array('width' => 120),
                                'filter' => '<div class="dtpicker">'.$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                    'model' => $model,
                                    'attribute' => 'created',
                                    'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                                    'htmlOptions' => array(
                                        'id' => 'newDatepicker',
                                    ),
                                    'defaultOptions' => array(
                                        'dateFormat' => 'dd.mm.yy',
                                        'showButtonPanel' => true,
                                        'changeYear' => true,
                                    )
                                ),
                                true).'</div>',
                            ),
                            'name',

                            array(
                                'class' => 'bootstrap.widgets.TbButtonColumn',
                                'headerHtmlOptions' => array('width' => 70),
                                'header'=> Yii::t('main-ui', 'Actions'),
                                'template'         => $template,
                            ),
                        ),
                        )); ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
               function reinstallDatePicker(id, data) {
                   $('#newDatepicker').datepicker();
               }
               "); ?>
