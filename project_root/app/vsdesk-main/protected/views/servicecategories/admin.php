<?php

/* @var $this ServiceCategoriesController */
/* @var $model ServiceCategories */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Service categories catalog') => ['index'],
    Yii::t('main-ui', 'Manage service category catalog'),
];

// $this->menu = [
//     Yii::app()->user->checkAccess('createServiceCategory') ? [
//         'icon' => 'fa-solid fa-circle-plus fa-xl',
//         'url' => ['create'],
//         'itemOptions' => ['title' => Yii::t('main-ui', 'Create service category')]
//     ] : [null],
// ];
$update = null;
$delete = null;
if (Yii::app()->user->checkAccess('updateService')) {
    $update = '{update}';
}
// if (Yii::app()->user->checkAccess('deleteService')) {
//     $delete = '{delete}';
// }
$template = $update . ' ' . $delete;

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#service-categories-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="page-header">
    <h3><i class="fa-solid fa-layer-group fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage service category catalog'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', [
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        ]); ?>
        <?php $this->widget('bootstrap.widgets.TbGridView', [
            'id' => 'services-grid',
            'type' => 'striped bordered condensed',
            'selectionChanged' => Yii::app()->user->checkAccess('updateServiceCategory') ? 'function(id){location.href = "' . $this->createUrl('/servicecategories/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : null,
            'dataProvider' => $model->search(),
            'htmlOptions' => ['style' => 'cursor: pointer'],
            'filter' => $model,
            'pager' => [
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ],
            'columns' => [
                'name',
//                'serviceNames',
                [
                    'name' => 'serviceNames',
                    'header' => Yii::t('main-ui', 'Services'),
                    'type' => 'raw',
                    'filter' => '',
                ],
                [
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => $template,
                    'header' => Yii::t('main-ui', 'Actions'),
                ],
            ],
        ]); ?>
    </div>
</div>
