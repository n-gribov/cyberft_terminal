<?php

namespace addons\edm\models\ForeignCurrencyOperation\cyberxml\dataTypes;

use common\base\Model;
use common\helpers\StringHelper;
use SimpleXMLElement;

class ApplicantDataType extends Model
{
    public $reference;
    public $name;
    public $inn;
    public $address;
    public $phone;
    public $fax;
    public $contactPerson;

    private $_xml;

    public function rules()
    {
        return [
            [['reference', 'name', 'inn', 'address', 'phone', 'fax', 'contactPerson'],'safe']
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
        $this->reference = (string) $this->_xml->Reference;
        $this->name = (string) $this->_xml->Name;
        $this->inn = (string) $this->_xml->INN;
        $this->address = (string) $this->_xml->Address;
        $this->phone = (string) $this->_xml->Phone;
        $this->fax = (string) $this->_xml->Fax;
        $this->contactPerson = (string) $this->_xml->Contact->FIO;
    }

    public function getDataTypeAsString($removeXmlDeclaration = true)
    {
        // Сформировать XML
        $this->buildXml();
        $body = StringHelper::fixBOM($this->_xml->saveXML());

        if (empty($body)) {
            return '';
        }

        if ($removeXmlDeclaration) {
            return trim(preg_replace('/^.*?\<\?xml.*\?>/uim', '', $body));
        }

        return StringHelper::fixXmlHeader($body);
    }

    private function buildXml()
    {
        $xml = new SimpleXMLElement('<Applicant></Applicant>');

        $xml->addChild('Reference');
        $xml->addChild('Name');
        $xml->addChild('INN');
        $xml->addChild('Address');
        $xml->addChild('Phone');
        $xml->addChild('Fax');
        $xml->addChild('Contact');

        $xml->Reference = $this->reference;
        $xml->Name = $this->name;
        $xml->INN = $this->inn;
        $xml->Address = $this->address;
        $xml->Phone = $this->phone;
        $xml->Fax = $this->fax;
        $xml->Contact->FIO = $this->contactPerson;

        $this->_xml = $xml;
    }

}
