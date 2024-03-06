<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt900 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['32A', 'date'],
            ['32A', 'sum'],
            ['32A', 'currency'],
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['52a', 'A', 'identityPart'];
            $verifyFields[] = ['52a', 'D', 'identityPart'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}