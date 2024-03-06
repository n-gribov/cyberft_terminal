<?php

namespace addons\ISO20022\models;

use common\models\BaseUserExt;

class ISO20022UserExt extends BaseUserExt
{
    public static function tableName()
    {
        return 'iso20022_UserExt';
    }
}
