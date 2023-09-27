<?php $this->widget('bootstrap.widgets.TbDetailView', [
            'data' => $model,
            'type' => 'striped bordered condensed',
            'attributes' => [
                'name',
                'description',
                'sla',
                'priority',
                'availability',
                [
                    'name' => 'checklist_id',
                    'value' => $model->checklist ? $model->checklist->name : null,
                ],
                [
                    'name' => 'shared',
                    'value' => $model->shared === '1' ? 'Да' : 'Нет',
                ],
            ],
        ]); ?>

        <?php
        if ($model->category_id) {
            $this->widget('bootstrap.widgets.TbDetailView', [
                'data' => $model,
                'type' => 'striped bordered condensed',
                'attributes' => [
                    [
                        'name' => 'category_id',
                        'value' => $model->category->name,
                    ],
                ],
            ]);
        }
        ?>

        <?php
        if ($model->watcher) {
            $this->widget('bootstrap.widgets.TbDetailView', [
                'data' => $model,
                'type' => 'striped bordered condensed',
                'attributes' => [
                    'watcher',
                ],
            ]);
        }
        ?>

        <?php
        if ($model->matchings) {
            $this->widget('bootstrap.widgets.TbDetailView', [
                'data' => $model,
                'type' => 'striped bordered condensed',
                'attributes' => [
                    'matchingNames',
                ],
            ]);
        }
        ?>

        <?php
        if ($model->gtype == 1) {
            $this->widget('bootstrap.widgets.TbDetailView', [
                'data' => $model,
                'type' => 'striped bordered condensed',
                'attributes' => [
                    'manager_name',
                ],
            ]);
        } else {
            $this->widget('bootstrap.widgets.TbDetailView', [
                'data' => $model,
                'type' => 'striped bordered condensed',
                'attributes' => [
                    'group',
                ],
            ]);
        }
        if ($model->fieldset) {
            $fieldset = Fieldsets::model()->findByPk($model->fieldset);
            $this->widget('bootstrap.widgets.TbDetailView', [
                'data' => $model,
                'type' => 'striped bordered condensed',
                'attributes' => [
                    [
                        'name' => 'fieldset',
                        'value' => $fieldset->name,
                    ],
                ],
            ]);
        }
        if ($model->content) {
            $this->widget('bootstrap.widgets.TbDetailView', [
                'data' => $model,
                'type' => 'striped bordered condensed',
                'attributes' => [
                    [
                        'name' => 'content',
                        'type' => 'html',
                        'value' => $model->content,
                    ],
                ],
            ]);
        }
        ?>