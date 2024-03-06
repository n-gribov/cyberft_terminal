<?php

namespace addons\sbbol2\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "sbbol2_scheduled_request".
 *
 * @property string $id
 * @property string $type
 * @property int $attempt
 * @property int $maxAttempts
 * @property string $status
 * @property string $dateCreate
 * @property string $dateUpdate
 * @property int $customerId
 * @property string $requestJsonData
 */
class Sbbol2ScheduledRequest extends ActiveRecord
{
    const STATUS_PENDING          = 'pending';
    const STATUS_SENDING_ERROR    = 'sendingError';
//    const STATUS_PROCESSED        = 'processed';
    const STATUS_PROCESSING_ERROR = 'processingError';
    const STATUS_PROCESSING       = 'processing';

    private $_jsonData = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sbbol2_scheduled_request';
    }

    public static function getStatusList()
    {
        return  [
            static::STATUS_PENDING, static::STATUS_SENDING_ERROR,
            static::STATUS_PROCESSING_ERROR, static::STATUS_PROCESSING
//             static::STATUS_PROCESSED,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'customerId'], 'required'],
            [['attempt', 'maxAttempts', 'customerId'], 'integer'],
            [['dateCreate', 'dateUpdate'], 'safe'],
            ['requestJsonData', 'string'],
            ['type', 'string', 'max' => 64],
            ['status', 'string', 'max' => 32],
            ['status', 'in', 'range' => static::getStatusList()]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'attempt' => 'Attempt',
            'maxAttempts' => 'Max Attempts',
            'status' => 'Status',
            'dateCreate' => 'Date Create',
            'dateUpdate' => 'Date Update',
            'customerId' => 'Customer ID',
            'requestJsonData' => 'Request Json Data',
        ];
    }

    public function getJsonData($attribute = null)
    {
        if (empty($this->requestJsonData)) {
            return false;
        }

        if (is_null($this->_jsonData)) {
            $this->_jsonData = json_decode($this->requestJsonData);
        }

        if (!$attribute) {
            return $this->_jsonData;
        }

        if (empty($this->_jsonData->$attribute)) {
            return false;
        }

        return $this->_jsonData->$attribute;
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        // Сохранить модель в БД и вернуть результат сохранения
        return $this->save();
    }

}
