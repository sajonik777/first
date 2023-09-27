<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Problems') => array('index'),
    $model->id,
);

$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Manage problems'))),
    Yii::app()->user->checkAccess('updateProblem') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' => $model->id), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Update problem'))) : array(NULL),
);
?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Problem'); ?> #<?php echo $model->id; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget(
            'bootstrap.widgets.TbTabs',
            array(
                'type' => 'tabs', // 'tabs' or 'pills'
                'tabs' => array_filter(array(
                    array(
                        'label' => Yii::t('main-ui', 'Description'),
                        'content' => $this->renderPartial('_cuview', array('model' => $model, 'files' => $files), true),
                        'active' => true
                    ),
                    array_filter($zayav) ? array('label' => Yii::t('main-ui', 'Assigned incidents'), 'content' => $this->renderPartial('_cuincidents', array('zayav' => $zayav), true)) : NULL,
                    array_filter($units) ? array('label' => Yii::t('main-ui', 'Assigned units'), 'content' => $this->renderPartial('_cunits', array('units' => $units), true)) : NULL,
                    Yii::app()->user->checkAccess('viewHistoryProblem') ? array('label' => Yii::t('main-ui', 'Problem history'), 'content' => $this->renderPartial('_history', array('history' => $history), true)) : NULL,
                )),
            )
        ); ?>
    </div>
    <?php
    if ($model->files) {
        FilesShow::show($model->files, 'problem', '/uploads', '', 'Problem');
    }
    ?>
    <?php if ($model->image): ?>
        <?php
        FilesShow::show($files, 'problem','/media/problems/', $model->id, 'Problem');
        ?>

    <?php endif; ?>
</div>


