<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class GetRequestStatusPackageRequest extends BaseObject
{
    public $packageGuid = [];
    public $sessionId;
    public $orgId;
}
