<?php

class FilterGridResizable extends FilterGrid
{
    public function init()
    {
        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.resizableColumns.min.js',
            CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/store.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/jquery.resizableColumns.css');

        Yii::app()->clientScript->registerScript('resizableColumns_js', "
            $('table').resizableColumns({
                store: window.store
            });
        ");

        Yii::app()->clientScript->registerScript('resizable-update', "
            function resizableUpdate(id, data) {
                $('table').resizableColumns({
                    store: window.store
                });
            }
        ");
        $this->componentsAfterAjaxUpdate[] = "$('table').resizableColumns({ store: window.store });";
        parent::init();
    }

    public function renderItems()
    {
        if ($this->dataProvider->getItemCount() > 0 || $this->showTableOnEmpty) {
            $user = Yii::app()->user->id;
            echo "<table class=\"{$this->itemsCssClass}\" data-resizable-columns-id=\"{$user}_{$this->id}\">\n";
            $this->renderTableHeader();
            ob_start();
            $this->renderTableBody();
            $body = ob_get_clean();
            $this->renderTableFooter();
            echo $body; // TFOOT must appear before TBODY according to the standard.
            echo "</table>";
        } else {
            $this->renderEmptyText();
        }
    }


}