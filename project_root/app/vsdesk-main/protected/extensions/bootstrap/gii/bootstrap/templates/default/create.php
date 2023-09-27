<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<?php
echo "<?php\n";
$label = $this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	Yii::t('main-ui', '".$label."')=>array('index'),
	Yii::t('main-ui', 'Create'),
);\n";
?>

$this->menu=array(
Yii::app()->user->checkAccess('list<?php echo $this->modelClass; ?>') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title' => Yii::t('main-ui', 'List <?php echo $this->modelClass; ?>'))) : array(NULL),
);
?>

<div class="page-header">
<h3><?php echo "<?php echo Yii::t('main-ui', 'Create $this->modelClass');?>"; ?></h3>
</div>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
