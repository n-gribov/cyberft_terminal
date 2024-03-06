<?php

namespace common\widgets\ToTopButton;

use yii\base\Widget;

class ToTopButtonWidget extends Widget
{
   public function run()
   {
       // Вывести кнопку
       return $this->render('view');
   }
}
