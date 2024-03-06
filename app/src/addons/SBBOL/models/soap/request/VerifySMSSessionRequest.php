<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class VerifySMSSessionRequest extends BaseObject
{
    public $sessionId;
    public $smsCode;
}
