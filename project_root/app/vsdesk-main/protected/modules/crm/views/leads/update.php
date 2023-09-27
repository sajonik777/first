<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'Leads') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = array(
    Yii::app()->user->checkAccess('listLeads') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions' => array('title' => Yii::t('main-ui', 'List Leads'))) : array(NULL),
);
?>
    <div class="page-header">
        <?php $this->widget(
            'bootstrap.widgets.TbEditableField',
            array(
                'type' => 'text',
                'mode' => 'inline',
                'inputclass' => 'span11',
                'model' => $model,
                'attribute' => 'name', // $model->name will be editable
                'options' => array('params' => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken)),
                'url' => $this->createUrl('updName', array('id' => $model->id)), //url for submit data
                'success' => 'js: function(data) {
                    location.reload();
                    }'
            )
        ); ?>
    </div>
<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => $this->menu,
)); ?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>