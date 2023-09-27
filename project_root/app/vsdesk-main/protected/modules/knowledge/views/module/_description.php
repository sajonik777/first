
    <div class="box-body">
        <i class="fa-regular fa-calendar-days"></i>  <b><?php echo Yii::t('main-ui', 'Created') ;?>:</b> <?php echo $model->created; ?> </br>
        <i class="fa-solid fa-user"></i>  <b><?php echo Yii::t('main-ui', 'Author') ;?>:</b> <?php echo $model->author; ?> </br>
		<i class="fa-solid fa-folder"></i>  <b><?php echo Yii::t('main-ui', 'Category') ;?>:</b> <?php echo $model->bcat_name; ?> </br>
        <hr>
            <h4><?php echo(Yii::t('main-ui', 'Content'));?>:</h4>
            <?php echo $model->content; ?>
    </div>
    <?php
    if ($model->files) {
        FilesShow::show($model->files, 'knowledge/module', '/uploads', '', 'KB');
    }
    ?>
    <?php if ($model->image):?>
        <?php
        FilesShow::show($files, 'knowledge/module','/media/kb/', $model->id, 'KB');
        ?>
    <?php endif;?>

    <?php if (Yii::app()->user->checkAccess('printRequest')): ?>
    <?php
     $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'print_kb',
        'enableAjaxValidation' => false,
        'method'=>'post',
        'action' => Yii::app()->createUrl('/knowledge/module/printform', array('id' => $model->id)),
    )); ?>
    <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal5')); ?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4><?php echo Yii::t('main-ui', 'Select print form template'); ?></h4>
    </div>
    <div class="modal-body">
        <div class="row-fluid">
            <?php $this->widget(
                'bootstrap.widgets.TbSelect2',
                array(
                    'model' => $model,
                    'name' => 'template_id',
                    'data' => CHtml::listData(UnitTemplates::model()->findAllByAttributes(array('type' => 5)), 'id', 'name'),
                    'htmlOptions' => array(
                        'class' => 'span12',
                    ),
                )
            ); ?>
        </div>
    </div>

    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => Yii::t('main-ui', 'Print'),
        )); ?>

        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Cancel'),
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )); ?>
    </div>
    <?php $this->endWidget(); ?>
    <?php $this->endWidget(); ?>
    <?php endif; ?>