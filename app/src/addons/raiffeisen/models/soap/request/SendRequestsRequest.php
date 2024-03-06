<?php

namespace addons\raiffeisen\models\soap\request;

use yii\base\BaseObject;

class SendRequestsRequest extends BaseObject
{
    public $requests = [];
    public $sessionId;
}
