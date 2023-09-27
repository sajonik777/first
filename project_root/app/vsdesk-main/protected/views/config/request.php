<?php
require_once '_grid.php';
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Ticket defaults'),

);
?>
<div class="page-header">
    <h3><i class="fa-solid fa-ticket fa-xl"> </i><?php echo Yii::t('main-ui', 'Ticket defaults'); ?></h3>
</div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbAlert', array(
            'block' => true,
            'fade' => true,
            'closeText' => '×',
        )); ?>
        <div class="form">
            <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'id' => 'request-form',
                'enableAjaxValidation' => false,
            ));
            ?>

            <?php echo $form->errorSummary($model5); ?>
            <p>
                <strong><?php echo Yii::t('main-ui', 'Select the fields used by default for a simplified ticket form.'); ?></strong>
            </p>
            <div class="row-fluid">
                <div class="span6">
                    <?php
                    // echo $form->radioButtonListRow(
                    //     $model5,
                    //     'enabled',
                    //     array(
                    //         'lite' => Yii::t('main-ui', 'Use a simple ticket form'),
                    //         'full' => Yii::t('main-ui', 'Use a full ticket form'),
                    //     )
                    // ); 
                    ?>

                    <?php echo $form->dropDownListRow($model5, 'zdsla', Sla::all_id(), array('class' => 'span12')); ?>

                    <?php echo $form->dropDownListRow($model5, 'zdpriority', Zpriority::all(), array('class' => 'span12')); ?>

                    <!-- <?php echo $form->dropDownListRow($model5, 'zdcategory', Category::all(), array('class' => 'span12')); ?>
                     -->

                    <?php echo $form->dropDownListRow($model5, 'zdtype', array('1' => 'Пользователь', '2' => 'Группа'), array(
                        'id' => 'zdtype',
                        'class' => 'span12',
                        'ajax' => array(
                            'type' => 'POST',//тип запроса
                            'url' => CController::createUrl('Config/SelectGroup'),//вызов контроллера c Ajax
                            'update' => '#manag',//id DIV - а в котором надо обновить данные
                        ))); ?>
                    <div id="manag">
                        <?php
                        if ($model5->zdtype == 1 OR $model5->zdtype == NULL) {
                            echo $form->dropDownListRow($model5, 'zdmanager', CUsers::model()->all(), array('class' => 'span12'));
                        } else {
                            echo $form->dropDownListRow($model5, 'zdmanager', Groups::model()->all(), array('class' => 'span12'));
                        }

                        ?>
                    </div>
                    <?php echo $form->labelEx($model5, 'req_columns_default'); ?>
                        <div class="row-fluid">
                        <div class="span12">
                            <div class="span12">
                                <a href="javascript:void(0);" id="request-grid-default-ecolumns-dlg-link"><i class="fa-solid fa-gear fa-xl" title="<?php echo Yii::t('main-ui', 'Columns settings'); ?>"></i> </a>
                            </div>
                            <?php echo $form->textField($model5, 'req_columns_default', array('class' => 'span12', 'readonly' => true)); ?>
                        </div>
                        </div>
                </div>
                <div class="span6">
                    <?php echo $form->toggleButtonRow($model5, 'update_grid'); ?>
                    <?php echo $form->toggleButtonRow($model5, 'monopoly'); ?>
                    <?php echo $form->toggleButtonRow($model5, 'autoaccept'); ?>
                    <?php echo $form->toggleButtonRow($model5, 'nocomment'); ?>

                    <?php echo $form->toggleButtonRow($model5, 'autoarch'); ?>
                    <?php echo $form->labelEx($model5, 'autoarchdays'); ?>
                    <?php echo $form->textField($model5, 'autoarchdays', array('class' => 'span12')); ?>
                    <?php echo $form->error($model5, 'autoarchdays'); ?>

                    <?php echo $form->labelEx($model5, 'update_grid_timeout'); ?>
                    <?php echo $form->textField($model5, 'update_grid_timeout', array('class' => 'span12')); ?>
                    <?php echo $form->error($model5, 'update_grid_timeout'); ?>

                    <?php echo $form->labelEx($model5, 'grid_items'); ?>
                    <?php echo $form->textField($model5, 'grid_items', array('class' => 'span12')); ?>
                    <?php echo $form->error($model5, 'grid_items'); ?>
                    <?php echo $form->dropDownListRow($model5, 'kbcategory', CHtml::listData(Categories::model()->findAll(), 'id', 'name'), array('class' => 'span12')); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box-footer">
            <?php echo CHtml::submitButton(Yii::t('main-ui', 'Save'), array('class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>
<?php
Yii::app()->getClientScript()->registerScript('req_columns_default', "
        jQuery('#req_columns_save').on('click', function(){
        var checked = [];
        $('input:checkbox:checked').each(function() {
            if($(this).val() != '1'){
            checked.push($(this).val());
            }
        });
        var columns = checked.join('||');
        $('#RequestForm_req_columns_default').val(columns);
        $('#request-grid-default-ecolumns-dlg').dialog('close');
            });
            ");

?>