<?php
namespace common\modules\participant\models;

use common\base\BaseType;
use SimpleXMLElement;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @deprecated
 */
class BICDirRequestType extends BaseType
{
    const XSD_SCHEME = '@addons/edm/resources/xsd/CyberFT_EDM_v2.7.xsd';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftsys.02';

    const TYPE = 'BICDirRequest';

    const REQUEST_TYPE_FULL = 'full';
    const REQUEST_TYPE_INCREMENT = 'increment';

    const CONTENT_FORMAT_CSV = 'BICDirCSV/1.0';

    protected $_xmlDom;

    public $requestType;
    private $_startDate;
    public $contentFormat = self::CONTENT_FORMAT_CSV;

    public $sender;
    public $recipient;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                ['startDate', 'safe'],
                [array_values($this->attributes()), 'safe'],
            ]);
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

    public function attributeLabels()
    {
        return [
            'startDate' => Yii::t('app/participant', 'Start date'),
            'requestType' => Yii::t('app/participant', 'Request type')
        ];
    }

    public function getTypeList()
    {
        return [
            self::REQUEST_TYPE_FULL => Yii::t('app/participant', 'Full'),
            self::REQUEST_TYPE_INCREMENT => Yii::t('app/participant', 'Incremental'),
        ];
    }

    private function parseXml()
    {
        $this->contentFormat = (string) $this->_xmlDom->ContentFormat;
        $this->setStartDate((string) $this->_xmlDom->StartDate);
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function setStartDate($str)
    {
        $this->_startDate = strtotime($str);
    }

    public function getStartDate()
    {
        return date('Y-m-d', $this->_startDate);
    }
}
