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

        if ($user = Yii::$app->user->identity) {
            if ($result = $user->checkUserResponsibility()) {
                $this->redirect($result);
            }
        }
	}
}