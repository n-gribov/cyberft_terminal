<?php
namespace addons\fileact\jobs;

use addons\fileact\helpers\FileActHelper;
use addons\fileact\models\FileActType;
use common\base\DocumentJob;
use common\components\storage\StoredFile;
use common\components\TerminalId;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\StringHelper;
use common\models\CryptoproCert;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\modules\certManager\models\Cert;
use common\settings\AppSettings;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use Resque_Job_DontPerform;
use Yii;
use const PHP_EOL;

/**
 * Export fileact job class
 *
 * @package addons
 * @subpackage fileact
 */
class ExportJob extends DocumentJob
{
    /**
     * @var string $_snlRef SNL reference
     */
    private $_snlRef;

    /**
     * @var StoredFile $_storedBin Stored BIN file
     */
    private $_storedBin;

    /**
     * @var StoredFile $_storedPdu Stored PDU file
     */
    private $_storedPdu;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->_module = Yii::$app->getModule('fileact');
        parent::setUp();

        $this->_storedBin = Yii::$app->storage->get($this->_document->extModel->binStoredFileId);
        if (empty($this->_storedBin)) {
            throw new Resque_Job_DontPerform('Cannot find BIN file');
        }

        $this->_storedPdu = Yii::$app->storage->get($this->_document->extModel->pduStoredFileId);
        if (empty($this->_storedPdu)) {
            throw new Resque_Job_DontPerform('Cannot find PDU file');
        }
    }

    /**
     * @inheritdoc
     */
	public function perform()
	{
        try {
            $this->exportFileAct($this->_cyxDocument);

            $this->log("Document ID {$this->_documentId} exported");
			$this->_document->updateStatus(Document::STATUS_EXPORTED, 'Export');
        } catch (Exception $ex) {
            Yii::warning("Export document ID [{$this->_documentId}] failed. Reason: [{$ex->getMessage()}]");
            $this->_document->updateStatus(Document::STATUS_NOT_EXPORTED, 'Export');
        }
	}

    /**
     * Export FileAct
     *
     * @param CyberXmlDocument $cyxDoc CyberXml document
     * @throws Exception
     */
	public function exportFileAct(CyberXmlDocument $cyxDoc)
	{
        $content = file_get_contents($this->_storedPdu->getRealPath());
        $pos = strpos($content, '<?xml');

        if ($pos === false) {
            throw new Exception('Wrong XML document');
        }

        // Перед тэгом xml идут fileact-specific data, которые нужно отрезать.
        // StringHelper::fixXmlHeader() здесь неприменим.
        $xml = substr($content, $pos);

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xml, LIBXML_PARSEHUGE);

        $ns = $dom->lookupNamespaceUri(FileActType::NAMESPACE_PREFIX);
        $inXpath = new DOMXPath($dom);
        $inXpath->registerNamespace(FileActType::NAMESPACE_PREFIX, $ns);

        $inElement = $dom->getElementsByTagNameNS($ns, 'DataPDU')->item(0);
        if (!$inElement) {
            throw new Exception('IN element error');
        }

        $service = $inXpath->query('//Saa:Service')->item(0);
        if ($service) {
            $service->nodeValue = 'cyberft.fileact';
        }

        $senderDN = $inXpath->query('//Saa:Sender/Saa:DN')->item(0);
        $name = $inXpath->query('//Saa:Sender/Saa:FullName/Saa:X1')->item(0)->nodeValue;
        $senderDN->nodeValue = 'o=' . FileActHelper::extractBIC8($name) . ',o=swift';

        $receiverDN = $inXpath->query('//Saa:Receiver/Saa:DN')->item(0);
        $name = $inXpath->query('//Saa:Receiver/Saa:FullName/Saa:X1')->item(0)->nodeValue;
        $receiverDN->nodeValue = 'o=' . FileActHelper::extractBIC8($name) . ',o=swift';

        $networkInfo = $inXpath->query('//Saa:NetworkInfo')->item(0);
        $priority = $inXpath->query('//Saa:NetworkInfo/Saa:Priority')->item(0);
        if (!$priority) {
            $networkInfo->appendChild(new DOMElement('Priority', 'Normal', $ns));
        }

        $snni = $inXpath->query('//Saa:SWIFTNetNetworkInfo')->item(0);
        if (!$snni) {
            $snni = $dom->createElementNS($ns, 'SWIFTNetNetworkInfo');
            $networkInfo->appendChild($snni);
        }

        $snlId = null;
        $date = date_create_from_format('Y-m-d\TH:i:sT', $cyxDoc->docDate);

        $snlRef = $inXpath->query('//Saa:SNLRef')->item(0);
        if (empty($snlRef)) {

            // SNL[SNLid]-YYYY-MM-DDTHH:MM:SS.procid.digitsZ

            $snlId = $this->getSnlId($cyxDoc->senderId);
            if (!$snlId) {
                throw new Exception('Get SNL ID (SNLRef) error');
            }
            $procid = getmypid();
            $count = str_pad(
                    DocumentHelper::getDayUniqueCount('pdu'),
                    6, '0', STR_PAD_LEFT
            );

            $this->_snlRef = 'SNL' . $snlId . '-' . date('Y-m-d\TH:i:s', $date->getTimestamp()) . '.' . $procid . '.' . $count . 'Z';
            $snni->appendchild(new DOMElement('SNLRef', $this->_snlRef, $ns));
        } else {
            $this->_snlRef = $snlRef->nodeValue;
        }

        $transferRef = '';

        if (!$inXpath->query('//Saa:TransferRef')->item(0)) {
            // SNL[SNLid]D1[timestamp]010042C
            $snlId = (empty($snlId)) ? $snlId = $this->getSnlId($cyxDoc->senderId) : $snlId;
            if (empty($snlId)){
                throw new Exception('Get SNL ID (transferRef) error');
            }

            $transferRef = 'SNL' . $snlId . 'D1' . time() . '010042' . 'C';
            $snni->appendChild(new DOMElement('TransferRef', $transferRef, $ns));
        }

        $body = $inXpath->query('//Saa:Body')->item(0);
        $bodyName = $body->nodeValue;
        $body->nodeValue = $bodyName . '.' . $transferRef . '.out';

        $fileLogicalName = $inXpath->query('//Saa:FileLogicalName')->item(0);
        if ($fileLogicalName) {
            $fileLogicalName->nodeValue = $bodyName;
        } else {
            $inElement->appendChild(new DOMElement('FileLogicalName', $bodyName, $ns));
        }

        $fileStartTime = $inXpath->query('//Saa:FileStartTime')->item(0);
        if (!$fileStartTime) {
            $snni->appendChild(new DOMElement('FileStartTime', date('YmdHis', $date->getTimeStamp()), $ns));
        }

        $fileEndTime = $inXpath->query('//Saa:FileEndTime')->item(0);
        if (!$fileEndTime) {
            $snni->appendChild(new DOMElement('FileEndTime', date('YmdHis', $date->getTimeStamp()), $ns));
        }

        /**
         * CYB-1336
         * Здесь считаем бинарный заголовок для PDU. Он имеет длину 31 байт и состоит из следующих полей:
         * 1 байт - константа 0x1f
         * 6 байт - сумма длин сигнатуры и тела pdu, в текстовом виде, выровнено нулями.
         * 24 байта - сигнатура. Должна считаться как SHA-256, у нас не считается, заполняется 0x0.
         * Длина сигнатуры у нас всегда 24 байта. Значит, к длине pdu просто добавляем 24.
         */
        $pdu = $dom->saveXml();
        $count = str_pad(strlen($pdu) + 24, 6, '0', STR_PAD_LEFT);
        $binData = chr(0x1f) . $count . str_repeat(chr(0), 24);

        $msgId = $inXpath->query('//Saa:MessageIdentifier')->item(0)->nodeValue;

        /**
         * Export receipt to file
         */
        $this->exportReceiptFile(
                $this->exportReceipt($cyxDoc, $msgId, $senderDN->nodeValue, $receiverDN->nodeValue, $body->nodeValue)
        );

        /**
         * Export BIN to file
         */
        $this->exportBinFile($body->nodeValue); //$transferRef);

        /**
         * Export signatures to file
         */
        $this->exportSignatureFile($this->createSignaturesDocument());

        /**
         * Export PDU
         */
        $this->exportPDUFile($binData . $pdu);
	}

    /**
     * Export receipt
     *
     * @param CyberXmlDocument $doc      CyberXml document
     * @param string           $msgId    Message ID
     * @param string           $sender   Sender
     * @param string           $receiver Receiver
     * @param string           $body     Body
     * @return string
     */
	public function exportReceipt(CyberXmlDocument $doc, $msgId, $sender, $receiver, $body)
	{
		$docDate = $doc->docDate;
		$exportDate = date('c');
		$out = <<<OUT
{$exportDate}	       FileAct CyberFT
_______________________________________________________________________________
    ---------------------  Instance Type and Transmission ---------------------
       Notification (Information) of Original received from CyberFT
       Priority      : Normal
       Message Output Reference  : {$body}
    ------------------------------ Message Header -----------------------------
	   FileAct CyberFT Output    : {$msgId}

       Requestor DN              : {$sender}
       Responder DN              : {$receiver}

       SWIFT  Request Reference  : {$this->_snlRef}
    ------------------------------ Interventions ------------------------------
       Creation Time : {$docDate}
       Application   : CyberFT

OUT;

	    return $this->getReceiptSignatures($out, $doc);
	}

    /**
     * Get receipt signatures
     *
     * @param string           $data Receipt data
     * @param CyberXmlDocument $doc  CyberXml document
     * @return string
     */
    private function getReceiptSignatures($data, CyberXmlDocument $doc)
    {
		$mySignVerifier = Yii::$app->xmlsec;

        /**
         * Get list of signatures from CyberXml document
         */
		$allSignatures = $mySignVerifier->locateAllSignatures($doc->getDom());
         if (empty($allSignatures)) {
            return $data;
        }

		$certManager = Yii::$app->getModule('certManager');
		$nameList = [];

        /**
         * Get information about each signatures
         */
        foreach ($allSignatures as $signatureClass => $signaturesList) {
            for ($signatureCnt = 0; $signatureCnt < $signaturesList->length; $signatureCnt++) {
                $signature = $signaturesList->item($signatureCnt);
                $fingerprint = $mySignVerifier->getFingerprint($signature);
                $cert = $certManager->getCertificateByAddress($doc->senderId, $fingerprint);
                if (!empty($cert)) {
                    $nameList[$fingerprint] = $cert->fullName;
                }
            }
        }

        return $this->appendReceiptSignatures($data, $nameList);
    }

    /**
     * Append receipt signatures if it need
     *
     * @param string $data     Receipt data
     * @param array  $nameList List of signatures in document ([fingerprint => name])
     * @return string
     */
    private function appendReceiptSignatures($data, $nameList)
    {
        if (empty($nameList)) {
            return $data;
        }

        $data .= <<<OUT
    ------------------------------ Signatories ------------------------------

OUT;
        foreach($nameList as $fingerprint => $name) {
            $name = str_pad($name, 26, ' ', STR_PAD_RIGHT);
            $data .= "       {$name}: {$fingerprint}" . PHP_EOL;

        }

        return $data;
    }

    /**
     * Get SNL ID
     *
     * @param string $sender Sender
     * @return string|NULL Return SnlID or NULL on error
     */
	private function getSnlId($sender)
	{
		$res = TerminalId::extract($sender);
		$cert = Cert::findOne([
			'participantCode' => $res->participantCode,
			'countryCode' => $res->countryCode,
			'sevenSymbol' => $res->sevenSymbol,
			'delimiter' => $res->delimiter,
			'terminalCode' => $res->terminalCode,
			'participantUnitCode' => $res->participantUnitCode
		]);
		if ($cert) {
			return str_pad($cert->id, 5, '0', STR_PAD_LEFT);
		} else {
			return null;
		}
	}


    /**
     * Create signature XML file
     *
     * @return string
     * @throws Exception
     */
    protected function createSignaturesDocument()
    {
        $document = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?><Document></Document>');

        $cyberFTSignData = $document->addChild('CyberFTSignData');

        $header = $cyberFTSignData->addChild('Header');
        $signaturesElement = $cyberFTSignData->addChild('Signatures');

        $header->addChild('Id', $this->_document->uuid);
        $header->addChild('CreDtTm', \Yii::$app->formatter->asDatetime(time(), 'Y-MM-dd\zzH:mm:ss'));
        $header->addChild('SnlRef', $this->_snlRef);

        $counter = 0;

        $cyberXmlStored = Yii::$app->storage->get($this->_document->getValidStoredFileId());
        $cyberXML = simplexml_load_file($cyberXmlStored->getRealPath(), 'SimpleXMLElement', LIBXML_PARSEHUGE);
        $signedData = $cyberXML->Body->SignedData;

        $certModel = CryptoproCert::getInstance('fileact');
        $certs = $certModel::findAll([
                'status' => CryptoproCert::STATUS_READY,
                'terminalId' => Terminal::getIdByAddress($this->_document->receiver),
                'senderTerminalAddress' => $this->_document->sender,
            ]);
        $verifyCerts = [];

        foreach ($certs as $cert) {
            $verifyCerts[strtoupper($cert->keyId)] = $cert;
        }

        foreach ($signedData->getNamespaces(true) as $prefix => $ns) {
            $signatures = $cyberXML->Body->SignedData->Signatures->children($prefix, true);

            if (empty($signatures)) {
                $signatures = [];
            }

            foreach ($signatures as $value) {
                $cn = false;
                $fingerprint = strtoupper($value->KeyInfo->KeyName);

                if (isset($verifyCerts[$fingerprint])) {
                    $cn = $verifyCerts[$fingerprint]->ownerName;
                    if (empty($cn)) {
                        $meta = openssl_x509_parse($verifyCerts[$fingerprint]->certData);
                        if (isset($meta['subject']['CN'])) {
                            $cn = $meta['subject']['CN'];
                        }
                    }
                }

                if (empty($cn)) {
                    $cn = $fingerprint;
                }

                $signatureElement = $signaturesElement->addChild('Signature');
                $signatureElement->addChild('SignPerson', $cn);
                $signatureElement->addChild('KeyName', $value->KeyInfo->KeyName);
                $signatureElement->addChild('SignType', ++$counter);
            }
        }

        return $document->asXML();
    }

    /**
     * Export PDU file
     *
     * @param string $data PDU data
     * @throws Exception
     */
    protected function exportPDUFile($data)
    {
        $resource = $this->getExportResource($this->_module->config->resourceXml);
        if (empty($resource)) {
            throw new Exception('Failed to get PDU export resource');
        }

        $tempDir = $resource->createDir('temp');

        if (!$tempDir) {
            throw new Exception('Failed to create temp dir in ' . $resource->getPath());
        }

        $fileName = $this->_document->uuid . '.xml';
        $savedTempPath = $tempDir . '/' . $fileName;

        if (false === file_put_contents($savedTempPath, $data)) {
            throw new Exception('Failed to write temp PDU file ' . $savedTempPath);
        }

        $savedPath = $resource->getPath() . '/' . $fileName;

        if (false === rename($savedTempPath, $savedPath)) {
            throw new Exception('Failed to move temp PDU file ' . $savedTempPath);
        }
    }

    /**
     * Export BIN file
     *
     * @param string $fileName
     * @throws Exception
     */
    protected function exportBinFile($fileName)
    {
        $resource = $this->getExportResource($this->_module->config->resourceBin);
        if (empty($resource)) {
            throw new Exception('Failed to get BIN export resource');
        }

        if (false === $resource->putFile($this->_storedBin->getRealPath(), $fileName)) {
            throw new Exception('Failed to save BIN file');
        }
    }

    /**
     * Export receipt file
     *
     * @param string $data Export receipt data
     * @throws Exception
     */
    protected function exportReceiptFile($data)
    {
        $resource = $this->getExportResource($this->_module->config->resourceReceipt);
        if (empty($resource)) {
            throw new Exception('Failed to get receipt export resource');
        }

        if (false === $resource->putData($data, $this->_document->uuid . '.prt')) {
            throw new Exception('Failed to save receipt file');
        }
    }

    /**
     * Export signatures to file
     *
     * @param string $data XML document with signatures
     * @throws Exception
     */
    protected function exportSignatureFile($data)
    {
        $resource = $this->getExportResource($this->_module->config->resourceSignatures);
        if (empty($resource)) {
            throw new Exception('Failed to get signatures export resource');
        }

        if (false === $resource->putData($data, $this->_document->uuid . '.xml')) {
            throw new Exception('Failed to save signature file');
        }
    }

    private function getExportResource($dirId)
    {
        return $this->shouldUseGlobalExportSettings()
            ? Yii::$app->registry->getExportResource($this->_module->serviceId, $dirId)
            : Yii::$app->registry->getTerminalExportResource($this->_module->serviceId, $this->getTerminalAddress(), $dirId);
    }

    private function shouldUseGlobalExportSettings(): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $this->getTerminalAddress());
        return (bool)$terminalSettings->useGlobalExportSettings;
    }

    private function getTerminalAddress()
    {
        return $this->_cyxDocument->receiverId;
    }
}