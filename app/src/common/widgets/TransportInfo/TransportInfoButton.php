<?php

namespace common\widgets\TransportInfo;

use yii\base\Widget;

class TransportInfoButton extends Widget
{
    public function run()
    {
        // Вывести кнопку
        return $this->render('transport-info-button');
    }
}
