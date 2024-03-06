<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt205COV extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['A', '20', 'reference'],
            ['A', '21', 'reference'],
            ['A', '32A', 'date'],
            ['A', '32A', 'currency'],
            ['A', '32A', 'sum'],
            ['A', '52a', 'A', 'identityPart'],
            ['A', '52a', 'D', 'identityPart'],
            ['A', '58a', 'A', 'identityPart'],
            ['B', '50a', 'A', 'account'],
            ['B', '50a', 'K', 'account'],
            ['B', '59a', 'NoLetter', 'account'],
            ['B', '59a', 'A', 'account'],
            ['B', '33B', 'currency'],
            ['B', '33B', 'sum']
        ];

        $this->prepareVerifyTags($verifyFields);
    }

}