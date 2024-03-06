<?php

namespace addons\edm\models\VTBPrepareCancellationRequest;

use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * Class VTBPrepareCancellationRequestExt
 * @package addons\edm\models\VTBPrepareCancellationRequest
 * @property int $id
 * @property int $documentId
 * @property int $targetDocumentUuid
 * @property string $messageForBank
 * @property string $status
 * @property string $targetDocumentInfo
 * @property string $targetDocumentVTBReferenceId
 * @property string $targetDocumentNumber
 * @property string $targetDocumentDate
 */
class VTBPrepareCancellationRequestExt extends ActiveRecord implements DocumentExtInterface
{
    const STATUS_CREATED = 'created';
    const STATUS_PROCESSED = 'processed';
    const STATUS_REJECTED = 'rejected';

    public static function tableName()
    {
        return 'documentExtEdmVTBPrepareCancellationRequest';
    }

    public function rules()
    {
        return [
            ['documentId', 'integer'],
            [
                ['status', 'messageForBank', 'targetDocumentNumber', 'targetDocumentUuid', 'targetDocumentInfo', 'targetDocumentVTBReferenceId'],
                'string'
            ],
            [['documentId', 'targetDocumentUuid', 'status'], 'required'],
            ['messageForBank', 'default', 'value' => null],
        ];
    }

    /**
     * @param VTBPrepareCancellationRequestType $typeModel
     */
    public function loadContentModel($typeModel)
    {
        $this->messageForBank = $typeModel->messageForBank;
        $this->targetDocumentUuid = $typeModel->documentUuid;
        $this->status = static::STATUS_CREATED;
        $this->targetDocumentNumber = $typeModel->documentNumber;
        $this->targetDocumentDate = $typeModel->documentDate;
    }

    public function isDocumentDeletable(User $user = null)
    {
        return false;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return true;
    }
}
