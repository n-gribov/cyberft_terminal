<?php
namespace addons\edm\models\PROVCSV;

use common\base\BaseType;
use common\helpers\StringHelper;

class ProvcsvType extends BaseType
{
    const TYPE = 'PROVSCV';
    public $sender;
    public $recipient;

    protected $_rawData;

    public function loadFromString($value, $isFile = false, $encoding = null)
    {
        $this->_rawData = StringHelper::utf8($value);

        return $this;
    }

    public function getModelDataAsString()
    {
        return $this->_rawData;
    }
    
    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return [
            'sender'   => $this->sender,
            'receiver' => $this->recipient,
            'body'     => $this->getModelDataAsString()
        ];
    }
}
