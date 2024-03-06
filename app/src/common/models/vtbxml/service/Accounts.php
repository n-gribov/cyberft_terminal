<?php

namespace common\models\vtbxml\service;

use common\models\vtbxml\VTBXmlDocument;

/**
 * @property Account[] accounts
 */
class Accounts extends VTBXmlDocument
{
    public $accounts = [];

    public function toXml($version = null)
    {
        $dom = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Accounts />');

        foreach ($this->accounts as $account) {
            $account->appendToDom($dom);
        }

        $xml = $dom->asXML();
        return static::replaceEncoding($xml, 'windows-1251');
    }

    public static function fromXml($xml)
    {
        $accounts = new Accounts();
        $dom = new \SimpleXMLElement(static::replaceEncoding($xml, 'utf-8'));

        foreach ($dom->xpath('//Accounts/Account') as $accountElement) {
            $accounts->accounts[] = Account::extractFromDom($accountElement);
        }

        return $accounts;
    }
}
