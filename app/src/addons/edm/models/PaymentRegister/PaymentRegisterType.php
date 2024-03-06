<?php

namespace addons\edm\models\PaymentRegister;

use addons\edm\models\PaymentOrder\PaymentOrderType;
use common\base\BaseType;
use common\base\interfaces\SignableType;
use common\components\xmlsec\xmlseclibs\XMLSecurityDSig;
use common\components\xmlsec\xmlseclibs\XMLSecurityKey;
use common\helpers\SigningHelper;
use common\helpers\StringHelper;
use common\helpers\Uuid;
use common\modules\certManager\components\ssl\X509FileModel;
use Exception;
use SimpleXMLElement;

class PaymentRegisterType extends BaseType implements SignableType
{
    const TYPE = 'PaymentRegister';

    private $_registryId;

    private $_paymentOrders = [];
    private $_attachmentsZipFileBinData;
    private $_sum = 0;
    private $_count = 0;
    private $_currency = 'RUB';
    private $_date;

    private $_xml;
    private $_xmlString;

    public $sender;
    public $recipient;
    public $comment;
    public $storedRegistryId;

    public function init()
    {
        parent::init();

        //$this->_registryId = Uuid::generate();
        $this->_date = date('c');
    }

    public function setCurrency($value)
    {
        $this->_currency = $value;
    }

    public function getCurrency()
    {
        return $this->_currency;
    }

    public function getCount()
    {
        return $this->_count;
    }

