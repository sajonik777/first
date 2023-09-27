<?php
/**
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 */
?>
<div class="box">
    <div class="box-body">
        <?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'" . $this->class2id($this->modelClass) . "-form',
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

        <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

        <?php
        foreach ($this->tableSchema->columns as $column) {
            if ($column->autoIncrement) {
                continue;
            }
            ?>
            <?php echo "<?php echo " . $this->generateActiveRow($this->modelClass, $column) . "; ?>\n"; ?>

            <?php
        }
        ?>
    </div>
    <div class="box-footer">
        <?php echo "<?php \$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'      =>'info',
			'label'     =>\$model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
		)); ?>\n"; ?>
    </div>

    <?php echo "<?php \$this->endWidget(); ?>\n"; ?>
</div>