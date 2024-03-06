<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class LoginRequest extends BaseObject
{
    public $sessionId;
    public $clientAuthData = [];
    public $fraudParams;
}
