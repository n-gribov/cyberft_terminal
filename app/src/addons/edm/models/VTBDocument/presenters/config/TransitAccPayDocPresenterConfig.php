<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class TransitAccPayDocPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'CHARGETYPE' => 'Способ списания комиссии',
        'ISSELL' => 'Признак продажи денежных средств',
        'REQUESTRATETYPE' => 'Курс продажи валюты',
        'ISCREDIT' => 'Признак зачисления денежных средств на валютный счет',
        'GROUNDRECEIPTSBLOB' => 'Информация об обосновывающих документах',
        'NOTICEBLOB' => 'Информация об уведомлениях',
        'CURRCODE' => 'Валюта документа',
        'DOCATTACHMENT' => 'Приложенные документы',
    ];

    public function getCustomFieldOrder()
    {
        return [
            'DOCUMENTDATE',
            'DOCUMENTNUMBER',
            'CUSTOMERNAME',
            'CUSTOMERINN',
            'SENDEROFFICIALS',
            'PHONEOFFICIALS',
            'CURRCODE',
            'TOTALAMOUNT',
            'ACCOUNTTRANSIT',
            'AMOUNTDEBET',
            'ADDINFO',
            'ISCREDIT',
            'AMOUNTCREDIT',
            'RECEIVERCURRACCOUNT',
            'CREDITBANKBICCURR',
            'ISSELL',
            'AMOUNTSELL',
            'DEALTYPE',
            'RECEIVERRURACCOUNT',
            'RECEIVERRURBIC',
            'SUPPLYCONDITION',
            'SUPPLYCONDITIONDATE',
            'REQUESTRATETYPE',
            'REQUESTRATE',
            'CHARGETYPE',
            'CHARGEACCOUNT',
            'DOCATTACHMENT',
        ];
    }

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CURRCODE':
                return DictCurrency::getNameByCode($this->_document->$fieldId, true);
            case 'CHARGETYPE':
                return static::createValueCallbackFromMap([
                    0 => 'со счета списания комиссии',
                    1 => 'удержать из суммы сделки',
                ]);
            case 'REQUESTRATETYPE':
                return static::createValueCallbackFromMap([
                    0 => 'курс банка',
                    1 => 'заданный пользователем',
                    2 => 'льготный курс'
                ]);
            case 'ISSELL':
                return static::createValueCallbackFromMap([
                    0 => 'нет',
                    1 => 'да',
                ]);
            case 'ISCREDIT':
                return static::createValueCallbackFromMap([
                    0 => 'нет',
                    1 => 'да',
                ]);
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }
}
