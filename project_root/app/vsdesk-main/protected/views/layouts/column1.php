<?php

/* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
    <div class="row-fluid">
        <div class="span1">
            <div class="fixed_sidebar">
            <div class="no_print">
                <div class="row-fluid" style="text-align: center">
                    <a href="/"><img width="40px" src="/images/dash.png" alt="request"/>
                        <h6><?php echo Yii::t('main-ui', 'Dashboard'); ?></h6></a>
                </div>
                <?php if (Yii::app()->user->checkAccess('listRequest')): ?>
                    <div class="row-fluid" style="text-align: center">
                        <a href="/request/"><img width="40px" src="/images/ticket.png" alt="request"/>
                            <h6><?php echo Yii::t('main-ui', 'Tickets'); ?></h6></a>
                    </div>
                <?php endif; ?>

                <?php if (Yii::app()->user->checkAccess('listProblem')): ?>
                    <div class="row-fluid" style="text-align: center">
                        <a href="/problems/"><img width="40px" src="/images/problems.png" alt="request"/>
                            <h6><?php echo Yii::t('main-ui', 'Problems'); ?></h6></a>
                    </div>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('listService')): ?>
                    <div class="row-fluid" style="text-align: center">
                        <a href="/service/"><img width="40px" src="/images/services.png" alt="request"/>
                            <h6><?php echo Yii::t('main-ui', 'Services'); ?></h6></a>
                    </div>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('listSla')): ?>
                    <div class="row-fluid" style="text-align: center">
                        <a href="/sla/"><img width="40px" src="/images/sla.png" alt="request"/>
                            <h6><?php echo Yii::t('main-ui', 'SLA'); ?></h6></a>
                    </div>
                <?php endif; ?>

                <?php if (Yii::app()->user->checkAccess('listAsset')): ?>
                    <div class="row-fluid" style="text-align: center">
                        <a href="/asset/"><img width="40px" src="/images/assets.png" alt="request"/>
                            <h6><?php echo Yii::t('main-ui', 'Assets'); ?></h6></a>
                    </div>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('listUnit')): ?>
                    <div class="row-fluid" style="text-align: center">
                        <a href="/cunits/"><img width="40px" src="/images/ke.png" alt="request"/>
                            <h6><?php echo Yii::t('main-ui', 'Units'); ?></h6></a>
                    </div>
                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess('listKB')): ?>
                    <div class="row-fluid" style="text-align: center">
                        <a href="/knowledge/module/"><img width="40px" src="/images/know.png" alt="request"/>
                            <h6><?php echo Yii::t('main-ui', 'Knowledgebase'); ?></h6></a>
                    </div>
                <?php endif; ?>
            </div>
            </div>
        </div>
        <div class="span11">
            <div class="no_print">
                <?php
                if (isset($this->breadcrumbs)):?>
                    <?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                        'links' => $this->breadcrumbs,
                        'separator' => '>',
                    )); ?>
                <?php endif ?>
            </div>
            <div id="content">
                <?php echo $content; ?>
            </div>
            <!-- content -->
        </div>

    </div>
<?php $this->endContent(); ?>