<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Phonebook')=>array('index'),
	Yii::t('main-ui', 'Manage'),
);

$this->menu=array(
    array('icon' => 'fa-solid fa-gear fa-xl', 'url' => array('javascript:void(0)'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Columns settings'), 'id'=>"phonebook-grid-ecolumns-dlg-link")),
);

?>
<div class="page-header">
    <h3><i class="fa-solid fa-address-book fa-xl"> </i><?php echo Yii::t('main-ui', 'Phonebook');?></h3>

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
        'id'=>'phonebook-grid',
        'dataProvider'=>$model->psearch(),
        'filter'=>$model,
        'summaryText' => '
        <div class="items_col2"> '. Yii::t('main-ui', 'Items: ').''.CHtml::dropDownList('',Yii::app()->session['CUsersPageCount'] ?Yii::app()->session['CUsersPageCount'] :30,Yii::app()->params['selectPageCount'],array('onchange'=>"document.location.href='/" .Yii::app()->request->pathInfo . "?pageCount='+this.value;")).'</div>'.Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total),
        'selectionChanged'=> Yii::app()->user->checkAccess('viewPhonebook') ? 'function(id){location.href = "'.$this->createUrl('/phonebook').'/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
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
