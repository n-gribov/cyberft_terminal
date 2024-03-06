<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use addons\edm\models\DictCurrency;

class ContractRegPresenterConfig extends BSDocumentPresenterConfig
{
    const CUSTOM_LABELS = [
        'CUSTOMERTYPE' => 'Тип организации',
        'ISCONNUMBER' => 'Признак номера контракта',
        'ISCONAMOUNT' => 'Признак суммы контракта',
        'NONRESIDENTINFO' => 'Реквизиты нерезидента',
        '_ADDRESS' => 'Адрес',
        'CONCURRCODE' => 'Валюта цены контракта',
        'DOCATTACHMENT' => 'Приложенные документы',
    ];

    public function getCustomFieldOrder()
    {
        return [
            'DOCUMENTDATE',
            'DOCUMENTNUMBER',
            'CUSTOMERINN',
            'CUSTOMERKPP',
            'CUSTOMEROGRN',
            '_ADDRESS',
            'CUSTOMERBANKNAME',
            'CUSTOMERBANKBIC',
            'SENDEROFFICIALS',
            'PHONEOFFICIALS',
            'DPNUM1',
            'DPNUM4',
            'DPNUM5',
            'DPDATE',
            'CONNUMBER',
            'ISCONNUMBER',
            'CONDATE',
            'CONAMOUNT',
            'CONCURRCODE',
            'ISCONAMOUNT',
            'CONENDDATE',
            'DPNUMBEROTHERBANK',
            'DOCATTACHMENT',
        ];
    }

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
            case '_ADDRESS':
                $document = $this->_document;
                return implode(', ', array_filter([
                    $document->LAWSTATE,
                    $document->LAWDISTRICT,
                    $document->LAWCITY,
                    $document->LAWPLACE,
                    $document->LAWSTREET,
                    $document->LAWBUILDING,
                    $document->LAWBLOCK,
                    $document->LAWOFFICE
                ]));
            default:
                return parent::getHtmlValueCallback($fieldId);
        }
    }

}
