<?php
/* @var $this TcategoryController */
/* @var $model Tcategory */

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Ticket tcategories') => array('index'),
    Yii::t('main-ui', 'Manage'),
);

if (Yii::app()->user->checkAccess('listTcategory')) {
    Yii::app()->clientScript->registerCssFile('/css/jstree/themes/default/style.min.css');
    Yii::app()->clientScript->registerScriptFile('/js/jstree.min.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScript('jstree', '
    
    $("#jstree_demo_div")
    .on("changed.jstree", 
            function (e, data) {
                console.log(data.changed.selected); // newly selected
                console.log(data.changed.deselected); // newly deselected
            })
    .jstree(
        {
        "checkbox" : {
            
            "tree_state" : false,
        },
        "plugins" : [ "checkbox", "wholerow","changed" ],
    
        "core" : {
            "data" : [
                { "id" : "ajson#", "parent" : "#", "text" : "Simple root node" },
                { "id" : "ajson2", "parent" : "ajson#", "text" : "Root node 2" },
                { "id" : "ajson3", "parent" : "ajson2", "text" : "Child 3" },
                { "id" : "ajson4", "parent" : "ajson2", "text" : "Child 4" },
                
                ]
            } 
        });'
        
    );
}

// [
//     { "id" : "ajson#", "parent" : "#", "text" : "Simple root node" },
//     { "id" : "ajson2", "parent" : "ajson#", "text" : "Root node 2" },
//     { "id" : "ajson3", "parent" : "ajson2", "text" : "Child 3" },
//     { "id" : "ajson4", "parent" : "ajson2", "text" : "Child 4" },
    
//     ]

$this->menu = array(
    Yii::app()->user->checkAccess('createCategory') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create'))) : array(NULL),
);
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('updateCategory')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteCategory')) {
    $delete = '{delete}';
}
$template = $update . ' ' . $delete;
?>
<div class="page-header">
    <h3><i class="fa-solid fa-inbox fa-xl"> </i><?php echo Yii::t('main-ui', 'Ticket tcategories management'); ?></h3>
</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
            )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
            )); ?>
        <?php $this->widget('ext.SilcomTreeGridView.SilcomTreeGridView', array(
			    'id'=>'tcategories',
			    'treeViewOptions'=>array(
			    	'initialState'=>'expanded',
			    	'expandable'=>true,
                    // 'clickableNodeNames'=>true,
			    ),
			    'parentColumn'=>'parent_id',
			    'dataProvider'=>$model->search(),
                'pager' => array(
                    'class' => 'CustomPager',
                    'displayFirstAndLast' => true,
                ),
			    'columns'=>array(
                                    array(
                                        'name' => 'name',
                                        'sortable' => false,
                                        'header' => Yii::t('main-ui', 'name'),
                                    ),
                                     array(
                                        'class' => 'bootstrap.widgets.TbButtonColumn',
                                        'template' => $template,
                                        'header' => Yii::t('main-ui', 'Actions'),
                                    ),
                ),
                
		));?>
        <!-- <?php $this->widget('system.web.widgets.CTreeView', array(
            'id' => 'tcategory-grid',
            // 'type' => 'striped bordered condensed',
            // 'selectionChanged' => Yii::app()->user->checkAccess('updateTcategory') ? 'function(id){location.href = "' . $this->createUrl('/tcategory/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
            'data' => [
                [ 
                    "id" => "ajson", 
                    "hasChildren"=>true,
                    "children" => [
                        [ 
                            "id" => "ajson2", 
                            "text" => "Root node 2" 
                        ],
                    ],
                    "text" => "Simple root node",
                ],
                
                
                // [ "id" : "ajson3", "parent" : "ajson2", "text" : "Child 3" ],
                // [ "id" : "ajson4", "parent" : "ajson2", "text" : "Child 4" ],
                
                ],
                'animated'=>'normal',
		'collapsed'=>true,
		'htmlOptions'=>array('class'=>'items table table-striped table-bordered table-condensed'),
            'htmlOptions' => array('style' => 'cursor: pointer'),
            // 'pager' => array(
            //     'class' => 'CustomPager',
            //     'displayFirstAndLast' => true,
            // ),
            // 'columns' => array(
            //     'name',
			// 	'parent_id',
            //     array(
            //         'class' => 'bootstrap.widgets.TbButtonColumn',
            //         'template' => $template,
            //         'header' => Yii::t('main-ui', 'Actions'),
            //     ),
            // ),
            )); ?> -->
        </div>
    </div>
    
