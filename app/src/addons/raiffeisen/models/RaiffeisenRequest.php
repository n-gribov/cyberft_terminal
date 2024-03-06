<?php

namespace addons\raiffeisen\models;

use common\document\Document;
use common\helpers\Lock;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $status
 * @property string $documentType
 * @property string $senderRequestId
 * @property string $receiverRequestId
 * @property string $documentStatusRequestId
 * @property integer $customerId
 * @property integer $holdingHeadCustomerId
 * @property string $receiverDocumentId
 * @property string $dateCreate
 * @property string $responseCheckDate
 * @property string $responseHandlerParamsJson
 * @property integer $incomingDocumentId
 * @property string $receiverRequestStatus
 * @property string $receiverDocumentStatus
 * @property array $responseHandlerParams
 * @property RaiffeisenCustomer $customer
 * @property Document|null $incomingDocument
 */
class RaiffeisenRequest extends ActiveRecord
{
    const STATUS_SENT                      = 'sent';
    const STATUS_DELIVERED                 = 'delivered';
    const STATUS_REJECTED                  = 'rejected';
    const STATUS_PROCESSED                 = 'processed';
    const STATUS_RESPONSE_PROCESSING_ERROR = 'processingError';

    public static function tableName()
    {
        return 'raiffeisen_request';
    }

    public function rules()
    {
        return [
            [
                [
                    'status', 'senderRequestId', 'receiverRequestId', 'receiverDocumentId',
                    'documentType', 'documentStatusRequestId', 'responseHandlerParamsJson',
                ],
                'string'
            ],
            [['incomingDocumentId', 'customerId', 'holdingHeadCustomerId'], 'integer'],
            [
                [
                    'status', 'senderRequestId', 'senderRequestId', 'receiverRequestId', 'customerId',
                    'holdingHeadCustomerId'
                ],
                'required'
            ],
        ];
    }

    public static function updateStatus($id, $status)
    {
        $request = static::findOne($id);

        if ($request === null) {
            Yii::info("Request $id is not found");

            return false;
        }
        $request->status = $status;

        // Сохранить модель в БД и вернуть результат сохранения
        return $request->save();
    }

    public function isFailed()
    {
        return in_array($this->status, [static::STATUS_REJECTED, static::STATUS_RESPONSE_PROCESSING_ERROR]);
    }

    public function hasFinalStatus()
    {
        return in_array(
            $this->status,
            [
                static::STATUS_REJECTED,
                static::STATUS_PROCESSED,
                static::STATUS_RESPONSE_PROCESSING_ERROR,
            ]
        );
    }

    public function getCustomer()
    {
        return $this->hasOne(RaiffeisenCustomer::class, ['id' => 'customerId']);
    }

    public function getIncomingDocument()
    {
        return $this->hasOne(Document::class, ['id' => 'incomingDocumentId']);
    }

    public function getResponseHandlerParams()
    {
        return $this->responseHandlerParamsJson ? json_decode($this->responseHandlerParamsJson, true) : [];
    }

    public function setResponseHandlerParams($params)
    {
        if (is_array($params) && !empty($params)) {
            $this->responseHandlerParamsJson = json_encode($params);
        } else {
            $this->responseHandlerParamsJson = null;
        }
    }

    public function getResponseHandlerParam(string $key, $defaultValue = null)
    {
        $params = $this->getResponseHandlerParams();
        return array_key_exists($key, $params) ? $params[$key] : $defaultValue;
    }

    public function lock($lockTime): bool
    {
        return Lock::acquire($this->getLockKeyName(), 1, $lockTime) === true;
    }

    public function releaseLock()
    {
        Lock::release($this->getLockKeyName(), 1);
    }

    public function getLockKeyName()
    {
        return 'lock:' . get_class($this) . ':' . $this->primaryKey;
    }
}
