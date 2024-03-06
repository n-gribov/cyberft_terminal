<?php

namespace addons\edm\models\Sbbol2ClientTerminalSettings;

use common\base\BaseType;
use SimpleXMLElement;

class Sbbol2ClientTerminalSettingsType extends BaseType
{
    const TYPE = 'Sbbol2ClientTerminalSettings';

    /** @var Sbbol2ClientTerminalSettingsCustomer */
    public $customer;

    /** @var Sbbol2ClientTerminalSettingsAccount[] */
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
