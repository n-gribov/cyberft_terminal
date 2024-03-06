<?php

namespace addons\edm\models\SBBOLClientTerminalSettings;

use common\base\BaseType;
use SimpleXMLElement;

class SBBOLClientTerminalSettingsType extends BaseType
{
    const TYPE = 'SBBOLClientTerminalSettings';

    /** @var SBBOLClientTerminalSettingsCustomer */
    public $customer;

    /** @var SBBOLClientTerminalSettingsAccount[] */
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

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function getSignaturesList()
    {
        return [];
    }
}
