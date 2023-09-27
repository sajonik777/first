<?php
/* @var $this TcategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main-ui', 'Ticket tcategories') => array('index'),
    Yii::t('main-ui', 'Manage'),
	// 'Tcategories',
);

$this->menu=array(
	Yii::app()->user->checkAccess('createTcategory') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create'))) : array(NULL),
	// array('label'=>'Create Tcategory', 'url'=>array('create')),
	// array('label'=>'Manage Tcategory', 'url'=>array('admin')),
);
$update = NULL;
$delete = NULL;
if (Yii::app()->user->checkAccess('updateTcategory')) {
    $update = '{update}';
}
if (Yii::app()->user->checkAccess('deleteTcategory')) {
    $delete = '{delete}';
}
$template = $update . ' ' . $delete;
?>
<div class="page-header">
    <h3><i class="fa-solid fa-inbox fa-xl"> </i><?php echo Yii::t('main-ui', 'Tcategories'); ?></h3>
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
        <?php $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'tcategory-grid',
            'type' => 'striped bordered condensed',
            'selectionChanged' => Yii::app()->user->checkAccess('updateTcategory') ? 'function(id){location.href = "' . $this->createUrl('/tcategory/update') . '/"+$.fn.yiiGridView.getSelection(id);}' : NULL,
            'dataProvider' => $model->search(),
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'columns' => array(
                'name',
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => $template,
                    'header' => Yii::t('main-ui', 'Actions'),
                ),
            ),
            )); ?>
        </div>
    </div>
