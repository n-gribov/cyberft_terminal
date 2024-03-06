<?php

namespace common\models\vtbxml\service;

use yii\base\BaseObject;

/**
 * @property string    $recordId
 * @property \DateTime $date
 * @property string    $type
 * @property string    $name
 */
class FreeBankDocListItem extends BaseObject
{
    const DATE_FORMAT = 'Y-m-d';

    public $recordId;
    public $date;
    public $type;
    public $name;

    public function appendToDom(\SimpleXMLElement $parentElement)
    {
        $documentElement = $parentElement->addChild('Doc');
        $documentElement->RecordID = $this->recordId;
        $documentElement->DocumentDate = $this->date === null ? null : $this->date->format(self::DATE_FORMAT);
        $documentElement->DocType = $this->type;
        $documentElement->DocName = $this->name;
    }

    public static function extractFromDom(\SimpleXMLElement $element)
    {
        $documentDate = empty((string)$element->DocumentDate)
            ? null
            : \DateTime::createFromFormat(self::DATE_FORMAT, (string)$element->DocumentDate);
        return new FreeBankDocListItem([
            'recordId' => (string)$element->RecordID,
            'date'     => $documentDate,
            'type'     => (string)$element->DocType,
            'name'     => (string)$element->DocName,
        ]);
    }
}
