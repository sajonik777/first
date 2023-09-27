<?php
/* @var $this ChecklistsController */
/* @var $model Checklists */
/* @var $modelChecklistFields ChecklistFields */
/* @var $form CActiveForm */
/* @var $fields ChecklistFields[] */
?>

<div class="box">
    <div class="box-body">
        <?php
        $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]);
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'id' => 'checklists-form',
            'enableAjaxValidation' => false,
        ]);
        echo $form->errorSummary($model);
        $config = [
            'keyField' => 'id',
            'pagination' => ['pageSize' => 10],
        ];
        $dataProvider = new CArrayDataProvider($fields, $config);
        $sort = new CSort();
        $sort->attributes = ['sorting' => 'sorting ASC'];
        $dataProvider->sort = $sort;
        $dataProvider->pagination = false;
        $dataProvider->sort->defaultOrder = 'sorting ASC';
        ?>
        <div class="row-fluid">
            <?php
            echo $form->textFieldRow($model, 'name',
                ['class' => 'span12', 'maxlength' => 100, 'disabled' => 'disabled']); ?>
            <hr>
            <h4><?php echo Yii::t('main-ui', 'Fields'); ?></h4>
            <?php
            $this->widget('bootstrap.widgets.TbButton', [
                'label' => Yii::t('main-ui', 'Add field'),
                'icon' => 'fa-solid fa-plus',

                'htmlOptions' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#myModal',
                ],
            ]);
            ?>
            <?php
            $this->widget('bootstrap.widgets.TbExtendedGridView', [
                'id' => 'checklists-grid',
                'type' => 'striped bordered condensed',
                'summaryText' => '',
                'sortableRows' => true,
                'sortableAttribute' => 'sorting',
                'sortableAjaxSave' => true,
                'sortableAction' => 'checklists/reorder',
                'afterSortableUpdate' => 'js:function(){}',
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'name' => 'name',
                        'header' => Yii::t('main-ui', 'Name'),
                    ],
                    [
                        'class' => 'bootstrap.widgets.TbButtonColumn',
                        'template' => '{update} {delete}',
                        'deleteButtonUrl' => 'Yii::app()->createUrl("/checklists/delete_field", array("id"=>$data->id))',
                        'updateButtonUrl' => 'Yii::app()->createUrl("/checklists/update_field", array("id"=>$data->id))',
                        //Здесь мы перегружаем страницу для получения пересчитаных результатов
                        'afterDelete' => 'function(){
                            document.location.reload(true)
                        }'
                    ]
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="box-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
        )); ?>
    </div>
    <?php
    $this->endWidget(); ?>
</div>
<?php
$this->beginWidget('bootstrap.widgets.TbModal', ['id' => 'myModal']); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php
        echo Yii::t('main-ui', 'Add field'); ?></h4>
</div>

<div class="modal-body">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'id' => 'addasset-form',
        'enableAjaxValidation' => false,
        'action' => Yii::app()->createUrl('/checklists/add_field', ['id' => $model->id]),
    ]); ?>
    <div class="row-fluid">
        <?php
        echo $form->textFieldRow($modelChecklistFields, 'name', ['class' => 'span12', 'maxlength' => 100]); ?>
    </div>
</div>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', [
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('main-ui', 'Add'),
    ]); ?>

    <?php
    $this->widget('bootstrap.widgets.TbButton', [
        'label' => Yii::t('main-ui', 'Cancel'),
        'url' => '#',
        'htmlOptions' => ['data-dismiss' => 'modal'],
    ]); ?>
</div>
<?php
$this->endWidget();
$this->endWidget();
?>

