<?php

$config = array('keyField' => 'id', 'sort' => array('defaultOrder' => 'id DESC'), 'pagination' => array('pageSize' => 10));
$rawData = $history;
$isEmpty = count($rawData) === 0;
$dataProvider = new CArrayDataProvider($rawData, $config);
// $this->widget('bootstrap.widgets.TbGridView', array(
//     'id' => 'history-grid',
//     'type' => 'striped bordered condensed',
//     'summaryText' => '',
//     'dataProvider' => $dataProvider,
//     'pager' => array(
//         'class' => 'CustomPager',
//         'displayFirstAndLast' => true,
//     ),
//     'columns' => array(
//         array(
//             'name' => 'datetime',
//             'header' => Yii::t('main-ui', 'Changed'),
//             'headerHtmlOptions' => array('width' => 100),
//         ),
//         array(
//             'name' => 'cusers_id',
//             'header' => Yii::t('main-ui', 'Username'),
//             'headerHtmlOptions' => array('width' => 200),
//         ),
//         array(
//             'name' => 'action',
//             'type' => 'html',
//             'header' => Yii::t('main-ui', 'Content'),
//         ),
//     ),
// ));
?>
<div class="box-body">
	<!-- row -->
	<div class="row-fluid">
		<div class="col-md-12">
			<!-- The time line -->
			<ul class="timeline <?= $isEmpty ? 'empty-history' : ''; ?>">
                <?php
                $this->widget('application.extensions.comments.CCommentsList', array(
                    'dataProvider' => $dataProvider,
                    'itemView' => '_historytimeline',
                    'ajaxUpdate' => true,
                    'id' => 'history-grid',
                    'pager' => array(
                        'class' => 'CustomPager',
                        'displayFirstAndLast' => true,
                    ),
                    'template' => "{pager}\n{items}\n{pager}",
                )); ?>
				<?php if (!$isEmpty): ?>
				<li id="clock-remove" style="margin-right: 70%">
					<i class="fa fa-regular fa-clock bg-gray"></i>
				</li>
				<?php endif ?>
			</ul>
		</div>
	</div>
	<!-- /.row -->
</div>