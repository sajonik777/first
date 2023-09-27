<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'depart-form',
            'enableAjaxValidation' => false,
        )); ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php //echo $form->textFieldRow($model, 'company', array('class' => 'span5', 'maxlength' => 100)); ?>
            <?php echo $form->dropDownListRow($model, 'company', Companies::all(),
                array('class' => 'span12', 'maxlength' => 100)); ?>
                <?php echo $form->select2Row($model, 'manager', [
                    'data' => CUsers::ffall(),
                    'multiple' => false,
                    'prompt' => '',
                    'options' => ['width' => '100%']
                ]); ?>

        </div>


        <?php if (!$model->isNewRecord): ?>
            <div class="row-fluid">
                <?php
                echo CHtml::label('Сервисы', 'service');
                echo CHtml::DropDownList('service', null, CHtml::listData(Service::model()->findAll(), 'id', 'name'),
                    array(
                        'class' => 'span12',
                        'empty' => '',
                        'ajax' => array(
                            'type' => 'POST',
                            //тип запроса
                            'url' => CController::createUrl('/depart/serviceadd', array("depart_id" => $model->id)),
                            //вызов контроллера c Ajax
                            'update' => '#services',
                            //id DIV - а в котором надо обновить данные
                        )
                    ));
                ?>
            </div>
            <div class="row-fluid">
                <div class="span12" id="services">
                    <?php $this->widget('FilterGridResizable', array(
                        'id' => 'services-grid',
                        'dataProvider' => new CArrayDataProvider($model->services),
                        'type' => 'striped bordered condensed',
                        'htmlOptions' => array('style' => 'cursor: pointer'),
                        'columns' => array(
                            'name:text:'.Yii::t('main-ui', 'Services'),
                            array(
                                'class' => 'bootstrap.widgets.TbButtonColumn',
                                'template' => '{delete}',
                                'deleteButtonUrl' => 'Yii::app()->createUrl("/depart/servicedelete", array("service_id"=>$data->id, "depart_id"=>"' . $model->id . '"))',
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        <?php endif; ?>

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
