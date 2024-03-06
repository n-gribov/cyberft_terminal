<?php

namespace addons\edm\models\VTBCancellationRequest;

use addons\edm\models\EdmPayerAccountUser;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * Class VTBCancellationRequestExt
 * @package addons\edm\models\VTBCancellationRequest
 * @property integer $documentId
 * @property integer $prepareCancellationRequestDocumentId
 * @property string $businessStatus
 * @property string $businessStatusComment
 * @property string $businessStatusDescription
 * @property string $businessStatusErrorCode
 * @property integer $cancelDocumentNum
 * @property integer $cancelDocumentType
 * @property date $cancelDocumentDate
 */
class VTBCancellationRequestExt extends ActiveRecord implements DocumentExtInterface
{
    public static function tableName()
    {
        return 'documentExtEdmVTBCancellationRequest';
    }

    public function rules()
    {
        return [
            [['documentId', 'prepareCancellationRequestDocumentId', 'cancelDocumentNum', 'cancelDocumentType'], 'integer'],
            ['documentId', 'required'],
            [['businessStatus', 'businessStatusComment', 'businessStatusDescription', 'businessStatusErrorCode',
                ], 'string'],
            ['cancelDocumentDate', 'date', 'format' => 'Y-m-d'],
        ];
    }

    public function loadContentModel($model)
    {
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return EdmPayerAccountUser::userCanSingDocumentsForBankTerminal($user->id, $document->receiver);
    }
}
