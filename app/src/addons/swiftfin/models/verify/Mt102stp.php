<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt102stp extends SwiftFinUserVerify
{
    public $verifyFields = [];

    protected function prepare()
    {
        $settings = $this->getSettings();

        $verifyFields = [
            ['C', '32A', 'date'],
            ['C', '32A', 'currency'],
            ['C', '32A', 'sum'],
        ];

        // счета
        if ($settings->accountsVerification) {
            $verifyFields[] = ['A', '52A', 'identityPart'];
            $verifyFields[] = ['A', '50a', 'A', 'account'];
            $verifyFields[] = ['A', '50a', 'K', 'account'];
        }

        $contentModel = $this->getContentModel();

        $collectionB = $contentModel->getValueByPath(['B', '21-36']);

        foreach($collectionB->model as $id => $item) {
            $verifyFields[] = ['B', '21-36', $id, '32B', 'currency'];
            $verifyFields[] = ['B', '21-36', $id, '32B', 'sum'];
            $verifyFields[] = ['B', '21-36', $id, '33B', 'currency'];
            $verifyFields[] = ['B', '21-36', $id, '33B', 'sum'];
            $verifyFields[] = ['B', '21-36', $id, '71G', 'currency'];
            $verifyFields[] = ['B', '21-36', $id, '71G', 'sum'];

            //
            $subCollection71F = $contentModel->getValueByPath(['B', '21-36', $id, '71F']);

            foreach($subCollection71F->model as $subId => $subItem) {
                $verifyFields[] = ['B', '21-36', $id, '71F', $subId, '71F', 'currency'];
                $verifyFields[] = ['B', '21-36', $id, '71F', $subId, '71F', 'sum'];
            }

            //счета
            if ($settings->accountsVerification) {
                $verifyFields[] = ['B', '21-36', $id, '56a', 'A', 'identityPart'];
                $verifyFields[] = ['B', '21-36', $id, '52A', 'identityPart'];
                $verifyFields[] = ['B', '21-36', $id, '57A', 'identityPart'];
                $verifyFields[] = ['B', '21-36', $id, '59a', 'NoLetter', 'account'];
                $verifyFields[] = ['B', '21-36', $id, '59a', 'A', 'account'];
            }

        }

        $this->prepareVerifyTags($verifyFields);
    }

}