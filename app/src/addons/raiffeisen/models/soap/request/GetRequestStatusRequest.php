<?php

namespace addons\raiffeisen\models\soap\request;

use yii\base\BaseObject;

class GetRequestStatusRequest extends BaseObject
{
    public $requests = [];
    public $sessionId;
}
