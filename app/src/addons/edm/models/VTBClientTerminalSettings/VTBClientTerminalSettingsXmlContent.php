<?php

namespace addons\edm\models\VTBClientTerminalSettings;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;


class VTBClientTerminalSettingsXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const DEFAULT_NS_PREFIX = 'vtb';
    const ROOT_ELEMENT = 'VTBClientTerminalSettings';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/vtb.01';

    /** @var VTBClientTerminalSettingsType */
    protected $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new VTBClientTerminalSettingsType();
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
            'customer'     => '//vtb:VTBClientTerminalSettings/vtb:Customer',
            'accounts'     => '//vtb:VTBClientTerminalSettings/vtb:Accounts',
            'bankBranches' => '//vtb:VTBClientTerminalSettings/vtb:BankBranches',
        ];
    }

    public function pushBankBranches()
    {
        $xpath = $this->getBoundAttributeXpath('bankBranches');
        $branchesNodeList = $this->_xpath->query($xpath);
        if (!$branchesNodeList->length) {
            $branchesNode = $this->forceCreateElement($xpath);
        } else {
            $branchesNode = $branchesNodeList->item(0);
        }

        while ($branchesNode->hasChildNodes()) {
            $branchesNode->removeChild($branchesNode->firstChild);
        }

        foreach ($this->_typeModel->bankBranches as $branch) {
            $branchNode = $this->_dom->createElementNS(static::DEFAULT_NS_URI, 'Branch');
            foreach ($branch->attributes as $key => $value) {
                if ($value === null) {
                    continue;
                }
                $branchNode->setAttribute($key, $value);
            }
            $branchesNode->appendChild($branchNode);
        }
    }

    public function fetchBankBranches()
    {
        $this->_attributes['bankBranches'] = [];

        $xpath = $this->getBoundAttributeXpath('bankBranches');

        /** @var \DOMNodeList $nodeList */
        $nodeList = $this->_xpath->query($xpath);
        if ($nodeList->length === 0) {
            return;
        }

        /** @var \DOMNode $node */
        foreach ($nodeList[0]->childNodes as $node) {
            $branch = new VTBClientTerminalSettingsBankBranch();
            foreach ($branch->attributes as $key => $value) {
                $domAttribute = $node->attributes->getNamedItem($key);
                if ($domAttribute) {
                    $branch->$key = $domAttribute->nodeValue;
                }
            }
            $this->_attributes['bankBranches'][] = $branch;
        }
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
        $customer = new VTBClientTerminalSettingsCustomer();
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
            $account = new VTBClientTerminalSettingsAccount();
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
