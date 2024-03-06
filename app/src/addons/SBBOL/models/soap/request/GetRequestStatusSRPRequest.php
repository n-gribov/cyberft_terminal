<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class GetRequestStatusSRPRequest extends BaseObject
{
    public $requests = [];
    public $sessionId;
    public $orgId;
}
