<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class LoginSignRequest extends BaseObject
{
    public $sessionId;
    public $clientAuthData;
    public $fraudParams;
}
