<?php

$dialog = $this->widget('ext.ecolumns.EColumnsDialog', array(
    'options' => array(
        'title' => Yii::t('main-ui', 'Columns settings'),
        'autoOpen' => false,
        'show' => 'fade',
        'hide' => 'fade',
    ),
    'htmlOptions' => array('style' => 'display: none'), //disable flush of dialog content
    'ecolumns' => array(
        'gridId' => 'request-grid-full-report', //id of related grid
        'storage' => 'db',  //where to store settings: 'db', 'session', 'cookie'
        'userSpecific' => true,
        'buttonApply' => '<input type="submit" value="' . Yii::t('main-ui', 'Apply') . '" style="float: left">',
        'buttonCancel' => false,
        'buttonReset' => '<input type="button" class="reset" value="' . Yii::t('main-ui', 'Reset') . '" style="float: right">',
        'fixedLeft' => array('CCheckBoxColumn'), //fix checkbox to the left side
        'model' => $model->searchcustom(), //model is used to get attribute labels
        'columns' => array(
            array(
                'name' => 'slabel',
                'type' => 'raw',
                'header' => Yii::t('main-ui', 'Status'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'slabel',
                    'data' => Status::all(),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'slabel2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'channel',
                'header' =>  Yii::t('main-ui', 'Channel'),
                'headerHtmlOptions' => array('width' => 90),
                'type' => 'raw',
                'filter' => ['Email' => Yii::t('main-ui', 'Email'), 'Manual' => Yii::t('main-ui', 'Manual'),'Planned' => Yii::t('main-ui', 'Planned'),'Portal' => Yii::t('main-ui', 'Portal'),'Telegram' => Yii::t('main-ui', 'Telegram'), 'Viber' => Yii::t('main-ui', 'Viber'), 'Whatsapp' => Yii::t('main-ui', 'Whatsapp'), 'Skype' => Yii::t('main-ui', 'Skype'), 'Slack' => Yii::t('main-ui', 'Slack'), 'Facebook' => Yii::t('main-ui', 'Facebook'), 'Webchat' => Yii::t('main-ui', 'Web chat'), 'Widget' => Yii::t('main-ui', 'Widget')],
                'value' => '$data->channel?Yii::t("main-ui" , "$data->channel") : ""',
            ),
            array(
                'name' => 'delays',
                'type' => 'raw',
                'header' => Yii::t('main-ui', 'Delays'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'delays',
                    'data' => ['delayed_start' => Yii::t('main-ui', 'Overdue reaction'), 'delayed_end' => Yii::t('main-ui', 'Overdue salvation')],
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'delay',
                        'style' => 'width: 200px;',
                    ),
                ), true),
                'value' => '$data->delayed_end ? "исполнение" : ( $data->delayed_start ? "реакция" : NULL )',
            ),

            array(
                'name' => 'delayedHours',
                'header' => Yii::t('main-ui', 'Expired hours'),
                'headerHtmlOptions' => array('width' => 70),
            ),

            array(
                'name' => 'Date',
                'headerHtmlOptions' => array('width' => 120),
                'header' => Yii::t('main-ui', 'Created'),
                'filter' => '<div class="dtpicker">'.$this->widget('bootstrap.widgets.TbDateRangePicker', array(
                    'model' => $model,
                    'attribute' => 'Date',
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
                'name' => 'StartTime',
                'header' => Yii::t('main-ui', 'Start Time'),
                'headerHtmlOptions' => array('width' => 70),
                'filter' => '<div class="dtpicker">'.$this->widget('bootstrap.widgets.TbDateRangePicker', array(
                    'model' => $model,
                    'attribute' => 'StartTime',
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
                        'id' => 'snewDatepicker',
                        'class' => 'betweenDatepicker',
                    ),
                ),
                    true).'</div>',

            ),
            array(
                'name' => 'fStartTime',
                'header' => Yii::t('main-ui', 'Fact Start time'),
                'headerHtmlOptions' => array('width' => 70),
                'filter' => '<div class="dtpicker">'.$this->widget('bootstrap.widgets.TbDateRangePicker', array(
                    'model' => $model,
                    'attribute' => 'fStartTime',
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
                        'id' => 'fsnewDatepicker',
                        'class' => 'betweenDatepicker',
                    ),
                ),
                    true).'</div>',

            ),
            array(
                'name' => 'EndTime',
                'header' => Yii::t('main-ui', 'Deadline'),
                'headerHtmlOptions' => array('width' => 70),
                'filter' => '<div class="dtpicker">'.$this->widget('bootstrap.widgets.TbDateRangePicker', array(
                    'model' => $model,
                    'attribute' => 'EndTime',
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
                        'id' => 'fnewDatepicker',
                        'class' => 'betweenDatepicker',
                    ),
                ),
                    true).'</div>',

            ),
            array(
                'name' => 'fEndTime',
                'header' => Yii::t('main-ui', 'Fact End Time'),
                'headerHtmlOptions' => array('width' => 70),
                'filter' => '<div class="dtpicker">'.$this->widget('bootstrap.widgets.TbDateRangePicker', array(
                    'model' => $model,
                    'attribute' => 'fEndTime',
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
                        'id' => 'fenewDatepicker',
                        'class' => 'betweenDatepicker',
                    ),
                ),
                    true).'</div>',
            ),
            array(
                'name' => 'lead_time',
                'header' => Yii::t('main-ui', 'Time worked'),
                'headerHtmlOptions' => array('width' => 70),
            ),
            array(
                'name' => 'Name',
                'header' => Yii::t('main-ui', 'Ticket subject'),
                'headerHtmlOptions' => array('width' => 250),
            ),
            array(
                'name' => 'phone',
                'header' => Yii::t('main-ui', 'Phone'),
                'headerHtmlOptions' => array('width' => 150),
            ),
            array(
                'name' => 'room',
                'header' => Yii::t('main-ui', 'Room'),
                'headerHtmlOptions' => array('width' => 150),
            ),
            array(
                'name' => 'Address',
                'header' => Yii::t('main-ui', 'Address'),
                'headerHtmlOptions' => array('width' => 250),
            ),
            array(
                'name' => 'company',
                'header' => Yii::t('main-ui', 'Company'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'company',
                    'data' => Companies::all(),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'company2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'depart',
                'header' => Yii::t('main-ui', 'Department'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'depart',
                    'data' => CHtml::listData(Depart::model()->findAll(), 'name', 'name'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'depart2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'creator',
                'headerHtmlOptions' => array('width' => 120),
                'header' => Yii::t('main-ui', 'Creator'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'creator',
                    //'asDropDownList' => false,
                    //'name' => 'fullname',
                    'data' => CHtml::listData(CUsers::model()->findAll(), 'fullname', 'fullname'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'creator2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'fullname',
                'headerHtmlOptions' => array('width' => 120),
                'header' => Yii::t('main-ui', 'Customer'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'fullname',
                    //'asDropDownList' => false,
                    //'name' => 'fullname',
                    'data' => CHtml::listData(CUsers::model()->findAll(), 'fullname', 'fullname'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'fullname2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'cunits',
                'header' => Yii::t('main-ui', 'Units'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'cunits',
                    'data' => CHtml::listData(Cunits::model()->findAll(), 'name', 'name'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'cunits2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'service_name',
                'header' => Yii::t('main-ui', 'Service'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'service_name',
                    'data' => CHtml::listData(Service::model()->findAll(), 'name', 'name'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'service2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'mfullname',
                'header' => Yii::t('main-ui', 'Manager'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'mfullname',
                    'data' => CHtml::listData(CUsers::model()->findAll(), 'fullname', 'fullname'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'mfullname2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'gfullname',
                'value' => '$data->groups_rl ? $data->groups_rl->name : NULL',
                'header' => Yii::t('main-ui', 'Group'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'groups_id',
                    'data' => CHtml::listData(Groups::model()->findAll(), 'id', 'name'),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'groups_id2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'ZayavCategory_id',
                'header' => Yii::t('main-ui', 'Category'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'ZayavCategory_id',
                    'data' => Category::model()->All(),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'ZayavCategory_id2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'Priority',
                'header' => Yii::t('main-ui', 'Priority'),
                'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                    'model' => $model,
                    'attribute' => 'Priority',
                    'data' => Zpriority::model()->all(),
                    'htmlOptions' => array(
                        'multiple' => 'multiple',
                        'id' => 'Priority2',
                        'style' => 'width: 250px;',
                    ),
                ), true),
            ),
            array(
                'name' => 'Content',
                'header' => Yii::t('main-ui', 'Content'),
                'value' => 'strip_tags($data->Content)',
                'filter' => false,
            ),
            array(
                'name' => 'rating',
                'header' => Yii::t('main-ui', 'Rating'),
                'type' => 'raw',
                'value' => '$this->grid->controller->widget("CStarRating", array(
                        "name" => $data->id,
                        "id" => $data->id,
                        "minRating" => "1",
                        "maxRating" => "5",
                        "ratingStepSize" => "1",
                        "starWidth" => "10",
                        "value" => $data->rating,
                        "readOnly" => true,
                    ), true)',
                'headerHtmlOptions' => array('width' => 100),
                'htmlOptions' => array('class' => 'rating-block'),
                'filter' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
                'sortable' => false,
            ),
        )
    ),
));
$fixed_columns = array_filter(array(
    array(
        'name' => 'id',
        'header' => Yii::t('main-ui', '#'),
        'headerHtmlOptions' => array('width' => 60),
    ),
));