<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Knowledgebase') => array('index'),
    Yii::t('main-ui', 'Search results'),
);

?>
<div class="page-header">
    <h3><?php echo Yii::t('main-ui', 'Search results'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php
        $config = array('keyField' => 'id', 'pagination' => array('pageSize' => 10),);
        $rawData = $model;
        $dataProvider = new CArrayDataProvider($rawData, $config);

        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'search-grid',
            'type' => 'striped bordered condensed',
            'selectionChanged' => 'function(id){window.open("' . $this->createUrl('knowledge/module/view/id/') . '/"+$.fn.yiiGridView.getSelection(id),"_blank");}',
            'dataProvider' => $dataProvider,
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => Yii::t('main-ui', 'Name'),
                ),
            ),
        ));
        ?>

    </div>
</div>