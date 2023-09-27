<?php

$total = '';

$criteria = new CDbCriteria();
$criteria->compare('fullname', $model->fullname, true);
$criteria->compare('company', $model->company, true);
$criteria->compare('department', $model->department, true);
$criteria->compare('role_name', $model->role_name, true);
$criteria->compare('active', 1, false);
$criteria->order = 'fullname ASC';


$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'selectionChanged' => 'function(id){
        var csrf = "' . Yii::app()->request->csrfToken . '";
                    $.ajax({
                                type: "POST",
                                url: "/request/selectuser",
                                data: {"id": $.fn.yiiGridView.getSelection(id), "YII_CSRF_TOKEN":csrf},
                                dataType: "text",
                                cache: false,
                                update: "#CUsers_id",
                                error: function (e) {
                                    console.log(e);
                                },
                                success: function (data) {
                                    $("#CUsers_id").html(data);
                                    var selectedRowElements = $("#asset-grid .items tbody tr.selected td");
                                    var userid = selectedRowElements[0];
                                    $("#s2id_CUsers_id .select2-chosen").html($(userid).text());
                                    $("#CUsers_id").val(userid).change();
                                    $("#myModal").modal("hide");
                                    var pid = document.getElementById("CUsers_id").value;
                                    $.ajax({
										type: "POST",
										url:  "/request/selectadmobject",
										data: {"pid":pid,"YII_CSRF_TOKEN":csrf},
										dataType: "text",
										cache: false,
										update: "#cunits",
										error: function(e) {
											console.log(e);
										},
										success: function(html) {
											$("#cunits").html(html);
										}
									});
                                }

                })
                }',
    'id' => 'asset-grid',
    'ajaxUrl' => Yii::app()->createUrl('/request/grid'),
    'dataProvider' => new CActiveDataProvider($model, array('criteria' => $criteria)),
    'htmlOptions' => array('style' => 'cursor: pointer'),
    'afterAjaxUpdate' => 'reinstallDatePicker',
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'fullname',
            'header' => Yii::t('main-ui', 'Fullname'),
        ),
        array(
            'name' => 'company',

            'filter' => $this->widget('bootstrap.widgets.TbSelect2', array(
                'model' => $model,
                'attribute' => 'company',
                'data' => array_merge(array('' => ''), Companies::all()),
                'htmlOptions' => array(
                    'multiple' => false,
                    'id' => 'company22',
                    'style' => 'width: 200px;',
                ),
            ), true),
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