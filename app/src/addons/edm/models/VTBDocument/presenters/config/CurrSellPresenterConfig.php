<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class CurrSellPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'CUSTOMERTYPE' => 'Тип предприятия',
        'VALUEDATETYPE' => 'Выбор даты валютирования',
        'REQUESTRATETYPE' => 'Курс продажи валюты',
        'CURRCODEDEBET' => 'Валюта счета по дебету',
        'CURRCODECREDIT' => 'Валюта счета по кредиту',
    ];

    function getCustomFieldOrder()
    {
        return [
            'DOCUMENTDATE',
            'DOCUMENTNUMBER',
            'SENDEROFFICIALS',
            'PHONEOFFICIAL',
            'CUSTOMERNAME',
            'CUSTOMERPROPERTYTYPE',
            'CUSTOMERINN',
            'CUSTOMERPLACE',
            'CUSTOMERADDRESS',
            'DEBETBANKBIC',
            'DEBETBANKNAME',
            'DEBETBANKPLACE',
            'DEBETBANKCORRACC',
            'ACCOUNTDEBET',
            'CURRCODEDEBET',
            'AMOUNTDEBET',
            'REQUESTRATE',
            'ACCOUNTCREDIT',
            'CURRCODECREDIT',
            'AMOUNTCREDIT',
            'CREDITBANKBIC',
            'CREDITBANKNAME',
            'CREDITBANKPLACE',
            'CREDITBANKCORRACC',
            'PAYMENTDETAILS',
            'CHARGEACCOUNT',
            'DEALTYPE',
            'OPERCODE',
            'REQUESTRATETYPE',
            'SUPPLYCONDITION',
            'SUPPLYCONDITIONDATE',
            'CODEMESSAGE',
            'MESSAGEFORBANK',
            'BALANCEACCOUNT',
        ];
    }

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CURRCODEDEBET':
            case 'CURRCODECREDIT':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            case 'CUSTOMERTYPE':
                return static::createValueCallbackFromMap([
                    0 => 'резидент',
                    1 => 'нерезидент',
                ]);
            case 'REQUESTRATETYPE':
                return static::createValueCallbackFromMap([
                    0 => 'курс банка',
                    1 => 'заданный пользователем',
                    2 => 'льготный курс'
                ]);
            case 'DEALTYPE':
                return static::createValueCallbackFromMap([
                    1 => 'по курсу ОАО Банк ВТБ',
                    2 => 'по курсу Банка России с расчетной ставкой вознаграждения ОАО Банк ВТБ',
                    3 => 'по курсу сделок ОАО Банк ВТБ на рынке с тарифной ставкой вознаграждения ОАО Банк ВТБ'
                ]);
            case 'REQUESTRATETYPE':
                return static::createValueCallbackFromMap([
                    0 => 'курс банка',
                    1 => 'заданный пользователем',
                    2 => 'льготный курс'
                 ]);
            case 'CHARGETYPE':
                return static::createValueCallbackFromMap([
                    0 => 'списать со счета',
                    1 => 'удержать из суммы сделки'
                ]);
            case 'SUPPLYCONDITION':
                return static::createValueCallbackFromMap([
                    1 => 'сроком "сегодня"',
                    2 => 'сроком "завтра"',
                    3 => 'на фиксированных условиях сроком'
                ]);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }
}
