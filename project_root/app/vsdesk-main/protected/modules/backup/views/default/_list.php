<?php

$this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'install-grid',
	'type' => 'striped bordered condensed',
	'dataProvider' => $dataProvider,
	'pager' => array(
		'class' => 'CustomPager',
		'displayFirstAndLast' => true,
	),
	'columns' => array(
		array(
			'name' =>'name',
			'header'=> Yii::t('main-ui', 'File name'),
		),
		array(
			'name' =>'size',
			'header'=> Yii::t('main-ui', 'File size'),
		),
		array(
			'name' =>'create_time',
			'header'=> Yii::t('main-ui', 'Created'),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=> Yii::t('main-ui', 'Download file'),
			'headerHtmlOptions'=> array('width'=>150),
			'htmlOptions'=>array(),
			'template' => ' {download}',
			'buttons'=>array
			(
				'download' => array
				(
					'url'=>'Yii::app()->createUrl("backup/default/download", array("file"=>$data["name"]))',
					'headerHtmlOptions'=> array('width'=>150),
					'icon' => 'fa-solid fa-download',
					'label' => Yii::t('main-ui', 'Download file'),
				),
			),		
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=> Yii::t('main-ui', 'Restore file'),
			//'htmlOptions'=>array('onclick'=>'return confirm("Вы уверены, что хотите восстановить БД из архива?")'),
			'headerHtmlOptions'=> array('width'=>150),
			'template' => '{restore}',
			'buttons'=>array
			(

				'restore' => array
				(
					//'url'=>'Yii::app()->createUrl("backup/default/restore", array("file"=>$data["name"]))',
					'icon' => 'fa-solid fa-share-from-square',
					'label' => Yii::t('main-ui', 'Restore file'),
					'click'=>'function(event){
						event.preventDefault(); 
						var checked= $(this).parent().parent().children(":nth-child(1)").text();                        
						swal({
							title: "Вы уверены, что хотите восстановить БД из архива?",
							type: "warning",
							showCancelButton: true,
							confirmButtonColor: "#3085d6",
							cancelButtonColor: "#d33",
							confirmButtonText: "'.Yii::t('main-ui', 'Yes').'",
							cancelButtonText: "'.Yii::t('main-ui', 'No').'",
						}).then(function (result) {
							if (result.value) {
								$.ajax({
									data:{file:checked},
									url:"' . CHtml::normalizeUrl(array('/backup/default/restore')) . '",
									success:function(data){
										swal(
											"Восстановлено!",
											"Ваша резервная копия была успешно восттановлена.",
											"success"
										);
									},
								});
							}
						});
					}',                     
				),
			),		
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'header'=> Yii::t('main-ui', 'Actions'),
			'htmlOptions'=>array(),
			'headerHtmlOptions'=> array('width'=>150),
			'template' => '{delete}',
			'buttons'=>array
			(

				'delete' => array
				(
					'url'=>'Yii::app()->createUrl("backup/default/delete", array("file"=>$data["name"]))',
				),
			),		
		),
	),
	)); ?>