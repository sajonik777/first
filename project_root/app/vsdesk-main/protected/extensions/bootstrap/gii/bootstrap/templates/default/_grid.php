<?php
echo "<?php\n";
echo "\$view = NULL;\n";
echo "\$update = NULL;\n";
echo "\$delete = NULL;\n";
echo "if (Yii::app()->user->checkAccess('view".$this->modelClass."')) {\n";
echo    "\$view = '{view}';\n";
echo "}\n";
echo "if (Yii::app()->user->checkAccess('update".$this->modelClass."')) {\n";
echo    "\$update = '{update}';\n";
echo "}\n";
echo "if (Yii::app()->user->checkAccess('delete".$this->modelClass."')) {\n";
echo    "\$delete = '{delete}';\n";
echo "}\n";
echo "\$template = \$view . ' ' . \$update . ' ' . \$delete;\n";

$model_columns = NULL;
foreach ($this->tableSchema->columns as $column) {
    $model_columns .= "\t\t'" . $column->name . "',\n";
}
echo "?>\n";
?>

<?php echo "<?php"; ?> $dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => '<?php echo $this->class2id($this->modelClass);?>-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            <?php echo $model_columns;?>
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'header' => Yii::t('main-ui', 'Actions'),
                'template' => $template,
            )
        )
    ),
));
$fixed_columns = array_filter(array(
    array(
        'name' => 'id',
        'header' => Yii::t('main-ui', '#'),
        'headerHtmlOptions' => array('width' => 60),
        //'filter' => '',
    )
)); ?>