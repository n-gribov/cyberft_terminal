<?php

namespace addons\SBBOL\models;

use common\models\BaseUserExt;

class SBBOLUserExt extends BaseUserExt
{
    public static function tableName()
    {
        return 'sbbol_UserExt';
    }
}
