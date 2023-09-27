
<div class="modal-footer">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $model->isNewRecord ? Yii::t('main-ui', 'Merge') : Yii::t('main-ui', 'Save'),
            )); ?>
</div>