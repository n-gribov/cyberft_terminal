<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt320 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['B', '30T', 'date'],
            ['B', '30V', 'date'],
            ['B', '30P', 'date'],
            ['B', '32B', 'currency'],
            ['B', '32B', 'sum'],
            ['B', '32H', 'currency'],
            ['B', '32H', 'sum'],
            ['B', '30X', 'sum'],
            ['B', '34E', 'currency'],
            ['B', '34E', 'sum'],
            ['B', '30F', 'date'],
            ['G', '33B', 'currency'],
            ['G', '33B', 'sum'],
            ['G', '33E', 'currency'],
            ['G', '33E', 'sum'],
        ];

        $contentModel = $this->getContentModel();

        $collection = $contentModel->getValueByPath(['I', '30F-32H']);

        foreach($collection->model as $id => $item) {
            $verifyFields[] = ['I', '30F-32H', $id, '32H', 'currency'];
            $verifyFields[] = ['I', '30F-32H', $id, '32H', 'sum'];
            $verifyFields[] = ['I', '30F-32H', $id, '30F', 'date'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}
