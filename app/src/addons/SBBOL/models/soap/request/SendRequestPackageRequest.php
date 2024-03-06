<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class SendRequestPackageRequest extends BaseObject
{
    public $packageRequest = [];
    public $orgId;
    public $packageGuid;
    public $sessionId;
}
