<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class SendRequestsSRPRequest extends BaseObject
{
    public $requests = [];
    public $sessionId;
}
