<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class CurrConversionPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'REQUESTRATETYPE' => 'Тип курса продажи',
        'CURRCODEDEBET' => 'Валюта счета по дебету',
        'CURRCODECREDIT' => 'Валюта счета по кредиту',
    ];

    public function getCustomFieldOrder()
    {
        return [
            'DOCUMENTDATE',
            'DOCUMENTNUMBER',
            'CUSTOMERNAME',
            'CUSTOMERINN',
            'CUSTOMEROKPO',
            'SENDEROFFICIALS',
            'PHONEOFFICIAL',
            'AMOUNTDEBET',
            'CURRCODEDEBET',
            'ACCOUNTDEBET',
            'AMOUNTCREDIT',
            'DEBETBANKNAME',
            'CURRCODECREDIT',
            'ACCOUNTCREDIT',
            'CREDITBANKBIC',
            'PAYMENTDETAILS',
            'VALIDITYPERIOD',
            'TRANSFERDOCUMENTDATE',
            'SUPPLYCONDITIONDATE',
            'REQUESTRATETYPE',
            'REQUESTRATE'
        ];
    }

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CURRCODEDEBET':
            case 'CURRCODECREDIT':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            case 'REQUESTRATETYPE':
                return static::createValueCallbackFromMap([
                    0 => 'курс банка',
                    1 => 'заданный пользователем',
                    2 => 'льготный курс'
                ]);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }
}
