<?php
$this->breadcrumbs=array(
	Yii::t('main-ui', 'Mail queue')=>array('index'),
	Yii::t('main-ui', 'Manage'),
);

?>
<div class="page-header">
    <h3><i class="fa-solid fa-square-envelope fa-xl"></i><?php echo Yii::t('main-ui', 'Mail queue');?></h3>
    <hr/>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('main-ui', 'Clear mailqueue'),
        'type' => 'danger',
        'icon' => 'trash',
        'url' => $this->createUrl('/mailqueue/deleteall'),

    ));
    ?>

</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
        'type' =>'pills',
        'items'=> $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block' =>true,
        'fade' =>true,
        'closeText'=>'×',
        )); ?>
        <?php require_once '_grid.php'; ?>        <?php $this->widget('bootstrap.widgets.TbExtendedGridView',array(
        'type'=>'striped bordered condensed',
        'id'=>'mail-queue-grid',
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'summaryText' => '
        <div class="items_col2"> '. Yii::t('main-ui', 'Items: ').''.CHtml::dropDownList('',Yii::app()->session['MailQueuePageCount'] ?Yii::app()->session['MailQueuePageCount'] :30,Yii::app()->params['selectPageCount'],array('onchange'=>"document.location.href='/" .Yii::app()->request->pathInfo . "?pageCount='+this.value;")).'</div>'.Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total),
        'htmlOptions' => array('style'=>'cursor: pointer'),
        'columns' => array_merge($fixed_columns, $dialog->columns()),
        'pager' => array(
        'class' => 'CustomPager',
        'displayFirstAndLast' => true,
        ),
        'template' => "{summary}\n{items}\n{pager}",
        )); ?>
    </div>
</div>
