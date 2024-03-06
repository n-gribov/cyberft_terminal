<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt547 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['B', '11A', 'currency'],
            ['A', '98a', 'A', 'date'],
            ['A', '98a', 'C', 'date'],
            ['A', '98a', 'E', 'date'],
            ['B', 'B1', '11A', 'currency'],
            ['B', '11A', 'currency']
        ];

        $contentModel = $this->getContentModel();

        $collection98 = $contentModel->getValueByPath(['B', '98a']);

        foreach($collection98->model as $id => $item) {
            $verifyFields[] = ['B', '98a', $id, '98a', 'A', 'date'];
            $verifyFields[] = ['B', '98a', $id, '98a', 'B', 'date'];
            $verifyFields[] = ['B', '98a', $id, '98a', 'C', 'date'];
            $verifyFields[] = ['B', '98a', $id, '98a', 'E', 'date'];
        }

        $collectionB = $contentModel->getValueByPath(['B', 'B1', '98A']);

        foreach($collectionB->model as $id => $item) {
            $verifyFields[] = ['B', 'B1', '98A', $id, '98A', 'date'];
        }

        $collectionD = $contentModel->getValueByPath(['D', '19A']);

        foreach($collectionD->model as $id => $item) {
            $verifyFields[] = ['D', '19A', $id, '19A', 'currency'];
            $verifyFields[] = ['D', '19A', $id, '19A', 'sum'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}
