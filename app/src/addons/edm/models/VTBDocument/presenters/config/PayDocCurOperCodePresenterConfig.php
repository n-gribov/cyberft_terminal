<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class PayDocCurOperCodePresenterConfig extends BSDocumentPresenterConfig
{
    const TABLE_VIEW_FIELDS_IDS = ['OPERCODE', 'AMOUNT', 'CURRCODE'];

    const CUSTOM_LABELS = [
        'CURRCODE' => 'Валюта операции',
    ];

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CURRCODE':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }

}
