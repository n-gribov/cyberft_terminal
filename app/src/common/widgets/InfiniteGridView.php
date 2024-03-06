<?php

namespace common\widgets;

use yii\web\JsExpression;

class InfiniteGridView extends GridView
{
    /**
     * @var string|JsExpression $eventOnRendered Triggered after new items have rendered.
     */
    public $onPageRendered;

    public function init()
    {
        parent::init();
        $this->pager = [
            'class' => InfiniteScrollPager::class,
            'container' => '.grid-view tbody',
            'item' => 'tr',
            'noneLeftText' => '',
            'triggerOffset' => 99999,
            'triggerTemplate' => '<tr class="ias-trigger"><td colspan="100%" style="text-align: center"><a style="cursor: pointer">{text}</a></td></tr>',
            'eventOnRendered' => $this->onPageRendered,
        ];
    }

    public function renderSummary()
    {
        return '';
    }
}
