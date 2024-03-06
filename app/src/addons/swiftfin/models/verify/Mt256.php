<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt256 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['C', '32A', 'date'],
            ['C', '32A', 'currency'],
            ['C', '32A', 'sum'],
            ['C', '30', 'date'],
            ['C', '19', 'sum'],
            ['C', '71J', 'currency'],
            ['C', '71J', 'sum'],
            ['C', '71L', 'currency'],
            ['C', '71L', 'sum'],
            ['C', '71K', 'currency'],
            ['C', '71K', 'sum'],
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['C', '57a', 'A', 'identityPart'];
        }

        $contentModel = $this->getContentModel();

        $collection = $contentModel->getValueByPath(['B', '44A-71H']);

        foreach($collection->model as $id => $item) {
            $verifyFields[] = ['B', '44A-71H', $id, '32J', 'sum'];
            $verifyFields[] = ['B', '44A-71H', $id, '71G', 'currency'];
            $verifyFields[] = ['B', '44A-71H', $id, '71G', 'sum'];
            $verifyFields[] = ['B', '44A-71H', $id, '71F', 'currency'];
            $verifyFields[] = ['B', '44A-71H', $id, '71F', 'sum'];
            $verifyFields[] = ['B', '44A-71H', $id, '71H', 'currency'];
            $verifyFields[] = ['B', '44A-71H', $id, '71H', 'sum'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}