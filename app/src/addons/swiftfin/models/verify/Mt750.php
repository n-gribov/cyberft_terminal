<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt750 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['32B', 'currency'],
            ['32B', 'sum'],
            ['33B', 'currency'],
            ['33B', 'sum'],
            ['34B', 'currency'],
            ['34B', 'sum'],
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['57a', 'A', 'identityPart'];
            $verifyFields[] = ['57a', 'B', 'identityPart'];
            $verifyFields[] = ['57a', 'D', 'identityPart'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}