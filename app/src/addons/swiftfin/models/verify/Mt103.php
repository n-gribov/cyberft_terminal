<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt103 extends SwiftFinUserVerify
{
    public $verifyFields = [];

    protected function prepare()
    {
        $verifyFields = [
            ['32A', 'date'],
            ['32A', 'currency'],
            ['32A', 'sum'],
            ['33B', 'currency'],
            ['33B', 'sum'],
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['50a', 'A', 'account'];
            $verifyFields[] = ['50a', 'F', 'identityPart'];
            $verifyFields[] = ['50a', 'K', 'account'];
            $verifyFields[] = ['51A', 'identityPart'];
            $verifyFields[] = ['52a', 'A', 'identityPart'];
            $verifyFields[] = ['52a', 'D', 'identityPart'];
            $verifyFields[] = ['53a', 'A', 'identityPart'];
            $verifyFields[] = ['53a', 'B', 'identityPart'];
            $verifyFields[] = ['53a', 'D', 'identityPart'];
            $verifyFields[] = ['54a', 'A', 'identityPart'];
            $verifyFields[] = ['54a', 'B', 'identityPart'];
            $verifyFields[] = ['54a', 'D', 'identityPart'];
            $verifyFields[] = ['55a', 'A', 'identityPart'];
            $verifyFields[] = ['55a', 'B', 'identityPart'];
            $verifyFields[] = ['55a', 'D', 'identityPart'];
            $verifyFields[] = ['56a', 'A', 'identityPart'];
            $verifyFields[] = ['56a', 'C', 'identityPart'];
            $verifyFields[] = ['56a', 'D', 'identityPart'];
            $verifyFields[] = ['57a', 'A', 'identityPart'];
            $verifyFields[] = ['57a', 'B', 'identityPart'];
            $verifyFields[] = ['57a', 'C', 'identityPart'];
            $verifyFields[] = ['57a', 'D', 'identityPart'];
            $verifyFields[] = ['59a', 'A', 'account'];
            $verifyFields[] = ['59a', 'NoLetter', 'account'];
        }

        $contentModel = $this->getContentModel();

        $collection71F = $contentModel->getValueByPath(['71F']);

        foreach($collection71F->model as $id => $item) {
            $verifyFields[] = ['71F', $id, '71F', 'currency'];
            $verifyFields[] = ['71F', $id, '71F', 'sum'];
        }

        $collection71G = $contentModel->getValueByPath(['71G']);

        foreach($collection71G->model as $id => $item) {
            $verifyFields[] = ['71G', $id, '71G', 'currency'];
            $verifyFields[] = ['71G', $id, '71G', 'sum'];
        }

        $this->prepareVerifyTags($verifyFields);
    }
}