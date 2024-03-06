<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt290 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['32a', 'D' ,'date'],
            ['32a', 'D' ,'currency'],
            ['32a', 'D' ,'sum'],
            ['32a', 'C' ,'date'],
            ['32a', 'C' ,'currency'],
            ['32a', 'C' ,'sum'],
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['52a', 'A', 'identityPart'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}