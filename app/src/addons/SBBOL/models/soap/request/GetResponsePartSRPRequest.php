<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class GetResponsePartSRPRequest extends BaseObject
{
    public $request;
    public $part;
    public $sessionId;
    public $orgId;
}
