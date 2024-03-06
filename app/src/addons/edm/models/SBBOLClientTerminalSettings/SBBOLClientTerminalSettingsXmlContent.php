<?php

namespace addons\edm\models\SBBOLClientTerminalSettings;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class SBBOLClientTerminalSettingsXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const DEFAULT_NS_PREFIX = 'sbbol';
    const ROOT_ELEMENT = 'SBBOLClientTerminalSettings';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/sbbol.01';

    /** @var SBBOLClientTerminalSettingsType */
    protected $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new SBBOLClientTerminalSettingsType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
        $this->setAttributes($this->_typeModel->attributes, false);
    }

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);
        $this->_typeModel->setAttributes($this->attributes, false);
        return $this->_typeModel;
    }

    public function boundAttributes()
    {
        return [
            'customer'     => '//sbbol:SBBOLClientTerminalSettings/sbbol:Customer',
            'accounts'     => '//sbbol:SBBOLClientTerminalSettings/sbbol:Accounts',
        ];
    }

    public function pushCustomer()
    {
        $customer = $this->_typeModel->customer;
        if ($customer === null) {
            return;
        }

        $xpath = $this->getBoundAttributeXpath('customer');
        $customerNodeList = $this->_xpath->query($xpath);
        if (!$customerNodeList->length) {
            $customerNode = $this->forceCreateElement($xpath);
        } else {
            $customerNode = $customerNodeList->item(0);
        }

        foreach ($customer->attributes as $key => $value) {
            if ($value === null) {
                continue;
            }
            $customerNode->setAttribute($key, $value);
        }
    }

    public function fetchCustomer()
    {
        $xpath = $this->getBoundAttributeXpath('customer');

        /** @var \DOMNodeList $nodeList */
        $nodeList = $this->_xpath->query($xpath);
        if ($nodeList->length === 0) {
            return;
        }

        /** @var \DOMNode $node */
        $node = $nodeList->item(0);
        $customer = new SBBOLClientTerminalSettingsCustomer();
        foreach ($customer->attributes as $key => $value) {
            $domAttribute = $node->attributes->getNamedItem($key);
            if ($domAttribute) {
                $customer->$key = $domAttribute->nodeValue;
            }
        }
        $this->_attributes['customer'] = $customer;
    }

    public function pushAccounts()
    {
        $xpath = $this->getBoundAttributeXpath('accounts');
        $accountsNodeList = $this->_xpath->query($xpath);
        if (!$accountsNodeList->length) {
            $accountsNode = $this->forceCreateElement($xpath);
        } else {
            $accountsNode = $accountsNodeList->item(0);
        }

        while ($accountsNode->hasChildNodes()) {
            $accountsNode->removeChild($accountsNode->firstChild);
        }

        foreach ($this->_typeModel->accounts as $account) {
            $accountNode = $this->_dom->createElementNS(static::DEFAULT_NS_URI, 'Account');
            foreach ($account->attributes as $key => $value) {
                if ($value === null) {
                    continue;
                }
                $accountNode->setAttribute($key, $value);
            }
            $accountsNode->appendChild($accountNode);
        }
    }

    public function fetchAccounts()
    {
        $this->_attributes['accounts'] = [];

        $xpath = $this->getBoundAttributeXpath('accounts');

        /** @var \DOMNodeList $nodeList */
        $nodeList = $this->_xpath->query($xpath);
        if ($nodeList->length === 0) {
            return;
        }

        /** @var \DOMNode $node */
        foreach ($nodeList[0]->childNodes as $node) {
            $account = new SBBOLClientTerminalSettingsAccount();
            foreach ($account->attributes as $key => $value) {
                $domAttribute = $node->attributes->getNamedItem($key);
                if ($domAttribute) {
                    $account->$key = $domAttribute->nodeValue;
                }
            }
            $this->_attributes['accounts'][] = $account;
        }
    }
}