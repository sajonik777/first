<?php

$view = NULL;
$update = NULL;
$delete = NULL;
//if (Yii::app()->user->checkAccess('viewLeads')) {
$view = '{view}';
//}
//if (Yii::app()->user->checkAccess('updateLeads')) {
$update = '{update}';
//}
//if (Yii::app()->user->checkAccess('deleteLeads')) {
$delete = '{delete}';
//}
$template = $view . ' ' . $update . ' ' . $delete;
?>

<?php $dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'leads-grid', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->search(), //model is used to get attribute labels
        'columns' => array(
            array(
                'name' => 'status',
                'type' => 'raw',
                'header' => Yii::t('main-ui', 'Этап сделки'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'status',
                    'data' => CHtml::listData(Pipeline::model()->findAll(), 'label', 'name'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        //'id' => 'status',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'name',
                'header' => Yii::t('main-ui', 'Name'),
            ),
            array(
                'name' => 'created',
                'headerHtmlOptions' => array('width' => 120),
                'header' => Yii::t('main-ui', 'Created'),
                'filter' => '<div class="dtpicker">'.$this->widget('bootstrap.widgets.TbDateRangePicker', array(
                    'model' => $model,
                    'attribute' => 'created',
                    'callback' => 'js:function(){$(this.element).change();}',
                    'options' => array(
                        'format' => 'DD.MM.YYYY',
                        'language' => 'ru',
                        'ranges' => array(
                            'Сегодня' => 'js:[moment(), moment()]',
                            'Вчера' => 'js:[moment().subtract("days", 1), moment().subtract("days", 1)]',
                            'Последние 7 дней' => 'js:[moment().subtract("days", 6), moment()]',
                            'Последние 30 дней' => 'js:[moment().subtract("days", 29), moment()]',
                            'В этом месяце' => 'js:[moment().startOf("month"), moment().endOf("month")]',
                            'В прошлом месяце' => 'js:[moment().subtract("month", 1).startOf("month"), moment().subtract("month", 1).endOf("month")]',
                        ),
                        'locale' => array(
                            'fromLabel' => 'От',
                            'toLabel' => 'До',
                            'weekLabel' => 'Н',
                            'customRangeLabel' => 'Задать даты',
                            'applyLabel' => 'Применить',
                            'cancelLabel' => 'Отмена',
                            'firstDay' => 1,
                        ),
                    ),

                    'htmlOptions' => array(
                        'id' => 'newDatepicker',
                        'class' => 'betweenDatepicker',
                    ),


                ),
                    true).'</div>',
            ),
            array(
                'name' => 'creator',
                'header' => Yii::t('main-ui', 'Кем создана'),
            ),
            array(
                'name' => 'manager',
                'header' => Yii::t('main-ui', 'Ответственный'),
            ),
            array(
                'name' => 'company',
                'header' => Yii::t('main-ui', 'Company'),
            ),
            array(
                'name' => 'contact',
                'header' => Yii::t('main-ui', 'Контакт'),
            ),
            array(
                'name' => 'contact_phone',
                'header' => Yii::t('main-ui', 'Phone'),
            ),
            array(
                'name' => 'contact_email',
                'header' => Yii::t('main-ui', 'E-mail'),
            ),
            array(
                'name' => 'contact_position',
                'header' => Yii::t('main-ui', 'Position'),
            ),
            array(
                'name' => 'cost',
                'header' => Yii::t('main-ui', 'Бюджет'),
            ),
            array(
                'name' => 'tag',
                'header' => Yii::t('main-ui', 'Тэг'),
            ),
            array(
                'name' => 'description',
                'header' => Yii::t('main-ui', 'Description'),
            ),
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