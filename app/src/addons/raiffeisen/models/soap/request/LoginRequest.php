<?php

namespace addons\raiffeisen\models\soap\request;

use yii\base\BaseObject;

class LoginRequest extends BaseObject
{
    public $userLogin;
    public $clientAuthData = [];
}
