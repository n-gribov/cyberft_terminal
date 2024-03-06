<?php

namespace addons\edm\models\ForeignCurrencyOperation\cyberxml\dataTypes;

use common\base\Model;
use common\helpers\StringHelper;
use SimpleXMLElement;

class AccountDataType extends Model
{

    public $number;
    public $bik;
    public $bankName;
    public $bankAccountNumber;

    private $_xml;

    public function rules()
    {
        return [
            [['number', 'bik', 'bankName', 'bankAccountNumber'], 'safe']
        ];
    }

    public function loadFromString($data, $isFile = false)
    {
        if ($isFile && is_readable($data)) {
            $data = file_get_contents($data);
        }

        libxml_use_internal_errors(true);
        $this->_xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_PARSEHUGE);

        if ($this->_xml === false) {
            $this->addError('xml', 'Error loading XML');
            return false;
        }

        $this->parseXml();

        return $this;
    }

    public function parseXml()
    {
        $this->number = empty($this->_xml->AccountNumber) ? null : (string) $this->_xml->AccountNumber;
        $this->bik = empty($this->_xml->BIK) ? null : (string) $this->_xml->BIK;
        $this->bankName = empty($this->_xml->BankName) ? null : (string) $this->_xml->BankName;
        $this->bankAccountNumber = empty($this->_xml->BankAccountNumber) ? null : (string) $this->_xml->BankAccountNumber;
    }

    public function getDataTypeAsString($headerTag, $removeXmlDeclaration = true)
    {
        $this->buildXml($headerTag);

        $body = StringHelper::fixBOM($this->_xml->saveXML());

        if (empty($body)) {
            return '';
        }

        if ($removeXmlDeclaration) {
            return trim(preg_replace('/^.*?\<\?xml.*\?>/uim', '', $body));
        }

        return StringHelper::fixXmlHeader($body);
    }

    private function buildXml($headerTag)
    {
        $xml = new SimpleXMLElement('<' . $headerTag . '></' . $headerTag . '>');

        $xml->addChild('AccountNumber');
        $xml->addChild('BIK');
        $xml->addChild('BankName');
        $xml->addChild('BankAccountNumber');

        $xml->AccountNumber = $this->number;
        $xml->BIK = $this->bik;
        $xml->BankName = $this->bankName;
        $xml->BankAccountNumber = $this->bankAccountNumber;

        $this->_xml = $xml;
    }

    public function getXml()
    {
        return $this->_xml;
    }

}
