<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use common\helpers\Countries;

class ContractRegNonResidentInfoPresenterConfig extends BSDocumentPresenterConfig
{

    public function getHtmlValueCallback($fieldId)
    {        
        switch ($fieldId) {
            case 'COUNTRY':
                return Countries::getNameByNumericCode($this->_document->COUNTRYCODE);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }
}
