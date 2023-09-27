<?php

/**
 * @var $model RequestProcessingRules
 */

$total = '';
$this->breadcrumbs = [
    Yii::t('main-ui', 'Request processing rules') => ['index'],
    Yii::t('main-ui', 'Manage request processing rules'),
];

$this->menu = [
    Yii::app()->user->checkAccess('createRequestProcessingRules') ? [
        'icon' => 'fa-solid fa-circle-plus fa-xl',
        'url' => ['create'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Create rule')]
    ] : [null],
];

$template = null;
if (Yii::app()->user->checkAccess('updateRequestProcessingRules')) {
    $template .= ' {update} ';
}
if (Yii::app()->user->checkAccess('deleteRequestProcessingRules')) {
    $template .= ' {delete} ';
}

?>

<div class="page-header">
    <h3><i class="fa-solid fa-sitemap fa-xl"> </i><?php echo Yii::t('main-ui', 'Request processing rules'); ?></h3>
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
            'id' => 'requestProcessingRules-grid',
            'dataProvider' => $model->search(),
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('',
                    Yii::app()->session['RequestProcessingRulesPageCount'] ? Yii::app()->session['RequestProcessingRulesPageCount'] : 30,
                    Yii::app()->params['selectPageCount'],
                    ['onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;"]) . '</div> ' . Yii::t('zii',
                    'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'selectionChanged' => Yii::app()->user->checkAccess('updateRequestProcessingRules') ? 'function(id){location.href = "' . $this->createUrl('/requestprocessingrules/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : null,
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
