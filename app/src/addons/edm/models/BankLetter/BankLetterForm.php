<?php

namespace addons\edm\models\BankLetter;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\helpers\RosbankHelper;
use addons\ISO20022\models\Auth026Type;
use common\base\BaseType;
use common\base\Model;
use common\components\storage\StoredFile;
use common\document\Document;
use common\helpers\Address;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\helpers\vtb\VTBHelper;
use common\helpers\vtb\VTBLetterHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\listitem\AttachedFile;
use common\models\Terminal;
use common\models\User;
use common\models\UserTerminal;
use common\models\vtbxml\documents\BSDocumentAttachment;
use common\models\vtbxml\documents\FreeClientDoc;
use common\models\vtbxml\service\SignInfo;
use common\modules\participant\models\BICDirParticipant;
use DateTime;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;
use Yii;
use yii\helpers\ArrayHelper;

class BankLetterForm extends Model
{
    private const MAX_VTB_DOCUMENT_ATTACHMENT_FILE_SIZE_KB = 10240;

    /** @var User */
    public $user;

    public $documentId;
    public $senderTerminalId;
    public $receiverBankBik;
    public $subject;
    public $message;
    public $isoMessageTypeCode;
    public $vtbMessageTypeCode;

    /** @var AttachedFileSession[] */
    public $attachedFiles = [];
    public $attachedFilesJson = '[]';
    public $attachPaths = [];

