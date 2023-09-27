<?php

$this->breadcrumbs = array(
    Yii::t('main-ui', 'E-mail templates') => array('index'),
    $model->name => array('view', 'id' => $model->id),
    Yii::t('main-ui', 'Edit'),
);
$this->menu = array(
    array('icon' => 'fa-solid fa-list-ul fa-xl', 'url' => array('index'), 'itemOptions'=>array('title'=>Yii::t('main-ui', 'List templates'))),
);
?>

    <div class="page-header">
        <h3><?php echo $model->name; ?></h3>
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
          <?php if ($model->static == '2' OR $model->static == '4' OR !$model->static): ?>
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
                    <li><b>{comment_message}</b> <?php echo Yii::t('main-ui', 'Comment'); ?></li>
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
                    <li><b>{voting}</b> <?php echo Yii::t('main-ui', 'Voting'); ?></li>
                    <li><b>{reopen}</b> <?php echo Yii::t('main-ui', 'Reopen ticket'); ?></li>
                    <li><b>{agreed}</b> <?php echo Yii::t('main-ui', 'Согласовано'); ?></li>
                    <li><b>{denied}</b> <?php echo Yii::t('main-ui', 'Отказано'); ?></li>
                    <li><b>{add_info}</b> <?php echo Yii::t('main-ui', 'Требуется дополнительная информация'); ?></li>
                </ul>
                <br>
            </div>
          <?php elseif($model->static == '3'): ?>
              <div class="span6">
                  <ul>
                      <li><b>{author}</b> <?php echo Yii::t('main-ui', 'Author'); ?></li>
                      <li><b>{date}</b> <?php echo Yii::t('main-ui', 'Date'); ?></li>
                      <li><b>{comment}</b> <?php echo Yii::t('main-ui', 'Comment'); ?></li>
                      <li><b>{url}</b> <?php echo Yii::t('main-ui', 'URL'); ?></li>
                      <li><b>{comments_list}</b> <?php echo Yii::t('main-ui', 'Comments list'); ?></li>
                  </ul>
              </div>
          <?php elseif($model->static == '1'): ?>
            <div class="span6">
                <ul>
                    <li><b>{login}</b> <?php echo Yii::t('main-ui', 'User login'); ?></li>
                    <li><b>{password}</b> <?php echo Yii::t('main-ui', 'Password'); ?></li>
                </ul>
            </div>
          <?php endif; ?>
        </div>
        <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
