<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'LDAP integration'),
);
if (Yii::app()->user->checkAccess('adSettings')) {
    $this->menu = array(
        Yii::app()->user->checkAccess('adSettings') ? array('icon' => 'fa-solid fa-circle-plus fa-xl', 'url' => array('adcreate'), 'itemOptions' => array('title' => Yii::t('main-ui', 'Add AD config'))) : array(NULL),
    );
}
?>
<div class="page-header">
    <h3><i class="fa-solid fa-folder-tree fa-xl"> </i><?php echo Yii::t('main-ui', 'Manage LDAP configurations'); ?></h3>
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
            'selectionChanged' => Yii::app()->user->checkAccess('adSettings') ? 'function(id){location.href = "' . $this->createUrl('/config/adview') . '/?file="+$.fn.yiiGridView.getSelection(id);}' : NULL,
            'dataProvider' => $dataProvider,
            'htmlOptions' => array('style' => 'cursor: pointer'),
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            //'filter' => $model,
            'columns' => array(
                /*array(
                    'name' => 'name',
                    'headerHtmlOptions' => array('width' => 250),
                ),*/
                array(
                    'name' => 'ad_enabled',
                    'header' => Yii::t('main-ui', 'Enabled'),
                    'value' => '$data["ad_enabled"] ? "Активен" : "Отключен"',
                ),
                //'ad_enabled',
                array(
                   'name' => 'type',
                   'header' => Yii::t('main-ui', 'Type'),
                   'value' => '$data["type"] == "openldap" ? "LDAP" : "Active Directory"',
               ),
                //'accountSuffix',
                array(
                    'name' => 'accountSuffix',
                    'header' => Yii::t('main-ui', 'Account suffix'),
                ),
                //'domaincontrollers',
//                array(
//                    'name' => 'domaincontrollers',
//                    'header' => Yii::t('main-ui', 'Domain controller'),
//                ),
                //'adminusername',
                //'adminpassword',
                //'fastAuth',
                //'fileName',
            ),
        ));
        ?>
    </div>
</div>
