<?php

namespace addons\fileact\models;

use common\models\CryptoproCertSearch;

class FileActCryptoproCertSearch extends CryptoproCertSearch
{
    public static function tableName()
    {
        return FileactCryptoproCert::tableName();
    }
}
