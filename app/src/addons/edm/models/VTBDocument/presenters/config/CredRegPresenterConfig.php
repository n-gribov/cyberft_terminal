<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class CredRegPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'CUSTOMERTYPE' => 'Тип организации',
        'ISCONNUMBER' => 'Признак номера контракта',
        'ISCONAMOUNT' => 'Признак суммы контракта',
        'FLAGPAYMENTRETURN' => 'Признак заполнения',
        'NONRESIDENTINFO' => 'Реквизиты нерезидента',
        'CREDTRANCHEBLOB' => 'Информация о предоставляемых траншах по кредитному договору',
        'CREDRECEIPTINFOBLOB' => 'Информация о получении резидентом кредита, предоставленного на синдицированной основе',
        'PAYMENTRETURNBLOB' => 'Информация о графике платежей по возврату заемных средств',
        'NONRESIDENTINFO' => 'Реквизиты нерезидента (нерезидентов)',
        'DOCATTACHMENT' => 'Приложенные документы',
    ];

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CONCURRCODE':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            case 'CUSTOMERTYPE':
                return static::createValueCallbackFromMap([
                    0 => 'резидент',
                    1 => 'нерезидент',
                ]);
            case 'ISCONNUMBER':
                return static::createValueCallbackFromMap([
                    0 => 'нет',
                    1 => 'да',
                ]);
            case 'ISCONAMOUNT':
                return static::createValueCallbackFromMap([
                    0 => 'с суммой',
                    1 => 'без суммы',
                ]);
            case 'FLAGPAYMENTRETURN':
                return static::createValueCallbackFromMap([
                    1 => 'на основании сведений из кредитного договора',
                    2 => 'на основании оценочных данных',
                ]);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }
}
