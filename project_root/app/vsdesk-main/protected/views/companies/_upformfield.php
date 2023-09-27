<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'fieldsets-form',
            'enableAjaxValidation' => false,
        )); ?>

        <?php echo $form->errorSummary($model2);
        $config = array(
            'keyField' => 'id',
            'pagination' => array('pageSize' => 10),
            //'sort' => array('attributes' => array('sid' => 'sid ASC')),
        );
        $rawData = $fields;
        $dataProvider = new CArrayDataProvider($rawData, $config);
        $sort = new CSort();
        $sort->attributes = array('sid' => 'sid ASC');
        $dataProvider->sort = $sort;
        $dataProvider->pagination = false;
        $dataProvider->sort->defaultOrder='sid ASC';   
        ?>
        <div class="row-fluid">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => Yii::t('main-ui', 'Add field'),
                'icon' => 'fa-solid fa-circle-plus',

                'htmlOptions' => array(
                    'data-toggle' => 'modal',
                    'data-target' => '#myModal',
                ),
            ));
            ?>
            <?php
            $this->widget('bootstrap.widgets.TbExtendedGridView', array(
                'id' => 'fields-grid',
                'type' => 'striped bordered condensed',
                'summaryText' => '',
                'sortableRows'=>true,
                'sortableAttribute' => 'sid',
                'sortableAjaxSave' => true,
                'sortableAction' => 'companies/reorder',
                'afterSortableUpdate' => 'js:function(){}',
                'dataProvider' => $dataProvider,
                'columns' => array(
                    array(
                        'name' => 'name',
                        'header' => Yii::t('main-ui', 'Name'),
                    ),
                    array(
                        'name' => 'type',
                        'header' => Yii::t('main-ui', 'Type'),
                        'htmlOptions' => array(
                            'width' => 200,
                        ),
                    ),

                    array(
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {delete}',
                        'deleteButtonUrl' => 'Yii::app()->createUrl("/companies/delete_field", array("id"=>$data->id))',
                        'updateButtonUrl' => 'Yii::app()->createUrl("/companies/update_field", array("id"=>$data->id))',
                        'afterDelete' => 'function(){
                            document.location.reload(true)
                        }'
                    )
                ),
            ));
            ?>
        </div>
    </div>
    <div class="box-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('main-ui', 'Add field'); ?></h4>
</div>

<div class="modal-body">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'addasset-form',
        'enableAjaxValidation' => false,
        'action' => Yii::app()->createUrl('/companies/add_field'),
    )); ?>
    <div class="row-fluid">
        <?php echo $form->textFieldRow($model2, 'name', array('class' => 'span12', 'maxlength' => 100)); ?>
    </div>
    <div class="row-fluid">
        <?php echo $form->dropDownListRow($model2, 'type',
            array('textFieldRow' => 'Text', 'toggle' => 'Toggle', 'date' => 'Date', 'select' => 'Select', 'ruler' => 'Horizontal rule'), array(
                'class' => 'span12',
                'maxlength' => 100,
                'ajax' => array(
                    'type' => 'POST',
                    'url' => CController::createUrl('/companies/select'),
                    'update' => '#select',
                )
            )); ?>
    </div>
    <div class="row-fluid" id="select">

    </div>
    <div class="row-fluid">
        <?php echo $form->toggleButtonRow($model2, 'req'); ?>
    </div>
</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('main-ui', 'Add'),
    )); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('main-ui', 'Cancel'),
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>