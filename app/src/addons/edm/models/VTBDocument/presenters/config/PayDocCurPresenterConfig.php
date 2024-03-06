<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;
use common\helpers\Countries;

class PayDocCurPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'ISMULTICURR' => 'Мультивалютный документ',
        'REQUESTRATETYPE' => 'Тип курса по поручению',
        'BENEFBANKCOUNTRYCODE' => 'Страна банка бенефициара',
        'IMEDIABANKCOUNTRYCODE' => 'Страна банка посредника',
        'DOCATTACHMENT' => 'Приложенные документы',
    ];

    public function getCustomFieldOrder()
    {
        return [
            'DOCUMENTNUMBER',
            'DOCUMENTDATE',
            'AMOUNT',
            'CURRCODE',
            'ISMULTICURR',
            'AMOUNTTRANSFER',
            'CURRCODETRANSFER',
            'SENDEROFFICIALS',
            'OFFICIALSPHONE',
            'BENEFICIAR',
            'BENEFICIARPLACE',
            'BENEFICIARADDRESS',
            'BENEFICIARCOUNTRYCODE',
            'BENEFICIARACCOUNT',
            'BENEFBANKNAME',
            'BENEFBANKBIC',
            'BENEFBANKADDRESS',
            'BENEFBANKCOUNTRYCODE',
            'BENEFBANKPLACE',
            'BENEFBANKACCOUNT',
            'IMEDIABANKACCOUNT',
            'IMEDIABANKADDRESS',
            'IMEDIABANKBIC',
            'IMEDIABANKCOUNTRYCODE',
            'IMEDIABANKNAME',
            'IMEDIABANKPLACE',
            'ADDINFOVALCONTROL',
            'PAYMENTSDETAILS',
            'MESSAGEFORBANK',
            'CHARGESACCOUNT',
            'CHARGESBANKACCOUNT',
            'CHARGESCURRCODE',
            'CHARGESTYPE',
            'DOCATTACHMENT',
        ];
    }

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'ISMULTICURR':
                return static::createValueCallbackFromMap([
                    0 => 'нет',
                    1 => 'да',
                ]);
            case 'REQUESTRATETYPE':
                return static::createValueCallbackFromMap([
                    0 => 'курс банка',
                    1 => 'заданный пользователем',
                    2 => 'льготный курс'
                ]);
            case 'CURRCODE':
            case 'CURRCODETRANSFER':
            case 'CHARGESCURRCODE':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            case 'BENEFICIARCOUNTRYCODE':
            case 'BENEFBANKCOUNTRYCODE':
            case 'IMEDIABANKCOUNTRYCODE':
                return Countries::getNameByNumericCode($this->_document->$fieldId, true);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }

    public function isExcludedField($fieldId)
    {
        // Если перевод не мультивалютный, эти поля не отображать
        if (
            ($fieldId == 'AMOUNTTRANSFER' || $fieldId == 'CURRCODETRANSFER')
            && !$this->_document->ISMULTICURR
        ) {
            return true;
        }

        return parent::isExcludedField($fieldId);
    }

}
