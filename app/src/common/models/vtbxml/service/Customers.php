<?php

namespace common\models\vtbxml\service;

use common\models\vtbxml\VTBXmlDocument;

/**
 * @property Customer[] customers
 */
class Customers extends VTBXmlDocument
{
    public $customers = [];

    public function toXml($version = null)
    {
        $dom = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Customers />');

        foreach ($this->customers as $customer) {
            $customer->appendToDom($dom);
        }

        $xml = $dom->asXML();
        return static::replaceEncoding($xml, 'windows-1251');
    }

    public static function fromXml($xml)
    {
        $customers = new Customers();
        $dom = new \SimpleXMLElement(static::replaceEncoding($xml, 'UTF-8'));

        foreach ($dom->xpath('//Customers/Customer') as $customerElement) {
            $customers->customers[] = Customer::extractFromDom($customerElement);
        }

        return $customers;
    }
}
