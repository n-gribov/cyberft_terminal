<?php

namespace addons\raiffeisen\models;

use common\models\BaseUserExt;

class RaiffeisenUserExt extends BaseUserExt
{
    public static function tableName()
    {
        return 'raiffeisen_userExt';
    }
}
