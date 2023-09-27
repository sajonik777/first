<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Fieldsets') => array('index'),
    Yii::t('main-ui', 'Manage'),
);
$update = NULL;
$delete = NULL;
$total = NULL;
if (Yii::app()->user->checkAccess('updateFieldsets')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteFieldsets')) {
    $delete = '{delete}';
}
$template = $update . ' ' . $delete;
$this->menu = array(
    Yii::app()->user->checkAccess('createFieldsets') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create Fieldsets'))) : array(NULL),
);

?>
<div class="page-header">
    <h3><i class="fa-solid fa-list-check fa-xl"> </i><?php echo Yii::t('main-ui', 'Fieldsets'); ?></h3>
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
        <?php $this->widget('bootstrap.widgets.TbExtendedGridView', array(
            'type' => 'striped bordered condensed',
            'id' => 'fieldsets-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['fieldsetsPageCount'] ? Yii::app()->session['fieldsetsPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'selectionChanged' => Yii::app()->user->checkAccess('updateFieldsets') ? 'function(id){location.href = "' . $this->createUrl('/fieldsets/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'columns' => array(
                'name',
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'header' => Yii::t('main-ui', 'Actions'),
                    'template' => $template,
                ),
            ),
            )); ?>
        </div>
    </div>
