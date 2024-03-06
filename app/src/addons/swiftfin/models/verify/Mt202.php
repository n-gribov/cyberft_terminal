<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt202 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['32A', 'date'],
            ['32A', 'currency'],
            ['32A', 'sum']
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['51A', 'identifyCode'];
            $verifyFields[] = ['52a', 'A', 'identityPart'];
            $verifyFields[] = ['52a', 'D', 'identityPart'];
            $verifyFields[] = ['53a', 'A', 'identityPart'];
            $verifyFields[] = ['53a', 'B', 'identityPart'];
            $verifyFields[] = ['53a', 'D', 'identityPart'];
            $verifyFields[] = ['54a', 'A', 'identityPart'];
            $verifyFields[] = ['54a', 'B', 'identityPart'];
            $verifyFields[] = ['54a', 'D', 'identityPart'];
            $verifyFields[] = ['56a', 'A', 'identityPart'];
            $verifyFields[] = ['56a', 'D', 'identityPart'];
            $verifyFields[] = ['57a', 'A', 'identityPart'];
            $verifyFields[] = ['57a', 'B', 'identityPart'];
            $verifyFields[] = ['57a', 'D', 'identityPart'];
            $verifyFields[] = ['58a', 'A', 'identityPart'];
            $verifyFields[] = ['58a', 'D', 'identityPart'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}