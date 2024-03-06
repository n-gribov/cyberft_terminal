<?php

namespace common\models;

use common\base\BaseBlock;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string $type
 * @property string $dateCreate
 * @property string $identity
 * @property string $filename
 * @property-read string $typeLabel
 * @property string $errorDescription
 * @property string $documentNumber
 * @property string $documentCurrency
 * @property string $documentType
 * @property string $documentTypeGroup
 * @property string $senderTerminalAddress
 * @property-read Terminal|null $senderTerminal
 * @property-read null|string $documentTypeName
 * @property-read string|null $senderName
 */
class ImportError extends ActiveRecord
{
    const TYPE_TRANSPORT  = 1;
    const TYPE_ISO20022   = 2;
    const TYPE_FILEACT    = 3;
    const TYPE_SWIFTFIN   = 4;
    const TYPE_EDM         = 5;

    public static function tableName()
    {
        return 'import_errors';
    }

    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreate',
                'updatedAtAttribute' => null,
                'value'              => date('Y-m-d H:i:s'),
            ],
            [
                'class'=>  \common\behaviors\JsonArrayBehavior::className(),
                'attributes' => [
                    'errorDescriptionData' => 'errorDescription'
                ]
            ],
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'type', 'dateCreate', 'identity', 'filename', 'errorDescription',
                    'documentType', 'documentTypeGroup', 'documentNumber', 'documentCurrency',
                    'senderTerminalAddress',
                ],
                'safe'
            ]
        ];
    }

    public function getTypeLabel()
    {
        $labels = self::typeLabels();

        return isset($labels[$this->type]) ? $labels[$this->type] : $this->type;
    }

    public static function typeLabels()
    {
        return [
            static::TYPE_TRANSPORT => 'transport',
            static::TYPE_ISO20022 => 'ISO20022',
            static::TYPE_FILEACT => 'FileAct',
            static::TYPE_SWIFTFIN => 'SwiftFin',
            static::TYPE_EDM => 'EDM',
        ];
    }

    public function attributeLabels()
    {
        return [
            'type'                  => Yii::t('other', 'Service'),
            'dateCreate'            => Yii::t('other', 'Create date'),
            'identity'              => Yii::t('other', 'Identity'),
            'filename'              => Yii::t('other', 'Filename'),
            'errorDescription'      => Yii::t('other', 'Error description'),
            'documentNumber'        => Yii::t('document', 'Document number'),
            'documentCurrency'      => Yii::t('document', 'Currency'),
            'documentType'          => Yii::t('document', 'Document'),
            'documentTypeGroup'     => Yii::t('document', 'Document'),
            'documentTypeName'      => Yii::t('document', 'Document'),
            'senderTerminalAddress' => Yii::t('document', 'Sender'),
        ];
    }

    public static function createError(array $params): bool
    {
        if (empty($params)) {
            return false;
        }

        $error = new self;

        $error->setAttributes($params);
        if (isset($params['errorDescriptionData'])) {
            $error->errorDescriptionData = $params['errorDescriptionData'];
        }

        return $error->save();
    }

    public function getSenderTerminal(): ActiveQuery
    {
        return $this->hasOne(Terminal::class, ['terminalId' => 'senderTerminalAddress']);
    }

    public function getSenderName(): ?string
    {
        return $this->senderTerminal
            ? $this->senderTerminal->getScreenName()
            : $this->senderTerminalAddress;
    }

    public function getSenderFilter(): array
    {
        return ArrayHelper::map(
            Terminal::find()->all(),
            'terminalId',
            'screenName'
        );
    }

    public function getDocumentTypeName(): ?string
    {
        return $this->getDocumentTypeGroupName() ?: $this->documentType;
    }

    private function getDocumentTypeGroupName(): ?string
    {
        if (!$this->documentTypeGroup) {
            return null;
        }

        $module = $this->getDocumentModule();
        if (!$module) {
            return null;
        }
        $documentTypeGroup = $module->getDocumentTypeGroupById($this->documentTypeGroup);
        return $documentTypeGroup
            ? $documentTypeGroup->name
            : null;
    }

    private function getDocumentModule(): ?BaseBlock
    {
        switch ($this->type) {
            case static::TYPE_EDM:
                return Yii::$app->getModule('edm');
            case static::TYPE_ISO20022:
                return Yii::$app->getModule('ISO20022');
            case static::TYPE_SWIFTFIN:
                return Yii::$app->getModule('swiftfin');
            case static::TYPE_FILEACT:
                return Yii::$app->getModule('finzip');
        }
        return null;
    }
}