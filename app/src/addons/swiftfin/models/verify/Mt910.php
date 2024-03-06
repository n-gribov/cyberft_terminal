<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt910 extends SwiftFinUserVerify
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
            $verifyFields[] = ['50a', 'A', 'account'];
            $verifyFields[] = ['50a', 'F', 'identityPart'];
            $verifyFields[] = ['50a', 'K', 'account'];
            $verifyFields[] = ['52a', 'A', 'identityPart'];
            $verifyFields[] = ['52a', 'D', 'identityPart'];
            $verifyFields[] = ['56a', 'A', 'identityPart'];
            $verifyFields[] = ['56a', 'D', 'identityPart'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}