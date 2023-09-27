<?php

$total = '';
$this->breadcrumbs=array(
    Yii::t('main-ui', 'News') => array('index'),
    Yii::t('main-ui', 'Manage'),
);
if (Yii::app()->user->checkAccess('createNews')){
    $this->menu = array(
        Yii::app()->user->checkAccess('createNews') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create new record'))) : array(NULL),
    );
}
$view = NULL;
$update = NULL;
$delete = NULL;
if(Yii::app()->user->checkAccess('viewNews')){
    $view = '{view}';  
}
if(Yii::app()->user->checkAccess('updateNews')){
    $update = '{update}';  
}
if(Yii::app()->user->checkAccess('deleteNews')){
    $delete = '{delete}';  
}
$template = $view.' '.$update.' '.$delete;
?>
<div class="page-header">
    <h3><i class="fa-solid fa-newspaper fa-xl">  </i><?php echo Yii::t('main-ui', 'News'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbGridView',array(
            'id'=>'news-grid',
            'dataProvider'=>$model->search(),
            'htmlOptions' => array('style'=>'cursor: pointer'),
            'selectionChanged'=>Yii::app()->user->checkAccess('viewNews')?'function(id){location.href = "'.$this->createUrl('/news/module/view/id').'/"+$.fn.yiiGridView.getSelection(id);}':NULL,
            'summaryText' => '<div class="items_col2"> '. Yii::t('main-ui', 'Items: ').''. CHtml::dropDownList('',Yii::app()->session['newsPageCount'] ? Yii::app()->session['newsPageCount'] : 30,Yii::app()->params['selectPageCount'],array('onchange'=>"document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")).'</div> '.Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total),
            'type' => 'striped bordered condensed',
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'filter'=>$model,
            'columns'=>array(
                array(
                    'name' => 'date',
                    'headerHtmlOptions' => array('width' => 120),
                    'filter' => '<div class="dtpicker">'.$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'date',
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
                array(
                    'name' => 'author',
                    'headerHtmlOptions' => array('width' => 90),

                ),
                'name',

                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'headerHtmlOptions' => array('width' => 70),
                    'template'         => $template,
                    'header'=> Yii::t('main-ui', 'Actions'),
                ),
            ),
        )); ?>
    </div>
</div>
<?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
	function reinstallDatePicker(id, data) {
	$('#newDatepicker').datepicker();
	}
	"); ?>