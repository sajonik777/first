<div class="box">
    <div class="box-body">
        <?php
        $this->breadcrumbs=array(
            Yii::t('main-ui', 'Manage backups')=>array('/backup'),
            Yii::t('main-ui', 'Restore'),
        );?>
        <div class="page-header">
            <h3><?php echo Yii::t('main-ui', 'Restore');?></h3>
        </div>
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' =>'pills',
            'items'=> $this->menu,
        )); ?>
        <p>
            <?php if(isset($error)) echo $error; else echo Yii::t('main-ui', 'Restored successfully');?>
        </p>
    </div>
</div>

