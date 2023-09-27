<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Manage cron jobs') => array('index'),
);

$this->menu = array(
    array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('create'), 'htmlOptions'=>array('title'=> Yii::t('main-ui', 'Create'))),
);
?>

<div class="page-header">
    <h3><i class="fa-solid fa-rotate fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage cron jobs'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <div id="myGrid"></div>
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'cron-grid',
            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/cron/update') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'type' => 'striped bordered condensed',
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'dataProvider' => $model->search(),
            'columns' => array(
                array(
                    'name' => 'name',
                    'headerHtmlOptions' => array('width' => 500),
                ),
                array(
                    'name' => 'time',
                    'headerHtmlOptions' => array('width' => 150),
                ),
                'job',

                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'headerHtmlOptions' => array('width' => 50),
                    'template' => '{run} {update} {delete}',
                    'buttons' => array(
                        'run' => array(
                            'icon' => 'fa-solid fa-circle-play',
                            'label' => Yii::t('main-ui', 'Run'),
                            'url' => 'Yii::app()->createUrl("cron/run", array("id"=>$data->id))',
                            'options' => array(
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url'=>'js:$(this).attr("href")',
                                    'data'=>array('id'=>$data->id, 'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
                                    'success' => 'js:function(data) {$("#flash_msg").append(data).fadeIn("slow");}')),
                        ),
                    ),
                ),
            ),
        )); ?>
    </div>
</div>
