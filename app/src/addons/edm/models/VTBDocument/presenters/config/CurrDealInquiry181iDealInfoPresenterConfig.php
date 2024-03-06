<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class CurrDealInquiry181iDealInfoPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'PAYMENTAMOUNT' => 'Сумма в валюте платежа',
        'PAYMENTCURRCODE' => 'Валюта платежа',
        'FOPERNUMMODE' => 'Признак',
        'CURRCODEPS' => 'Валюта контракта'
    ];

    const TABLE_VIEW_FIELDS_IDS = [
        'NUM', 'OPERDATE', 'AMOUNTPSCURRENCY', 'CURRCODEPS',
        'PAYMENTAMOUNT', 'PAYMENTCURRCODE'
    ];

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'PAYMENTCURRCODE':
            case 'CURRCODEPS':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            case 'FOPERNUMMODE':
                return static::createValueCallbackFromMap([
                    0 => 'Номер контракта',
                    1 => 'Номер-дата контракта',
                ]);
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

