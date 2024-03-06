<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt210 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['30' ,'date'],
        ];

        $contentModel = $this->getContentModel();

        $settings = $this->getSettings();

        $collection = $contentModel->getValueByPath(['21-56a']);
        $collection11_56 = $contentModel->getValueByPath(['11-56a']);

        foreach($collection->model as $id => $item) {
            $verifyFields[] = ['21-56a', $id, '32B', 'currency'];
            $verifyFields[] = ['21-56a', $id, '32B', 'sum'];

            if ($settings->accountsVerification) {
                $verifyFields[] = ['21-56a', $id, '52a', 'A', 'identityPart'];
                $verifyFields[] = ['21-56a', $id, '52a', 'D', 'identityPart'];
                $verifyFields[] = ['21-56a', $id, '56a', 'A', 'identityPart'];
                $verifyFields[] = ['21-56a', $id, '56a', 'D', 'identityPart'];
            }
        }

        foreach($collection11_56->model as $id => $item) {
            if ($settings->accountsVerification) {
                $verifyFields[] = ['11-56a', $id, '50a', 'F', 'identityPart'];
            }
        }

        $this->prepareVerifyTags($verifyFields);
    }

}