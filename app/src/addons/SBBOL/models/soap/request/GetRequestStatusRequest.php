<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class GetRequestStatusRequest extends BaseObject
{
    public $requests = [];
    public $orgId;
}
