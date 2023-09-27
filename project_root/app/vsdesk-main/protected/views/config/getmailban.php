<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Mail parser'), Yii::t('main-ui', 'Ban list'),
);
if (Yii::app()->user->checkAccess('mailParserSettings')) {
    $this->menu = array(
        Yii::app()->user->checkAccess('mailParserSettings') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('getmail'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Email Parser configurations'))) : array(NULL),
    );
}
?>

<div class="page-header">
    <h3><i class="fa-solid fa-lock fa-xl"> </i><?php echo Yii::t('main-ui', 'Ban list'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
      <?php $this->widget('bootstrap.widgets.TbMenu', array(
          'type' => 'pills',
          'items' => $this->menu,
      )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'getmailban-form',
                'enableAjaxValidation' => false,
            ));
            ?>
            <?php
            $config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10));
            $rawData = $model;

            $dataProvider = new CArrayDataProvider($rawData, $config);
            ?>
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => Yii::t('main-ui', 'Добавить'),
                'icon' => 'fa-solid fa-circle-plus',
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
                        'deleteButtonUrl' => 'Yii::app()->createUrl("/config/deleteban_item", array("id"=>$data[id]))',
                    )
                ),
            ));
            ?>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo Yii::t('main-ui', 'Add value'); ?></h4>
</div>

<div class="modal-body">
    <div class="row-fluid">
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'addlist-form',
            'enableAjaxValidation' => false,
            'action' => Yii::app()->createUrl('/config/addban_item'),
        )); ?>
<p><strong><?php echo Yii::t('main-ui', 'Insert one value in Email format, like user@email.com'); ?></strong></p>
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
