<?php
/* @var $this TcategoryController */
/* @var $model Tcategory */
/* @var $form CActiveForm */
?>


<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'tcategory-types-form',
            'enableAjaxValidation' => false,
        )); ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid">
		<!-- echo $form->dropDownListRow($model, 'status_id', $list_data, array('class' => 'span12')); -->
			<?php $list_data = CHtml::listData(Tcategory::model()->findAll(), 'id', 'name'); 
			array_unshift($list_data, NULL);
			?>
            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 70)); ?>

			<?php
			// $this->widget('ext.select2.ESelect2Tree',array(
			// 	'model'=>Tcategory::model(),
			// 	// 'attribute'=>'watcher',
			// 	'dataProvider'=> $model->search(),
			// 	'htmlOptions'=>array(
			// 		'multiple'=>'multiple',
			// 		'style'=>'width:100%',
			// 		'name' => 'watcher'
			// 	),
			// ));
			
			?>

			<?php 
			// echo 'ext.select2.ESelect2', array('model' => $model, 'parent_id', array(
            //         'asDropDownList'=>false,
            //         'multiple' => false,
			// 		'data' => 
            //         'options'=>array(
            //             'tags'=> $list_data,
            //             'width'=>'100%',
            //             'tokenSeparators'=>array(','),
            //             'class' => 'biginp'
            //         ),
            //     )); 
				?>
			<?php echo $form->dropDownListRow($model, 'parent_id', $model->getTcategories($model), array('class' => 'span12', 'maxlength' => 70)); ?>

				<?php 
				
				// $this->widget('ext.CTree.CTree', array(
				// 	'id'=>'newTree',
				// 	// additional javascript options for the dialog plugin
				// 	'openAll'=>false,
				// 	'width' => 200, 
				// 	'height' => 400,
				// 	'onLoad' => ' function () { alert("onLoad Function"); }',
				// 	'firstSelect' => false,
				// 	'multi' => true,
				// 	'data' => array(
				// 		array('nodeId' => rand(10000, 100000), 'nodeTitle' => 'Node1', 
				// 					'selected' => true, 'nodeChildren' => array()), 
				// 		array('nodeId' => null, 'nodeTitle' => 'Node1', 'selected' => true,               'nodeChildren' => array()), 
				// 		array('nodeId' => null, 'nodeTitle' => 'Node2', 'selected' => false,              'nodeChildren' => array(
				// 		array('nodeId' => rand(10000, 100000), 'nodeTitle' => 'Node2.1', 'selected' => false,              'nodeChildren' => array()), 
				// 		array('nodeId' => null, 'nodeTitle' => 'Node2.2', 'selected' => true,               'nodeChildren' => array(
				// 				array('nodeId' => rand(10000, 100000), 'nodeTitle' => 'Node2.2.1', 'selected' => false,            'nodeChildren' => array()), 
				// 				array('nodeId' => null, 'nodeTitle' => 'Node2.2.2', 'selected' => true,                'nodeChildren' => array( 
				// 						  array('nodeId' => rand(10000, 100000), 'nodeTitle' => 'Node2.2.2.1', 'selected' => false,  'nodeChildren'=>array())
				// 				),
				// 		 )
				// 	   )
				// 	)
				// 	 )
				// ))));
				
				?>

        </div>
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
<!-- 

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tcategory-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'parent_id'); ?>
		<?php echo $form->textField($model,'parent_id'); ?>
		<?php echo $form->error($model,'parent_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->textField($model,'enabled'); ?>
		<?php echo $form->error($model,'enabled'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>form -->