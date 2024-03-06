<?php
namespace addons\ISO20022\models;

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\traits\WithAttachments;
use addons\ISO20022\models\traits\WithSignature;
use common\base\BaseType;
use common\components\TerminalId;
use common\components\xmlsec\xmlseclibs\XMLSecurityDSig;
use common\helpers\Address;
use common\helpers\CryptoProHelper;
use common\helpers\StringHelper;
use common\helpers\Uuid;
use common\helpers\ZipHelper;
use common\models\listitem\AttachedFile;
use common\models\Terminal;
use common\modules\participant\models\BICDirParticipant;
use Exception;
use SimpleXMLElement;
use Yii;
use yii\helpers\ArrayHelper;

class Auth026Type extends BaseType
{
    use WithAttachments;
    use WithSignature;

    const TYPE = 'auth.026';
    const FULL_TYPE = 'auth.026.001.01';
    const NAMESPACE_PREFIX = 'urn:iso:std:iso:20022:tech:xsd:';
    const DEFAULT_NS_URI = 'urn:iso:std:iso:20022:tech:xsd:auth.026.001.01';

    const CRYPTOPRO_SIGNATURE_PREFIX = 'ds';
    const CRYPTOPRO_SIGNATURE_NS = 'http://www.w3.org/2000/09/xmldsig#';

    public const MAX_EMBEDDED_ATTACHMENT_FILE_SIZE_KB = 10240;

    private $_xml;
    private $_isValid = true;
    private $_type;
    private $_fullType;
    private $_filePath;

    public $sender;
    public $receiver;

    public $numberOfItems;
    public $msgId;
    public $dateCreated;
    public $originalFilename;
    public $fileNames = [];
    public $fileHashes = [];
    public $typeCode;
    public $subject;
    public $descr;
    public $senderTaxId;
    public $embeddedAttachments = [];

    /** @var RosbankEnvelope|null */
    public $rosbankEnvelope;

