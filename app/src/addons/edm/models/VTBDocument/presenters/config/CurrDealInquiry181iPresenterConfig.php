<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use common\helpers\Countries;

class CurrDealInquiry181iPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'DEALINFOBLOB' => 'Валютные операции',
        'CUSTOMERBANKNAME' => 'Уполномоченный банк',
        'BANKCOUNTRYCODE' => 'Страна банка-нерезидента',
        // В автогенерированном документе неверно указано "Нас. пункт организации"
        'CUSTOMERINN' => 'ИНН организации',
        'DEALINFOBLOB' => 'Валютные операции',

    ];

    public function getCustomFieldOrder()
    {
        return [
            'DOCUMENTDATE',
            'DOCUMENTNUMBER',
            'CUSTOMERNAME',
            'CUSTOMERINN',
            'CUSTOMERBANKNAME',
            'CUSTOMERBANKBIC',
            'SENDEROFFICIALS',
            'PHONEOFFICIAL',
            'ACCOUNT',
            'BANKCOUNTRYCODE'
        ];
    }

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'BANKCOUNTRYCODE':
                return Countries::getNameByNumericCode($this->_document->$fieldId, true);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }
}
