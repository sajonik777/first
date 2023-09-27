<?php
// $target_type = $model->getAttributes(array('target_type'))['target_type'];
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Service catalog') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
);

$this->menu = [
    Yii::app()->user->checkAccess('listService') ? [
        'icon' => 'fa-solid fa-list-ul fa-xl',
        'url' => ['index'],
        'itemOptions' => ['title' => Yii::t('main-ui', 'List services')]
    ] : [null],
];
?>
    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Edit') . ' ' . $model->name; ?></h3>
    </div>
    <div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
    <?php $this->widget(
            'bootstrap.widgets.TbTabs',
            [
                'type' => 'tabs', // 'tabs' or 'pills'
                'encodeLabel' => false,
                'tabs' => array_filter([
                    [
                        'label' => Yii::t('main-ui', 'Description'),
                        'content' => $this->renderPartial('_form', [
                            'model' => $model, 
                            'escalateNew' => new Escalates()], true),
                        'active' => true,
                    ],
                    $model->category_id == '2' ? [
                        'label' => Yii::t('main-ui', 'Support Services'),
                        'content' => $this->renderPartial('_support-services', [
                            'model' => $model, 
                            'escalateNew' => new Escalates()], true),
                        'active' => false,
                    ] : null,
                    $model->category_id == '1' ? [
                        'label' => Yii::t('main-ui', 'User Services'),
                        'content' => $this->renderPartial('_user-services', [
                            'model' => $model, 
                            'escalateNew' => new Escalates()], true),
                        'active' => false,
                    ] : null,
                ]),
            ]); ?>

    </div>
</div>