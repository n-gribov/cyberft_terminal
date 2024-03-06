<?php

namespace addons\ISO20022\models;

use common\models\CryptoproCert;

class ISO20022CryptoproCert extends CryptoproCert
{
    protected static $_table = 'iso20022_CryptoproCert';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [['senderTerminalAddress', 'string', 'max' => 12]]);
    }

}
