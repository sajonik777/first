<?php

$config2 = array('keyField' => 'id', 'pagination' => false);
$rawData2 = $merged;
$dataProvider2 = new CArrayDataProvider($rawData2, $config2);
echo '<h4>' . Yii::t('main-ui', 'Merged items') . '</h4>';
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
    'type' => 'striped bordered condensed',
    'id' => 'request-grid-merged',
    'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/request') . '/"+$.fn.yiiGridView.getSelection(id);}',
    'template' => "{items}",
    'dataProvider' => $dataProvider2,
    'columns' => array(
        array(
            'name' => 'id',
            'header' => Yii::t('main-ui', '#'),
            'headerHtmlOptions' => array('width' => 30),
            //'filter' => '',
        ),
        array(
            'name' => 'image',
            'headerHtmlOptions' => array('width' => 10),
            'type' => 'html',
            'header' => CHtml::tag('i class="fa-solid fa-paperclip"'),
            'filter' => '',
            'value' => '$data->image ? CHtml::tag("i class=\"fa-solid fa-paperclip\"") : ""',
        ),
//        array(
//            'name' => 'update_by',
//            'headerHtmlOptions' => array('width' => 10),
//            'type' => 'html',
//            'header' => CHtml::tag('i class="fa-solid fa-lock"'),
//            'filter' => '',
//            'value' => '$data->update_by?CHtml::tag("i class=\"fa-solid fa-lock\""): ""',
//        ),
        array(
            'name' => 'Comment',
            'type' => 'html',
            'header' => CHtml::tag('i class="fa-solid fa-comment"'),
            'headerHtmlOptions' => array('width' => 10),
            'filter' => '',
        ),
        array(
            'name' => 'Name',
            'header' => Yii::t('main-ui', 'Name'),
        ),
        array(
            'name' => 'slabel',
            'type' => 'html',
            'header' => Yii::t('main-ui', 'Status'),
            'headerHtmlOptions' => array('width' => 50),
        ),

        array(
            'name' => 'fullname',
            'header' => Yii::t('main-ui', 'Username'),
            'headerHtmlOptions' => array('width' => 150),
        ),
        array(
            'name' => 'mfullname',
            'header' => Yii::t('main-ui', 'Manager'),
            'headerHtmlOptions' => array('width' => 150),
        ),
        array(
            'name' => 'ZayavCategory_id',
            'header' => Yii::t('main-ui', 'Category'),
            'filter' => Category::model()->All(),
            'headerHtmlOptions' => array('width' => 150),
        ),
        array(
            'name' => 'Priority',
            'header' => Yii::t('main-ui', 'Priority'),
            'filter' => Zpriority::model()->all(),
            'headerHtmlOptions' => array('width' => 150),
        ),

        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'header' => Yii::t('main-ui', 'Actions'),
            'template' => Yii::app()->user->checkAccess('viewRequest') ? '{view} {split}' : NULL,
            'buttons' => array
            (
                'view' => array
                (
                    'label' => Yii::t('main-ui', 'View'),
                    'url' => 'Yii::app()->createUrl("request/view", array("id"=>$data->id))',
                ),
                'split' => array
                (
                    'label' => Yii::t('main-ui', 'Split'),
                    'icon' => 'fa-solid fa-delete-left',
                    'url' => 'Yii::app()->createUrl("request/split", array("id"=>$data->id, "YII_CSRF_TOKEN"=>Yii::app()->request->csrfToken))',
                        'options' => array(
                            'ajax'=>array(
                                'type' => 'GET',
                                'url'=>'js:$(this).attr("href")',
                                'data'=>array('id'=>$data->id, 'pid' => $pid, 'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken),
                                'success'=> 'function(data) { $.fn.yiiGridView.update("request-grid-merged"); $(".lb-danger").html(data); console.log(data)}'))
                ),
            ),
        ),
    ))); ?>