    public function getSum()
    {
        return $this->_sum;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function getRegistryId()
    {
        return $this->_registryId;
    }

    public function addPaymentOrders($idList = [])
    {
        foreach ($idList as $id) {
            if (!isset($this->_paymentOrders[$id])) {
                if ($paymentOrderModel = PaymentRegisterPaymentOrder::findOne(['id' => $id])) {

                    $this->_sum = $this->_sum + $paymentOrderModel->sum;
                    $this->_count++;

                    $this->_paymentOrders[$id] = [
                        'id' => Uuid::generate(),
                        'body' => $paymentOrderModel->body,
                        'sum' => $paymentOrderModel->sum,
                    ];
                } else {
                    $this->addError('paymentOrder', 'Не удалось найти платежное поручение с ID ' . $id);

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return PaymentOrderType[]
     */
    public function getPaymentOrders()
    {
        $paymentOrders = [];

        foreach ($this->_paymentOrders as $paymentOrder) {
            if (isset($paymentOrder['typeModel']) && $paymentOrder['typeModel'] instanceof PaymentOrderType) {
                $paymentOrders[] = $paymentOrder['typeModel'];
            } else {
                $typeModel = new PaymentOrderType();
                $typeModel->loadFromString($paymentOrder['body']);

                $paymentOrders[] = $typeModel;
            }
        }

        return $paymentOrders;
    }

    public function getPaymentOrderIds()
    {
        $idList = [];

        foreach ($this->_paymentOrders as $paymentOrder) {
            if (isset($paymentOrder['id'])) {
                $idList[] = $paymentOrder['id'];
            }
        }

        return $idList;
    }

    public function deletePaymentOrder($id)
    {
        if (isset($this->_paymentOrders[$id])) {

            $this->_sum = $this->_sum - $this->_paymentOrders[$id]['sum'];
            $this->_count--;

            unset($this->_paymentOrders[$id]);

            return true;
        }

        $this->addError('paymentOrder', 'Ошибка удаления из реестра платежного поручения с ID '.$id);

        return false;
    }

    public function addZippedAttachment($zipFilePath)
    {
        if (!empty($zipFilePath) && is_readable($zipFilePath)) {
            $this->_attachmentsZipFileBinData = file_get_contents($zipFilePath);

            if (false === $this->_attachmentsZipFileBinData) {
                $this->addError('attachment', 'Ошибка получения данных zip файла "{$zipFilePath}"');

                return false;
            }
        }

        return true;
    }

    public function getAttachmentAsZippedBinData()
    {
        return (!empty($this->_attachmentsZipFileBinData) ? $this->_attachmentsZipFileBinData : null);
    }

    public function getModelDataAsString($removeXmlDeclaration = true)
    {
        if (empty($this->_xml)) {
            // Сформировать XML
            $this->buildXml();
        }

        $body = StringHelper::fixBOM($this->_xml->saveXML());

        if (empty($body)) {
            return '';
        }

        //if ($removeXmlDeclaration) {
            return trim(preg_replace('/^.*?\<\?xml.*\?>/uim', '', $body));
        //}

        //return StringHelper::fixXmlHeader($body);
    }

    public function loadFromString($data, $isFile = false, $encoding = null)
    {
        if ($isFile && is_readable($data)) {
            $data = file_get_contents($data);
        }

        libxml_use_internal_errors(true);
        $this->_xml = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_PARSEHUGE);

        if ($this->_xml === false) {
            $this->addError('xml', 'Error loading XML');
            return false;
        } else {
            if ('PaymentRegister' !== $this->_xml->getName()) {
                $this->addError('xml', 'Unknown XML root element');
                return false;
            } else {
                $this->parseXml();
            }
        }

        return true;
    }

    private function parseXml()
    {
        $this->_registryId = (string) $this->_xml->Header->RegisterId;
        $this->_date = (string) $this->_xml->Header->RegisterDate;
        $this->_count = (string) $this->_xml->Header->PaymentCount;
        $this->_sum = (string) $this->_xml->Header->PaymentSum;
        $this->_currency = (string) $this->_xml->Header->PaymentSum['Currency'];
        $this->comment = (string) $this->_xml->Header->RegisterDetails->Comment;

        $this->_paymentOrders = [];

        if ($this->_xml->Content->RawData) {

            foreach ($this->_xml->Content->RawData as $paymentOrder) {
                /**
                 * Ранее платежки формировались с ошибкой: body был в utf8, а charSet у RawData был cp1251
                 * Поэтому ориентироваться на charSet в текущей ситуации мы не можем
                 * ловим эксепшн, если body уже был в utf8 (платежка, сформированная по-старому)
                 */
                try {
                    $body = base64_decode((string) $paymentOrder);
                    $body = iconv('cp1251', 'UTF-8', $body);
                } catch (Exception $ex) {
                     // do nothing
                }

                $typeModel = new PaymentOrderType();
                $typeModel->loadFromString($body);

                $this->_paymentOrders[] = [
                    'body' => $body,
                    'typeModel' => $typeModel,
                    'sum' => $typeModel->sum,
                ];
            }

        } else {
// Что за странная хрень с if-else?
            $xml = $this->_xml;
            $content = $xml->Content;
            $ns = $content->getNamespaces(true);

            if (!isset($ns['data'])) {

                return;
            }

            $rawData = $content->children($ns['data'])->RawData;

            $count = $rawData->count();

            for ($i = 0; $i < $count; $i++) {

                $paymentOrder = $rawData[$i];

                /**
                 * Ранее платежки формировались с ошибкой: body был в utf8, а charSet у RawData был cp1251
                 * Поэтому ориентироваться на charSet в текущей ситуации мы не можем
                 * ловим эксепшн, если body уже был в utf8 (платежка, сформированная по-старому)
                 */
                try {
                    $body = base64_decode((string) $paymentOrder);
                    $body = iconv('cp1251', 'UTF-8', $body);
                } catch (Exception $ex) {
                    // do nothing
                }

                $typeModel = new PaymentOrderType();
                $typeModel->loadFromString($body);

                $this->_paymentOrders[] = [
                    'body' => $body,
                    'typeModel' => $typeModel,
                    'sum' => $typeModel->sum,
                ];
            }
        }

        if (isset($this->_xml->Attachments)) {
            if (isset($this->_xml->Attachments->Attachment->Content->RawData)) {
                $this->_attachmentsZipFileBinData = base64_decode((string)$this->_xml->Attachments->Attachment->Content->RawData);
            }
        } else {
            $xml = $this->_xml;
            $content = $xml->Content;
            $ns = $content->getNamespaces(true);

            $attach = $content->children($ns['data'])->Attachments;

            $count = $attach->count();

            for ($i = 0; $i < $count; $i++) {
                $this->_attachmentsZipFileBinData = base64_decode((string)$attach[$i]->Attachment->Content->RawData);
            }

        }
    }

    public function injectSignature($signatureValue, $certBody)
    {
        $x509Info = X509FileModel::loadData($certBody);
        $fingerprint = $x509Info->fingerprint;
        $certBody = \common\helpers\CertsHelper::linearize($certBody);

        // Hack for backward compatibility with cyberft-sign service
//        if (X509FileModel::isCertificate($certBody)) {
//            $x509Info = X509FileModel::loadData($certBody);
//            $fingerprint = $x509Info->fingerprint;
//        } else {
//            $fingerprint = $certBody;
//        }

        $signatures = $this->_xml->addChild('Signatures:data:Signatures');
        $signatures->addAttribute('xmlns:xmlns:data','http://cyberft.ru/xsd/cftdata.02');

        $signature = $signatures->addChild('Signature:ds:Signature');
        $signature->addAttribute('xmlns:xmlns:ds','http://www.w3.org/2000/09/xmldsig#');

        $toDom = dom_import_simplexml($signature);
        $fromDom = dom_import_simplexml(new SimpleXMLElement($this->getSignedInfo()));
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));

        $signature->addChild('xmlns:ds:SignatureValue', $signatureValue);
        $keyInfo = $signature->addChild('xmlns:ds:KeyInfo');
        $x509Data = $keyInfo->addChild('xmlns:ds:X509Data');
        $x509Data->addChild('xmlns:ds:X509SubjectName', $x509Info->subjectString);
        $x509Data->addChild('xmlns:ds:X509Certificate', $certBody);
        $keyInfo->addChild('xmlns:ds:KeyName', $fingerprint);

        // Signing time
        $object = $signature->addChild('Object:ds:Object');
        $qualifyingProperties = $object->addChild('QualifyingProperties:ds:QualifyingProperties');
        $signedProperties = $qualifyingProperties->addChild('SignedProperties:ds:SignedProperties');
        $signedSignatureProperties = $signedProperties->addChild('SignedSignatureProperties:ds:SignedSignatureProperties');
        $signedSignatureProperties->addChild('xmlns:ds:SigningTime', date('c'));

        return true;
    }

    public function getSignaturesTemplate()
    {
        if (!$this->_xml) {
            // Сформировать XML
            $this->buildXml();
        }

        $signedInfo = $this->_xml->Signatures->Signature->SignedInfo;

        return $signedInfo->asXML();
    }

    public function getSignaturesList()
    {
        if (!$this->_xml) {
            // Сформировать XML
            $this->buildXml();
        }

        return SigningHelper::getUserSignaturesList($this->_xml);
    }

    public function getSignedInfo(?string $signerCertificate = null)
    {
        $hashAlgo = 'sha256';

        $xml = $this->_xml;

        $header = $xml->Header->asXml();
        $content = $xml->Content->asXml();

        $namespaces = $xml->getNamespaces(true);

        $attachments = $xml->children($namespaces['data'])->Attachments;
        $atr = $attachments->attributes();

        $ids = [
            [
                'id' => $this->_xml->Header['Id'],
                'hash' => hash($hashAlgo, $header)
            ],
            [
                'id' => $this->_xml->Content['Id'],
                'hash' => hash($hashAlgo, $content)
            ],
            [
                'id' => $atr['Id'],
                'hash' => hash($hashAlgo, $attachments)
            ],
        ];

        $signedInfo = new SimpleXMLElement('<ds:SignedInfo/>');

        $canMethod = $signedInfo->addChild('xmlns:ds:CanonicalizationMethod');
        $canMethod->addAttribute('Algorithm', 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315');

        $signMethod = $signedInfo->addChild('xmlns:ds:SignatureMethod');
        $signMethod->addAttribute('Algorithm', 'http://www.w3.org/2000/09/xmldsig#rsa-sha1');

        // Время подписания личной подписью
        //$signingTime = $signedInfo->addChild('xmlns:ds:SigningTime', date('c'));

        foreach($ids as $id) {
            $reference = $signedInfo->addChild('xmlns:ds:Reference');
            $reference->addAttribute('URI', '#' . $id['id']);

            $digestMethod = $reference->addChild('xmlns:ds:DigestMethod');
            $digestMethod->addAttribute('Algorithm', XMLSecurityDSig::SHA256);

            $digestValue = $reference->addChild('xmlns:ds:DigestValue', $id['hash']);
        }

        $data = preg_replace("/^<\?xml[^\?]+\?>/", '' , $signedInfo->saveXML());

        return trim($data);
    }

    private function buildXml()
    {
        if (empty($this->_registryId)) {
            $this->_registryId = Uuid::generate();
        }

        $xml = new SimpleXMLElement('<PaymentRegister xmlns="http://cyberft.ru/xsd/edm.02"></PaymentRegister>');

        $xml->Header['Id'] = 'id' . Uuid::generate();
        $xml->Header->RegisterId = $this->_registryId;
        $xml->Header->RegisterDate = $this->_date;
        $xml->Header->PaymentCount = $this->_count;
        $xml->Header->PaymentSum = $this->_sum;
        $xml->Header->PaymentSum['currency'] = $this->_currency;
        $xml->Header->RegisterDetails->Comment = $this->comment;

        /*
         * Блок с платежками
         */
        $content = $xml->addChild('Content');
        $content->addAttribute('Id', 'id' . Uuid::generate());
        $content->addAttribute('format', '1C');

        /*
         * Блок с вложениями
         */
        $attachments = $xml->addChild('xmlns:data:Attachments');
        $attachments['Id'] = 'id' . Uuid::generate();
        $attachments->addAttribute('xmlns:xmlns:data', 'http://cyberft.ru/xsd/cftdata.02');

        /*
         * Блок с подписью
         */
        foreach ($this->_paymentOrders as $key => $value) {
            $this->buildXmlContent($value, $content);
        }

        if (!empty($this->_attachmentsZipFileBinData)) {
            $this->buildXmlAttachment($attachments, $signatures);
        }

        $this->_xml = $xml;
    }

    private function buildXmlContent($data, &$xml)
    {
        $rawData = $xml->addChild('xmlns:data:RawData', base64_encode(iconv('utf-8', 'cp1251', $data['body'])));
        $rawData->addAttribute('xmlns:xmlns:data', 'http://cyberft.ru/xsd/cftdata.02');
        $rawData->addAttribute('mimeType', 'application/text');
        $rawData->addAttribute('charSet', 'windows-1251');
        $rawData->addAttribute('encoding', 'base64');
        $rawData->addAttribute('filename', $data['id'] . '.txt');
        $rawData->addAttribute('Id', 'id' . Uuid::generate());
    }

    private function buildXmlAttachment(&$attachments, &$signatures)
    {
        $id = Uuid::generate();

        $attachment = $attachments->addChild('Attachment');

        $attachment->DocNum = $this->id;
        $attachment->DocDate = $this->_date;
        $attachment->DocType = null;

        $attachment->Content->RawData = base64_encode($this->_attachmentsZipFileBinData);
        $attachment->Content->RawData->addAttribute('xmlns:content', 'http://cyberft.ru/xsd/cftdata.02', 'http://cyberft.ru/xsd/cftdata.02');
        $attachment->Content->RawData['Id'] = 'id' . $id;
        $attachment->Content->RawData['filename'] = $id . '.zip';
        $attachment->Content->RawData['mimeType'] = 'application/zip';

        $this->buildXmlSignatures($id, $this->_attachmentsZipFileBinData, $signatures);
    }

    private function buildXmlSignatures($id, $body, &$signedInfo)
    {
        $reference = $signedInfo->addChild('Reference');
        $reference->addAttribute('URI', '#id' . $id);

        $reference->DigestMethod['Algorithm'] = XMLSecurityKey::RSA_SHA256;

        $reference->DigestValue = hash('sha256', $body);
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function __sleep()
    {
        if (!empty($this->_xml)) {
            $this->_xmlString = $this->_xml->saveXML();
            $this->_xml = null;
        }

        return [
            '_registryId',
            '_paymentOrders',
            '_attachmentsZipFileBinData',
            '_sum',
            '_count',
            '_currency',
            '_date',
            '_xmlString',
            'sender',
            'recipient',
            'comment',
            'storedRegistryId',
        ];
    }

    public function getAccountNumber()
    {
        $accountNumber = '';
        $paymentOrders = $this->getPaymentOrders();
        if (!empty($paymentOrders) && is_array($paymentOrders)) {
            $paymentOrder = reset($paymentOrders);
            $accountNumber = $paymentOrder->payerAccount;
        }

        return $accountNumber;
    }

    public function getPayerName()
    {
        $payerName = '';
        $paymentOrders = $this->getPaymentOrders();
        if (!empty($paymentOrders) && is_array($paymentOrders)) {
            $paymentOrder = reset($paymentOrders);
            $payerName = $paymentOrder->payerName;
        }

        return $payerName;
    }

    public function __wakeup()
    {
        if (!empty($this->_xmlString)) {
            $this->_xml = simplexml_load_string($this->_xmlString, 'SimpleXMLElement', LIBXML_PARSEHUGE);
            $this->_xmlString = null;
        }
    }

}