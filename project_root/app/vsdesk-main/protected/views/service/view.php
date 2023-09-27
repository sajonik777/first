<?php

/** @var $model Service */

$this->breadcrumbs = [
    Yii::t('main-ui', 'Service catalog') => ['index'],
    $model->name,
];
$this->menu = [
    Yii::app()->user->checkAccess('listService') ? [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'List services')]
    ] : [null],
    Yii::app()->user->checkAccess('createService') ? [
        'icon' => 'fa-solid fa-circle-plus fa-xl',
        'url' => ['create'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Create service')]
    ] : [null],
    Yii::app()->user->checkAccess('updateService') ? [
        'icon' => 'fa-solid fa-pencil fa-xl',
        'url' => ['update', 'id' => $model->id],
        'itemOptions' => ['title' => Yii::t('main-ui', 'Edit service')]
    ] : [null],
];
?>
<div class="page-header">
    <h3><?php echo $model->name; ?></h3>
</div>

<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]); ?>

        <!-- Yii::app()->user->checkAccess('viewHistoryProblem') ? array('label' => Yii::t('main-ui', 'Problem history'), 'content' => $this->renderPartial('_history', array('history' => $history), true)) : NULL, -->
        <?php $this->widget(
            'bootstrap.widgets.TbTabs',
            array(
                'type' => 'tabs', // 'tabs' or 'pills'
                'tabs' => array_filter(array(
                    array(
                        'label' => Yii::t('main-ui', 'Description'),
                        'content' => $this->renderPartial('_description', array('model' => $model, 'files' => $files), true),
                        'active' => true
                    ),
                    $zayav ? array('label' => Yii::t('main-ui', 'Assigned incidents'), 'content' => $this->renderPartial('_cuincidents', array('zayav' => $zayav), true)) : NULL,
                    // array_filter($units) ? array('label' => Yii::t('main-ui', 'Assigned units'), 'content' => $this->renderPartial('_cunits', array('units' => $units), true)) : NULL,
                    Yii::app()->user->checkAccess('viewHistoryProblem') ? array('label' => Yii::t('main-ui', 'Service history'), 'content' => $this->renderPartial('_history', array('history' => $history), true)) : NULL,
                )),
            )
        ); ?>
        
    </div>
</div>
