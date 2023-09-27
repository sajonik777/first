<style>
	#tickets {
		max-height: 65vh;
	}
</style>

<div class="page-header">
	<h3>Результаты поиска по запросу "<?= $query; ?>"</h3>
</div>

<div class="box">
	<div class="box-body">

        <?php
        if (!$results): ?>
			<h4>По вашему запросу ничего не найдено</h4>
        <?php
        else: ?>
        <?php
        if (count($results) > 0): ?>
			<ul id="search-results-tabs" class="nav nav-tabs">
                <?php
                foreach ($results as $alias => $result): ?>
					<li>
						<a href="#<?= $result['id']; ?>">
                            <?= $result['name']; ?>
						</a>
					</li>
                <?php
                endforeach; ?>
			</ul>

        <?php
        //            var_dump($results[1]);
        ?>
			<div class="tab-content">

				<div id="<?= $results[0]['id']; ?>" class="tab-pane">
                    <?php
                    require_once '_grid.php';
                    $this->widget('bootstrap.widgets.TbGridView', array(
                        'type' => 'striped bordered condensed',
                        'id' => 'request-grid',
                        'selectionChanged' => 'function(id){location.href = "/request/"+$.fn.yiiGridView.getSelection(id);}',
                        'dataProvider' => $results[0]['data'],
                        'htmlOptions' => array('style' => 'cursor: pointer'),
                        'summaryText' => '',
                        'afterAjaxUpdate' => "function() {
                                        if($('.rating-block input').length != 0) $('.rating-block input').rating({'readOnly':true});
                                    }",
                        'columns' => array_merge($fixed_columns, $dialog->columns()),
                        'template' => "{summary}\n{items}\n{pager}",
                    ));
                    ?>
				</div>
				<div id="<?= $results[1]['id']; ?>" class="tab-pane">
                    <?php
                    $this->widget('bootstrap.widgets.TbGroupGridView', array(
                        'id' => 'brecords-grid',
                        'dataProvider' => $results[1]['data'],
                        'htmlOptions' => array('style' => 'cursor: pointer'),
                        'type' => 'striped bordered condensed',
                        'selectionChanged' => Yii::app()->user->checkAccess('viewKB') ? 'function(id){location.href = "/knowledge/module/view/id/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
                        'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['knowPageCount'] ? Yii::app()->session['knowPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
                        'afterAjaxUpdate' => 'reinstallDatePicker',
                        'mergeColumns' => array('bcat_name'),
                        'pager' => array(
//                            'class' => 'CustomPager',
//                            'displayFirstAndLast' => true,
                        ),
                        'columns' => array(
                            array(
                                'name' => 'bcat_name',
                                'headerHtmlOptions' => array('width' => 300),
                            ),
                            array(
                                'name' => 'image',
                                'headerHtmlOptions' => array('width' => 10),
                                'type' => 'raw',
                                'header' => CHtml::tag('i', array('class' => "fa-solid fa-paperclip"), null),
                                'value' => '($data->image || $data->files) ? CHtml::tag("i", array("class"=>"fa-solid fa-paperclip"), null) : ""',
                            ),
                            array(
                                'name' => 'author',
                                'headerHtmlOptions' => array('width' => 300),

                            ),
                            array(
                                'name' => 'created',
                                'headerHtmlOptions' => array('width' => 120),
                            ),
                            'name',
                        ),
                    ));
                    ?>
				</div>
			</div>


			<script>
				$('#search-results-tabs a:first').tab('show');

				$('#search-results-tabs a').click(function (e) {
					e.preventDefault();
					$(this).tab('show');
				})
			</script>

        <?php
        endif; ?>
        <?php
        endif; ?>
	</div>
</div>