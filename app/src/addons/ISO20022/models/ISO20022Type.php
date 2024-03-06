<?php
namespace addons\ISO20022\models;

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\ISO20022Module;
use common\base\BaseType;
use common\helpers\CryptoProHelper;
use common\helpers\SimpleXMLHelper;
use common\helpers\StringHelper;
use Exception;
use SimpleXMLElement;
use Yii;
use yii\base\InvalidValueException;
use yii\helpers\ArrayHelper;

class ISO20022Type extends BaseType
{
    const TYPE = null;

    const CRYPTOPRO_SIGNATURE_PREFIX = 'ds';
    const CRYPTOPRO_SIGNATURE_NS = 'http://www.w3.org/2000/09/xmldsig#';

    const CRYPTOPRO_SIGNATURE_OLD = '<ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="{SIGNATURE_ID}">
        <ds:SignedInfo>
                <ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                <ds:SignatureMethod Algorithm="{SIGNATURE_ALGO}"/>
                <ds:Reference URI="">
                    <ds:Transforms>
                        <ds:Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                        <ds:Transform Algorithm="http://www.w3.org/TR/1999/REC-xpath-19991116">
                            <ds:XPath xmlns:dsig="http://www.w3.org/2000/09/xmldsig#">not(ancestor-or-self::dsig:Signature)</ds:XPath>
                        </ds:Transform>
                    </ds:Transforms>
                    <ds:DigestMethod Algorithm="{DIGEST_ALGO}"/>
                    <ds:DigestValue></ds:DigestValue>
                </ds:Reference>
        </ds:SignedInfo>
        <ds:SignatureValue></ds:SignatureValue>
        <ds:KeyInfo>
            <ds:X509Data>
            <ds:X509SubjectName>{SUBJECTNAME}</ds:X509SubjectName>
            <ds:X509Certificate>{CERTIFICATE}</ds:X509Certificate>
            </ds:X509Data>
            <ds:KeyName>{FINGERPRINT}</ds:KeyName>
        </ds:KeyInfo>
        <ds:Object>
            <ds:QualifyingProperties>
                <ds:SignedProperties>
                    <ds:SignedSignatureProperties>
                        <ds:SigningTime>{SIGNING_TIME}</ds:SigningTime>
                    </ds:SignedSignatureProperties>
                </ds:SignedProperties>
            </ds:QualifyingProperties>
        </ds:Object>
    </ds:Signature>';

    const CRYPTOPRO_SIGNATURE = '<ds:Signature xmlns:ds="http://www.w3.org/2000/09/xmldsig#" Id="{SIGNATURE_ID}">
        <ds:SignedInfo>
            <ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
            <ds:SignatureMethod Algorithm="{SIGNATURE_ALGO}"/>
            <ds:Reference URI="">
                <ds:Transforms>
                    <ds:Transform Algorithm="http://www.w3.org/2002/06/xmldsig-filter2">
                        <dsf:XPath xmlns:dsf="http://www.w3.org/2002/06/xmldsig-filter2" Filter="subtract">//*[local-name()=\'Signature\' and namespace-uri()=\'http://www.w3.org/2000/09/xmldsig#\']</dsf:XPath>
                    </ds:Transform>
                </ds:Transforms>
                <ds:DigestMethod Algorithm="{DIGEST_ALGO}"/>
                <ds:DigestValue></ds:DigestValue>
            </ds:Reference>
        </ds:SignedInfo>
        <ds:SignatureValue></ds:SignatureValue>
        <ds:KeyInfo>
            <ds:X509Data>
            <ds:X509SubjectName>{SUBJECTNAME}</ds:X509SubjectName>
            <ds:X509Certificate>{CERTIFICATE}</ds:X509Certificate>
            </ds:X509Data>
            <ds:KeyName>{FINGERPRINT}</ds:KeyName>
        </ds:KeyInfo>
        <ds:Object>
            <ds:QualifyingProperties>
                <ds:SignedProperties>
                    <ds:SignedSignatureProperties>
                        <ds:SigningTime>{SIGNING_TIME}</ds:SigningTime>
                    </ds:SignedSignatureProperties>
                </ds:SignedProperties>
            </ds:QualifyingProperties>
        </ds:Object>
    </ds:Signature>';

