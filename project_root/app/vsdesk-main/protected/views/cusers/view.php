<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Users') => array('index'),
    $model->Username,
);
$this->menu = array(
    Yii::app()->user->checkAccess('listUser') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions' => array('title' => Yii::t('main-ui', 'List users'))) : array(NULL),
    Yii::app()->user->checkAccess('updateUser') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' => $model->id), 'itemOptions' => array('title' => Yii::t('main-ui', 'Edit user'))) : array(NULL),
    Yii::app()->user->checkAccess('readChat') ? array('icon' => 'fa-solid fa-comment fa-xl', 'url' => array('/chat/privates', 'user' => $model->fullname), 'itemOptions' => array('title' => Yii::t('main-ui', 'Message'))) : array(NULL),
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
                    ],
                    $units ? ['label' => Yii::t('main-ui', 'Configuration units'), 'content' => $this->renderPartial('_cunits', ['units' => $units], true)] : NULL,
                ]),
            ]
        ); ?>
    </div>
    <?php if(Yii::app()->user->checkAccess('systemManager') OR Yii::app()->user->checkAccess('systemAdmin')): ?>
    <div class="box-footer">
      <a class="btn btn-info" href='/request/createfromcall?user=<?php echo $model->Username; ?>&call='><?php echo Yii::t('main-ui', 'Create ticket'); ?></a>
    </div>
  <?php endif; ?>
</div>
