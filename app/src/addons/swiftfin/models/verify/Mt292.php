<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt292 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['11S', 'date']
        ];

        $this->prepareVerifyTags($verifyFields);
    }

}