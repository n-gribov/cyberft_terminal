<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class PreLoginRequest extends BaseObject
{
    public $userLogin;
    public $changePassword;
    public $senderKey;
}
