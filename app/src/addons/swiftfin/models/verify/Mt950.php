<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt950 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [
            ['64', 'date'],
            ['64', 'currency'],
            ['64', 'sum'],
            ['60a', 'F', 'date'],
            ['60a', 'F', 'sum'],
            ['60a', 'F', 'currency'],
            ['60a', 'M', 'date'],
            ['60a', 'M', 'sum'],
            ['60a', 'M', 'currency'],
            ['62a', 'F', 'date'],
            ['62a', 'F', 'sum'],
            ['62a', 'F', 'currency'],
            ['62a', 'M', 'date'],
            ['62a', 'M', 'sum'],
            ['62a', 'M', 'currency'],
        ];

        $settings = $this->getSettings();

        if ($settings->accountsVerification) {
            $verifyFields[] = ['25', 'account'];
        }

        $contentModel = $this->getContentModel();

        $collection = $contentModel->getValueByPath(['61']);

        foreach($collection->model as $id => $item) {
            $verifyFields[] = ['61', $id, '61', 'date1'];
            $verifyFields[] = ['61', $id, '61', 'date2'];
            $verifyFields[] = ['61', $id, '61', 'sum'];
        }

        $this->prepareVerifyTags($verifyFields);
    }

}