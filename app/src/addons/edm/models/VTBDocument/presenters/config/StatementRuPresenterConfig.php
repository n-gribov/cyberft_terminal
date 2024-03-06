<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class StatementRuPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'STATEMENTTYPE' => 'Тип выписки',
    ];
    const EXCLUDED_FIELDS = ['CUSTID'];

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CURRCODE':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            case 'STATEMENTTYPE':
                return static::createValueCallbackFromMap([
                    0 => 'выписка',
                    1 => 'справка',
                ]);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }

}
