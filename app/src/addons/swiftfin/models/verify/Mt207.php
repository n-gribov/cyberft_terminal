<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt207 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['A', '30' ,'date'],
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['A', '51A', 'identityPart'];
            $verifyFields[] = ['A', '52G', 'account'];
        }

        $contentModel = $this->getContentModel();

        $collection = $contentModel->getValueByPath(['B','21-58a']);

        foreach($collection->model as $id => $item) {
            $verifyFields[] = ['B', '21-58a', $id, '32B', 'currency'];
            $verifyFields[] = ['B', '21-58a', $id, '32B', 'sum'];

            if ($settings->accountsVerification) {
                $verifyFields[] = ['B', '21-58a', $id, '56a', 'A', 'identityPart'];
                $verifyFields[] = ['B', '21-58a', $id, '56a', 'D', 'identityPart'];
                $verifyFields[] = ['B', '21-58a', $id, '57a', 'A', 'identityPart'];
                $verifyFields[] = ['B', '21-58a', $id, '57a', 'B', 'identityPart'];
                $verifyFields[] = ['B', '21-58a', $id, '57a', 'D', 'identityPart'];
                $verifyFields[] = ['B', '21-58a', $id, '58a', 'A', 'identityPart'];
                $verifyFields[] = ['B', '21-58a', $id, '58a', 'D', 'identityPart'];
            }
        }

        $this->prepareVerifyTags($verifyFields);
    }

}