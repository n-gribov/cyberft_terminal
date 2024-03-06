<?php

namespace common\modules\participant\models;

use yii\helpers\ArrayHelper;
use common\base\BaseType;

/**
 * @deprecated
 */
class BICDirType extends BaseType
{
    const TYPE = 'BICDir';
    const NAMESPACE_PREFIX = 'data';

    const REQUEST_TYPE_FULL = 'full';
    const REQUEST_TYPE_INCREMENT = 'increment';

    private $_rawDataIncrement;
    private $_rawDataFull;

    public $requestType;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [array_values($this->attributes()), 'safe'],
            ]);
    }

    public function loadFromDom($dom)
    {
        $xml = simplexml_import_dom($dom);

        $xml->registerXPathNamespace('bicdir', 'http://cyberft.ru/xsd/cftsys.02');
        $nodeIncrement = $xml->xpath('//bicdir:IncrementLoad');
        $nodeFull = $xml->xpath('//bicdir:FullLoad');

        $this->_rawDataIncrement = [];
        $this->_rawDataFull = [];

        foreach($nodeIncrement as $update) {
            $update->registerXPathNamespace('bicdir', 'http://cyberft.ru/xsd/cftsys.02');
            $rawData = $update->xpath('bicdir:Content/data:RawData');
            $this->_rawDataIncrement[] = [
                'startDate' => strtotime((string) $update->Header->StartDate),
                'endDate' => strtotime((string) $update->Header->EndDate),
                'rawData' => base64_decode((string) $rawData[0])
            ];
        }

        foreach($nodeFull as $update) {
            $update->registerXPathNamespace('bicdir', 'http://cyberft.ru/xsd/cftsys.02');
            $rawData = $update->xpath('bicdir:Content/data:RawData');
            $this->_rawDataFull[] = [
                'lastUpdatedDate' => strtotime((string) $update->Header->LastUpdatedDate),
                'rawData' => base64_decode((string) $rawData[0])
            ];
        }

        if (count($this->_rawDataFull)) {
            $this->requestType = self::REQUEST_TYPE_FULL;
        } else {
            $this->requestType = self::REQUEST_TYPE_INCREMENT;
        }
    }

    public function getRawDataIncrement()
    {
        return $this->_rawDataIncrement;
    }

    public function getRawDataFull()
    {
        return $this->_rawDataFull;
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getModelDataAsString()
    {
        return '';
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