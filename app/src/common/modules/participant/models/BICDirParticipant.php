<?php

namespace common\modules\participant\models;

use common\document\DocumentFormatGroup;
use common\helpers\Countries;
use common\validators\BICDirValidator;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id              - ID записи экспорта
 * @property string  $participantBIC  - CyberFT/BIC Участника
 * @property string  $providerBIC     - CyberFT/BIC Провайдера
 * @property int     $type            - Тип
 * @property string  $name            - Краткое наименование на русском языке
 * @property string  $institutionName - Наименование на английском языке
 * @property string  $creditOrgFlag   - Признак кредитной организации
 * @property int     $status          - Статус
 * @property int     $blocked         - Блокировка
 * @property string  $countryCode     - Страна
 * @property string  $city            - Город
 * @property string  $validFrom       - Действителен с
 * @property string  $validBefore     - Действителен по
 * @property string  $website         - Web-сайт(ы)
 * @property string  $phone           - Телефон(ы) контакт-центра
 * @property int     $lang            - Язык
 * @property string  $documentFormatGroup
 * @property string  $documentFormatGroupLabel
 * @property int     $maxAttachmentSize
 */
class BICDirParticipant extends ActiveRecord
{
    const TYPE_PROVIDER    = 1;
    const TYPE_PARTICIPANT = 2;
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE   = 1;

    private const DEFAULT_DOCUMENT_FORMAT_GROUPS = [
        'VTBRRUMMCFT' => DocumentFormatGroup::VTB,
        'SABRRUMMCFT' => DocumentFormatGroup::SBBOL2,
        'RZBMRUMMCFT' => DocumentFormatGroup::RAIFFEISEN,
        'IMBKRUMMXXX' => DocumentFormatGroup::ISO20022,
        'ALFARUMMXXX' => DocumentFormatGroup::ISO20022,
        'COMKRUMMXXX' => DocumentFormatGroup::ISO20022,
        'NDEARUMMXXX' => DocumentFormatGroup::ISO20022,
        'RSBNRUMMXXX' => DocumentFormatGroup::ROSBANK_ISO20022,
        'GAZPRUMMXXX' => DocumentFormatGroup::ISO20022,
    ] ;

    public static function tableName()
    {
        return 'participant_BICDir';
    }

    public static function getDefaultDocumentFormatGroup(string $participantBic): ?string
    {
        return self::DEFAULT_DOCUMENT_FORMAT_GROUPS[$participantBic] ?? null;
    }

    public function rules()
    {
        return [
            [['participantBIC', 'providerBIC', 'name'], 'required'],
            [['validFrom', 'validBefore'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['type', 'status', 'blocked', 'lang', 'maxAttachmentSize'], 'integer'],
            ['participantBIC', BICDirValidator::className()],
            [
                'participantBIC', 'unique',
                'message' => Yii::t('app/participant', 'Participant is already registered')
            ],
            ['type', 'in', 'range' => [self::TYPE_PROVIDER, self::TYPE_PARTICIPANT]],
            [['website', 'phone', 'documentFormatGroup'], 'string', 'max' => 255],
            [
                'status', 'in',
                'range' => [self::STATUS_BLOCKED, self::STATUS_ACTIVE],
            ],
            ['documentFormatGroup', 'safe'],
            ['documentFormatGroup', 'default', 'value' => null],
            ['documentFormatGroup', 'in', 'range' => array_keys(DocumentFormatGroup::getAll())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/participant', 'Id'),
            'participantBIC' => Yii::t('app/participant', 'Member BIC'),
            'providerBIC' => Yii::t('app/participant', 'Provider BIC'),
            'type' => Yii::t('app/participant', 'Type'),
            'name' => Yii::t('app/participant', 'Name'),
            'institutionName' => Yii::t('app/participant', 'Name in English (for Russian version)'),
            'creditOrgFlag' => Yii::t('app/participant', 'Credit organization flag'),
            'status' => Yii::t('app/participant', 'Status'),
            'statusLabel' => Yii::t('app/participant', 'Status'),
            'blocked' => Yii::t('app/participant', 'Blocked'),
            'country' => Yii::t('app/participant', 'Country'),
            'city' => Yii::t('app/participant', 'City'),
            'validFrom' => Yii::t('app/participant', 'Valid since'),
            'validBefore' => Yii::t('app/participant', 'Expires'),
            'website' => Yii::t('app/participant', 'Web site'),
            'phone' => Yii::t('app/participant', 'Phone'),
            'lang' => Yii::t('app/participant', 'Language'),
            'documentFormatGroup' => Yii::t('app', 'Preferred exchange format'),
            'documentFormatGroupLabel' => Yii::t('app', 'Preferred exchange format'),
            'maxAttachmentSize' => Yii::t('app/participant', 'Max attachment size, MB'),
        ];
    }

    public static function statusLabels()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app/participant', 'Active'),
            self::STATUS_BLOCKED => Yii::t('app/participant', 'Blocked'),
        ];
    }

    public function getStatusLabel()
    {
        $labels = self::statusLabels();

        if (!array_key_exists($this->status, $labels)) {
            return $this->status;
        }

        return $labels[$this->status];
    }

    public function typeLabels()
    {
        return [
            self::TYPE_PROVIDER => Yii::t('app/participant', 'Provider'),
            self::TYPE_PARTICIPANT => Yii::t('app/participant', 'Member'),
        ];
    }

    public function getTypeLabel()
    {
        return $this->typeLabels()[$this->type];
    }

    public function getCountry()
    {
        return Countries::getName($this->countryCode);
    }

    public function getDocumentFormatGroupLabel(): ?string
    {
        return $this->documentFormatGroup
            ? DocumentFormatGroup::getNameById($this->documentFormatGroup)
            : null;
    }
}
