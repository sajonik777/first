<?php

// import required classes to my widget
Yii::import('bootstrap.widgets.TbExtendedGridView');
Yii::import('bootstrap.widgets.TbExtendedFilter');

class FilterGrid extends TbExtendedGridView
{
    /**
     * We need this attribute in order to fire the saved filter.
     * In fact, you could remove its requirement from TbExtendedFilter but
     * we thought is better to provide 'less' magic.
     */
    public $redirectRoute;

    public function renderSummary()
    {
        $this->renderExtendedFilter();
        if (($count = $this->dataProvider->getItemCount()) <= 0) {
            return;
        }
        echo CHtml::openTag($this->summaryTagName, array('class' => $this->summaryCssClass));
        if ($this->enablePagination) {
            $pagination = $this->dataProvider->getPagination();
            $total = $this->dataProvider->getTotalItemCount();
            $start = $pagination->currentPage * $pagination->pageSize + 1;
            $end = $start + $count - 1;
            if ($end > $total) {
                $end = $total;
                $start = $end - $count + 1;
            }
            if (($summaryText = $this->summaryText) === null) {
                $summaryText = $this->summaryText;
            }
            echo strtr($summaryText, array(
                '{start}' => $start,
                '{end}' => $end,
                '{count}' => $total,
                '{page}' => $pagination->currentPage + 1,
                '{pages}' => $pagination->pageCount,
            ));
        } else {
            if (($summaryText = $this->summaryText) === null) {
                $summaryText = Yii::t('zii', 'Total 1 result.|Total {count} results.', $count);
            }
            echo strtr($summaryText, array(
                '{count}' => $count,
                '{start}' => 1,
                '{end}' => $count,
                '{page}' => 1,
                '{pages}' => 1,
            ));
        }
        echo CHtml::closeTag($this->summaryTagName);
    }

    protected function renderExtendedFilter()
    {
        // at the moment it only works with instances of CActiveRecord
        if (!$this->filter instanceof CActiveRecord) {
            return false;
        }
        /** @var TbExtendedFilter $extendedFilter */
        $extendedFilter = Yii::createComponent(array(
            'class' => 'TbExtendedFilter',
            'model' => $this->filter,
            'grid' => $this,
            'redirectRoute' => $this->redirectRoute //ie: array('/report/index', 'ajax'=>$this->id)
        ));

        $extendedFilter->init();
        $extendedFilter->run();
    }
}