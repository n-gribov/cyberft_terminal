<?php

namespace addons\edm\models\VTBClientTerminalSettings;

use common\base\BaseType;
use SimpleXMLElement;

class VTBClientTerminalSettingsType extends BaseType
{
    const TYPE = 'VTBClientTerminalSettings';

    /** @var VTBClientTerminalSettingsCustomer */
    public $customer;

    /** @var VTBClientTerminalSettingsBankBranch[] */
    public $bankBranches = [];

    /** @var VTBClientTerminalSettingsAccount[] */
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
