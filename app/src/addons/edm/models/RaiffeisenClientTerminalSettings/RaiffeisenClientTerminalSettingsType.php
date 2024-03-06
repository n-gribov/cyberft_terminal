<?php

namespace addons\edm\models\RaiffeisenClientTerminalSettings;

use common\base\BaseType;
use SimpleXMLElement;

class RaiffeisenClientTerminalSettingsType extends BaseType
{
    const TYPE = 'RaiffeisenClientTerminalSettings';

    /** @var RaiffeisenClientTerminalSettingsCustomer */
    public $customer;

    /** @var RaiffeisenClientTerminalSettingsAccount[] */
    public $accounts = [];

    protected $xmlDom;

    public function getType()
    {
        return static::TYPE;
    }

    public function loadFromString($xml, $isFile = false, $encoding = null)
    {
        $this->xmlDom = new SimpleXMLElement($xml);

        return $this;
    }

    public function getSearchFields()
    {
        return false;
    }

    public function getSignaturesList()
    {
        return [];
    }
}
