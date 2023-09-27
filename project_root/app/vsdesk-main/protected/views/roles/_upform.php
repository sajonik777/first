<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'roles-form',
            'enableAjaxValidation' => false,
        )); ?>

        <div class="row-fluid">
            <?php echo $form->errorSummary($model);
            ?>
            <?php echo $form->textFieldRow($model, 'name', array('class' => 'span12', 'maxlength' => 50)); ?>
            <?php echo $form->textFieldRow($model, 'value', array('class' => 'span12', 'maxlength' => 50)); ?>
            <div class="row-fluid">
                <?php
                $this->widget('bootstrap.widgets.TbGridView', array(
                    'id' => 'rights-grid',
                    'type' => 'striped bordered condensed',
                    'summaryText' => '',
                    'dataProvider' => $dataProvider,
                    'filter' => $filtersForm,
                    'pager' => array(
                        'class' => 'CustomPager',
                        'displayFirstAndLast' => true,
                    ),
                    'columns' => array(
                        array(
                            'class' => 'bootstrap.widgets.TbToggleColumn',
                            'toggleAction' => 'toggle',
                            'headerHtmlOptions' => array('width' => 10),
                            'name' => 'value',
                            'header' => false,
                            'filter' => false,
                        ),
                        array(
                            'name' => 'category',
                            'headerHtmlOptions' => array('width' => 200),
                            'header' => Yii::t('main-ui', 'Category'),
                            'filter' => $filters,
                        ),
                        array(
                            'name' => 'description',
                            'header' => Yii::t('main-ui', 'Name'),
                            //'filter' => false,
                        ),
                    ),
                ));
                ?>
            </div>
        </div>
    </div>
            <div class="box-footer">
                <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
                )); ?>
            </div>
        <?php $this->endWidget(); ?>
</div>