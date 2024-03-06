<?php

namespace common\widgets\TransportInfo;

use yii\base\Widget;

class TransportInfoButton extends Widget
{
    public function run()
    {
        return $this->render('transport-info-button');
    }
}
