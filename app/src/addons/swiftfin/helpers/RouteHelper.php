<?php

namespace addons\swiftfin\helpers;

use addons\swiftfin\models\containers\swift\SwtContainer;
use common\models\Participant;
use Yii;

class RouteHelper
{
    /**
     * @param SwtContainer $container Swift container
     * @return boolean
     */
    public static function isRouteEnabled(SwtContainer $container)
    {
        if (!empty(Yii::$app->getModule('swiftfin')->settings->swiftRouting)) {
            $participant = Participant::findOne(['address' => $container->recipient]);

            return !empty($participant);
        }

        return false;
    }

    /**
     * @param string $path Swift file path
     * @return string
     */
    public function getRoutePath($path)
    {
        $pathInfo = pathinfo($path);

        return Yii::$app->getModule('swiftfin')->settings->swiftRoutePath
			   . '/' . $pathInfo['filename']
			   . '.' . date('Ymd_His')
			   . '.' . substr((string)microtime(), 2, 3)
			   . '.' . $pathInfo['extension'];
    }
}