<?php
namespace common\base;

use Yii;
use yii\web\Controller as YiiController;

class Controller extends YiiController
{
    public function init()
    {
        parent::init();
        Yii::$app->setHomeUrl('/' . (Yii::$app->language ? Yii::$app->language . '/' : '') );

        // Получить модель пользователя из активной сессии
        $user = Yii::$app->user->identity;
        if ($user) {
            $result = $user->checkUserResponsibility();
            if ($result) {
                // Перенаправить на страницу перенаправления
                $this->redirect($result);
            }
        }
    }
}
