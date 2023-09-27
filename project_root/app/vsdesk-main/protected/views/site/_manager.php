<?php

echo '<h4>Поиск по базе знаний</h4>';
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'search_form',
        'type' => 'inline',
        'action' => '/site/freesearch',
    )
);
echo CHtml::textField('search_field', NULL, array('id' => 'idTextField',
    'class' => 'span12',
    'placeholder' => Yii::t('main-ui', 'Enter the text to search for Knowledge Base and press ENTER')));

$this->endWidget();
unset($form);
echo '<div class="row-fluid">';
echo '<div class="span6">';
echo '<h4>' . Yii::t('main-ui', 'Latest news and alerts') . '</h4>';
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'id' => 'news-grid',
    'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/news/module/view/id') . '/"+$.fn.yiiGridView.getSelection(id);}',
    'dataProvider' => $news->searchmain(),
    'htmlOptions' => array('style' => 'cursor: pointer'),
    'summaryText' => '',
    'columns' => array(
        array(
            'name' => 'date',
            'headerHtmlOptions' => array('width' => 100),
            'header' => Yii::t('main-ui', 'Created'),
        ),
        array(
            'name' => 'author',
            'headerHtmlOptions' => array('width' => 50),
            'header' => Yii::t('main-ui', 'Author'),
        ),
        array(
            'name' => 'name',
            //'headerHtmlOptions'=> array('width'=>120),
            'header' => Yii::t('main-ui', 'Name'),
        ),

        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            //'headerHtmlOptions'=> array('width'=>50),
            'template' => '{view}',
            'buttons' => array
            (
                'view' => array
                (
                    'label' => Yii::t('main-ui', 'View'),
                    'url' => 'Yii::app()->createUrl("news/module/view", array("id"=>$data->id))',
                ),
            ),
        )
    )));
echo '<span class="fa-solid fa-angles-right">   </span><a href="/news/">' . Yii::t('main-ui', 'View all') . '</a>';
echo '</div>';
echo '<div class="span6">';
echo '<h4>' . Yii::t('main-ui', 'Latest knowledgebase records') . '</h4>';
$config = array('keyField' => 'id', 'pagination' => false);
$rawData = $faq;
$dataProvider = new CArrayDataProvider($rawData, $config);
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'id' => 'faq-grid',
    'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/knowledge/module/view/id') . '/"+$.fn.yiiGridView.getSelection(id);}',
    'dataProvider' => $dataProvider,
    'htmlOptions' => array('style' => 'cursor: pointer'),
    'summaryText' => '',
    'columns' => array(
        array(
            'name' => 'created',
            'headerHtmlOptions' => array('width' => 100),
            'header' => Yii::t('main-ui', 'Created'),
        ),
        array(
            'name' => 'author',
            'headerHtmlOptions' => array('width' => 50),
            'header' => Yii::t('main-ui', 'Author'),
        ),
        array(
            'name' => 'name',
            //'headerHtmlOptions'=> array('width'=>120),
            'header' => Yii::t('main-ui', 'Name'),
        ),

        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            //'headerHtmlOptions'=> array('width'=>50),
            'template' => '{view}',
            'buttons' => array
            (
                'view' => array
                (
                    'label' => Yii::t('main-ui', 'View'),
                    'url' => 'Yii::app()->createUrl("knowledge/module/view", array("id"=>$data->id))',
                ),
            ),
        )
    )));
echo '<span class="fa-solid fa-angles-right">   </span><a href="/knowledge/">' . Yii::t('main-ui', 'View all') . '</a>';
echo '</div>';
echo '</div>';
require_once '_grid.php';
echo '<h4>' . Yii::t('main-ui', 'Last ') . Yii::app()->params['grid_items'] . Yii::t('main-ui', ' tickets') . '</h4>';
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'id' => 'request-grid',
    'selectionChanged' => 'function(id){location.href = "' . $this->createUrl('/request') . '/"+$.fn.yiiGridView.getSelection(id);}',
    'dataProvider' => $model->searchmain(),
    'htmlOptions' => array('style' => 'cursor: pointer'),
    'summaryText' => '',
    'columns' => array_merge($fixed_columns, $dialog->columns()),
    'template' => $dialog->link($text = '<i class="icon-cog"> ' . Yii::t('main-ui', 'Columns settings') . '</i>') . "{summary}\n{items}\n{pager}",
));
echo '<span class="fa-solid fa-angles-right">   </span><a href="/request/">' . Yii::t('main-ui', 'View all') . '</a>';
echo '
';
echo '
    </div>
    </div>
    ';
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#faq_search_input').keypress(function (e) {
            if (e.keyCode == 13)
                if ($("#search_field").is(":focus")) {
                    document.getElementById('search_form').submit();
                    return false;
                }
        });
</script>
<?php
if (Yii::app()->params->update_grid == 1) {
    $timeout = (Yii::app()->params->update_grid_timeout) * 1000;
    Yii::app()->clientScript->registerScript('autoupdate-activations-application-grid',
        "setInterval(function(){;$.fn.yiiGridView.update('request-grid');
               return false;}," . $timeout . ");");
} ?>
