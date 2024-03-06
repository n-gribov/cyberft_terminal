<?php

namespace addons\fileact\models;

use common\models\BaseUserExt;

class FileActUserExt extends BaseUserExt
{
    public static function tableName()
    {
        return 'fileact_UserExt';
    }
}
