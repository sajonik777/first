<?php

/* @var $this ReplyTemplatesController */
/* @var $model ReplyTemplates */
$total = NULL;
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reply templates') => array('index'),
    Yii::t('main-ui', 'Manage'),
);

$update = NULL;
$delete = NULL;

if (Yii::app()->user->checkAccess('updateTemplates')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteTemplates')) {
    $delete = '{delete}';
}
$template = $update . ' ' . $delete;
$this->menu = array(
    Yii::app()->user->checkAccess('createTemplates') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'),'itemOptions'=>array('title'=>Yii::t('main-ui', 'Create reply template'))) : array(NULL),
);

?>

<div class="page-header">
    <h3><i class="fa-solid fa-pen-to-square fa-xl"> </i><?php echo Yii::t('main-ui', 'Reply templates'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
            )); ?>

        <?php $this->widget('bootstrap.widgets.TbGridView', array(
            'id'=>'reply-templates-grid',
            'type' => 'striped bordered condensed',
            'selectionChanged' => Yii::app()->user->checkAccess('updateTemplates') ? 'function(id){location.href = "' . $this->createUrl('/replytemplates/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['rmessPageCount'] ? Yii::app()->session['rmessPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'dataProvider'=>$model->search(),
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'filter'=>$model,
            'columns'=>array(
                'name',
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => $template,
                    'header' => Yii::t('main-ui', 'Actions'),
                ),
            ),
            )); ?>
        </div>
    </div>
