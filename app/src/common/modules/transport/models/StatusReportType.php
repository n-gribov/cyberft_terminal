<?php
namespace common\modules\transport\models;

use common\base\BaseType;
use yii\helpers\ArrayHelper;

class StatusReportType extends BaseType
{
    const TYPE = 'StatusReport';

    const ERROR_CODE_RECIPIENT_REJECTION = 731; // Код из справочника ошибок процессинга

    protected $_xmlDom;

    public $sender;
    public $recipient;

    public $refDocId;
    public $statusCode;
    public $errorCode;
    public $errorDescription;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [
                    array_values($this->attributes()), 'safe'
                ],
            ]
        );
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function loadFromString($xml, $isFile = false, $encoding = null)
    {
        $this->_xmlDom = new SimpleXMLElement($xml);
        $this->parseXml();

        return $this;
    }

    private function parseXml()
    {
        $this->refDocId = (string) $this->_xmlDom->RefDocId;
        $this->statusCode = (string) $this->_xmlDom->StatusCode;
        $this->errorCode = (string) $this->_xmlDom->ErrorCode;
        $this->errorDescription = (string) $this->_xmlDom->ErrorDescription;
    }

    public function getSearchFields()
    {
        return false;
    }

}