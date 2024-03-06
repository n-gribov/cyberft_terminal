<?php

namespace addons\edm\models\StatementRequest;

use common\base\BaseType;
use common\helpers\StringHelper;
use Yii;
use yii\helpers\ArrayHelper;

class StatementRequestType extends BaseType
{
    const TYPE           = 'StatementRequest';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/edm.02';
    const NS_URI_V1 = 'http://cyberft.ru/xsd/swiftfin.01';
    const NS_URI_V2 = 'http://cyberft.ru/xsd/edm.02';
    //const DEFAULT_MAPPER_CLASS = 'addons\edm\models\StatementRequest\StatementRequestMapperV2';

    public $accountNumber;
    public $BIK;
    public $startDate;
    public $endDate;

    private $_mapper;
    private $_xmlDom;
    private $_xmlString;

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
        return self::TYPE;
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($clearErrors) {
            $this->clearErrors();
        }

        $startDate = strtotime($this->startDate);
        $endDate   = strtotime($this->endDate);
        $diff      = $endDate - $startDate;

        if ($endDate > time()) {
            $this->addError('endDate',
                    Yii::t('edm', 'End date must not be greater than current date'));
        } else {
            if ($startDate == 0 || $endDate == 0) {
                $this->addError('endDate',
                    Yii::t('edm', 'Both dates should be set'));
            }

            if ($diff < 0) {
                $this->addError('endDate',
                        Yii::t('edm', 'End date must be greater than start date'));
            }
        }

        return parent::validate($attributeNames, false);
    }

    public function validateXSD()
    {
        return $this->_mapper ? $this->_mapper->validateXSD($this->_xmlDom) : false;
    }

    public function loadFromString($string, $isFile = false, $encoding = null)
    {
        $this->_xmlDom = simplexml_load_string($string, 'SimpleXMLElement', LIBXML_PARSEHUGE);
        $this->_mapper = $this->getMapperByNs($this->_xmlDom);
        $this->_mapper->parseXml($this->_xmlDom, $this);

        return $this;
    }

    public function buildXml()
    {
        if (empty($this->_mapper)) {
            $this->_mapper = $this->getDefaultMapper();
        }

        return $this->_mapper->buildXml($this);
    }

    public function getXmlDom()
    {
        return $this->_xmlDom;
    }

    public function getSearchFields()
    {
        return [];
    }

    public function getModelDataAsString($removeXmlDeclaration = true)
    {
        if (!$this->_xmlDom) {
            $this->_xmlDom = $this->buildXml();
        }

        $body = StringHelper::fixBOM($this->_xmlDom->asXML());

        //if ($removeXmlDeclaration) {
            return trim(preg_replace('/^.*?\<\?xml.*\?>/uim', '', $body));
        //}

        //return StringHelper::fixXmlHeader($body);
    }

    public function attributeLabels()
    {
        return [
            'startDate' => Yii::t('edm', 'Start date'),
            'endDate'   => Yii::t('edm', 'End date'),
            'accountNumber' => Yii::t('edm', 'Account number')
        ];
    }

    private function getMapperByNs($xml)
    {
        $namespaces = $xml->getNamespaces();

        if (is_array($namespaces) && count($namespaces)) {
            $ns = current($namespaces);
        }

        if ($ns == self::NS_URI_V1) {
            return new StatementRequestMapperV1();
        }

        return $this->getDefaultMapper();
    }

    private function getDefaultMapper()
    {
        return new StatementRequestMapperV2();
    }

    public function __sleep()
    {
        if (!empty($this->_xmlDom)) {
            $this->_xmlString = $this->_xmlDom->asXML();
            $this->_xmlDom = null;
        }

        return [
            'accountNumber',
            'BIK',
            'startDate',
            'endDate',
            '_mapper',
            '_xmlString'
        ];
    }

    public function __wakeup()
    {
        if (!empty($this->_xmlString)) {
            $this->_xmlDom = simplexml_load_string($this->_xmlString, 'SimpleXMLElement', LIBXML_PARSEHUGE);
            $this->_xmlString = null;
        }
    }

}