<?php
/* @var $this ChecklistsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Checklists') => ['index'],
    Yii::t('main-ui', 'Manage'),
];
$update = null;
$delete = null;
$total = null;
if (Yii::app()->user->checkAccess('updateChecklists')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteChecklists')) {
    $delete = '{delete}';
}
$template = $update . ' ' . $delete;
$this->menu = [
    Yii::app()->user->checkAccess('createChecklists') ? [
        'icon' => 'fa-solid fa-circle-plus fa-xl',
        'url' => ['create'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Create Checklists')]
    ] : [null],
];

?>
<div class="page-header">
    <h3><i class="fa-solid fa-list-check fa-xl"> </i>
        <?php echo Yii::t('main-ui', 'Checklists'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php
        $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>
        <?php
        $this->widget('bootstrap.widgets.TbAlert', [
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        ]); ?>
        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView', [
            'type' => 'striped bordered condensed',
            'id' => 'checklists-grid',
            'dataProvider' => $dataProvider,
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('',
                    Yii::app()->session['checklistsPageCount'] ? Yii::app()->session['checklistsPageCount'] : 30,
                    Yii::app()->params['selectPageCount'],
                    ['onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;"]) . '</div> ' . Yii::t('zii',
                    'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'selectionChanged' => Yii::app()->user->checkAccess('updateChecklists') ? 'function(id){location.href = "' . $this->createUrl('/checklists/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : null,
            'htmlOptions' => ['style' => 'cursor: pointer'],
            'pager' => [
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ],
            'columns' => [
                'name',
                [
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'header' => Yii::t('main-ui', 'Actions'),
                    'template' => $template,
                ],
            ],
        ]); ?>
    </div>
</div>
