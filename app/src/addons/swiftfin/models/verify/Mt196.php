<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt196 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['11a', 'R' ,'date'],
            ['11a', 'S' ,'date']
        ];

        $this->prepareVerifyTags($verifyFields);
    }

}