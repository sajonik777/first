<?php

/** @var $this RequestProcessingRules */
/** @var $model RequestProcessingRules */
/** @var $readOnly bool */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Request processing rules') => ['index'],
    $model->name => ['index'],
    Yii::t('main-ui', 'Edit'),
];

$this->menu = [
    Yii::app()->user->checkAccess('listRequestProcessingRules') ? [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Request processing rules')]
    ] : [null],
];
?>
<div class="page-header">
    <h3><?php echo $model->name; ?></h3>
</div>

<?php
echo $this->renderPartial('_upform', [
    'model' => $model,
    'readOnly' => $readOnly,
]);
?>