    private static $mapTags = [
        'numberOfItems' => '/a:Document/a:CcyCtrlReqOrLttr/a:GrpHdr/a:NbOfItms',
        'dateCreated' => '/a:Document/a:CcyCtrlReqOrLttr/a:GrpHdr/a:CreDtTm',
        'msgId' => '/a:Document/a:CcyCtrlReqOrLttr/a:GrpHdr/a:MsgId',
        'subject' => '/a:Document/a:CcyCtrlReqOrLttr/a:ReqOrLttr/a:Sbjt',
        'descr' => '/a:Document/a:CcyCtrlReqOrLttr/a:ReqOrLttr/a:Desc',
        'typeCode' => '/a:Document/a:CcyCtrlReqOrLttr/a:ReqOrLttr/a:Tp',
        'senderTaxId' => "/a:Document/a:CcyCtrlReqOrLttr/a:GrpHdr/a:InitgPty/a:Pty/a:Id/a:OrgId/a:Othr[a:SchmeNm/a:Cd/text()='TXID']/a:Id",
    ];

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [['fileName', 'embeddedAttachments'], 'safe'],
                [array_values($this->attributes()), 'safe'],
            ]);
    }

    public function getType()
    {
        if (!$this->_type) {
            $this->_type = self::TYPE;
        }

        return $this->_type;
    }

    public function getFullType()
    {
        return $this->_fullType ?: static::FULL_TYPE;
    }

    public function setType($value)
    {
        $this->_type = $value;
    }

    public function setFullType($value)
    {
        $this->_fullType = $value;
    }


    // Данная функция используется как сеттер для маппинга параметра
    // fileName (см. $mapTags) в атрибут fileNames[]
    public function setFileName($value)
    {
        if ($value !== null) {
            $this->fileNames = [$value];
        }
    }

    public function loadFromString($data, $isFile = false, $encoding = null)
    {
        $this->_isValid = false;
        $this->_xml = null;

        if ($isFile && is_readable($data)) {
            $data = file_get_contents($data);
        }

        libxml_use_internal_errors(true);
        $xml = new SimpleXMLElement($data);

        if ($xml === false) {
            $this->addError('xml', 'Error loading XML');
        } else {
            if ('Document' !== $xml->getName()) {
                $this->addError('xml', 'Unknown root XML element');
            } else if (!$this->parseNamespace(array_values($xml->getNamespaces()))) {
                $this->addError('xml', 'Incorrect namespace');
            }
        }

        if (!$this->hasErrors()) {
            $this->parseXml($xml);
            $this->_xml = $xml;
            $this->_isValid = true;
        }

        return $this->isValid();
    }

    /**
     * @inheritdoc
     */
    public function getModelDataAsString($removeXmlDeclaration = true)
    {
        if (!$this->_xml) {
            $this->buildXML();
        }

        $body = StringHelper::fixBOM($this->_xml->asXML());

        if ($removeXmlDeclaration) {
            return StringHelper::removeXmlHeader($body);
        } else {
            return StringHelper::fixXmlHeader($body);
        }
    }

    private function parseNamespace($ns)
    {
        if (is_array($ns) && count($ns)) {
            $fullType  = str_replace(self::NAMESPACE_PREFIX, '', $ns[0]);
            $result = explode('.', $fullType);

            if (count($result)) {
                $type = '';

                if (isset($result[0])) {
                    $type .= $result[0];
                }

                if (isset($result[1])) {
                    $type .= '.' . $result[1];
                }

                if (array_key_exists($type, Yii::$app->registry->getModuleTypes(ISO20022Module::SERVICE_ID))) {
                    $this->_type = $type;
                    $this->_fullType = $fullType;

                    return true;
                }
            }
        }

        $this->addError('xml', 'Unsupported document type');

        return false;
    }

    public function isValid()
    {
        return $this->_isValid;
    }

    /**
     * Валидация по XSD xml-содержимого модели типа
     * @return bool
     */
    public function validateXSD()
    {
        return ISO20022Helper::validateXSD($this);
    }

    protected function parseXml($xml)
    {
        foreach($xml->getDocNamespaces() as $strPrefix => $strNamespace) {
            if (empty($strPrefix)) {
                $strPrefix = 'a'; //Assign an arbitrary namespace prefix
            }
            $xml->registerXPathNamespace($strPrefix, $strNamespace);
        }

        $attributes = [];

        foreach(static::$mapTags as $attr => $xpath) {
            $nodes  = $xml->xpath($xpath);
            $attributes[$attr] = $nodes != false ? (string) $nodes[0] : null;
        }
        $attributes['fileNames'] = array_map(
            function($element) {
                return (string)$element;
            },
            $xml->xpath('/a:Document/a:CcyCtrlReqOrLttr/a:ReqOrLttr/a:Attchmnt/a:URL')
        );
        $attributes['embeddedAttachments'] = array_map(
            function($element) {
                return base64_decode((string)$element);
            },
            $xml->xpath('/a:Document/a:CcyCtrlReqOrLttr/a:ReqOrLttr/a:Attchmnt/a:AttchdBinryFile/a:InclBinryObjct')
        );

        $this->setAttributes($attributes);
    }

    public function buildXML()
    {
        $xml = new SimpleXMLElement(
                '<?xml version="1.0" encoding="UTF-8"?>'
                . "\n"
                . '<Document xmlns="' . self::DEFAULT_NS_URI . '" xmlns:ds="http://www.w3.org/2000/09/xmldsig#"></Document>'
        );
        $xml->CcyCtrlReqOrLttr->GrpHdr->MsgId = $this->getMsgId();
        $node = $xml->CcyCtrlReqOrLttr->GrpHdr;
        $node->CreDtTm = date('c', $this->dateCreated);
        $node->NbOfItms = $this->numberOfItems;

        $senderParticipant = $this->findParticipant($this->sender);
        $receiverParticipant = $this->findParticipant($this->receiver);

        if ($senderParticipant !== null) {
            $node->InitgPty->Pty->Nm = $senderParticipant->name;
        }

        $node->InitgPty->Pty->Id->OrgId->Othr[0]->Id = $this->getRemoteSenderId();
        $node->InitgPty->Pty->Id->OrgId->Othr[0]->SchmeNm->Cd = 'BANK';
        $node->InitgPty->Pty->Id->OrgId->Othr[1]->Id = $this->sender;
        $node->InitgPty->Pty->Id->OrgId->Othr[1]->SchmeNm->Prtry = 'CFTBIC';

        if ($this->senderTaxId) {
            $node->InitgPty->Pty->Id->OrgId->Othr[2]->Id = $this->senderTaxId;
            $node->InitgPty->Pty->Id->OrgId->Othr[2]->SchmeNm->Cd = 'TXID';
        }

        if ($receiverParticipant) {
            $node->FwdgAgt->FinInstnId->BICFI = $receiverParticipant->participantBIC;
            $node->FwdgAgt->FinInstnId->Nm = $receiverParticipant->name;
        }

        $xml->CcyCtrlReqOrLttr->ReqOrLttr->ReqOrLttrId = $this->getMsgId();
        $node = $xml->CcyCtrlReqOrLttr->ReqOrLttr;
        $node->Dt = date('Y-m-d', $this->dateCreated);

        if ($senderParticipant !== null) {
            $node->Sndr->Pty->Nm = $senderParticipant->name;
        }

        if ($this->isSwiftAddress($this->sender)) {
            $node->Sndr->Pty->Id->OrgId->AnyBIC = static::extractBIC($this->sender);
        }

        $node->Sndr->Pty->Id->OrgId->Othr->Id = $this->getRemoteSenderId();
        $node->Sndr->Pty->Id->OrgId->Othr->SchmeNm->Cd = 'BANK';

        if ($receiverParticipant !== null) {
            $node->Rcvr->Pty->Nm = $receiverParticipant->name;
        }

        if (!$this->isSwiftAddress($this->receiver)) {
            $node->Rcvr->Pty->Id->OrgId->Othr->Id = $this->receiver;
            $node->Rcvr->Pty->Id->OrgId->Othr->SchmeNm->Prtry = 'CFTBIC';
        } else {
            $node->Rcvr->Pty->Id->OrgId->AnyBIC = static::extractBIC($this->receiver);
        }

        $node->Sbjt = $this->subject;
        $node->Tp = $this->typeCode;
        $node->Desc = $this->descr;
        $node->RspnReqrd = 'false';

        if (!empty($this->fileNames)) {
            if (!empty($this->fileHashes)) {
                foreach (array_combine($this->fileNames, $this->fileHashes) as $fileName => $fileHash) {
                    $att = $node->addChild('Attchmnt');
                    $att->DocTp = 'NONE';
                    $att->DocNb = 0;
                    $att->URL = $fileName;
                    $hash = $att->addChild('LkFileHash');
                    $ref = $hash->addChild('ds:Reference', null, 'http://www.w3.org/2000/09/xmldsig#');
                    $ref->addAttribute('URI', $fileName);
                    $algorithm = XMLSecurityDSig::SHA256;
                    $digest = $ref->addChild('ds:DigestMethod');
                    $digest->addAttribute('Algorithm', $algorithm);
                    $ref->addChild('ds:DigestValue', $fileHash);
                    $att->addChild('AttchdBinryFile');
                }
            } else if (!empty($this->embeddedAttachments)) {
                foreach (array_combine($this->fileNames, $this->embeddedAttachments) as $fileName => $attachmentContent) {
                    $att = $node->addChild('Attchmnt');
                    $att->DocTp = 'NONE';
                    $att->DocNb = 0;
                    $att->URL = $fileName;
                    $fileElement = $att->addChild('AttchdBinryFile');
                    $mimeType = $this->getMimeType($attachmentContent);
                    if ($mimeType && strlen($mimeType) <= 35) { // MIMETp length is restricted in XSD
                        $fileElement->MIMETp = $mimeType;
                    }
                    $fileElement->NcodgTp = 'base64';
                    $fileElement->InclBinryObjct = base64_encode($attachmentContent);
                }

            }
        }

        $this->_type = static::TYPE;
        $this->_fullType = static::FULL_TYPE;
        $this->_xml = $xml;
    }

    private function getMimeType(string $fileContent): ?string
    {
        $filePath = tempnam(sys_get_temp_dir(), '');
        file_put_contents($filePath, $fileContent);
        $mimeType = mime_content_type($filePath) ?: null;
        unlink($filePath);
        return $mimeType;
    }

    protected static function extractBIC($address)
    {
        $terminalId = TerminalId::extract($address);
        if ($terminalId === null) {
            return null;
        }
        return array_reduce(
            ['participantCode', 'countryCode', 'sevenSymbol', 'delimiter', 'participantUnitCode'],
            function ($carry, $part) use ($terminalId) {
                return $carry . $terminalId->$part;
            },
            ''
        );
    }

    protected function findParticipant($address)
    {
        $model = BICDirParticipant::findOne(['participantBIC' => Address::truncateAddress($address)]);
        if (!empty($model)) {
            return $model;
        }

        return null;
    }

    protected function getRemoteSenderId()
    {
        // Значение кода по умолчанию
        $code = '00000';

        // Поиск терминала по его id
        $terminal = Terminal::findOne(['terminalId' => $this->sender]);

        // Получаем значение кода из таблица кодов в системе получателя по терминалу
        if ($terminal) {
            $remoteCode = $terminal->getRemoteTerminalId($this->receiver);

            if ($remoteCode) {
                $code = htmlentities($remoteCode);
            }
        }

        return $code;
    }

    protected function isSwiftAddress($address)
    {
        return strpos($address, '@') === false;
    }

    public function getSearchFields()
    {
        return [];
    }

    public function getSignatureTemplate($signatureId, $fingerprint, $algo = null, $certBody = null)
    {
        if ($this->_isValid === false) {
            $this->addError('xml', 'Некорректный XML документ');
        }

        if (empty($this->_xml)) {
            $this->addError('xml', 'XML документ пуст');
        }

        libxml_use_internal_errors(true);

        /**
        * Анализ структуры документа.
        * Если найдется неймспейс, то префикс в шаблоне заменится на указанный.
        * Если не найдется, то неймспейс будет добавлен с дефолтным префиксом
        */
        $signaturePrefix = '';

        foreach ($this->_xml->getDocNamespaces() as $prefix => $uri) {
            if (self::CRYPTOPRO_SIGNATURE_NS === $uri) {
                $signaturePrefix = $prefix;

                break;
            }
        }

        $signatureId = $this->getPrefixSignature() . $signatureId;

        /** @todo refactoring */
        $signatureTemplate = ISO20022Type::getActualSignatureTemplate();
        $signatureTemplate = CryptoProHelper::updateSignatureTemplate($signatureTemplate, $signatureId, $fingerprint, $algo, $certBody);

        if (empty($signaturePrefix)) {
            $this->_xml->addAttribute('xmlns:xmlns:default', 'http://www.w3.org/2000/09/xmldsig#');
        } else if ($signaturePrefix!== self::CRYPTOPRO_SIGNATURE_PREFIX) {
            $signatureTemplate = str_replace(self::CRYPTOPRO_SIGNATURE_PREFIX . ':', $signaturePrefix . ':', $signatureTemplate);
        }

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

        $this->appendSimpleXML(
            $rootElement->SplmtryData->Envlp->SgntrSt,
            new \SimpleXMLElement($signatureTemplate)
        );

        $out = $this->_xml->asXML();

        return $out;
    }

    public function getPrefixSignature()
    {
        return 'id_';
    }

    private function appendSimpleXML(\SimpleXMLElement $to, \SimpleXMLElement $from)
    {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }

    public function getFilePath()
    {
        return $this->_filePath;
    }

    public function getAttachmentNodes()
    {
        $xml = $this->getRawXml();

        return $xml->CcyCtrlReqOrLttr->ReqOrLttr->Attchmnt;
    }

    public function getAttachedFileList()
    {
        $attachedFiles = [];

        $nodes = $this->getAttachmentNodes();

        foreach ($nodes as $node) {
            $fileName = (string)$node->URL;
            $fixedName = $fileName;
            if (substr($fileName, 0, 7) == 'attach_') {
                $fixedName = substr($fileName, 7);
            }

            $attachedFiles[] = new AttachedFile([
                'name' => $fixedName,
                'path' => $fileName
            ]);
        }

        return $attachedFiles;
    }

    public function createFilename()
    {
        return $this->sender . '_' . $this->getFullType() . '_' . $this->getMsgId();
    }

    public function getMsgId()
    {
        if (empty($this->msgId)) {
            $this->msgId = str_replace('-', '', Uuid::generate());
        }

        return $this->msgId;
    }

    public function getRawXml()
    {
        return $this->_xml;
    }

    public function addEmbeddedAttachment(string $fileName, string $filePath): void
    {
        $fileSize = filesize($filePath);
        if ($fileSize > self::MAX_EMBEDDED_ATTACHMENT_FILE_SIZE_KB * 1024) {
            throw new Exception('Embedded attachment file cannot exceed '
                    . self::MAX_EMBEDDED_ATTACHMENT_FILE_SIZE_KB . ' KB');
        }
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new Exception("Failed to read attachment from $filePath");
        }
        $this->fileNames[] = $fileName;
        $this->embeddedAttachments[] = $fileContent;
    }

    public function extractAttachments(string $targetDirPath): array
    {
        $attachedFiles = [];

        function extractAttachment(string $filePath, string $content): void
        {
            $saveResult = file_put_contents($filePath, $content);
            if ($saveResult === false) {
                throw new Exception("Failed to save file to $filePath");
            }
        }

        function normalizeFileName(string $fileName): string
        {
            return preg_replace('/^attach_/', '', $fileName);
        }

        if ($this->useZipContent) {
            $zip = ZipHelper::createArchiveFileZipFromString($this->zipContent);
            $filesFromZip = $zip->getFileList('cp866');
            $nodes = $this->getAttachmentNodes();
            foreach ($nodes as $node) {
                $fileName = (string)$node->URL;
                $fileIndex = array_search($fileName, $filesFromZip);
                if ($fileIndex === false) {
                    throw new Exception("File $fileName is not found in zip archive");
                }
                $content = $zip->getFromIndex($fileIndex);
                $filePath = "$targetDirPath/$fileIndex";
                extractAttachment($filePath, $content);
                $attachedFiles[normalizeFileName($fileName)] = $filePath;
            }
            $zip->purge();
        } else {
            foreach ($this->fileNames as $fileIndex => $fileName) {
                $content = $this->embeddedAttachments[$fileIndex];
                $filePath = "$targetDirPath/$fileIndex";
                extractAttachment($filePath, $content);
                $attachedFiles[normalizeFileName($fileName)] = $filePath;
            }
        }
        return $attachedFiles;
    }
}
