<?php

namespace addons\raiffeisen\models\soap\request;

use yii\base\BaseObject;

class SendDocumentReceiptsRequest extends BaseObject
{
    public $receipt = [];
    public $sessionId;
}
