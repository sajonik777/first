<?php
if (!empty($model->email)){
 Yii::app()->clientScript->registerScript('search', "
     $('#email').click(function(){
            if ($('.comment-form').is(':visible') == false) $('.comment-form').toggle();
         location.href = '#comment-form';
         return false;
     });
 ");
}

/* @var $this CompaniesController */
/* @var $model Companies */
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Companies') => array('index'),
    $model->name,
);
$this->menu = array(
    Yii::app()->user->checkAccess('listCompany') ? array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions' => array('title' => Yii::t('main-ui', 'List companies'))) : array(NULL),
    Yii::app()->user->checkAccess('updateCompany') ? array('icon' => 'fa-solid fa-pencil fa-xl', 'url' => array('update', 'id' => $model->id), 'itemOptions'=>array('title' => Yii::t('main-ui', 'Edit company'))) : array(NULL),
    (Yii::app()->user->checkAccess('updateCompany') AND !empty($model->email)) ? array('icon' => 'fa-solid fa-envelope fa-xl', 'url' => 'javascript:void(0);', 'itemOptions'=>array('id' => 'email','title' => Yii::t('main-ui', 'Send Email'))) : array(NULL),
);
$manager_name = CUsers::model()->findByAttributes(array('Username' => $model->manager));
$fields = $model->flds;
$sub = array();
foreach ($fields as $field) {
    if(!empty($field['value'])){
       $sub[] = array(
        'name' => $field['name'],
        'value' => $field['value'],
    ); 
    }
}
?>
<div class="page-header">
    <h3>"<?php echo $model->name; ?>"</h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '×',
            )); ?>
        <div class="row-fluid">
                <h4><?php echo Yii::t('main-ui', 'Main information'); ?></h4>
                <?php $this->widget('bootstrap.widgets.TbDetailView', array(
                    'data' => $model,
                    'type' => 'striped bordered condensed',
                    'attributes' => array_filter(array_merge(array(
                        'name',
                        'director',
                        'city',
                        'street',
                        'building',
                        'bblock',
                        'bcorp',
                        $model->phone ? 'phone' : NULL,
                        $model->email ? 'email' : NULL,
                        $model->contact_name ? 'contact_name' : NULL,
                        $model->manager ? array(
                            'name' => 'manager',
                            'value' => $manager_name->fullname,
                        ) : NULL,
                        $model->inn ? 'inn' : NULL,
                        $model->kpp ? 'kpp' : NULL,
                        $model->ogrn ? 'ogrn' : NULL,
                        $model->bik ? 'bik' : NULL,
                        $model->korschet ? 'korschet' : NULL,
                        $model->schet ? 'schet' : NULL,
                    ),$sub)),
                )); ?>
        </div>
        <?php if(Yii::app()->user->checkAccess('listContracts') AND isset($contracts)): ?>
            <?php
            $cconfig = array('keyField' => 'id', 'pagination' => false);
            $crawData = $contracts;
            $cdataProvider = new CArrayDataProvider($crawData, $cconfig);
            ?>
            <div class="row-fluid">
                <h4><?php echo Yii::t('main-ui', 'Contracts'); ?></h4>
                <div class="row-fluid">
                    <div class="span12" id="services">
                        <?php $this->widget('bootstrap.widgets.TbGridView', array(
                            'id' => 'contracts-grid',
                            'dataProvider' => $cdataProvider,
                            'summaryText' => false,
                            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/contracts') . '/"+$.fn.yiiGridView.getSelection(id);}',
                            'type' => 'striped bordered condensed',
                            'htmlOptions' => array('style' => 'cursor: pointer'),
                            'columns' => array(
                                [
                                    'name' => 'expired',
                                    'headerHtmlOptions' => array('width' => 10),
                                    'type' => 'raw',
                                    'filter' => '',
                                    'header' => CHtml::tag('i', array('class' => "fa-solid fa-circle-exclamation"), null),
                                    'value' => '$data->expired ? CHtml::tag("i", array("class"=>"fa-solid fa-circle-exclamation", "style" => "color: red"), null) : ""',
                                ],
                                [
                                    'name' => 'number',
                                    'header' => Yii::t('main-ui', 'Contract number'),
                                ],
                                [
                                    'name' => 'name',
                                    'header' => Yii::t('main-ui', 'Name'),
                                ],
                                [
                                    'name' => 'type',
                                    'header' => Yii::t('main-ui', 'Type'),
                                ],
                                [
                                    'name' => 'date',
                                    'header' => Yii::t('main-ui', 'Start of contract'),
                                ],
                                [
                                    'name' => 'tildate',
                                    'header' => Yii::t('main-ui', 'Contract termination'),
                                ],
                                [
                                    'name' => 'customer_name',
                                    'header' => Yii::t('main-ui', 'Customer'),
                                ],
                                [
                                    'name' => 'company_name',
                                    'header' => Yii::t('main-ui', 'Contractor'),
                                ],
                                [
                                    'name' => 'cost',
                                    'header' => Yii::t('main-ui', 'Cost'),
                                ],
                            ),
                        )); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
            <div class="row-fluid">
                <h4><?php echo Yii::t('main-ui', 'Services'); ?></h4>
                <div class="row-fluid">
                    <div class="span12" id="services">
                        <?php $this->widget('bootstrap.widgets.TbGridView', array(
                            'id' => 'services-grid',
                            'dataProvider' => new CArrayDataProvider($model->services),
                            'summaryText' => false,
                            'type' => 'striped bordered condensed',
                            'htmlOptions' => array('style' => 'cursor: pointer'),
                            'columns' => array(
                                'name:text:'.Yii::t('main-ui', 'Services'),
                            ),
                        )); ?>
                    </div>
                </div>
        </div>
    </div>
    <?php if ($model->files): ?>
            <div class="box-body">
                <?php FilesShow::show($model->files, 'companies', '/uploads', '', 'Company'); ?>
            </div>
    <?php endif; ?>
</div>
<?php if(isset($model->add1) AND !empty($model->add1)): ?>
<div class="box">
    <div class="box-header">
        <h4>
        <?php echo Yii::t('main-ui', 'Additional field'); ?>
        </h4>
    </div>
    <div class="box-body">
        <?php echo $model->add1; ?>
    </div>
</div>
<?php endif; ?>
<?php if(isset($model->add2) AND !empty($model->add2)): ?>
    <div class="box">
        <div class="box-header">
            <h4>
            <?php echo Yii::t('main-ui', 'Additional field2'); ?>
            </h4>
        </div>
        <div class="box-body">
            <?php echo $model->add2; ?>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($model->email)): ?>
<div class="comment-form" id="comment-form" style="display:none">
                <?php $this->renderPartial('_email', array(
                    'model' => $model,
                )); ?>
                <br/>
</div><!-- comment-form -->
<?php endif; ?>
