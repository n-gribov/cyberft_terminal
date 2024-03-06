<?php
namespace common\modules\transport\models;

use common\base\BaseType;
use yii\helpers\ArrayHelper;

class CFTAckType extends BaseType
{
    const TYPE = 'CFTAck';

    protected $_xmlDom;

    public $sender;
    public $recipient;

    public $refDocId;
    public $refSenderId;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [array_values($this->attributes()), 'safe'],
        ]);
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function loadFromString($xml, $isFile = false, $encoding = null)
    {
        $this->_xmlDom = new \SimpleXMLElement($xml);
        $this->parseXml();

        return $this;
    }

    private function parseXml()
    {
        $this->refDocId = (string) $this->_xmlDom->RefDocId;
        $this->refSenderId = (string) $this->_xmlDom->RefSenderId;
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

}
