<?php


Yii::app()->clientScript->registerCssFile('/js/redactor3/redactor.css');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/redactor.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/langs/ru.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/alignment/alignment.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/table/table.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/fullscreen/fullscreen.js');
Yii::app()->clientScript->registerScriptFile('/js/redactor3/plugins/video/video.js');

?>

    <style>
        .redactor-in {
            padding: 24px;
            border: 1px dashed rgba(0, 0, 0, .15);
            border-top: none;
            #background: #f6f9fe;
        }
    </style>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'htmlOptions' => [
        'enctype' => 'multipart/form-data',
    ],
    'id' => 'batchCommentForm',
    'enableAjaxValidation' => false,
]); ?>
    <!--    <div class="box-body" style="max-height: 500px;">-->
    <div class="box-body">
        <input type="hidden" name="r_ids" id="r_ids" value="" />
        <input type="hidden" name="url" id="url" value="" />
        <input type="hidden" name="status" id="status_batch" value="" />
<!--        <div class="alert in alert-block fade alert-error"><a href="#" class="close" data-dismiss="alert">×</a><strong>Для смены статуса заявки, необходимо добавить комментарий!</strong></div>-->
        <?php if (!Yii::app()->user->checkAccess('systemManager')) : ?>
        <div class="modal-rating" style="display: none">
            <h5>Поставьте оценку</h5>
            <?php $this->widget('CStarRating', array(
                    'name' => 'rating',
                    'minRating' => '1',
                    'maxRating' => '5',
                    //'value' => $model->rating, // mark 1...5
                    'starWidth' => '20',
                    'ratingStepSize' => '1',
                    'allowEmpty' => false,
                ));
            ?>

            <br/><br/>
        </div>
        <?php endif; ?>
        <?php
        $comment = new Comments();

        if (!Yii::app()->user->checkAccess('systemUser') OR Yii::app()->user->checkAccess('systemAdmin') ) {
            echo '<div class="row-fluid">';
            echo '<div class="span12">';
            $connection = Yii::app()->db;
            $criteria = new CDbCriteria;
            $criteria->order = ' id DESC';
            $user_sql = 'SELECT * FROM `CUsers` `t` WHERE `t`.`id`=' . Yii::app()->user->id . ' LIMIT 1';
            $user = Yii::app()->user->id ? $connection->createCommand($user_sql)->queryRow() : '';
            if (!Yii::app()->user->isGuest) {
                $username = $user['fullname'];
            } else {
                $username = 'Гость';
            }
            $role_sql = 'SELECT * FROM `roles` `t` WHERE `t`.`value`="' . Yii::app()->user->role . '" LIMIT 1';
            $role_name = $connection->createCommand($role_sql)->queryRow();
            if ($role_name) {
                if (!Yii::app()->user->checkaccess('systemAdmin')) {
                    $criteria->compare('access', $role_name['name'], true);
                }
            }
            $faq = Knowledge::model()->findAll($criteria);
            echo $form->select2Row($comment, 'kbtheme', [
                'multiple' => false,
                'data' => ['0' => Yii::t('main-ui', 'Select item')] +CHtml::listData($faq, 'id', 'name'),
                'options' => [
                    'width' => '100%',
                    'tokenSeparators' => [','],
                ],
                //'prompt' => Yii::t('main-ui', 'Select item'),
                'ajax' => [
                    'type' => 'POST',
                    'dataType' => 'json',
                    'url' => CController::createUrl('Request/SelectKB'),
                    'success' => 'function(data) {
                        var text;
                        text = $("#comment").val();
                        text = text.replace("<p>&#8203;</p>","");
                        if($(".redactor-in-1").length){
                            $(".redactor-in-1").html(data.content);
                          }else{
                            $(".redactor-in-0").html(data.content);
                          }
                        $("#comment").val(text+data.content);
                    }',
                ]
            ]);
            echo '</div>';
            echo '</div>';
            echo '<div class="row-fluid">';
            echo '</div>';
            echo '<br>';
        }
        if (!Yii::app()->user->checkAccess('systemUser') OR Yii::app()->user->checkAccess('systemAdmin') ) {
            echo '<div class="row-fluid">';
            echo '<div class="span12">';
            echo '<div class="span4">';
            echo CHtml::activeLabel($comment, 'show');
            $form->widget('bootstrap.widgets.TbToggleButton', [
                'model' => $comment,
                'attribute' => 'show',
            ]);
            echo '</div>';
            if (Yii::app()->user->checkAccess('canAddTemplate')){
                echo '<div class="span4">';
                echo CHtml::activeLabel($comment, 'add_temp');
                $form->widget('bootstrap.widgets.TbToggleButton', [
                    'model' => $comment,
                    'attribute' => 'add_temp',
                ]);
                echo '</div>';
            }
            if (Yii::app()->user->checkAccess('canAddKBreply')) {
                echo '<div class="span4">';
                echo CHtml::activeLabel($comment, 'add_kb');
                $form->widget('bootstrap.widgets.TbToggleButton', [
                    'model' => $comment,
                    'attribute' => 'add_kb',
                ]);
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        echo '<div class="row-fluid">';
        echo $form->textAreaRow($comment, 'comment', array('id' => 'comment', 'rows' => 5));
        Yii::app()->clientScript->registerScript('redactor-init4', "
       function addField(id) {
        if(id){
            $(\"form\").append('<input id=\"file' + id + '\" type=\"hidden\" value=\"' + id + '\" name=\"Comments[files][]\">');
        }
    }
    $(function () {
        $('#comment').redactor({
            lang: 'ru',
            plugins: ['alignment', 'table', 'fullscreen', 'video'],
            imageResizable: true,
            imagePosition: true,
            linkValidation: false,
            linkSize: 200,
            
        });
    });
    ");
        echo '</div>';
        ?>
    </div>

<?php $this->endWidget(); ?>