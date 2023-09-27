<?php

$total = '';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Log analyzer') => array('index'),
);

?>
<div class="page-header">
    <h3><i class="fa-solid fa-list-check fa-xl"> </i><?php echo Yii::t('main-ui', 'Log analyzer'); ?></h3>
</div>
<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => Yii::t('main-ui', 'Clear log'),
    'type' => 'danger',
    'icon' => 'trash',
    'url' => $this->createUrl('/log/deleteall'),

));
?>
<hr>
<div class="box">
    <div class="box-body table-responsive">
        <?php
        $levels = array('error' => 'Error', 'warning' => 'Warning', 'info' => 'Info', 'created' => 'Created', 'updated' => 'Updated', 'deleted' => 'Deleted');
        $this->widget('FilterGridResizable', array(
            'id' => 'log-grid',
            'redirectRoute' => CHtml::normalizeUrl(''),
            'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/log') . '/"+$.fn.yiiGridView.getSelection(id);}',
            'summaryText' => '<div class="items_col2"> ' . Yii::t('main-ui', 'Items: ') . '' . CHtml::dropDownList('', Yii::app()->session['logPageCount'] ? Yii::app()->session['logPageCount'] : 30, Yii::app()->params['selectPageCount'], array('onchange' => "document.location.href='/" . Yii::app()->request->pathInfo . "?pageCount='+this.value;")) . '</div> ' . Yii::t('zii', 'Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.', $total),
            'type' => 'striped bordered condensed span12',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'afterAjaxUpdate' => 'reinstallDatePicker',
            'template' => "{summary}\n{items}\n{pager}",
            'pager' => array(
                'class' => 'CustomPager',
                'displayFirstAndLast' => true,
            ),
            'columns' => array(
                array(
                    'name' => 'logtime',
                    'header' => Yii::t('main-ui', 'Date'),
                    'headerHtmlOptions' => array('width' => 90),
                    'filter' => '<div class="dtpicker">'.$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'logtime',
                        'i18nScriptFile' => 'jquery.ui.datepicker-ru.js',
                        'htmlOptions' => array(
                            'id' => 'newDatepicker2',
                        ),
                        'defaultOptions' => array(
                            'dateFormat' => 'dd.mm.yy',
                            'showButtonPanel' => true,
                            'changeYear' => true,
                        )
                    ),
                    true).'</div>',

                ),
                array(
                    'name' => 'level',
                    'headerHtmlOptions' => array('width' => 150),
                    'type' => 'raw',
                    'filter' => $levels,
                ),
                'category',
                array(
                    'name' => 'message',
                ),
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'header' => Yii::t('main-ui', 'Actions'),
                    'headerHtmlOptions' => array('width' => 70),
                    'template' => '{view} {delete}',
                ),
            ),
            )); ?>
        </div>
    </div>

    <?php Yii::app()->clientScript->registerScript('re-install-date-picker', "
       function reinstallDatePicker(id, data) {
           $('#newDatepicker2').datepicker();
       }
       ");
       ?>