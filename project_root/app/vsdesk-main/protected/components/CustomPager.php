<?php

class CustomPager extends CLinkPager {

    /**
     * @var string the text shown before page buttons. Defaults to ''.
     */
    public $header = '';

    /**
     * @var string the URL of the CSS file used by this pager.
     * Defaults to false, meaning that no CSS will be included.
     */
    public $cssFile = false;

    /**
     * @var boolean whether to display the first and last items.
     */
    public $displayFirstAndLast = false;

    /**
     * Initializes the pager by setting some default property values.
     */
    public function init() {
        if ($this->nextPageLabel === null)
            $this->nextPageLabel = '<i class="fa-solid fa-chevron-right fa-xl"></i>';
        if ($this->prevPageLabel === null)
            $this->prevPageLabel = '<i class="fa-solid fa-chevron-left fa-xl"></i>';
        if ($this->firstPageLabel === null)
            $this->firstPageLabel = '<i class="fa-solid fa-arrow-left fa-xl"></i>';
        if ($this->lastPageLabel === null)
            $this->lastPageLabel = '<i class="fa-solid fa-arrow-right fa-xl"></i>';
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = ''; // would default to yiiPager
        parent::init();
    }

    /**
     * Creates the page buttons.
     * @return array a list of page buttons (in HTML code).
     */
    protected function createPageButtons() {
        //$totalpages = $this->getPageCount();

        if (($pageCount = $this->getPageCount()) <= 1)
            return array();
        list ($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons = array();
        /**
         * first page
         */
        if ($this->displayFirstAndLast)
            $buttons[] = $this->createPageButton($this->firstPageLabel, 0, 'first', $currentPage <= 0, false);
        /**
         * previous page
         */
        if (($page = $currentPage - 1) < 0)
            $page = 0;
        $buttons[] = $this->createPageButton($this->prevPageLabel, $page, 'previous', $currentPage <= 0, false);
        /**
         * internal page
         */
        //$buttons[] = '<li class=""><a herf="">'.($currentPage+1).' '.Yii::t('main-ui', 'of').' ' .  $totalpages .' </a><li>';
        for($i=$beginPage;$i<=$endPage;++$i)
            $buttons[]=$this->createPageButton($i+1,$i,$this->internalPageCssClass,false,$i==$currentPage);
        /**
         * next page
         */
        if (($page = $currentPage + 1) >= $pageCount - 1)
            $page = $pageCount - 1;
        $buttons[] = $this->createPageButton($this->nextPageLabel, $page, 'next', $currentPage >= ($pageCount - 1), false);

        /**
         * last page
         */

        if ($this->displayFirstAndLast)
            $buttons[] = $this->createPageButton($this->lastPageLabel, $pageCount - 1, 'last', $currentPage >= ($pageCount - 1), false);
        return $buttons;
    }

    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button. This could be 'page', 'first', 'last', 'next' or 'previous'.
     * @param boolean $hidden whether this page button is visible
     * @param boolean $selected whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label, $page, $class, $hidden, $selected) {
        if ($hidden || $selected)
            $class .= ' ' . ($hidden ? 'disabled' : 'active');
        return CHtml::tag('li', array('class' => $class), CHtml::link($label, $this->createPageUrl($page)));
    }



}
