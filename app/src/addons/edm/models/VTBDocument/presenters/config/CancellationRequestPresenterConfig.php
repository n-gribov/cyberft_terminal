<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use yii\helpers\Html;

class CancellationRequestPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'CANCELDOCTYPEID' => 'Тип отзываемого документа',
        'CANCELCUSTID' => 'ID организации отзываемого документа',
        'CANCELDOCMANDATORYFIELDS' => 'Описание основных полей отзываемого документа',
    ];
    
    public static function cancelDocTypeIdMap()
    {
        return
        [
            1    => 'Платежное поручение',
            3    => 'Выписка',
            9    => 'Валютный платеж',
            8    => 'Покупка валюты',
            7    => 'Продажа валюты',
            14   => 'Поручение на конверсию валют',
            4    => 'Письмо в Банк',
            11   => 'Запрос выписки',
            12   => 'Письмо из банка',
            13   => 'Запрос на отзыв документа',
            49   => 'Списание средств с транзитного счета',
            51   => 'Зарплатная ведомость',
            1012 => 'Справка о подтверждающих документах',
            3304 => 'Письмо в Банк (гособоронзаказ)',
            3306 => 'Письмо из Банка (гособоронзаказ)',
            1050 => 'Сведения о валютной операции',
            1042 => 'Заявление о постановке контракта на учет',
            1044 => 'Заявление о постановке кредитного договора на учет',
            1048 => 'Заявление о внесении изменений в раздел I ведомости банковского контроля',
            1046 => 'Заявление о снятии контракта (кредитного договора) с учета',
        ];

    }

    public function getHtmlValueCallback($fieldId)
    {
        switch ($fieldId) {
            case 'CANCELDOCMANDATORYFIELDS':
                return function ($value) {
                    return nl2br(Html::encode($value));
                };
            case 'CANCELDOCTYPEID':
                return static::createValueCallbackFromMap($this->cancelDocTypeIdMap());
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }
}
