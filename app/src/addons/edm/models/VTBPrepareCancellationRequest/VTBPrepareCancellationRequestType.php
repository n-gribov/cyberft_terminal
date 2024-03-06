<?php

namespace addons\edm\models\VTBPrepareCancellationRequest;

use common\base\BaseType;
use SimpleXMLElement;
use yii\helpers\ArrayHelper;

class VTBPrepareCancellationRequestType extends BaseType
{
    const TYPE = 'VTBPrepareCancellationRequest';

    public $documentUuid;
    public $documentNumber;
    public $documentDate;
    public $messageForBank;
    public $vtbDocumentType;
    public $vtbCustomerId;

    protected $xmlDom;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [
                    array_values($this->attributes()), 'safe'
                ],
            ]
        );
    }

    public function getType()
    {
        return static::TYPE;
    }

    public function loadFromString($data, $isFile = false, $encoding = null)
    {
        $this->xmlDom = new SimpleXMLElement($data);
        $this->parseXml();
        return $this;
    }

    private function parseXml()
    {
        foreach ($this->attributes() as $attribute) {
            $this->$attribute = $this->xmlDom->$attribute;
        }
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

}
