<?php

if ($model->round_days == 1) {
    $round_days = 'Да';
} else {
    $round_days = 'Нет';
}; ?>
<?php
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Service level') => array('index'),
    $model->name,
);
$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List service levels'))),
    Yii::app()->user->checkAccess('updateSla') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' => $model->id), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List service levels'))) : NULL,
);
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
                    Yii::app()->user->checkAccess('viewHistoryProblem') ? array('label' => Yii::t('main-ui', 'SLA history'), 'content' => $this->renderPartial('_history', array('history' => $history), true)) : NULL,
                )),
            )
        ); ?>
    </div>
</div>

