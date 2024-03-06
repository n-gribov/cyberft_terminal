<?php

namespace addons\VTB\models;

use common\models\BaseUserExt;

class VTBUserExt extends BaseUserExt
{
    public static function tableName()
    {
        return 'vtb_UserExt';
    }
}
