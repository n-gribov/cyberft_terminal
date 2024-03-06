<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

/**
 * Common config for currency fields in configless documents
 */
class CommonCurrencyFieldPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'CURRCODE1' => 'Валюта документа',
        'CURRCODE2' => 'Валюта цены контракта',
    ];

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CURRCODE':
            case 'CURRCODE1':
            case 'CURRCODE2':
            case 'CONCURRCODE':
            case 'CURRCODEDEBET':
            case 'CURRCODECREDIT':
            case 'CURRCODECHARGE':
            case 'CURRCODEPS':
            case 'PAYMENTCURRCODE':
            case 'CURRCODECHARGE':
            case 'CURRCODETRANSFERTOTAL':
            case 'CURRCODEFACT':
            case 'NOTICECURRCODE':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }

}
