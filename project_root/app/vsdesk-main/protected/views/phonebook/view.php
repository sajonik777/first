<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Phonebook') => array('index'),
    $model->Username,
);
$this->menu = array(
    Yii::app()->user->checkAccess('listUser') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Phonebook'))) : array(NULL),
);
?>
<div class="page-header">
    <h3><?php echo $model->fullname; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>

        <?php $this->widget(
            'bootstrap.widgets.TbTabs',
            [
                'type' => 'tabs', // 'tabs' or 'pills'
                'tabs' => array_filter([
                    [
                        'label' => Yii::t('main-ui', 'Main information'),
                        'content' => $this->renderPartial('_main', ['model' => $model], true),
                        'active' => true
                    ]
                ]),
            ]
        ); ?>
    </div>
    <?php if(Yii::app()->user->checkAccess('systemManager') OR Yii::app()->user->checkAccess('systemAdmin')): ?>
    <div class="box-footer">
      <a class="btn btn-info" href="/request/createfromcall?user=<?php echo $model->fullname; ?>&call="><?php echo Yii::t('main-ui', 'Create ticket'); ?></a>
    </div>
  <?php endif; ?>
</div>
