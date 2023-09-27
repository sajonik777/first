<?php
$total = NULL;
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('main-ui', '" . $label . "')=>array('index'),
	Yii::t('main-ui', 'Manage'),
);\n";
?>

$this->menu=array(
array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Create'))),
);

?>
<div class="page-header">
    <h3><i class="fa-solid fa-list-ul fa-xl"> </i><?php echo "<?php echo"; ?> Yii::t('main-ui', '<?php echo $label; ?>');?></h3>

</div>
<div class="box">
    <div class="box-body table-responsive">
        <?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbMenu', array(
        'type' =>'pills',
        'items'=> $this->menu,
        )); ?>
        <?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbAlert', array(
        'block' =>true,
        'fade' =>true,
        'closeText'=>'×',
        )); ?>
        <?php echo "<?php require_once '_grid.php'; ?>"; ?>
        <?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbExtendedGridView',array(
        'type'=>'striped bordered condensed',
        'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'summaryText' => '
        <div class="items_col2"> '. Yii::t('main-ui', 'Items: ').''.CHtml::dropDownList('',Yii::app()->session['<?php echo $this->modelClass; ?>PageCount'] ?Yii::app()->session['<?php echo $this->modelClass; ?>PageCount'] :30,Yii::app()->params['selectPageCount'],array('onchange'=>"document.location.href='/" .Yii::app()->request->pathInfo . "?pageCount='+this.value;")).'</div>'.Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total),
        'selectionChanged'=>'function(id){location.href = "'.$this->createUrl('/<?php echo $this->modelClass;?>').'/"+$.fn.yiiGridView.getSelection(id);}',
        'htmlOptions' => array('style'=>'cursor: pointer'),
        'columns' => array_merge($fixed_columns, $dialog->columns()),
        'pager' => array(
        'class' => 'CustomPager',
        'displayFirstAndLast' => true,
        ),
        'template' => $dialog->link($text = '<i class="fa-solid fa-gear"> ' . Yii::t('main-ui', 'Columns settings') . '</i>') .
        "{summary}\n{items}\n{pager}",
        )); ?>
    </div>
</div>
