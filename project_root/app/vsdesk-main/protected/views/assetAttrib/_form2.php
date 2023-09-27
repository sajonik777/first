<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'asset-attrib-form',
            'enableAjaxValidation' => false,
        )); ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <?php echo $form->textFieldRow($model, 'name', array('class'=>'span12','maxlength' => 50)); ?>
            <?php echo $form->hiddenField($model, 'id', array('value' => $model->id)); ?>

            <?php
            $config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10));
            $rawData = $model->assetAttribValues;
            $dataProvider = new CArrayDataProvider($rawData, $config);
            ?>
            <hr/>
            <h4><?php echo Yii::t('main-ui', 'Create attributes'); ?></h4>
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => Yii::t('main-ui', 'Add value'),
                'icon' => 'fa-solid fa-plus',

                'htmlOptions' => array(
                    'data-toggle' => 'modal',
                    'data-target' => '#myModal',
                ),
            ));
            ?>
            <?php
            $this->widget('bootstrap.widgets.TbGridView', array(
                'id' => 'tarif-services-grid',
                'type' => 'striped bordered condensed',
                'summaryText' => '',
                'dataProvider' => $dataProvider,
                'columns' => array(
                    array(
                        'header' => Yii::t('main-ui', 'Name'),
                        'type' => 'raw',
                        'value' => '$data->name',
                    ),
                    array(
                        'class' => 'bootstrap.widgets.TbButtonColumn'
                    , 'template' => '{delete}'
                    , 'deleteButtonUrl' => 'Yii::app()->createUrl("/AssetAttribValue/delete", array("asset_attrib_id"=>$data["id"]))'
                        //Здесь мы перегружаем страницу для получения пересчитаных результатов
                    , 'afterDelete' => 'function(){
				document.location.reload(true)
				}'
                    )
                ),
            ));
            ?>
        </div>
    </div>
        <div class="box-footer">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
            ));
            ?>
        </div>

        <?php $this->endWidget(); ?>

</div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('main-ui', 'Create new attributes'); ?></h4>
</div>

<div class="modal-body">
    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'asset-attribs-form',
        'enableAjaxValidation' => false,
        'action' => '/assetAttribValue/create'
    )); ?>

    <?php echo $form->errorSummary($model_s); ?>
    <div class="row-fluid">
        <?php echo CHtml::activeLabel($model, 'name'); ?>
        <?php echo $form->textField($model_s, 'name', array('class'=>'span12','maxlength' => 50)); ?>

        <?php echo $form->hiddenField($model_s, 'asset_id', array('value' => $model->id)); ?>
        <?php echo $form->hiddenField($model_s, 'asset_attrib_id', array('value' => $model->id)); ?>
    </div>
</div>

<div class="modal-footer">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model_s->isNewRecord ? Yii::t('main-ui', 'Add') : Yii::t('main-ui', 'Save'),
    )); ?>

    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('main-ui', 'Cancel'),
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )); ?>
</div>
<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
