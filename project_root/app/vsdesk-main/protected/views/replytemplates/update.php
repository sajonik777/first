<?php

/* @var $this ReplyTemplatesController */
/* @var $model ReplyTemplates */
$this->breadcrumbs = array(
    Yii::t('main-ui', 'Reply templates') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
);
$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List templates'))),
);
?>

    <div class="page-header">
        <h3><?php echo Yii::t('main-ui', 'Update reply template'); ?></h3>
    </div>
<div class="box">
    <div class="box-body">
        <?php $this->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills',
            'items' => $this->menu,
        )); ?>
        <h5><?php echo Yii::t('main-ui', 'You can create your template using HTML tags and following tags:'); ?></h5>
        <p><?php echo Yii::t('main-ui', 'Tags are case sensitive'); ?></p>
        <div class="row-fluid">
            <div class="span6">
                <ul>
                    <li><b>{id}</b> <?php echo Yii::t('main-ui', 'Ticket #'); ?></li>
                    <li><b>{name}</b> <?php echo Yii::t('main-ui', 'Ticket name'); ?></li>
                    <li><b>{status}</b> <?php echo Yii::t('main-ui', 'Ticket status'); ?></li>
                    <li><b>{fullname}</b> <?php echo Yii::t('main-ui', 'Customer fullname'); ?></li>
                    <li><b>{phone}</b> <?php echo Yii::t('main-ui', 'Customer phone'); ?></li>
                    <li><b>{department}</b> <?php echo Yii::t('main-ui', 'Department'); ?></li>
                    <li><b>{position}</b> <?php echo Yii::t('main-ui', 'Position'); ?></li>
                    <li><b>{watchers}</b> <?php echo Yii::t('main-ui', 'Observers'); ?></li>
                    <li><b>{manager_name}</b> <?php echo Yii::t('main-ui', 'Manager name'); ?> </li>
                    <li><b>{groupname}</b> <?php echo Yii::t('main-ui', 'Group name'); ?> </li>
                    <li><b>{manager_phone}</b> <?php echo Yii::t('main-ui', 'Manager phone'); ?></li>
                    <li><b>{manager_intphone}</b> <?php echo Yii::t('main-ui', 'Internal phone'); ?></li>
                    <li><b>{manager_mobile}</b> <?php echo Yii::t('main-ui', 'Mobile'); ?></li>
                    <li><b>{manager_email}</b>  <?php echo Yii::t('main-ui', 'Manager e-mail'); ?></li>
                    <li><b>{category}</b> <?php echo Yii::t('main-ui', 'Ticket category'); ?></li>
                    <li><b>{priority}</b> <?php echo Yii::t('main-ui', 'Ticket priority'); ?></li>
                    <li><b>{created}</b> <?php echo Yii::t('main-ui', 'Ticket created'); ?></li>
                </ul>
            </div>
            <div class="span6">
                <ul>
                    <li><b>{unit}</b> <?php echo Yii::t('main-ui', 'Configuration Unit'); ?></li>
                    <li><b>{StartTime}</b> <?php echo Yii::t('main-ui', 'Start Time'); ?></li>
                    <li><b>{fStartTime}</b> <?php echo Yii::t('main-ui', 'Fact Start time'); ?></li>
                    <li><b>{EndTime}</b> <?php echo Yii::t('main-ui', 'End Time'); ?></li>
                    <li><b>{fEndTime}</b> <?php echo Yii::t('main-ui', 'Fact End Time'); ?></li>
                    <li><b>{service_name}</b> <?php echo Yii::t('main-ui', 'Service name'); ?></li>
                    <li><b>{room}</b> <?php echo Yii::t('main-ui', 'Room'); ?></li>
                    <li><b>{company}</b> <?php echo Yii::t('main-ui', 'Company'); ?></li>
                    <li><b>{address}</b> <?php echo Yii::t('main-ui', 'Address'); ?></li>
                    <li><b>{content}</b> <?php echo Yii::t('main-ui', 'Content'); ?></li>
                    <li><b>{url}</b> <?php echo Yii::t('main-ui', 'URL'); ?></li>
                </ul>
            </div>
        </div>
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
