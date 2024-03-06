<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class SendPcHashRequest extends BaseObject
{
    public $sessionId;
    public $pcHash;
}