    protected $_type;
    protected $_fullType;
    protected $_filePath;
    protected $_xml;
    protected $_xmlString;

    public $sender;
    public $receiver;
    public $typeCode;
    public $subject;
    public $descr;
    public $msgId;
    public $mmbId;
    public $fileName;
    public $fileNames;
    public $originalFilename;
    public $dateCreated;
    public $useZipContent = false;

    /** @var RosbankEnvelope|null */
    public $rosbankEnvelope;

    protected static $mapTags = [
        'msgId' => [
            'default' => '/a:Document/a:CstmrCdtTrfInitn/a:GrpHdr/a:MsgId',
            'camt.054' => '/a:Document/a:BkToCstmrDbtCdtNtfctn/a:GrpHdr/a:MsgId',
            'camt.053' => '/a:Document/a:BkToCstmrStmt/a:GrpHdr/a:MsgId',
            'camt.052' => '/a:Document/a:BkToCstmrAcctRpt/a:GrpHdr/a:MsgId'
        ],
        'dateCreated' => [
            'default' => '/a:Document/a:CstmrCdtTrfInitn/a:GrpHdr/a:CreDtTm',
            'camt.054' => '/a:Document/a:BkToCstmrDbtCdtNtfctn/a:GrpHdr/a:CreDtTm',
            'camt.053' => '/a:Document/a:BkToCstmrStmt/a:GrpHdr/a:CreDtTm',
            'camt.052' => '/a:Document/a:BkToCstmrAcctRpt/a:GrpHdr/a:CreDtTm'
        ]
    ];

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [array_values($this->attributes()), 'safe'],
        ]);
    }

    public function getType()
    {
        if (!$this->_type) {
            $this->_type = static::TYPE;
        }

        return $this->_type;
    }

    public function getFullType()
    {
        return $this->_fullType;
    }

    /**
     * @return SimpleXMLElement
     */
    public function getRawXml()
    {
        return $this->_xml;
    }

    public function setType($value)
    {
        $this->_type = $value;
    }

    public function setFullType($value)
    {
        $this->_fullType = $value;
    }

    public function loadFromString($data, $isFile = false, $encoding = null)
    {
        if ($isFile) {
            $this->_filePath = $data;
            $data = file_get_contents($data);
        }

        libxml_use_internal_errors(true);

        try {
            $xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_PARSEHUGE);
        } catch (Exception $ex) {
            throw new InvalidValueException('Error while reading xml format: ' . $ex->getMessage());
        }
        if ($xml === false) {
            $libxmlErrors = [];
            foreach(libxml_get_errors() as $error) {
                $libxmlErrors[] = $error->message;
            }
            $this->addError('xml', 'Ошибка загрузки XML : ' . PHP_EOL . join(PHP_EOL, $libxmlErrors));
        } else {
            if ('Document' !== $xml->getName()) {
                $this->addError('xml', 'Неизвестный корневой элемент XML');
            } else if (!$this->parseNamespace(array_values($xml->getNamespaces()))) {
                $this->addError('xml', 'Некорректный namespace');
            }
        }

        $this->_xml = $xml;
        $this->registerXPathNamespaces();

        $this->parseXml($xml);
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        return !$this->hasErrors() && parent::validate($attributeNames, $clearErrors);
    }

    /**
     * Валидация по XSD xml-содержимого модели типа
     * @return bool
     */
    public function validateXSD()
    {
        return ISO20022Helper::validateXSD($this);
    }

    public static function getModelFromString($data, $isFile = false, $encoding = null)
    {
        if ($isFile) {
            $body = file_get_contents($data);
        } else {
            $body = $data;
        }

        /**
         * проверка наличия xml-тэга отключена
         * @see CYB-4505
         */
        //if (!StringHelper::hasXmlHeader($body)) { // не XML
        //    return null;
        //}

        if ($encoding) {
            $body = StringHelper::utf8($body, $encoding);
        }

        libxml_use_internal_errors(true);
        try {
            $xml = simplexml_load_string($body, 'SimpleXMLElement', LIBXML_PARSEHUGE);
            $errors = libxml_get_errors();
            if (count($errors)) {
                Yii::error('XML loading error: ' . var_export($errors, true));
            }
        } catch (Exception $ex) {
            throw new InvalidValueException('Exception while reading XML: ' . $ex->getMessage());
        }

        /*
         * (CYB-4568) Если тип документа - Envelope, то пробуем вытащить из него документ и раскодировать его
         * из base64. После чего меняем xml на тот, что вытащили
         */
        if ($xml && 'Envelope' === $xml->getName())
        {
            $body = base64_decode((string) $xml->body->doc);

            try {
                $xml = simplexml_load_string($body, 'SimpleXMLElement', LIBXML_PARSEHUGE);
                $errors = libxml_get_errors();
                if (count($errors)) {
                    Yii::error('XML loading error: ' . var_export($errors, true));
                }
            } catch (Exception $ex) {
                throw new InvalidValueException('Exception while reading XML: ' . $ex->getMessage());
            }
        }

        if ($xml === false || 'Document' !== $xml->getName()) {
            return null;
        }

        $ns = array_values($xml->getNamespaces());

        if (is_array($ns) && count($ns)) {
            $fullType = str_replace('urn:iso:std:iso:20022:tech:xsd:', '', $ns[0]);

            $result = explode('.', $fullType);

            if (count($result)) {
                $type = array_shift($result);
                if (count($result)) {
                    $type .= '.' . array_shift($result);
                }

                $class = Yii::$app->registry->getTypemodelClass($type);

                if ($class) {
                    $model = new $class();
                    $model->fullType = $fullType;
                    $model->loadFromString($body);

                    return $model;
                }
            }
        }

        return null;
    }

    public function getModelDataAsString($removeXmlDeclaration = true)
    {
        if (empty($this->_xml)) {
            return '';
        }

        $body = StringHelper::fixBOM($this->_xml->asXML());

        if ($removeXmlDeclaration) {
            return StringHelper::removeXmlHeader($body);
        }

        return StringHelper::fixXmlHeader($body);
    }

    protected function parseNamespace($ns)
    {
        if (is_array($ns) && count($ns)) {
            $fullType = str_replace('urn:iso:std:iso:20022:tech:xsd:', '', $ns[0]);

            $result = explode('.', $fullType);

            if (count($result)) {
                $type = array_shift($result);
                if (count($result)) {
                    $type .= '.' . array_shift($result);
                }

                if ($this->isValidType($type)) {
                    $this->_type = static::TYPE ?: $type;
                    $this->_fullType = $fullType;

                    return true;
                }
            }
        }

        $this->addError('xml', 'Неподдерживаемый тип документа');

        return false;
    }

    public function getFilePath()
    {
        return $this->_filePath;
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function getSignatureTemplate($signatureId, $fingerprint, $algo = null, $certBody = null)
    {
        if (empty($this->_xml)) {
            $this->addError('xml', 'Empty XML document');
        }

        libxml_use_internal_errors(true);

        $signatureTemplate = static::getActualSignatureTemplate();
        $signaturePrefix = '';
        /**
        * Анализ структуры документа.
        * Если найдется неймспейс, то префикс в шаблоне заменится на указанный.
        * Если не найдется, то неймспейс будет добавлен с дефолтным префиксом
        */
        foreach ($this->_xml->getDocNamespaces() as $prefix => $uri) {
            if (self::CRYPTOPRO_SIGNATURE_NS === $uri) {
                $signaturePrefix = $prefix;

                break;
            }
        }

        if (empty($signaturePrefix)) {
            if (!$this->_xml->attributes()['xmlns:ds']) {
                $this->_xml->addAttribute('xmlns:xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
            }
        } else if ($signaturePrefix !== self::CRYPTOPRO_SIGNATURE_PREFIX) {
            $signatureTemplate = str_replace(self::CRYPTOPRO_SIGNATURE_PREFIX . ':', $signaturePrefix . ':', $signatureTemplate);
        }

        $signatureId = $this->getPrefixSignature() . $signatureId;

        $signatureTemplate = CryptoProHelper::updateSignatureTemplate(
            $signatureTemplate, $signatureId, $fingerprint, $algo, $certBody
        );

        /**
        * Создание контейнера для подписи
        */
        $rootElement = $this->_xml->children()[0];
        if (!isset($rootElement->SplmtryData)) {
            $rootElement->addChild('SplmtryData');
        }

        if (!isset($rootElement->SplmtryData->Envlp)) {
            $rootElement->SplmtryData->addChild('Envlp');
        }

        if (!isset($rootElement->SplmtryData->Envlp->SgntrSt)) {
            $rootElement->SplmtryData->Envlp->addChild('SgntrSt');
        }

        SimpleXMLHelper::insertAfter(
            new SimpleXMLElement($signatureTemplate),
            $rootElement->SplmtryData->Envlp->SgntrSt
        );

        $xml = $this->_xml->asXML();

        return $xml;
    }

    public function getPrefixSignature()
    {
        return 'id_';
    }

    public function __sleep()
    {
        if (!empty($this->_xml)) {
            $this->_xmlString = $this->_xml->asXML();
            $this->_xml = null;
        }

        return [
            '_xmlString',
            '_fullType',
            '_filePath',
            'sender',
            'receiver',
            'msgId'
        ];
    }

    public function __wakeup()
    {
        $this->init();

        if (!empty($this->_xmlString)) {
            $this->loadFromString($this->_xmlString);
            $this->_xmlString = null;
        }
    }

    protected function parseXml($xml = null)
    {
        $attributes = [];

        foreach(self::$mapTags as $attr => $xpath) {
            if (is_array($xpath)) {
                if (isset($xpath[$this->type])) {
                    $xpath = $xpath[$this->type];
                } else {
                    $xpath = $xpath['default'];
                }
            }

            $result = $xml->xpath($xpath);
            if (!empty($result)) {
                $attributes[$attr] = (string) $result[0];
            }
        }

        $this->setAttributes($attributes);
    }

    private function registerXPathNamespaces(): void
    {
        foreach($this->_xml->getDocNamespaces() as $strPrefix => $strNamespace) {
            if (empty($strPrefix)) {
                $strPrefix = 'a'; //Assign an arbitrary namespace prefix.
            }
            $this->_xml->registerXPathNamespace($strPrefix, $strNamespace);
        }
    }

    public function removeSignatures()
    {
        if ($this->type == Auth024Type::TYPE) {
            unset($this->_xml->PmtRgltryInfNtfctn->SplmtryData);
        } else if ($this->type == Auth025Type::TYPE) {
            unset($this->_xml->CcyCtrlSpprtgDocDlvry->SplmtryData);
        } else if ($this->type == Auth018Type::TYPE) {
            unset($this->_xml->CtrctRegnReq->SplmtryData);
        } else {
            throw new Exception('Failed to find signatures block');
        }

        return true;
    }

    public static function getActualSignatureTemplate()
    {
        $module = Yii::$app->getModule('ISO20022');

        return $module->settings->useCompatibleSigning
                ? self::CRYPTOPRO_SIGNATURE_OLD
                : self::CRYPTOPRO_SIGNATURE;
    }

    public function createFilename()
    {
        return $this->sender . '_' . $this->getFullType() . '_' . $this->msgId;
    }

    public function searchSender()
    {
        return null;
    }

    public function searchReceiver()
    {
        return null;
    }

    public function buildXML()
    {
        return true;
    }

    public static function joinTagsContent(\SimpleXMLElement $parentElement, string $xpath): string
    {
        $tagElements = $parentElement->xpath($xpath);
        return implode('', array_map('strval', $tagElements));
    }

    private function isValidType(string $type): bool
    {
        $isoModuleTypes = array_keys(Yii::$app->registry->getModuleTypes(ISO20022Module::SERVICE_ID));
        $edmModuleTypes = ['camt.052', 'camt.053', 'camt.054'];
        $supportedTypes = array_merge($isoModuleTypes, $edmModuleTypes);
        return in_array($type, $supportedTypes);
    }
}
