<?php


namespace addons\VTB\models;


use common\document\Document;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @property integer  $id
 * @property string   $status
 * @property integer  $documentId
 * @property string   $dateCreate
 * @property string   $importAttemptDate
 * @property string   $statusCheckDate
 * @property string   $externalRequestId
 * @property integer  $externalDocumentStatus
 * @property string   $externalDocumentInfo
 * @property Document $document
 */
class VTBDocumentImportRequest extends ActiveRecord
{
    const STATUS_PENDING          = 'pending';
    const STATUS_SENT             = 'sent';
    const STATUS_SENDING_ERROR    = 'sendingError';
    const STATUS_PROCESSED        = 'processed';
    const STATUS_PROCESSING_ERROR = 'processingError';

    public function rules()
    {
        return [
            [['documentId', 'status'], 'required'],
        ];
    }

    public static function tableName()
    {
        return 'vtb_documentImportRequest';
    }

    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'documentId']);
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        // Сохранить модель в БД
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
        // Сохранить модель в БД
        return $this->save();
    }
}
