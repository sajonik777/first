<?php

$this->breadcrumbs=array(
    Yii::t('main-ui', 'News')=>array('index'),
	$model->name,
);

    $this->menu = array(
        Yii::app()->user->checkAccess('listNews') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'News'))): array(NULL),
        Yii::app()->user->checkAccess('updateNews') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' =>$model->id), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'Update record'))): array(NULL),
    );

?>

<div class="page-header">
    <h3><?php echo $model->name; ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' =>'pills',
            'items'=> $this->menu,
        )); ?>
        <i class="fa-regular fa-calendar-days"> </i><?php echo Yii::t('main-ui', 'Created').': '.$model->date; ?> </br>
        <i class="fa-solid fa-user"> </i><?php echo Yii::t('main-ui', 'Author').': '. $model->author; ?> </br>

            <h4><?php echo Yii::t('main-ui', 'Content').': '; ?></h4>
            <?php echo $model->content; ?>
    </div>
</div>

