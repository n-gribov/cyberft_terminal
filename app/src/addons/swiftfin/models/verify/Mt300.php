<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt300 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['A', '77H', 'date'],
            ['B', '30T', 'date'],
            ['B', '30V', 'date'],
            ['B', 'B1', '32B', 'currency'],
            ['B', 'B1', '32B', 'sum'],
            ['B', 'B2', '33B', 'currency'],
            ['B', 'B2', '33B', 'sum'],
            ['E', '98D', 'date']
        ];

        $settings = $this->getSettings();

        $contentModel = $this->getContentModel();

        $collection = $contentModel->getValueByPath(['D', '17A-58a']);

        foreach($collection->model as $id => $item) {
            $verifyFields[] = ['D', '17A-58a', $id, '32B', 'currency'];
            $verifyFields[] = ['D', '17A-58a', $id, '32B', 'sum'];

            if ($settings->accountsVerification) {
                $verifyFields[] = ['D', '17A-58a', $id, '57a', 'A', 'account'];
                $verifyFields[] = ['D', '17A-58a', $id, '57a', 'D', 'account'];
            }
        }

        if ($settings->accountsVerification) {
            $verifyFields[] = ['B', 'B1', '57a', 'A' ,'account'];
            $verifyFields[] = ['B', 'B1', '57a', 'D' ,'account'];
            $verifyFields[] = ['B', 'B2', '57a', 'A' ,'account'];
            $verifyFields[] = ['B', 'B2', '57a', 'D' ,'account'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}