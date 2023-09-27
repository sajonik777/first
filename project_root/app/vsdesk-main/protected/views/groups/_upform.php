<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'groups-form',
            'enableAjaxValidation' => false,
        )); ?>
        <?php
        $config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10));
        $rawData = $users;
        if ($rawData[0] == NULL) {
            $rawData = [];
        }
        $dataProvider = new CArrayDataProvider($rawData, $config);
        ?>

        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 100, 'disabled'=>'disabled')); ?>
            <?php echo $form->toggleButtonRow($model, 'send');  ?>
            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span12', 'maxlength' => 100)); ?>
            <?php echo $form->textFieldRow($model, 'phone', array('class' => 'span12', 'maxlength' => 100)); ?>
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('main-ui', 'Добавить пользователей'),
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
                    'name' => 'fullname',
                    'header' => Yii::t('main-ui', 'Username'),

                ),
                // array(
                //     'name' => 'group_manager',
                //     'header' => Yii::t('main-ui', 'Group manager'),

                // ),
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn'
                , 'template' => '{delete}'
                , 'deleteButtonUrl' => 'Yii::app()->createUrl("/groups/delete_user", array("id"=>$data->id, "mid"=>' . $model->id . '))'
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
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $model->isNewRecord ? 'Создать' : 'Сохранить',
            )); ?>
            <?php $this->endWidget(); ?>
        </div>
</div>

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('main-ui', 'Выберите пользователей'); ?></h4>
</div>

<div class="modal-body">
    <div class="row-fluid">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'adduser-form',
            'enableAjaxValidation' => false,
            'action' => Yii::app()->createUrl('/groups/add_user', array('id' => $model->id)),
        )); ?>

        <?php $this->widget(
            'bootstrap.widgets.TbSelect2',
            array(
                'model' => $model,
                'name' => 'users',
                'data' => CUsers::all_id(),
                'htmlOptions' => array(
                    'multiple' => 'multiple',
                    'class' => 'biginp',
                ),
            )
        ); ?>
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