    public function rules()
    {
        return [
            [['senderTerminalId', 'receiverBankBik', 'subject', 'message'], 'required'],
            [['senderTerminalId', 'receiverBankBik', 'subject', 'message', 'isoMessageTypeCode', 'vtbMessageTypeCode', 'documentId'], 'safe'],
            ['attachedFilesJson', 'validateAttachmentSize'],
            ['attachedFilesJson', 'validateAttachmentsCount'],
            [['senderTerminalId', 'vtbMessageTypeCode', 'documentId'], 'integer'],
            [['receiverBankBik', 'subject', 'message', 'attachedFilesJson'], 'string'],
            ['senderTerminalId', 'validateSenderTerminalId'],
            ['receiverBankBik', 'validateReceiverBankBik'],
            ['isoMessageTypeCode', 'string', 'length' => 4],
            ['subject', 'validateSubjectLength'],
            ['message', 'validateMessageLength'],
            ['vtbMessageTypeCode', 'required', 'when' => function (self $model) {
                return $model->isInVtbFormat();
            }],
            ['isoMessageTypeCode', 'required', 'when' => function (self $model) {
                return !$model->isInVtbFormat();
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'senderTerminalId'   => Yii::t('edm', 'Sender'),
            'receiverBankBik'    => Yii::t('edm', 'Receiver bank'),
            'subject'            => Yii::t('edm', 'Subject'),
            'message'            => Yii::t('edm', 'Message text'),
            'isoMessageTypeCode' => Yii::t('edm', 'Request type'),
            'vtbMessageTypeCode' => Yii::t('edm', 'Request type'),
            'attachedFiles'      => Yii::t('edm', 'Attached files'),
        ];
    }

    public function fields()
    {
        return [
            'documentId',
            'senderTerminalId',
            'receiverBankBik',
            'subject',
            'message',
            'isoMessageTypeCode',
            'vtbMessageTypeCode',
            'attachedFilesJson',
        ];
    }

    public static function fromDocument(User $user, Document $document): self
    {
        $cyxDoc = CyberXmlDocument::read($document->getValidStoredFileId());
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        $tempResource = Yii::$app->registry->getTempResource(EdmModule::SERVICE_ID);
        $tmpDirPath = $tempResource->createDir(uniqid('bank_letter_', true));
        if (VTBHelper::isVTBDocument($document)) {
            $attachments = VTBLetterHelper::extractAttachments($typeModel, $tmpDirPath);
        } elseif ($typeModel instanceof Auth026Type) {
            $attachments = $typeModel->extractAttachments($tmpDirPath);
        } else {
            throw new InvalidArgumentException("Unsupported document type: {$typeModel->getType()}");
        }

        $attachedFiles = array_map(
            function ($fileName, $filePath) {
                return AttachedFile::createFromFile($fileName, $filePath);
            },
            array_keys($attachments),
            $attachments
        );

        (new Filesystem())->remove($tmpDirPath);

        $viewModel = BankLetterViewModel::create($document);
        return new self([
            'user'               => $user,
            'documentId'         => $document->id,
            'senderTerminalId'   => $document->terminalId,
            'receiverBankBik'    => EdmHelper::isBankAvailableToUser($viewModel->receiverBankBik, $user) ? $viewModel->receiverBankBik : null,
            'subject'            => $viewModel->subject,
            'message'            => $viewModel->message,
            'isoMessageTypeCode' => $viewModel->isoMessageTypeCode,
            'vtbMessageTypeCode' => $viewModel->vtbMessageTypeCode,
            'attachedFilesJson'  => AttachedFile::listToJson($attachedFiles),
        ]);
    }

    public function getAvailableSenderTerminals()
    {
        return array_map(
            function ($terminalId) {
                return Terminal::findOne($terminalId);
            },
            $this->getAvailableSenderTerminalsIds()
        );
    }

    /**
     * @return DictBank[]
     */
    public function getAvailableReceiverBanks(): array
    {
        if ($this->user === null) {
            return [];
        }

        return EdmHelper::getBanksAvailableToUser($this->user);
    }

    public function validateSenderTerminalId($attribute, $params = [])
    {
        if (!in_array($this->senderTerminalId, $this->getAvailableSenderTerminalsIds())) {
            $this->addError($attribute, Yii::t('edm', 'You cannot create document from selected terminal'));
        }
    }

    public function validateReceiverBankBik($attribute, $params = [])
    {
        $biks = ArrayHelper::getColumn($this->getAvailableReceiverBanks(), 'bik');
        if (!in_array($this->receiverBankBik, $biks)) {
            $this->addError($attribute, Yii::t('edm', 'You cannot create document for selected bank'));
        }
    }

    public function validateAttachmentSize($attribute)
    {
        if (empty($this->attachedFiles)) {
            return;
        }

        $maxAttachmentSize = $this->getMaxAttachmentSize();
        if ($maxAttachmentSize === null) {
            return;
        }

        foreach ($this->attachedFiles as $attachment) {

            $attachmentSizeKb = filesize($attachment->getPath()) / 1024;

            if ($attachmentSizeKb > $maxAttachmentSize) {
                if ($maxAttachmentSize >= 1024) {
                    $limit = (int)($maxAttachmentSize / 1024);
                    $unit = 'MB';
                } else {
                    $limit = $maxAttachmentSize;
                    $unit = 'KB';
                }

                $this->addError(
                    $attribute,
                    Yii::t(
                        'app/iso20022',
                        'Attachment file size cannot exceed {limit} {unit,select,MB{MB} KB{KB} other{}}',
                        [
                            'limit' => $limit,
                            'unit'  => $unit,
                        ]
                    )
                );

                break;
            }
        }
    }

    public function validateAttachmentsCount($attribute, $params = [])
    {
        $maxAttachmentsCount = $this->getMaxAttachmentsCount($this->receiverBankBik);
        if ($maxAttachmentsCount === null) {
            return;
        }
        if (count($this->attachedFiles) > $maxAttachmentsCount) {
            $this->addError(
                $attribute,
                Yii::t(
                    'edm',
                    'Letters for selected recipient cannot have more than {limit,plural,=1{one attached file} other{# attached files}}',
                    ['limit' => $maxAttachmentsCount]
                )
            );
        }
    }

    public function validateSubjectLength($attribute, $params = [])
    {
        $maxLength = $this->isInVtbFormat() ? 255 : 140;
        if (mb_strlen($this->subject) > $maxLength) {
            $errorMessage = Yii::t(
                'yii',
                '{attribute} should contain at most {max, number} {max, plural, one{character} other{characters}}.',
                ['attribute' => $this->getAttributeLabel($attribute), 'max' => $maxLength]
            );
            $this->addError($attribute, $errorMessage);
        }
    }

    public function validateMessageLength($attribute, $params = [])
    {
        if ($this->isInVtbFormat()) {
            return;
        }

        $maxLength = 1025;
        if (mb_strlen($this->message) > $maxLength) {
            $errorMessage = Yii::t(
                'yii',
                '{attribute} should contain at most {max, number} {max, plural, one{character} other{characters}}.',
                ['attribute' => $this->getAttributeLabel($attribute), 'max' => $maxLength]
            );
            $this->addError($attribute, $errorMessage);
        }
    }

    private function isInVtbFormat()
    {
        static $cache = [];

        if (!array_key_exists($this->receiverBankBik, $cache)) {
            $receiverTerminalAddress = $this->getReceiverTerminalAddress();
            $cache[$this->receiverBankBik] = $receiverTerminalAddress
                ? VTBHelper::isGatewayTerminal($receiverTerminalAddress)
                : false;
        }

        return $cache[$this->receiverBankBik];
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        $this->attachedFiles = AttachedFileSession::createListFromJson($this->attachedFilesJson);

        return $result;
    }

    public function createDocument()
    {
        $senderTerminal = Terminal::findOne($this->senderTerminalId);
        $receiverTerminalAddress = $this->getReceiverTerminalAddress();
        $typeModel = $this->createDocumentTypeModel($senderTerminal, $receiverTerminalAddress);
        $attachments = $this->storeAttachments();

        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            $this->createDocumentAttributes($typeModel, $senderTerminal, $receiverTerminalAddress),
            $this->createExtModelAttributes($typeModel, $attachments)
        );

        if ($context === false) {
            throw new Exception('Failed to create document context');
        }

        /** @var Document $document */
        $document = $context['document'];
        if ($document->status == Document::STATUS_SERVICE_PROCESSING) {
            DocumentHelper::waitForDocumentsToLeaveStatus([$document->id], Document::STATUS_SERVICE_PROCESSING);
        }

        return $document;
    }

    public function updateDocument(Document $document): void
    {
        $this->ensureDocumentIsEditable($document);

        $senderTerminal = Terminal::findOne($this->senderTerminalId);
        $receiverTerminalAddress = $this->getReceiverTerminalAddress();
        $typeModel = $this->createDocumentTypeModel($senderTerminal, $receiverTerminalAddress);
        $attachments = $this->storeAttachments();
        $storedFile = Yii::$app->storage->get($document->actualStoredFileId);

        /** @var BankLetterDocumentExt $extModel */
        $extModel = $document->extModel;

        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        $cyxDoc = $cyxDoc->replaceTypeModel($typeModel);
        $cyxDoc->rejectSignatures();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $document->setAttributes(
                $this->createDocumentAttributes($typeModel, $senderTerminal, $receiverTerminalAddress),
                false
            );
            if (!$document->save()) {
                throw new \Exception('Failed to update document attributes, errors: ' . var_export($document->errors, true));
            }

            $extModel->setAttributes(
                $this->createExtModelAttributes($typeModel, $attachments),
                false
            );
            if (!$extModel->save()) {
                throw new \Exception('Failed to update document ext attributes, errors: ' . var_export($extModel->errors, true));
            }
            $updateResult = $storedFile->updateData($cyxDoc->saveXML());
            if (!$updateResult) {
                throw new \Exception('Failed to update document stored file');
            }

            /** @var EdmModule $module */
            $module = Yii::$app->getModule(EdmModule::SERVICE_ID);
            $module->processDocument($document);

            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    private function createDocumentAttributes(BaseType $typeModel, Terminal $senderTerminal, string $receiverTerminalAddress): array
    {
        return [
            'type'               => $typeModel->getType(),
            'typeGroup'          => EdmModule::SERVICE_ID,
            'direction'          => Document::DIRECTION_OUT,
            'origin'             => Document::ORIGIN_WEB,
            'terminalId'         => $senderTerminal->id,
            'sender'             => $senderTerminal->terminalId,
            'receiver'           => $receiverTerminalAddress,
            'status'             => Document::STATUS_CREATING,
            'signaturesRequired' => Yii::$app->getModule('edm')->getSignaturesNumber($senderTerminal->terminalId),
            'signaturesCount'    => 0,
            //'signData'           => null,
        ];
    }

    /**
     * @param BaseType $typeModel
     * @param StoredFile[] $attachments
     * @return array
     */
    private function createExtModelAttributes(BaseType $typeModel, array $attachments): array
    {
        $storedFileId = $attachments ? array_keys($attachments)[0] : null;
        return [
            'subject'      => $this->subject,
            'message'      => $this->message,
            'storedFileId' => $storedFileId,
            'fileName'     => $storedFileId !== null ? $attachments[$storedFileId] : null,
            'jsonData'     => json_encode($attachments),
            'uuid'         => $typeModel instanceof Auth026Type ? $typeModel->msgId : null,
            'bankBik'      => $this->receiverBankBik,
        ];
    }

    /**
     * @return array
     */
    private function getAvailableSenderTerminalsIds()
    {
        if ($this->user === null) {
            return [];
        }

        return $this->user->disableTerminalSelect
            ? UserTerminal::getUserTerminalIndexes($this->user->id)
            : [$this->user->terminalId => $this->user->terminalId];
    }

    private function createDocumentTypeModel(Terminal $senderTerminal, $receiverTerminalId)
    {
        return VTBHelper::isGatewayTerminal($receiverTerminalId)
            ? $this->createVTBFreeClientDocTypeModel($senderTerminal)
            : $this->createAuth026TypeModel($senderTerminal, $receiverTerminalId);
    }

    private function createVTBFreeClientDocTypeModel(Terminal $senderTerminal)
    {
        $vtbCustomerId = VTBHelper::getVTBCustomerId($senderTerminal->terminalId);
        if (empty($vtbCustomerId)) {
            throw new Exception(Yii::t('edm', 'VTB customer id for terminal {terminalId} is unknown', ['terminalId' => $senderTerminal->terminalId]));
        }

        $vtbBranch = DictVTBBankBranch::findOne(['bik' => $this->receiverBankBik]);
        if ($vtbBranch === null) {
            throw new Exception(Yii::t('edm', 'VTB bank branch for {bik} is not found in dictionary', ['bik' => $this->receiverBankBik]));
        }

        $freeClientDoc = new FreeClientDoc([
            'CUSTID'         => $vtbCustomerId,
            'KBOPID'         => $vtbBranch->branchId,
            'DOCUMENTDATE'   => new DateTime(),
            'DOCUMENTNUMBER' => VTBHelper::generateDocumentNumber(),
            'DOCNAME'        => $this->subject,
            'DOCTEXT'        => $this->message,
            'DOCTYPE'        => $this->vtbMessageTypeCode,
            'DOCATTACHMENT'  => $this->createBSDocumentAttachments(),
        ]);

        $documentVersion = 3;

        return new VTBFreeClientDocType([
            'customerId'      => $vtbCustomerId,
            'documentVersion' => $documentVersion,
            'document'        => $freeClientDoc,
            'signatureInfo'   => new SignInfo(['signedFields' => $freeClientDoc->getSignedFieldsIds($documentVersion)]),
        ]);
    }

    private function createAuth026TypeModel(Terminal $senderTerminal, $receiverTerminalId)
    {
        $typeModel = new Auth026Type();
        $typeModel->typeCode = $this->isoMessageTypeCode;
        $typeModel->dateCreated = time();
        $typeModel->sender = $senderTerminal->terminalId;
        $typeModel->receiver = $receiverTerminalId;
        $typeModel->subject = $this->subject;
        $typeModel->descr = $this->message;
        $typeModel->numberOfItems = 1;
        $typeModel->senderTaxId = $this->getSenderInnByTerminalId($senderTerminal->id);

        if (!empty($this->attachedFiles)) {
            if ($this->isInRosbankFormat()) {
                $this->createRosbankAuth026Attachments($typeModel);
            } else {
                $this->createAuth026Attachments($typeModel, $senderTerminal);
            }
        }

        $typeModel->buildXML();

        return $typeModel;
    }

    private function createAuth026Attachments(Auth026Type $typeModel, Terminal $senderTerminal): void
    {
        $attachments = [];
        foreach ($this->attachedFiles as $attachedFile) {
            $fileNameParts = FileHelper::mb_pathinfo($this->getAttachmentFileName($attachedFile));
            $tempFileName = 'attach_' . $fileNameParts['filename'];
            if (isset($fileNameParts['extension'])) {
                $tempFileName .= '.' . $fileNameParts['extension'];
            }
            // creating new AttachedFile in case we need non-mutable original list
            $attachments[] = new AttachedFile([
                'name' => $tempFileName,
                'path' => $attachedFile->getPath()
            ]);
        }

        ISO20022Helper::attachZipContent($typeModel, $attachments);
    }

    private function createRosbankAuth026Attachments(Auth026Type $typeModel): void
    {
        foreach ($this->attachedFiles as $attachedFile) {
            $typeModel->addEmbeddedAttachment($attachedFile->name, $attachedFile->getPath());
        }
    }

    private function createBSDocumentAttachments()
    {
        if (empty($this->attachedFiles)) {
            return [];
        }

        $iconsPath = Yii::getAlias('@common/models/vtbxml/documents/resources/attachment');
        $icon16Content = file_get_contents("$iconsPath/icon16.ico");
        $icon32Content = file_get_contents("$iconsPath/icon32.ico");

        return array_map(
            function (AttachedFileSession $attachedFile) use ($icon32Content, $icon16Content) {
                $fileContent = file_get_contents($attachedFile->getPath());
                return new BSDocumentAttachment([
                    'fileName'      => $attachedFile->name,
                    'fileContent'   => $fileContent,
                    'icon16Content' => $icon16Content,
                    'icon32Content' => $icon32Content,
                ]);
            },
            $this->attachedFiles
        );
    }

    /**
     * Функция нормализует имя файла
     * Если входной параметр не задан, берется имя файла первого аттачмента
     * (для обратной совместимости)
     * @param type $name
     * @return type
     */
    private function getAttachmentFileName($attachedFile = null, $name = null)
    {
        if (!$name) {
            if (empty($attachedFile)) {
                return null;
            }
            $name = $attachedFile->name;
        }

        return preg_replace('#^.*?([^\\/]+)$#', '$1', $name);
    }

    private function storeAttachments()
    {
        $out = [];

        foreach ($this->attachedFiles as $attachment) {

            $storedFile = Yii::$app->storage->putFile(
                $attachment->getPath(),
                EdmModule::SERVICE_ID,
                'out',
                $this->getAttachmentFileName(null, $attachment->name)
            );

            if ($storedFile) {
                $out[$storedFile->id] = $attachment->name;
            }
        }

        return $out;
    }

    private function getSenderInnByTerminalId(int $terminalId): ?string
    {
        $organization = DictOrganization::findOne(['terminalId' => $terminalId]);
        return $organization ? $organization->inn : null;
    }

    private function getReceiverTerminalAddress(): ?string
    {
        $bank = DictBank::findOne(['bik' => $this->receiverBankBik]);
        return $bank ? $bank->terminalId : null;
    }

    private function isInRosbankFormat(): bool
    {
        $receiverTerminalAddress = $this->getReceiverTerminalAddress();
        return $receiverTerminalAddress && RosbankHelper::isGatewayTerminal($receiverTerminalAddress);
    }

    private function getMaxAttachmentSize(): ?int
    {
        if ($this->receiverBankBik) {
            $truncatedIdRecipient = Address::truncateAddress($this->getReceiverTerminalAddress());
            $participant = BICDirParticipant::findOne(['participantBIC' => $truncatedIdRecipient]);
            if ($participant->maxAttachmentSize) {
                return $participant->maxAttachmentSize * 1024;
            }
        }
        if ($this->isInRosbankFormat()) {
            return Auth026Type::MAX_EMBEDDED_ATTACHMENT_FILE_SIZE_KB;
        } else if ($this->isInVtbFormat()) {
            return self::MAX_VTB_DOCUMENT_ATTACHMENT_FILE_SIZE_KB;
        } else {
            return null;
        }
    }

    private function getMaxAttachmentsCount(string $receiverBankBik): ?int
    {
        if ($this->isInRosbankFormat()) {
            return 1;
        }
        return null;
    }

    public function getMaxAttachmentsCountsByBiks(): array
    {
        return array_reduce(
            $this->getAvailableReceiverBanks(),
            function (array $carry, DictBank $bank): array {
                $carry[$bank->bik] = $this->getMaxAttachmentsCount($bank->bik);
                return $carry;
            },
            []
        );
    }

    private function ensureDocumentIsEditable(Document $document): void
    {
        $isEditable = $document->direction === Document::DIRECTION_OUT
            && in_array($document->status, [Document::STATUS_CREATING, Document::STATUS_FORSIGNING]);
        if (!$isEditable) {
            throw new \Exception("Bank letter {$document->id} is not editable");
        }
    }
}
