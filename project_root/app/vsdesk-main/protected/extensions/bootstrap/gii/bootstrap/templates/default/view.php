<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn = $this->guessNameColumn($this->tableSchema->columns);
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('main-ui', '".$label."')=>array('index'),
	\$model->{$nameColumn},
);\n";
?>

$this->menu=array(
        Yii::app()->user->checkAccess('list<?php echo $this->modelClass; ?>') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List <?php echo $this->modelClass; ?>'))) : array(NULL),
);
?>
<div class="page-header">
<h3><?php echo "<?php echo \$model->name;?>"; ?></h3>
</div>
<div class="box">
    <div class="box-body">
<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbMenu', array(
        'type' =>'pills',
        'items'=> $this->menu,
    )); ?>
<?php echo "<?php"; ?> $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
<?php
foreach ($this->tableSchema->columns as $column) {
	echo "\t\t'" . $column->name . "',\n";
}
?>
),
)); ?>
</div>
    </div>