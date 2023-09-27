<?php

/** @var $this RequestprocessingrulesController */
/** @var $model RequestProcessingRules */
/** @var $form CActiveForm */
/** @var $readOnly bool */

$requestProcessingRuleCondition = new RequestProcessingRuleConditions();
$requestProcessingRuleAction = new RequestProcessingRuleActions();

$statuses = Status::all();
$priorities = Zpriority::model()->all();
$categories = Category::model()->all();
$services = Service::model()->all();
$companies = Companies::model()->all();
$departs = Depart::model()->all();
$managers = CUsers::all();
$groups = Groups::allWithId();

?>

<div class="box">
    <div class="box-body">
        <?php
        $this->widget('bootstrap.widgets.TbMenu', [
            'type' => 'pills',
            'items' => $this->menu,
        ]);
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
            'id' => 'checklists-form',
            'enableAjaxValidation' => false,
        ]);
        echo $form->errorSummary($model);
        ?>
        <div class="row-fluid">
            <?= $form->textFieldRow($model, 'name',
                ['class' => 'span12', 'maxlength' => 500, 'readonly' => $readOnly]); ?>
        </div>
        <div class="row-fluid">
            <?php echo $form->labelEx($model, 'is_all_match'); ?>
            <?php $this->widget('bootstrap.widgets.TbToggleButton',array(
                'model' => $model,
                'attribute'=>'is_all_match',
                'enabledLabel'=>'Все',
                'disabledLabel'=>'Одно',
            )); ?>
        </div>
        <div class="row-fluid">
            <?php echo $form->toggleButtonRow($model, 'is_apply_to_bots'); ?>
        </div>
    </div>
    <div class="box-footer">
        <?php
        if (!$readOnly) {
            $this->widget('bootstrap.widgets.TbButton', [
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $model->isNewRecord ? Yii::t('main-ui', 'Create') : Yii::t('main-ui', 'Save'),
            ]);
        }
        ?>
    </div>
    <?php $this->endWidget(); ?>
    <div class="box-body">
        <div class="row-fluid" style="margin-top: 15px;">
            <div class="span12">
                <hr>
                <h4><?php echo Yii::t('main-ui', 'List of conditions'); ?></h4>
            </div>
        </div>

        <?php foreach ($model->conditions as $condition): ?>
            <div class="row-fluid condition">
                <form>
                    <div class="span12">
                        <div class="span3">
                            <input name="YII_CSRF_TOKEN" type="hidden" value="<?= Yii::app()->request->csrfToken ?>">
                            <input name="RequestProcessingRuleConditions[id]" type="hidden" value="<?= $condition->id ?>">
                            <input name="RequestProcessingRuleConditions[request_processing_rule_id]" type="hidden" value="<?= $model->id ?>">
                            <label><?= Yii::t('main-ui', 'Target') ?></label>
                            <?php
                            echo CHtml::activeDropDownList($condition, 'target',
                                RequestProcessingRuleConditions::TARGETS, [
                                    'options' => [$condition->target => ['selected' => 'selected']],
                                    'class' => 'span12',
                                ]
                            );
                            ?>
                        </div>
                        <div class="span3">
                            <label><?= Yii::t('main-ui', 'Condition') ?></label>
                            <?php
                            echo CHtml::activeDropDownList($condition, 'condition',
                                RequestProcessingRuleConditions::CONDITIONS, [
                                    'options' => [$condition->condition => ['selected' => 'selected']],
                                    'class' => 'span12',
                                ]
                            );
                            ?>
                        </div>
                        <div class="span4">
                            <label><?= Yii::t('main-ui', 'Comparison text') ?></label>
                            <?php
                            echo CHtml::activeTelField($condition, 'val', ['class' => 'span12']);
                            ?>
                        </div>
                        <div class="span2">
                            <label>&nbsp;</label>
                            <button class="btn btn-success save"><i class="fa-solid fa-circle-check"></i></button>
                            <button class="btn btn-danger delete" data-id="<?= $condition->id ?>">
                                <span class="fa-solid fa-trash"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>

        <?php if (!$readOnly): ?>
            <div class="row-fluid condition">
                <form>
                    <div class="span12">
                        <div class="span3">
                            <input name="YII_CSRF_TOKEN" type="hidden" value="<?= Yii::app()->request->csrfToken ?>">
                            <input name="RequestProcessingRuleConditions[request_processing_rule_id]" type="hidden" value="<?= $model->id ?>">
                            <label><?= Yii::t('main-ui', 'Target') ?></label>
                            <?php
                            echo CHtml::activeDropDownList($requestProcessingRuleCondition, 'target',
                                RequestProcessingRuleConditions::TARGETS,
                                ['class' => 'span12']
                            );
                            ?>
                        </div>
                        <div class="span3">
                            <label><?= Yii::t('main-ui', 'Condition') ?></label>
                            <?php
                            echo CHtml::activeDropDownList($requestProcessingRuleCondition, 'condition',
                                RequestProcessingRuleConditions::CONDITIONS,
                                ['class' => 'span12']
                            );
                            ?>
                        </div>
                        <div class="span4">
                            <label><?= Yii::t('main-ui', 'Comparison text') ?></label>
                            <?php
                            echo CHtml::activeTelField($requestProcessingRuleCondition, 'val',
                                ['class' => 'span12 val']);
                            ?>
                        </div>
                        <div class="span2">
                            <label>&nbsp;</label>
                            <button class="btn btn-success new"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>


        <div class="row-fluid" style="margin-top: 15px;">
            <div class="span12">
                <hr>
                <h4><?php echo Yii::t('main-ui', 'Action List'); ?></h4>
            </div>
        </div>

        <?php foreach ($model->actions as $action): ?>
            <div class="row-fluid action">
                <form>
                    <div class="span12">
                        <div class="span3">
                            <input name="YII_CSRF_TOKEN" type="hidden" value="<?= Yii::app()->request->csrfToken ?>">
                            <input name="RequestProcessingRuleActions[id]" type="hidden" value="<?= $action->id ?>">
                            <input name="RequestProcessingRuleActions[request_processing_rule_id]" type="hidden" value="<?= $model->id ?>">
                            <label><?= Yii::t('main-ui', 'Target') ?></label>
                            <?php
                            echo CHtml::activeDropDownList($action, 'target',
                                RequestProcessingRuleActions::TARGETS, [
                                    'id' => 'target2',
                                    'options' => [$action->target => ['selected' => 'selected']],
                                    'class' => 'span12 target',
                                ]
                            );
                            ?>
                        </div>
                        <div class="span7">
                            <label><?= Yii::t('main-ui', 'Target value') ?></label>
                            <?php
                            $vals = [];
                            switch ($action->target) {
                                case "1";
                                    $vals = $statuses;
                                    break;
                                case "2";
                                    $vals = $priorities;
                                    break;
                                case "3";
                                    $vals = $categories;
                                    break;
                                case "4";
                                    $vals = $services;
                                    break;
                                case "5";
                                    $vals = $companies;
                                    break;
                                case "6";
                                    $vals = $departs;
                                    break;
                                case "7";
                                    $vals = $managers;
                                    break;
                                case "8";
                                    $vals = $groups;
                                    break;
                            }

                            echo CHtml::activeDropDownList($action, 'val', $vals, [
                                    'options' => [$action->target => ['selected' => 'selected']],
                                    'class' => 'span12 target_val',
                                ]
                            );
                            ?>
                        </div>
                        <div class="span2">
                            <label>&nbsp;</label>
                            <button class="btn btn-success save2"><i class="fa-solid fa-circle-check"></i></button>
                            <button class="btn btn-danger delete2" data-id="<?= $action->id ?>">
                                <span class="fa-solid fa-trash"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endforeach; ?>

        <?php if (!$readOnly): ?>
            <div class="row-fluid action">
                <form>
                    <div class="span12">
                        <div class="span3">
                            <input name="YII_CSRF_TOKEN" type="hidden" value="<?= Yii::app()->request->csrfToken ?>">
                            <input name="RequestProcessingRuleActions[request_processing_rule_id]" type="hidden" value="<?= $model->id ?>">
                            <label><?= Yii::t('main-ui', 'Target') ?></label>
                            <?php
                            echo CHtml::activeDropDownList($requestProcessingRuleAction, 'target',
                                RequestProcessingRuleActions::TARGETS,
                                ['class' => 'span12 target']
                            );
                            ?>
                        </div>

                        <div class="span7">
                            <label><?= Yii::t('main-ui', 'Target value') ?></label>
                            <?php
                            $vals = [];

                            echo CHtml::activeDropDownList($requestProcessingRuleAction, 'val', $vals, [
                                    'class' => 'span12 target_val target_val_new',
                                ]
                            );
                            ?>
                        </div>

                        <div class="span2">
                            <label>&nbsp;</label>
                            <button class="btn btn-success new2"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    $(document).ready(function () {

        let statuses = <?= json_encode($statuses) ?>;
        let priorities = <?= json_encode($priorities) ?>;
        let categories = <?= json_encode($categories) ?>;
        let services = <?= json_encode($services) ?>;
        let companies = <?= json_encode($companies) ?>;
        let departs = <?= json_encode($departs) ?>;
        let managers = <?= json_encode($managers) ?>;
        let groups = <?= json_encode($groups) ?>;

        $("form .new").on("click", function () {

            if ($(this).closest(".condition").find(".val").val() == "") {
                return false;
            }

            let form = $(this.form);
            $.ajax({
                type: "POST",
                url: "/requestprocessingrules/conditionsave",
                data: form.serialize(),
                success: function (data) {
                    window.location = window.location;
                }
            });

            return false;
        });

        $("form .save").on("click", function () {

            let form = $(this.form);
            $.ajax({
                type: "POST",
                url: "/requestprocessingrules/conditionsave",
                data: form.serialize(),
                success: function (data) {
                    window.location = window.location;
                }
            });

            return false;
        });

        $(".delete").on("click", function () {
            let csfr = "<?= Yii::app()->request->csrfToken ?>";
            let eId = $(this).data("id");
            $.ajax({
                type: "POST",
                url: "/requestprocessingrules/conditiondel",
                data: {id: eId, YII_CSRF_TOKEN: csfr},
                success: function (data) {
                    window.location = window.location;
                }
            });

            return false;
        });

        $(".action .target").on("change", function () {
            if ($(this).val()) {

                let select = $(this).closest(".action").find(".target_val");
                let values = [];
                switch ($(this).val()) {
                    case "1": // статус
                        values = statuses;
                        break;
                    case "2": // приоритет
                        values = priorities;
                        break;
                    case "3": // категории
                        values = categories;
                        break;
                    case "4": // сервисы
                        values = services;
                        break;
                    case "5": // компании
                        values = companies;
                        break;
                    case "6": // подраздиления
                        values = departs;
                        break;
                    case "7": // исполнители
                        values = managers;
                        break;
                    case "8": // группы исполнитилей
                        values = groups;
                        break;
                }

                select.empty();
                $.each(values, function (k, v) {
                    select.append( '<option value="'+k+'">'+v+'</option>');
                });

            }
        });

        $("form .new2").on("click", function () {

            let form = $(this.form);
            $.ajax({
                type: "POST",
                url: "/requestprocessingrules/actionsave",
                data: form.serialize(),
                success: function (data) {
                    window.location = window.location;
                }
            });

            return false;
        });

        $("form .save2").on("click", function () {

            let form = $(this.form);
            $.ajax({
                type: "POST",
                url: "/requestprocessingrules/actionsave",
                data: form.serialize(),
                success: function (data) {
                    window.location = window.location;
                }
            });

            return false;
        });

        $(".delete2").on("click", function () {
            let csfr = "<?= Yii::app()->request->csrfToken ?>";
            let eId = $(this).data("id");
            $.ajax({
                type: "POST",
                url: "/requestprocessingrules/actiondel",
                data: {id: eId, YII_CSRF_TOKEN: csfr},
                success: function (data) {
                    window.location = window.location;
                }
            });

            return false;
        });

        $.each(statuses, function (k, v) {
            $(".action").find(".target_val_new").append( '<option value="'+k+'">'+v+'</option>');
        });

    });
</script>
