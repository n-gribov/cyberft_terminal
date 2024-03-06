<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt720 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['31D', 'date'],
            ['32B', 'currency'],
            ['32B', 'sum'],
            ['39B', 'sum'],
            ['44C', 'date'],
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['52a', 'A', 'identityPart'];
            $verifyFields[] = ['52a', 'D', 'identityPart'];
            $verifyFields[] = ['42a', 'A', 'identityPart'];
            $verifyFields[] = ['42a', 'D', 'identityPart'];
            $verifyFields[] = ['57a', 'A', 'identityPart'];
            $verifyFields[] = ['57a', 'B', 'identityPart'];
            $verifyFields[] = ['57a', 'D', 'identityPart'];
            $verifyFields[] = ['59', 'account'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}
