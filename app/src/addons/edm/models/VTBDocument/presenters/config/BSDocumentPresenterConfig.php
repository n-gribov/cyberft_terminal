<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use Yii;

class BSDocumentPresenterConfig
{
    const EXCLUDED_FIELDS = [];
    const CUSTOM_LABELS = [];
    const TABLE_VIEW_FIELDS_IDS = [];
    const VALUE_CALLBACKS = [];

    protected $_document;

    public function setDocument($document)
    {
        $this->_document = $document;
    }

    public function isExcludedField($fieldId)
    {
        return in_array($fieldId, static::EXCLUDED_FIELDS);
    }

    public function getCustomLabel($fieldId)
    {
        if (array_key_exists($fieldId, static::CUSTOM_LABELS)) {
            return static::CUSTOM_LABELS[$fieldId];
        }

        return null;
    }

    public function getTableViewFieldsIds()
    {
        return static::TABLE_VIEW_FIELDS_IDS;
    }

    public function getHtmlValueCallback($fieldId)
    {
         switch ($fieldId) {
            case 'AMOUNT':
            case 'AMOUNTDEBET': 
            case 'AMOUNTCREDIT':
            case 'AMOUNTSELL':
            case 'TOTALAMOUNT':
            case 'NOTICEAMOUNT':
                return Yii::$app->formatter->asDecimal($this->_document->$fieldId, 2);
                break;
         }
         
         return null;
    }

    protected static function createValueCallbackFromMap($map)
    {
        return function ($value) use ($map) {
            if ($value === null || $value === '' || !array_key_exists($value, $map)) {
                return null;
            }

            return $map[$value];
        };
    }

    public function getCustomFieldOrder()
    {
        return [];
    }

}
