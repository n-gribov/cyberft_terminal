<?php

namespace addons\edm\models\BaseRaiffeisenDocument;

use common\base\BaseType;
use common\helpers\raiffeisen\RaiffeisenXmlSerializer;
use common\models\raiffeisenxml\response\Response;
use SimpleXMLElement;
use yii\helpers\ArrayHelper;

abstract class BaseRaiffeisenResponseDocumentType extends BaseType
{
    const TYPE = null;

    /** @var Response */
    public $response;

    public $rawContent;

    /** @var SimpleXMLElement */
    protected $xmlDom;

    public function rules()
    {
        $allAttributes = array_values($this->attributes());
        return ArrayHelper::merge(
            parent::rules(),
            [
                [$allAttributes, 'safe'],
                [$allAttributes, 'required'],
            ]
        );
    }

    public function getType()
    {
        return static::TYPE;
    }

    public function loadFromString($data, $isFile = false, $encoding = null)
    {
        $this->response = RaiffeisenXmlSerializer::deserialize($data, Response::class);
        return $this;
    }

    public function getSearchFields()
    {
        return false;
    }

    public function getModelDataAsString()
    {
        return $this->rawContent ? $this->rawContent : RaiffeisenXmlSerializer::serialize($this->response);
    }
}
