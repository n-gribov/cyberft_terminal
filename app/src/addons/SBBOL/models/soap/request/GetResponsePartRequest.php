<?php

namespace addons\SBBOL\models\soap\request;

use yii\base\BaseObject;

class GetResponsePartRequest extends BaseObject
{
    public $request;
    public $part;
    public $orgId;
}
