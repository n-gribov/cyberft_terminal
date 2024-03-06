<?php

namespace addons\sbbol2\models;

use common\document\Document;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "sbbol2_documentImportRequest".
 *
 * @property integer $id
 * @property integer $documentId
 * @property string $createDate
 * @property string $importAttemptDate
 * @property string $statusCheckDate
 * @property string $externalDocumentId
 * @property string $bankDocumentStatus
 * @property string $bankComment
 * @property string $documentFieldsHash
 * @property string $status
 * @property Document $document
 * @property string $customerId
 */
class Sbbol2DocumentImportRequest extends ActiveRecord
{
    const STATUS_PENDING          = 'pending';
    const STATUS_SENT             = 'sent';
    const STATUS_SENDING_ERROR    = 'sendingError';
    const STATUS_PROCESSED        = 'processed';
    const STATUS_PROCESSING_ERROR = 'processingError';
    
    public static function tableName()
    {
        return 'sbbol2_documentImportRequest';
    }
    
    public function rules()
    {
        return [
            [['documentId', 'status'], 'required'],
        ];
    }    

    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'documentId']);
    }
    
    public function getCustomerId()
    {        
        $senderId = $this->document->sender;       
        $customerModel = Sbbol2Customer::findOne(['terminalAddress' => $senderId]);
        
        if (empty($customerModel)){
            return '';
        }
        
        return $customerModel->id;
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        // Сохранить модель в БД и вернуть результат сохранения
        return $this->save();
    }
    
    public function updateExternalDocumentId($externalId)
    {
        $this->externalDocumentId = $externalId;
        // Сохранить модель в БД и вернуть результат сохранения
        return $this->save();
    }

    public function touchImportAttemptDate()
    {
        return $this->touchTimestampAttribute('importAttemptDate');
    }

    public function touchStatusCheckDate()
    {
        return $this->touchTimestampAttribute('statusCheckDate');
    }

    private function touchTimestampAttribute($attribute)
    {
        $this->$attribute = new Expression('CURRENT_TIMESTAMP()');
        // Сохранить модель в БД и вернуть результат сохранения
        return $this->save();
    }
}
