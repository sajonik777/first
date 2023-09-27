<?php

$this->breadcrumbs = array(
	Yii::t('main-ui', 'Knowledgebase')=>array('index'),
	$model->name                      ,
    );
    $this->menu = array(
        (Yii::app()->user->checkAccess('listKB') OR Yii::app()->user->isGuest) ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List records'))): array(NULL),
        
    Yii::app()->user->checkAccess('updateKB') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' => $model->id),'itemOptions'=>array('title'=>Yii::t('main-ui', 'Edit record'))) : array(NULL),
    Yii::app()->user->checkAccess('printRequest') ? [
        'icon' => 'fa-solid fa-print fa-xl',
        'url' => '#',
        'itemOptions' => [
            'title' => Yii::t('main-ui', 'Print ticket'),
            'data-toggle' => 'modal',
            'data-target' => '#myModal5'
        ],
        'linkOptions' => ['target' => '_BLANK']
    ] : [NULL]);
?>
<div class="page-header">
    <h3>"<?php echo $model->name; ?>"</h3>
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
                    Yii::app()->user->checkAccess('viewHistoryProblem') ? array('label' => Yii::t('main-ui', 'Knowledge history'), 'content' => $this->renderPartial('_history', array('history' => $history), true)) : NULL,
                )),
            )
        ); ?>
    </div>
</div>