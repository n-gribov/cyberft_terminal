<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt760 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['30', 'date'],
        ];

        $this->prepareVerifyTags($verifyFields);
    }

}