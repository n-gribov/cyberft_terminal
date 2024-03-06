<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class ChangePasswordRequest extends BaseObject
{
    public $sessionId;
    public $newPasswordData = [];
}
