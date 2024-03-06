<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class ConfDocInquiry138IConfDocPSPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'CURRCODE1' => 'Валюта документа',
        'CURRCODE2' => 'Валюта цены контракта',
    ];

    const TABLE_VIEW_FIELDS_IDS = ['AMOUNTCURRENCY1', 'CURRCODE1', 'DOCUMENTNUMBER', 'DOCDATE'];

    const EXCLUDED_FIELDS = [
        'REMARK',
    ];

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CURRCODE1':
            case 'CURRCODE2':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }

}
