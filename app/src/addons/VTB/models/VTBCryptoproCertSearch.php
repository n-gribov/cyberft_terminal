<?php

namespace addons\VTB\models;

use common\models\CryptoproCertSearch;

class VTBCryptoproCertSearch extends CryptoproCertSearch
{
    public static function tableName()
    {
        return VTBCryptoproCert::tableName();
    }

}
