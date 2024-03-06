<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt101 extends SwiftFinUserVerify
{
    public $verifyFields = [];

    protected function prepare()
    {
        $verifyFields = [
            ['30', 'date']
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['51A', 'identityCode'];
            $verifyFields[] = ['52a', 'A', 'identityPart'];
            $verifyFields[] = ['50a', 'F', 'identityPart'];
            $verifyFields[] = ['50a', 'G', 'account'];
            $verifyFields[] = ['50a', 'H', 'account'];
            $verifyFields[] = ['50a', 'L', 'identityPart'];
        }

        $this->prepareVerifyTags($verifyFields);
    }
}