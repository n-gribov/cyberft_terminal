<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class PreLoginSignRequest extends BaseObject
{
    public $serial;
    public $issue;
    public $senderKey;
}
