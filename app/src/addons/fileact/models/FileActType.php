<?php

namespace addons\fileact\models;

use common\base\BaseType;
use DOMDocument;
use DOMXPath;
use Yii;
use yii\helpers\ArrayHelper;

class FileActType extends BaseType {

    const TYPE = 'FileAct';
    const NAMESPACE_PREFIX = 'Saa';
    const NS = 'urn:swift:saa:xsd:saa.2.0';
    const SIGNATURE = '<?xml';

//    const CRYPTOPRO_SIGNATURE_PREFIX = 'cpsign';
//    const CRYPTOPRO_SIGNATURE_NS = 'http://www.w3.org/2000/09/xmldsig#';
//    const SIGNATURE_TEMPLATE =
//        '<cpsign:Signature xmlns="http://www.w3.org/2000/09/xmldsig#" Id="{SIGNATURE_ID}">
//            <cpsign:SignedInfo>
//                <cpsign:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
//                <cpsign:SignatureMethod Algorithm="{SIGNATURE_ALGO}"/>
//                <cpsign:Reference URI="#{REFERENCE_ID}">
//                    <cpsign:DigestMethod Algorithm="{DIGEST_ALGO}"/>
//                    <cpsign:DigestValue></cpsign:DigestValue>
//                </cpsign:Reference>
//            </cpsign:SignedInfo>
//            <cpsign:SignatureValue></cpsign:SignatureValue>
//            <cpsign:KeyInfo>
//                <cpsign:X509Data>
//                <cpsign:X509SubjectName>{SUBJECTNAME}</cpsign:X509SubjectName>
//                <cpsign:X509Certificate>{CERTIFICATE}</cpsign:X509Certificate>
//                </cpsign:X509Data>
//                <cpsign:KeyName>{FINGERPRINT}</cpsign:KeyName>
//            </cpsign:KeyInfo>
//        </cpsign:Signature>';

    private $_attributeXPaths = [
        'sender' => '/Saa:DataPDU/Saa:Header/Saa:Message/Saa:Sender/Saa:FullName/Saa:X1',
        'receiver' => '/Saa:DataPDU/Saa:Header/Saa:Message/Saa:Receiver/Saa:FullName/Saa:X1',
        'senderReference' => '/Saa:DataPDU/Saa:Header/Saa:Message/Saa:SenderReference',
        'file' => '/Saa:DataPDU/Saa:Body',
    ];
    private $_pduAttributes = [];
    private $_dom;
    private $_xpath;
    public $sender;
    public $recipient;
    public $pduStoredFileId;
    public $binStoredFileId;
    public $zipStoredFileId;
    public $binFileName;
    public $senderReference;
    public $uuid;

    public function rules() {
        return ArrayHelper::merge(parent::rules(),
            [[array_values($this->attributes()), 'safe']]
        );
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * @inheritdoc
     */
    public function getModelDataAsString()
    {
        $zipData = $this->getZipAsString();
        if ($zipData === false) {
            \Yii::warning('Error getting zip content');

            return null;
        }

        return $zipData;
    }

    /**
     * Get Zip as binary
     *
     * @return mixed Return binary zip or false
     */
    protected function getZipAsString()
    {
        if (is_null($this->zipStoredFileId)) {
            return '';
        }

        return Yii::$app->storage->get($this->zipStoredFileId)->data;
    }

    /**
     * Загружает DOM из файла PDU, отрезая бинарный заголовок
     * Готовит к использованию атрибуты sender, receiver, file
     * @param type $pduPath
     * @return boolean
     */
    public function loadHeader($pduPath)
    {
        $body = file_get_contents($pduPath);

        if (empty($body)) {
            return false;
        }

        if (($pos = strpos($body, static::SIGNATURE)) === false) {
            return false;
        }

        $this->_dom = new DOMDocument();
        $this->_dom->loadXML(substr($body, $pos), LIBXML_PARSEHUGE);
        $this->_xpath = new DOMXPath($this->_dom);
        $this->_xpath->registerNamespace(static::NAMESPACE_PREFIX, static::NS);

        $this->_pduAttributes = [];

        foreach ($this->_attributeXPaths as $attrName => $attrXPath) {
            $nodes = $this->_xpath->query($attrXPath);
            if ($nodes->length) {
                $this->_pduAttributes[$attrName] = $nodes->item(0)->nodeValue;
            }
        }

        if (!count($this->_pduAttributes)) {
            return false;
        }

        if (!empty($this->_pduAttributes['file'])) {
            $this->binFileName = $this->_pduAttributes['file'];
        }

        if (!empty($this->_pduAttributes['senderReference'])) {
            $this->senderReference = $this->_pduAttributes['senderReference'];
        }

        return true;
    }

    /**
     * Возвращает массив атрибутов Fileact: sender, receiver, file
     * @return array|false
     */
    public function getPduAttributes()
    {
        if (empty($this->_dom)) {
            return false;
        }

        return $this->_pduAttributes;
    }

    public function getPduAttribute($name)
    {
        if (!isset($this->_pduAttributes[$name])) {
            return null;
        }

        return $this->_pduAttributes[$name];
    }

    public function setPduAttribute($name, $value)
    {
        if (isset($this->_pduAttributes[$name])) {
            $this->_pduAttributes[$name] = $value;
            $attrXPath = $this->_pduAttributes[$name];
            $nodes = $this->_xpath->query($attrXPath);
            if ($nodes->length) {
                $nodes->item(0)->nodeValue = $value;

                return true;
            }
        }

        return false;
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return array|bool
     */
    public function getSearchFields()
    {
        return [
            'sender' => $this->sender,
            'receiver' => $this->recipient,
        ];
    }

}
