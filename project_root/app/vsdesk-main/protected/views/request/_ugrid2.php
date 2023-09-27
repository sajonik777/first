<?php

$total = '';

$criteria = new CDbCriteria();
$criteria->compare('fullname', $model->fullname, true);
$criteria->compare('company', $model->company, true);
$criteria->compare('department', $model->department, true);
$criteria->compare('role_name', $model->role_name, true);
$criteria->order = 'fullname ASC';


$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'selectionChanged' => 'function(id){
        var model = "' . $reqmodel. '";
        var csrf = "' . Yii::app()->request->csrfToken . '";
                    $.ajax({
                                type: "POST",
                                url: "/request/upduser2",
                                data: {"model":model, "user": $.fn.yiiGridView.getSelection(id), "YII_CSRF_TOKEN":csrf},
                                dataType: "text",
                                cache: false,
                                error: function (e) {
                                    console.log(e);
                                },
                                success: function (data) {
                                    location.reload();
                                }

                })
                }',
    'id' => 'asset-grid',
    'ajaxUrl' => Yii::app()->createUrl('/request/grid2'),
    'dataProvider' => new CActiveDataProvider($model, array('criteria' => $criteria)),
    'htmlOptions' => array('style' => 'cursor: pointer'),
    'filter' => $model,
    'columns' => array(
            array(
                'name' => 'fullname',
                'header' => Yii::t('main-ui', 'Fullname'),
            ),
            array(
                'name' => 'company',
            ),
            array(
                'name' => 'department',
                'type' => 'html',
                'filter' => Depart::all(),
            ),
            array(
                'name' => 'role_name',
                'header' => Yii::t('main-ui', 'Role'),
                'filter' => Roles::fall(),
            ),

    ),
    )); ?>