<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class SendRequestZipRequest extends BaseObject
{
    public $zipRequest;
    public $orgId;
    public $requestId;
    public $customsCardId;
    public $customsOperatorId;
    public $sessionId;
    public $clientOrgId;
}
