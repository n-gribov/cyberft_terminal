<?php

namespace addons\ISO20022\models;

use common\models\CryptoproCertSearch;

class ISO20022CryptoproCertSearch extends CryptoproCertSearch
{
    public static function tableName()
    {
        return ISO20022CryptoproCert::tableName();
    }

}
