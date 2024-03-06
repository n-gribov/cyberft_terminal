<?php

namespace addons\finzip\models;

use common\models\BaseUserExt;

class FinZipUserExt extends BaseUserExt
{
    public static function tableName()
    {
        return 'finzip_UserExt';
    }
}
