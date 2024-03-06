<?php

namespace addons\edm\models\VTBDocument\presenters\config;

class StatementQueryPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'STATEMENTTYPE' => 'Тип запрашиваемой выписки',
    ];

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
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
