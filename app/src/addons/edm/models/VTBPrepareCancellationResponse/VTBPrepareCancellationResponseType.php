<?php

namespace addons\edm\models\VTBPrepareCancellationResponse;

use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestExt;
use common\base\BaseType;
use SimpleXMLElement;
use yii\helpers\ArrayHelper;

class VTBPrepareCancellationResponseType extends BaseType
{
    const TYPE = 'VTBPrepareCancellationResponse';

    public $requestDocumentUuid;
    public $status;
    public $documentInfo;
    public $vtbReferenceId;

    protected $xmlDom;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [
                    array_values($this->attributes()), 'safe'
                ],
                ['status', 'in', 'range' => [VTBPrepareCancellationRequestExt::STATUS_PROCESSED, VTBPrepareCancellationRequestExt::STATUS_REJECTED]]
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
