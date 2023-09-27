<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Email Parser configurations'),
);
if (Yii::app()->user->checkAccess('mailParserSettings')) {
    $this->menu = array(
        Yii::app()->user->checkAccess('mailParserSettings') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('getmailcreate'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Add Parser config'))) : array(NULL),
        Yii::app()->user->checkAccess('mailParserSettings') ? array('icon' => 'fa-solid fa-envelope fa-xl', 'url' => array('/mailqueue'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Mail queue'))) : array(NULL),
        Yii::app()->user->checkAccess('mailParserSettings') ? array('icon' => 'fa-solid fa-lock fa-xl', 'url' => array('getmailban'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Ban list'))) : array(NULL),
    );
}
?>
<div class="page-header">
    <h3><i class="fa-solid fa-envelope fa-xl"> </i><?php echo Yii::t('main-ui', 'Email configurations'); ?></h3>
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
            'type' => 'striped bordered condensed',
            'id' => 'configs-grid',
            'selectionChanged' => Yii::app()->user->checkAccess('mailParserSettings') ? 'function(id){location.href = "' . $this->createUrl('/config/getmailview') . '/?file="+$.fn.yiiGridView.getSelection(id);}' : NULL,
            'dataProvider' => $dataProvider,
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            //'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'ad_enabled',
                    'header' => Yii::t('main-ui', 'Email parser enabled'),
                    'value' => '$data["getmail_enabled"] ? "Активен" : "Отключен"',
                    'headerHtmlOptions' => array('width' => 150),
                ),
                array(
                    'name' => 'getmailuser',
                    'header' => Yii::t('main-ui', 'Mail username'),
                ),
                array(
                    'name' => 'getmailsmhost',
                    'header' => Yii::t('main-ui', 'Outgoing mail'),
                ),
                array(
                    'name' => 'getmailserver',
                    'header' => Yii::t('main-ui', 'Incoming mail'),
                ),
                array(
                    'name' => 'getmailservice',
                    'header' => Yii::t('main-ui', 'Service'),
                ),
            ),
        ));
        ?>
    </div>
</div>
