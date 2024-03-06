<?php

namespace addons\edm\models\BaseSBBOLDocument;

use common\base\BaseType;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\models\sbbolxml\response\Response;
use SimpleXMLElement;
use yii\helpers\ArrayHelper;

abstract class BaseSBBOLResponseDocumentType extends BaseType
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
        $this->response = SBBOLXmlSerializer::deserialize($data, Response::class);
        return $this;
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function getModelDataAsString()
    {
        return $this->rawContent ? $this->rawContent : SBBOLXmlSerializer::serialize($this->response);
    }
}
