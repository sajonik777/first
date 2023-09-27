<?php

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Calls')=>array('index'),
	Yii::t('main-ui', 'Manage'),
);

$this->menu=array(
	array('icon' => 'fa-solid fa-gear fa-xl', 'url' => array('#'), 'itemOptions'=>array('id'=> 'calls-grid-ecolumns-dlg-link','title' => Yii::t('main-ui', 'Columns settings'))),
);

?>
<div class="page-header">
	<h3><i class="fa-solid fa-phone fa-xl"> </i><?php echo Yii::t('main-ui', 'Calls');?></h3>
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
		<?php require_once '_grid.php'; ?>
		<?php $this->widget('FilterGridResizable',array(
			'type'=>'striped bordered condensed',
			'id'=>'calls-grid',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'summaryText' => '
			<div class="items_col2"> '. Yii::t('main-ui', 'Items: ').''.CHtml::dropDownList('',Yii::app()->session['CallsPageCount'] ?Yii::app()->session['CallsPageCount'] :30,Yii::app()->params['selectPageCount'],array('onchange'=>"document.location.href='/" .Yii::app()->request->pathInfo . "?pageCount='+this.value;")).'</div>'.Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total),
			'selectionChanged'=>'function(id){location.href = "'.$this->createUrl('/calls').'/"+$.fn.yiiGridView.getSelection(id);}',
			'htmlOptions' => array('style'=>'cursor: pointer'),
			'columns' => $dialog->columns(),
			'pager' => array(
				'class' => 'CustomPager',
				'displayFirstAndLast' => true,
			),
			'template' =>
			"{summary}\n{items}\n{pager}",
		)); ?>
	</div>
</div>
