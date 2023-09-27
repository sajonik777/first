<?php
/**
 * @var CActiveDataProvider $dataProvider
 */

Yii::app()->clientScript->registerScript('checklist', "
    function checkListCheck(id){
    var csrf = '" . Yii::app()->request->csrfToken . "';
        $.post('/request/checklist_check',
          {
            id: id,
            YII_CSRF_TOKEN: csrf
          },
        ).success(function() { 
             $.fn.yiiGridView.update('checklists-grid');
        });
    }

", CClientScript::POS_HEAD);

?>
<hr>
<div class="box-body table-responsive">
    <h4 style="cursor: pointer" onclick="$('.checklist').toggle()" ><?= Yii::t('main-ui', 'Checklist') ?></h4>
    <div class="checklist" style="display: block">
        <?php
        $this->widget('bootstrap.widgets.TbAlert', [
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        ]); ?>
        <?php
        $this->widget('bootstrap.widgets.TbExtendedGridView', [
            'id' => 'checklists-grid',
            'type' => 'striped bordered condensed',
            'dataProvider' => $dataProvider,
            'summaryText' => '',
            'sortableRows' => true,
            'sortableAttribute' => 'sorting',
            'sortableAjaxSave' => true,
            'sortableAction' => 'request/reorder',
            'afterSortableUpdate' => 'js:function(){}',

            'columns' => [
                [
                    'header' => Yii::t('main-ui', 'Name'),
                    'type' => 'raw',
                    'value' => function ($model) {
                        /** @var RequestChecklistFields $model */
                        return $model->checked ? '<s>' . $model->checklistField->name . '<s>' : $model->checklistField->name ;
                    }
                ],
                !Yii::app()->user->checkAccess('viewOnlyChecklist') ? [
                    'header' => Yii::t('main-ui', 'Checked'),
                    'type' => 'raw',
                    'value' => function ($model) {
                        /** @var RequestChecklistFields $model */
                        return '<a class="value_toggle" onClick="checkListCheck(' . $model->id . ')" href="javascript:void(0);">
                    <i class="fa-regular ' . ($model->checked ? 'fa-square-check' : 'fa-circle-xmark') . '">XXX</i></a>';
                    }
                ] : [
                    'header' => Yii::t('main-ui', 'Checked'),
                    'type' => 'raw',
                    'value' => function ($model) {
                        /** @var RequestChecklistFields $model */
                        return '<a class="value_toggle">
                    <i class="fa-regular ' . ($model->checked ? 'fa-square-check' : 'fa-circle-xmark') . '">XXX</i></a>';
                    }
                ],
                [
                    'header' => Yii::t('main-ui', 'User'),
                    'value' => function ($model) {
                        /** @var RequestChecklistFields $model */
                        return $model->checkedUser ? $model->checkedUser->fullname : null;
                    }
                ],
                [
                    'header' => Yii::t('main-ui', 'Check time'),
                    'name' => 'checked_time',
                ],
            ],
        ]); ?>
    </div>
</div>
