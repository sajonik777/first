<h5><?php echo Yii::t('main-ui', 'You can create your template using HTML tags and following tags:'); ?></h5>
<p><?php echo Yii::t('main-ui', 'Tags are case sensitive'); ?></p>
<div class="row-fluid">
    <div class="span6">
        <ul>
            <li><b>{title}</b> <?php echo Yii::t('main-ui','Title');?></li>
            <li><b>{created_at}</b> <?php echo Yii::t('main-ui','Created at');?></li>
            <li><b>{author}</b> <?php echo Yii::t('main-ui','Author');?></li>
            <li><b>{category}</b> <?php echo Yii::t('main-ui','Category');?></li>
            <li><b>{content}</b> <?php echo Yii::t('main-ui','Content');?></li>
        </ul>
    </div>