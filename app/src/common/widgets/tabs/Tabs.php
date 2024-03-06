<?php

namespace common\widgets\tabs;

use yii\base\Widget;

class Tabs extends Widget
{
    public $items = [];

    public function run()
    {
        return $this->render('tabs', ['items' => $this->items]);
    }
}
