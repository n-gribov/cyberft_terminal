<?php

namespace addons\edm\models\BankLetter;

use addons\edm\EdmModule;
use addons\edm\models\DictBank;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocType;
use addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocType;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\document\DocumentPermission;
use common\helpers\vtb\VTBHelper;
use Yii;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use yii\base\BaseObject;
use yii\helpers\Url;

/**
 * @property-read null|string $businessStatusLabel
 */
class BankLetterViewModel extends BaseObject
{
    public $date;
    public $subject;
    public $message;
    public $isoMessageTypeCode;
    public $vtbMessageTypeCode;
    public $messageType;
    public $senderName;
    public $receiverName;
    public $receiverBankBik;
    public $attachedFiles;
    public $businessStatus;
    public $businessStatusDescription;

    /** @var Document */
    public $document;

    public static function create(Document $document)
    {
        /** @var BankLetterDocumentExt|ISO20022DocumentExt|null $extModel */
        $extModel = $document->extModel;
        $cyxDoc = CyberXmlDocument::read($document->getValidStoredFileId());

        /** @var VTBFreeClientDocType|VTBFreeBankDocType|Auth026Type $typeModel */
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        $senderName = $document->senderParticipant !== null
            ? $document->senderParticipant->name
            : $document->senderParticipantId;
        $receiverName = $document->receiverParticipant !== null
            ? $document->receiverParticipant->name
            : $document->receiverParticipantId;

        if ($extModel instanceof BankLetterDocumentExt) {
            $receiverBankBik = $extModel->bankBik;
        } elseif ($extModel instanceof ISO20022DocumentExt) {
            $bank = DictBank::findOne(['terminalId' => $document->receiver]);
            $receiverBankBik = $bank ? $bank->bik : null;
        } else {
            throw new \InvalidArgumentException('Unsupported document ext model type: ' . get_class($extModel));
        }

        if ($typeModel instanceof VTBFreeClientDocType || $typeModel instanceof VTBFreeBankDocType) {
            $message = $typeModel->document->DOCTEXT;
            $messageType = VtbMessageTypeCodes::getNameById($typeModel->document->DOCTYPE);
            $isoMessageTypeCode = null;
            $vtbMessageTypeCode = $typeModel->document->DOCTYPE;
        } elseif ($typeModel instanceof Auth026Type) {
            $message = $typeModel->descr;
            $messageType = IsoMessageTypeCodes::getNameById($typeModel->typeCode);
            $isoMessageTypeCode = $typeModel->typeCode;
            $vtbMessageTypeCode = null;
        } else {
            throw new \InvalidArgumentException("Unsupported document type: {$typeModel->getType()}");
        }

        return new BankLetterViewModel([
            'date' => date('d.m.Y', strtotime($document->dateCreate)),
            'subject' => (!$extModel || $extModel->subject !== null) ? $extModel->subject : '',
            'senderName' => $senderName,
            'receiverName' => $receiverName,
            'receiverBankBik' => $receiverBankBik,
            'message' => $message,
            'messageType' => $messageType,
            'isoMessageTypeCode' => $isoMessageTypeCode,
            'vtbMessageTypeCode' => $vtbMessageTypeCode,
            'attachedFiles' => static::createAttachedFiles($extModel, $typeModel),
            'businessStatus' => static::getBusinessStatus($extModel),
            'businessStatusDescription' => static::getBusinessStatusDescription($extModel),
            'document' => $document,
        ]);
    }

    public function getBusinessStatusLabel(): ?string
    {
        $statusLabels = BankLetterSearch::getBusinessStatusLabels();
        return $statusLabels[$this->businessStatus] ?? null;
    }

    public function canBeSent(): bool
    {
        return $this->document->status === Document::STATUS_CREATING
            && $this->document->signaturesRequired == 0
            && $this->userHasPermission(DocumentPermission::CREATE);
    }

    public function canBeSigned(): bool
    {
        return $this->document->isSignableByUserLevel(EdmModule::SERVICE_ID);
    }

    public function canBeEdited(): bool
    {
        return
            $this->document->direction === Document::DIRECTION_OUT
            && in_array($this->document->status, [Document::STATUS_CREATING, Document::STATUS_FORSIGNING])
            && $this->userHasPermission(DocumentPermission::CREATE);
    }

    public function canBeDeleted(): bool
    {
        return in_array($this->document->status, [Document::STATUS_CREATING, Document::STATUS_FORSIGNING])
            && $this->userHasPermission(DocumentPermission::DELETE);
    }

    public function canBeCalledOff(): bool
    {
        return VTBHelper::isVTBDocument($this->document) && VTBHelper::isCancellableDocument($this->document)
            && $this->userHasPermission(DocumentPermission::CREATE);
    }

    private function userHasPermission(string $permission): bool
    {
        return Yii::$app->user->can(
            $permission,
            [
                'serviceId' => EdmModule::SERVICE_ID,
                'documentTypeGroup' => EdmDocumentTypeGroup::BANK_LETTER,
            ]
        );
    }

    private static function createAttachedFiles($extModel, $typeModel)
    {
        if ($typeModel instanceof Auth026Type) {
            return self::createStoredFilesFromAuth026TypeModel($typeModel, $extModel->documentId);
        } else {
            return self::createStoredFilesFromBankLetterDocumentsExt($extModel);
        }
    }

    private static function createStoredFilesFromBankLetterDocumentsExt(BankLetterDocumentExt $extModel): array
    {
        $storedFiles = $extModel->getStoredFileList();
        return array_reduce(
            array_keys($storedFiles),
            function ($carry, $storedFileId) use ($extModel, $storedFiles) {
                $carry[] = [
                    'fileName' => $storedFiles[$storedFileId],
                    'url' => Url::to(['/edm/bank-letter/download-attachment', 'id' => $extModel->documentId, 'fileId' => $storedFileId])
                ];
                return $carry;
            },
            []
        );
    }

    private static function createStoredFilesFromAuth026TypeModel(Auth026Type $typeModel, $documentId): array
    {
        $storedFiles = [];
        $attachedFiles = $typeModel->getAttachedFileList();
        foreach ($attachedFiles as $pos => $attachedFile) {
            $storedFiles[] = [
                'fileName' => $attachedFile['name'],
                'url' => Url::to(['/ISO20022/documents/download-attachment-by-number', 'id' => $documentId, 'pos' => $pos]),
            ];
        }

        return $storedFiles;
    }

    /**
     * @param BankLetterDocumentExt|ISO20022DocumentExt|null $extModel
     * @return string|null
     */
    private static function getBusinessStatus($extModel): ?string
    {
        if ($extModel instanceof BankLetterDocumentExt) {
            return $extModel->businessStatus;
        } elseif ($extModel instanceof ISO20022DocumentExt) {
            return $extModel->statusCode;
        } else {
            return null;
        }
    }

    /**
     * @param BankLetterDocumentExt|ISO20022DocumentExt|null $extModel
     * @return string|null
     */
    private static function getBusinessStatusDescription($extModel): ?string
    {
        if ($extModel instanceof BankLetterDocumentExt) {
            return $extModel->businessStatusDescription;
        } elseif ($extModel instanceof ISO20022DocumentExt) {
            return $extModel->errorDescription;
        } else {
            return null;
        }
    }
}
