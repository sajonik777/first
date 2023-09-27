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

        <?php
        $config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10));
        $rawData = [];
        if(!empty($model->select_value)){
            $vals = explode(',', $model->select_value);
            if(count($vals) !== 0){
                foreach ($vals as $item) {
                    $rawData[] = ['id' => $item, 'value' => $item];
                }
            }
        }
        $dataProvider = new CArrayDataProvider($rawData, $config);
        ?>

        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldRow($model, 'select_name', array('class' => 'span6', 'maxlength' => 128)); ?>
        <br>
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Добавить'),
            'icon' => 'fa-solid fa-plus',
            'htmlOptions' => array(
                'data-toggle' => 'modal',
                'data-target' => '#myModal',
            ),
        ));
        ?>
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'problems-grid',
            'type' => 'striped bordered condensed',
            'summaryText' => '',
            'dataProvider' => $dataProvider,
            'columns' => array(
                array(
                    'name' => 'value',
                    'header' => Yii::t('main-ui', 'Value'),

                ),
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{delete}',
                    'deleteButtonUrl' => 'Yii::app()->createUrl("/selects/delete_item", array("value"=>$data[value], "id"=>' . $model->id . '))',
                    //Здесь мы перегружаем страницу для получения пересчитаных результатов
                    'afterDelete' => 'function(){
				        document.location.reload(true)
				    }'
                )
            ),
        ));
        ?>
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
    <h4><?php echo Yii::t('main-ui', 'Add value'); ?></h4>
</div>

<div class="modal-body">
    <div class="row-fluid">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'adduser-form',
            'enableAjaxValidation' => false,
            'action' => Yii::app()->createUrl('/selects/add_item', array('id' => $model->id)),
        )); ?>

        <input type="text" name="value" class="span12">
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
