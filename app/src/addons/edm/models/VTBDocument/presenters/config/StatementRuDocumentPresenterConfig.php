<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class StatementRuDocumentPresenterConfig extends BSDocumentPresenterConfig
{
    const TABLE_VIEW_FIELDS_IDS = ['AMOUNT', 'CURRCODE', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'PAYER', 'RECEIVER', 'PAYERACCOUNT', 'RECEIVERACCOUNT'];

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
