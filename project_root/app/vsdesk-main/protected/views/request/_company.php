<?php
$fields = $company->flds;
$sub = array();
foreach ($fields as $field) {
    if (!empty($field['value']) && $field['name'] != "Город") {
        $sub[] = array(
            'name' => $field['name'],
            'value' => $field['value'],
        );
    }
}
?>
<?php
$enabled = Asterisk::isEnabled();
$canCall = Yii::app()->user->checkAccess('amiCalls');
?>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $company,
    'type' => 'striped bordered condensed',
    'attributes' => array_filter(array_merge(array(
        array(
            'label' => Yii::t('main-ui', 'Name'),
            'type' => 'raw',
            'value' => '<a href="/companies/' . $company->id . '">' . $company->name . '</a>',
        ),
        'director',
        'uraddress',
        'faddress',
        $company->contact_name ? 'contact_name' : NULL,
        $company->phone ? [
            'label' => Yii::t('main-ui', 'Phone'),
            'type' => 'raw',
            'value' => ($enabled && $canCall) ? $company->phone . ' <a onClick="call(' . $company->phone . ');return false" href="/cusers/call" target="_blank"><i class="fa-solid fa-phone"></i></a>' : '<a href="tel:'.$company->phone.'" >'.$company->phone.'</a>',
        ] : null,
        $company->email ? 'email' : NULL,
        $company->add1 ? 'add1:raw' : NULL,
        $company->add2 ? 'add2:raw' : NULL,
        $company->manager ? 'manager' : NULL,
        $company->inn ? 'inn' : NULL,
        $company->kpp ? 'kpp' : NULL,
        $company->ogrn ? 'ogrn' : NULL,
        $company->bik ? 'bik' : NULL,
        $company->korschet ? 'korschet' : NULL,
        $company->schet ? 'schet' : NULL,

    ), $sub)),
));
if (isset($contracts) AND !empty($contracts)) {
    $cconfig = array('keyField' => 'id', 'pagination' => false);
    $crawData = $contracts;
    $cdataProvider = new CArrayDataProvider($crawData, $cconfig);
}
?>
<?php if (isset($contracts) AND !empty($contracts)): ?>
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
