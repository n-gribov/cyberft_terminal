<?php

namespace addons\edm\models\VTBDocument;

class VTBDocumentType
{
    const TYPE_NAMES = [
        'VTBPayDocRu'            => 'Платежное поручение',
        'VTBStatementRu'         => 'Выписка',
        'VTBPayDocCur'           => 'Валютный платеж',
        'VTBCurrBuy'             => 'Покупка валюты',
        'VTBCurrSell'            => 'Продажа валюты',
        'VTBCurrConversion'      => 'Поручение на конверсию валют',
        'VTBFreeClientDoc'       => 'Произвольные документы в банк',
        'VTBStatementQuery'      => 'Запрос выписки',
        'VTBFreeBankDoc'         => 'Письмо из банка',
        'VTBCancellationRequest' => 'Запрос на отзыв документа',
        'VTBTransitAccPayDoc'    => 'Списание средств с транзитного счета',
        'VTBPayRollDoc'          => 'Зарплатная ведомость',
        'VTBConfDocInquiry138I'  => 'Справка о подтверждающих документах',
        'VTBFreeClientDocGOZ'    => 'Письмо в Банк (гособоронзаказ)',
        'VTBFreeBankDocGOZ'      => 'Письмо из банка (гособоронзаказ)',
        'VTBCurrDealInquiry181i' => 'Сведения о валютной операции',
        'VTBContractReg'         => 'Заявление о постановке контракта на учет',
        'VTBCredReg'             => 'Заявление о постановке кредитного договора на учет',
        'VTBContractChange'      => 'Заявление о внесении изменений в раздел I ведомости банковского контроля',
        'VTBContractUnReg'       => 'Заявление о снятии контракта (кредитного договора) с учета',
    ];

    public static function getName($type)
    {
        if (array_key_exists($type, static::TYPE_NAMES)) {
            return static::TYPE_NAMES[$type];
        }

        return $type;
    }

}
