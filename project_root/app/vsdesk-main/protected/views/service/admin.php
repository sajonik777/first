<?php

$total = '';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Service catalog') => array('index'),
    Yii::t('main-ui', 'Manage service catalog'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('createService') ? array(
        'icon' => 'fa-solid fa-circle-plus fa-xl',
        'url' => array('create'),
        'itemOptions' => array('title' => Yii::t('main-ui', 'Create service'))
    ) : array(null),
);
$view = null;
$update = null;
$delete = null;
if (Yii::app()->user->checkAccess('viewService')) {
    $view = '{view}';
}
if (Yii::app()->user->checkAccess('updateService')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteService')) {
    $delete = '{delete}';
}
$template = $view . ' ' . $update . ' ' . $delete;
?>
<div class="page-header">
    <h3><i class="fa-solid fa-layer-group fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage service catalog'); ?></h3>
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
        <?php $this->widget('FilterGridResizable', array(
            'id' => 'services-grid',
            'type' => 'striped bordered condensed',
            'selectionChanged' => Yii::app()->user->checkAccess('updateService')
				? 'function(id){location.href = "' . $this->createUrl('/service/update') . '/"+$.fn.yiiGridView.getSelection(id);}'
				: 'function(id){location.href = "' . $this->createUrl('/service/') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('',
                    Yii::app()->session['servicesPageCount'] ? Yii::app()->session['servicesPageCount'] : 30,
                    Yii::app()->params['selectPageCount'],
                    array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii',
                    'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'dataProvider' => $model->search(),
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'filter' => $model,
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'columns' => array(
                'name',
                'sla',
                'priority',
                array(
                    'name' => 'manager_name',
                    'value' => '$data->gtype == 1 ? $data->manager_name : $data->group',
                ),
                'availability',
                'description',
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => $template,
                    'header' => Yii::t('main-ui', 'Actions'),
                ),
            ),
        )); ?>
    </div>
</div>