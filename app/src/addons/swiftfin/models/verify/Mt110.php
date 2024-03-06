<?php
namespace addons\swiftfin\models\verify;

use addons\swiftfin\models\SwiftFinUserVerify;

class Mt110 extends SwiftFinUserVerify
{
    protected function prepare()
    {
        $verifyFields = [];

        $contentModel = $this->getContentModel();

        $collection = $contentModel->getValueByPath(['21-59']);

        $settings = $this->getSettings();

        foreach($collection->model as $id => $item) {
            $verifyFields[] = ['21-59', $id, '21', 'idCheque'];
            $verifyFields[] = ['21-59', $id, '32a', 'A' , 'date'];
            $verifyFields[] = ['21-59', $id, '32a', 'A' , 'currency'];
            $verifyFields[] = ['21-59', $id, '32a', 'A' , 'sum'];
            $verifyFields[] = ['21-59', $id, '32a', 'B' , 'currency'];
            $verifyFields[] = ['21-59', $id, '32a', 'B' , 'sum'];
            $verifyFields[] = ['21-59', $id, '59', 'receiver'];

            if ($settings->accountsVerification) {
                $verifyFields[] = ['21-59', $id, '52a', 'A' , 'identityPart'];
                $verifyFields[] = ['21-59', $id, '52a', 'D' , 'identityPart'];
                $verifyFields[] = ['21-59', $id, '59', 'receiver'];
            }
        }

        $this->prepareVerifyTags($verifyFields);
    }
}