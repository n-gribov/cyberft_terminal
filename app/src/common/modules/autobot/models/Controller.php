<?php

namespace common\modules\autobot\models;

use common\models\Terminal;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class Controller
 * @package common\modules\autobot\models
 *
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 * @property string $middleName
 * @property string $country
 * @property string $stateOrProvince
 * @property string $locality
 * @property integer $terminalId
 * @property string $fullName
 * @property bool $isDeletable
 * @property bool $isEditable
 * @property bool $isUpdateRequired
 * @property Autobot[] $autobots
 * @property Terminal $terminal
 * @property ControllerCertAcknowledgementActData $certAcknowledgementActData
 */
class Controller extends ActiveRecord
{
    public static function tableName()
    {
        return 'controller';
    }

    public function rules()
    {
        return [
            [['lastName', 'firstName', 'middleName', 'country', 'stateOrProvince', 'locality'], 'string'],
            ['terminalId', 'integer'],
            [['lastName', 'firstName', 'middleName', 'country', 'stateOrProvince', 'locality', 'terminalId'], 'required'],
            [['lastName', 'firstName', 'middleName', 'country', 'stateOrProvince', 'locality'], 'safe'],
            [
                'country',
                'match', 'pattern' => '/^[A-Z]{2}$/', 'message' => Yii::t('app/autobot', 'Allowed only latin characters')
            ],
            [
                ['stateOrProvince', 'locality'],
                'match', 'pattern' => '/^[0-9a-z \-]*$/i', 'message' => Yii::t('app/autobot', 'Allowed only latin characters and digits')
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lastName'        => Yii::t('app/autobot', 'Last name'),
            'firstName'       => Yii::t('app/autobot', 'First name'),
            'middleName'      => Yii::t('app/autobot', 'Middle name'),
            'country'         => Yii::t('app/autobot', 'Country name (C)'),
            'stateOrProvince' => Yii::t('app/autobot', 'State or province (S)'),
            'locality'        => Yii::t('app/autobot', 'Locality name (L)'),
            'terminalId'      => Yii::t('app/autobot', 'Terminal'),
        ];
    }

    public function getAutobots()
    {
        return $this->hasMany(Autobot::class, ['controllerId' => 'id']);
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminal::class, ['id' => 'terminalId']);
    }

    public function getCertAcknowledgementActData()
    {
        return $this->hasOne(ControllerCertAcknowledgementActData::class, ['controllerId' => 'id']);
    }

    public function getFullName()
    {
        return implode(' ', array_filter([$this->lastName, $this->firstName, $this->middleName]));
    }

    public function getIsDeletable(): bool
    {
        return count($this->autobots) === 0;
    }

    public function getIsEditable(): bool
    {
        return count($this->autobots) === 0 || $this->isUpdateRequired;
    }

    public function getIsUpdateRequired(): bool
    {
        $copy = new self($this->attributes);
        return !$copy->validate();
    }

    public function getIsUsedForSigning()
    {
        foreach ($this->autobots as $autobot) {
            if ($autobot->status === Autobot::STATUS_USED_FOR_SIGNING) {
                return true;
            }
        }

        return false;
    }
}
