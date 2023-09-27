<?php

$this->breadcrumbs=array(
    Yii::t('main-ui', 'Manage backups')=>array('/backup'),
);?>
<div class="page-header">
<h3><i class="fa-solid fa-box-archive fa-xl">  </i><?php echo Yii::t('main-ui', 'Manage backups');?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' =>'pills',
            'items'=> $this->menu,
        )); ?>
        <?php $this->renderPartial('_list', array(
            'dataProvider'=>$dataProvider,
        ));
        ?>
    </div>
</div>
