<?php

namespace common\widgets;

use common\data\InfinitePagination;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

class InfiniteLinkPager extends LinkPager
{
    protected function renderPageButtons()
    {
        if (!($this->pagination instanceof InfinitePagination)) {
            throw new InvalidConfigException('The "pagination" property must be an instance of a class common\data\InfinitePagination or its subclasses.');
        }

        $currentPage = $this->pagination->getPage();
        if ($this->hideOnSinglePage && $currentPage === 0 && !$this->pagination->hasMorePages) {
            return '';
        }

        $buttons = [];

        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $currentPage + 1, $this->nextPageCssClass, !$this->pagination->hasMorePages, false);
        }

        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'ul');
        return Html::tag($tag, implode("\n", $buttons), $options);
    }
}
