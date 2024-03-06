<?php

namespace addons\swiftfin\models;

use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documentExtSwiftFin".
 *
 * @package addons
 * @subpackage swiftfin
 *
 * @property string    $id                  ID
 * @property string    $documentId          Document ID
 * @property string    $operationReference  Operation reference
 * @property integer   $nestedItemsCount    Count of nested items
 * @property string    $extStatus			Local status
 * @property double    $sum                 Sum
 * @property string    $currency            Currency
 * @property string    $date                Date
 * @property string    $absId               ABS ID
 * @property string    $valueDate           Value date
 * @property integer   $userId              Document author id
 * @property string    $correctionReason    Correction reason
 */
class SwiftFinDocumentExt extends ActiveRecord implements DocumentExtInterface
{
	const STATUS_PREAUTHORIZATION = 'preAuthorization';
	const STATUS_AUTHORIZATION = 'authorization';
	const STATUS_AUTHORIZED = 'authorized';
    const STATUS_INAUTHORIZATION = 'inAuthorization';
    /**
     * @var Document $_document Document
     */
    private $_document;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtSwiftFin';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        /**
         * Здесь закодены дефолтовые значения для sum и currency, чтобы не
         * генерировать пустые поля в XML-контейнере.
         * @todo Необходимо корректно извлекать данные поля из источников,
         * однако пока это невозможно.
         */
        $this->sum = '1.00';
        $this->currency = 'RUB';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentId', 'nestedItemsCount'], 'integer'],
            [['sum'], 'number'],
            [['date', 'valueDate', 'extStatus', 'userId', 'correctionReason'], 'safe'],
            [['operationReference'], 'string', 'max' => 32],
            [['currency'], 'string', 'max' => 3],
            [['absId'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                 => Yii::t('app', 'ID'),
            'documentId'         => Yii::t('app', 'Document ID'),
            'operationReference' => Yii::t('doc', 'Operation reference'),
            'nestedItemsCount'   => Yii::t('app', 'Nested Items Count'),
			'extStatus'			 => Yii::t('doc', 'Status'),
            'sum'                => Yii::t('doc', 'Sum'),
            'currency'           => Yii::t('app', 'Currency'),
            'date'               => Yii::t('app', 'Date'),
            'errorCode'          => Yii::t('app', 'Error Code'),
            'absId'              => Yii::t('app', 'ABS ID'),
            'valueDate'          => Yii::t('doc', 'Value date'),
            'correctionReason'  => Yii::t('doc','Comment')
        ];
    }

    /**
     * Get document
     *
     * @return Document
     */
    public function getDocument()
    {
        if (is_null($this->_document)) {
            $this->_document = $this->hasOne('common\document\Document', ['id' => 'documentId']);
        }

        return $this->_document;
    }

    /**
     * Load content model
     *
     * @param \addons\swiftfin\models\SwiftFinType $model Model
     * @return boolean
     */
    public function loadContentModel($model)
    {
        if ($model instanceof SwiftFinType) {
            $this->date = $model->date;

            if (!is_null($model->sum)) {
                $this->sum = $model->sum;
            }

            if (!is_null($model->currency)) {
                $this->currency = $model->currency;
            }

            if (!is_null($model->nestedItemsCount)) {
                $this->nestedItemsCount = $model->nestedItemsCount;
            }

            $this->operationReference = $model->operationReference;
            $this->valueDate = $model->valueDate;
        } else {
            return false;
        }
    }

    /**
     * Get status label
     *
     * @return string Return localization status label
     */
    public function getStatusLabel($status = null)
    {
        if (is_null($status)) {
            $status = $this->extStatus;
        }

        return (!is_null($this->extStatus) && array_key_exists($this->extStatus, self::getStatusLabels()))
            ? self::getStatusLabels()[$status]
            : $this->extStatus;
    }

    /**
     * Get list of status labels
     *
     * @return array
     */
	public static function getStatusLabels()
    {
        return [
            self::STATUS_PREAUTHORIZATION => Yii::t('doc', 'Awaiting preauthorization'),
            self::STATUS_AUTHORIZATION    => Yii::t('doc', 'Awaiting authorization'),
            self::STATUS_AUTHORIZED       => Yii::t('doc', 'Authorized'),
            self::STATUS_INAUTHORIZATION  => Yii::t('doc', 'Undergoing authorization'),
        ];
    }

    public function isDocumentDeletable(User $user = null)
    {
        if ($user === null) {
            $user = Yii::$app->user ? Yii::$app->user->identity : null;
        }
        if ($user != null && in_array($user->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN])) {
            return true;
        }

        return $this->document != null && $this->document->status == Document::STATUS_CORRECTION;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return true;
    }
}
