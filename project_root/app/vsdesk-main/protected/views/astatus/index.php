<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Unit statuses')=>array('index'),
	Yii::t('main-ui', 'Manage'),
);

$this->menu=array(
    Yii::app()->user->checkAccess('createAstatus') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create'))) : array(NULL),
);

?>
<div class="page-header">
<h3><i class="fa-solid fa-tag fa-xl">  </i><?php echo Yii::t('main-ui', 'Unit statuses');?></h3>
</div>
<div class="box table-responsive">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' =>'pills',
            'items'=> $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block'    =>true,
            'fade'     =>true,
            'closeText'=>'×',
        )); ?>
        <?php require_once '_grid.php'; ?><?php $this->widget('bootstrap.widgets.TbExtendedGridView',array(
            'type'=>'striped bordered condensed',
            'id'=>'astatus-grid',
            'dataProvider'=>$model->search(),
            'filter'=>$model,
            'summaryText' => '<div class="items_col2"> '. Yii::t('main-ui', 'Items: ').''. CHtml::dropDownList('',Yii::app()->session['astatusPageCount'] ? Yii::app()->session['astatusPageCount'] : 30,Yii::app()->params['selectPageCount'],array('onchange'=>"document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")).'</div> '.Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total),
            'selectionChanged'=>'function(id){location.href = "'.$this->createUrl('/astatus/update').'/"+$.fn.yiiGridView.getSelection(id);}',
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

