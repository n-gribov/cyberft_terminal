<?php
namespace addons\fileact\models;

use common\models\CryptoproCert;

class FileactCryptoproCert extends CryptoproCert
{
    protected static $_table = 'fileact_CryptoproCert';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [['senderTerminalAddress', 'string', 'max' => 12]]);
    }

}
