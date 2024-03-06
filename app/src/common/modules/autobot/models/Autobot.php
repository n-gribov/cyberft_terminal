<?php

namespace common\modules\autobot\models;

use common\helpers\DateHelper;
use common\modules\certManager\components\ssl\X509FileModel;
use DateTime;
use Yii;
use yii\base\InvalidValueException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\helpers\Url;

/**
 * This is the model class for table "autobot".
 *
 * @package modules
 * @subpackage autobot
 * @property integer  $id                   Autobot ID
 * @property string   $updatedAt            Update date
 * @property string   $name                 Autobot name
 * @property string   $ownerSurname         Owner surname
 * @property string   $ownerName            Owner name
 * @property integer  $primary              Autobot primary status
 * @property string   $privateKey           Autobot private key
 * @property string   $publicKey            Autobot public key
 * @property string   $certificate          Autobot certigicate
 * @property string   $fingerprint          Autobot fingerprint
 * @property string   $countryName          Autobot country name
 * @property string   $stateOrProvinceName  Autobot key satate or province name
 * @property string   $localityName         Autobot locality name
 * @property string   $organizationName     Autobot organization name
 * @property string   $userId               Autobot common name
 * @property integer  $active               Active status
 * @property datetime $expirationDate       Autobot expiration date
 * @property string   $status               Autobot status
 * @property integer $controllerId
 * @property bool $isActive
 * @property bool $isBlocked
 * @property bool $isUsedForSigning
 * @property string $code
 * @property Controller $controller
 */
class Autobot extends ActiveRecord
{
    /**
     * Additional autobot
     */
    const AUTOBOT_ADDITIONAL = 0;

    /**
     * Primary autobot
     */
    const AUTOBOT_PRIMARY = 1;

    const STATUS_CREATED = 'created';
    const STATUS_ACTIVE = 'active';
    const STATUS_WAITING_FOR_ACTIVATION = 'statusWaitingForActivation';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_USED_FOR_SIGNING = 'usedForSigning';

    private $_commonName;

    private $controllerVerificationFlag = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'autobot';
    }

    public function beforeValidate()
    {
        $result = parent::beforeValidate();

        if ($result) {
            $this->countryName = str_replace('_', '', $this->countryName);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        // Получаем дату истечения сертификата - текущая дата + 1 год
        $currentDate = new \DateTime(date('Y-m-d H:i:s'));
        $expirationDate = $currentDate->add(new \DateInterval('P1Y'));

        return [
            [['controllerId'], 'required'],
            [['updatedAt'], 'safe'],
            [['primary'], 'integer'],
            [['privateKey', 'publicKey', 'certificate'], 'string'],
            [['name', 'fingerprint', 'stateOrProvinceName',
                'localityName', 'organizationName',
                'ownerSurname', 'ownerName'], 'string', 'max' => 64],
            ['countryName', 'string', 'min' => 2, 'max' => 2],
            ['countryName', 'default', 'value' => 'RU'],
            ['stateOrProvinceName', 'default', 'value' => 'Moscow'],
            ['localityName', 'default', 'value' => 'Moscow'],
            ['organizationName', 'default', 'value' => 'CyberFT'],
            ['userId', 'integer'],
            ['controllerVerificationFlag', 'integer'],
            ['expirationDate', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['expirationDate'], 'default', 'value' =>  $expirationDate->format('Y-m-d 00:00:00')],
            [
                'countryName',
                'match', 'pattern' => '/^([a-z \-]*)$/ui',
                'message' => Yii::t('app/autobot', 'Allowed only latin characters')
            ],
            [
                // Валидация полей на наличие только латинских символов и цифр
                [
                    'stateOrProvinceName',
                    'localityName'
                ],
                'match', 'pattern' => '/^([0-9a-z \-]*)$/ui',
                'message' => Yii::t('app/autobot', 'Allowed only latin characters and digits')
            ],
            [
                'status', 'default', 'value' => self::STATUS_CREATED,
            ],
            [
                'primary', 'default', 'value' => self::AUTOBOT_PRIMARY
            ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function scenarios()
	{
		return ArrayHelper::merge(parent::scenarios(), [
			'control' => [
				'id'
			],
		]);
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => Yii::t('app/autobot', 'ID'),
            'updatedAt'           => Yii::t('app/autobot', 'Updated at'),
            'name'                => Yii::t('app/autobot', 'Key name'),
            'primary'             => Yii::t('app/autobot',
                'Primary or additional key'),
            'privateKey'          => Yii::t('app/autobot', 'Private key'),
            'publicKey'           => Yii::t('app/autobot', 'Public key'),
            'certificate'         => Yii::t('app/autobot', 'Certificate'),
            'fingerprint'         => Yii::t('app/autobot', 'Fingerprint'),
            'countryName'         => Yii::t('app/autobot', 'Country name (C)'),
            'stateOrProvinceName' => Yii::t('app/autobot', 'State or province (S)'),
            'localityName'        => Yii::t('app/autobot', 'Locality name (L)'),
            'organizationName'    => Yii::t('app/autobot', 'Organization name (O)'),
            'ownerSurname'        => Yii::t('app/autobot', 'Owner surname (SN)'),
            'ownerName'        => Yii::t('app/autobot', 'Owner name (G)'),
            'controllerVerificationFlag' => Yii::t('app/autobot', 'Manual control'),
            'expirationDate' => Yii::t('app/autobot', 'Valid before'),
            'userId' => Yii::t('app/autobot', 'Controller'),
            'status' => Yii::t('app/autobot', 'Status'),
        ];
    }

    /**
     * Get primaty labels
     *
     * @return array
     */
    public function primaryLabels()
    {
        return [
            self::AUTOBOT_ADDITIONAL => Yii::t('app/autobot', 'Additional key'),
            self::AUTOBOT_PRIMARY    => Yii::t('app/autobot', 'Primary key'),
        ];
    }

    public static function statusLabels()
    {
        return [
            self::STATUS_CREATED                => Yii::t('app/autobot', 'Created'),
            self::STATUS_BLOCKED                => Yii::t('app/autobot', 'Blocked'),
            self::STATUS_ACTIVE                 => Yii::t('app/autobot', 'Active'),
            self::STATUS_WAITING_FOR_ACTIVATION => Yii::t('app/autobot', 'Waiting for activation'),
            self::STATUS_USED_FOR_SIGNING       => Yii::t('app/autobot', 'Used for signing'),
        ];
    }

    public function getStatusLabel()
    {
        $labels = self::statusLabels();

        return $labels[$this->status] ?? null;
    }

    /**
     * Get primary label
     *
     * @return string
     */
    public function getPrimaryLabel()
	{
		return !is_null($this->primary) && array_key_exists($this->primary,
				$this->primaryLabels()) ? $this->primaryLabels()[$this->primary] : '';
	}

    /**
     * @inheritdoc
     */
	public function beforeSave($insert)
	{
		parent::beforeSave($insert);

		$this->updatedAt = gmdate('Y-m-d H:i:s');
        if ($this->primary == 0) {
            $this->setAttributes([
                'controllerVerificationFlag' => 0,
                'userId' => NULL
            ]);
        }

		return true;
	}

    /**
     * @inheritdoc
     */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

        if (empty($this->name)) {
            $this->name = Yii::t('app/autobot', 'Controller Key - {ownerName} - {organization}', [
                'ownerName' => $this->controller->fullName,
                'organization' => $this->organizationName,
            ]);

            $this->save();
        }
    }

    /**
     * @param string $privateKeyPassword
     * @param string $terminalAddress
     * @return bool
     */
	public function generate($privateKeyPassword, $terminalAddress)
	{
		try {
            /**
             * @var $certManager \common\modules\certManager\Module
             */
            $certManager = Yii::$app->getModule('certManager');
            $keys = $certManager->generateAutobotKeys($privateKeyPassword,
                [
                    'countryName' => $this->countryName,
                    'stateOrProvinceName' => $this->stateOrProvinceName,
                    'localityName' => $this->localityName,
                    'organizationName' => $this->organizationName,
                    'surname' => $this->ownerSurname,
                    'givenName' => $this->ownerName,
                    // Пустой commonName вызывает ошибку при генерации
                    'commonName' => "{$terminalAddress} ({$this->ownerSurname})",
                ]
            );

            if ($keys === false) {
                throw new \Exception('Autobot generated keys are false');
            }

            $this->setAttributes([
                'privateKey' => $keys['private'],
                'publicKey' => $keys['public'],
                'certificate' => $keys['cert'],
                'fingerprint' => $certManager->getCertFingerprint($keys['cert'])
            ], false);

            return true;
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage());

            return false;
        }
	}

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function getCommonName()
    {
        return $this->_commonName;
    }

    /**
     * Активация ключа
     * @param $password
     */
    public function activate($password)
    {
        $this->ensureCanChangeStatus();

        $this->storePassword($password);

        // Еcли это единственный активный ключ, то сразу делаем его используемым для подписания
        $hasKeyUsedForSigning = self::find()
            ->joinWith('controller.terminal')
            ->where([
                'autobot.status' => [self::STATUS_USED_FOR_SIGNING],
                'autobot.primary' => true,
                'terminal.terminalId' => $this->controller->terminal->terminalId
            ])
            ->exists();
        $hasOtherKeys = self::find()
            ->joinWith('controller.terminal')
            ->where([
                'autobot.status' => [self::STATUS_ACTIVE, self::STATUS_WAITING_FOR_ACTIVATION],
                'autobot.primary' => true,
                'terminal.terminalId' => $this->controller->terminal->terminalId,
            ])
            ->andWhere(['!=', 'autobot.id', $this->id])
            ->exists();

        if ($hasKeyUsedForSigning || $hasOtherKeys || $this->isExpired() || $this->primary == false) {
            $newStatus = static::STATUS_ACTIVE;
        } else {
            $newStatus = static::STATUS_USED_FOR_SIGNING;
        }

        $this->status = $newStatus;
        $this->save(false);
    }

    public function makeWaitingForActivation(string $password): void
    {
        $this->ensureCanChangeStatus();

        $this->storePassword($password);
        $this->status = static::STATUS_WAITING_FOR_ACTIVATION;
        $this->save(false);
    }

    private function storePassword(string $password): void
    {
        $terminalId = $this->controller->terminal->terminalId;

        // Запись пароля ключа в данные по терминалу
        $terminals = Yii::$app->terminals;
        $terminalData = $terminals->findTerminalData($terminalId);
        $passwords = $terminalData['passwords'] ?? [];
        $passwords[$this->id] = $password;
        $terminalData['passwords'] = $passwords;
        $terminals->storeTerminalData($terminalId, $terminalData);
        $terminals->save($terminalId);
    }

    /**
     * Деактивация ключа
     */
    public function block()
    {
        $this->ensureCanChangeStatus();

        $terminalId = $this->controller->terminal->terminalId;

        // При деактивации удаление данных ключа из данных терминала
        $terminals = Yii::$app->terminals;
        $terminalData = $terminals->findTerminalData($terminalId);
        $passwords = $terminalData['passwords'] ?? [];

        if (isset($passwords[$this->id])) {
            unset($passwords[$this->id]);
        }

        $terminalData['passwords'] = $passwords;

        $terminals->storeTerminalData($terminalId, $terminalData);
        $terminals->save($terminalId);

        $this->status = static::STATUS_BLOCKED;
        $this->save(false);
    }

    /**
     * Сделать ключ используемым для подписания
     */
    public function useForSigning()
    {
        $this->ensureCanChangeStatus();

        // Текущий ключ используемый для подписания делаем просто активным
        $currentlyUsedForSigning = static::find()
            ->joinWith('controller')
            ->where([
                'controller.terminalId' => $this->controller->terminalId,
                'autobot.status' => Autobot::STATUS_USED_FOR_SIGNING,
            ])
            ->all();
        Autobot::updateAll(
            ['status' => Autobot::STATUS_ACTIVE],
            ['id' => ArrayHelper::getColumn($currentlyUsedForSigning, 'id')]
        );

        $this->status = static::STATUS_USED_FOR_SIGNING;
        $this->save(false);
    }

    /**
     * Проверка: ключ истёк
     * @return bool
     */
    public function isExpired()
    {
        // Если дата истечения сертификата не указана, это ошибка
        if ($this->expirationDate == '0000-00-00 00:00:00') {
            return true;
        }

        // Если дата истечения просрочена, это ошибка
        $now = strtotime(date('c'));
        $expirationDate = strtotime($this->expirationDate);

        if ($expirationDate < $now) {
            return true;
        }

        return false;
    }

    /**
     * Проверка: ключ истечёт через указанное количество дней
     * @param int $days
     * @return bool
     */
    public function isExpiringSoon($days = 30)
    {
        $dateNow = new DateTime();
        $dateExpiration = new DateTime($this->expirationDate);
        $interval = $dateNow->diff($dateExpiration);

        return $interval->days <= $days;
    }

    /**
     * Проверка указанного пароля для ключа
     * @param $password
     * @return bool
     */
    public function isCorrectPassword($password)
    {
        return boolval(openssl_pkey_get_private($this->privateKey, $password));
    }

    public function getListUrl()
    {
        $terminal = $this->controller->terminal;

        if (!$terminal) {
            throw new InvalidValueException('Failed to find autobot terminal');
        }

        return Url::to(['/autobot/terminals/index', 'id'=> $terminal->id, 'tabMode' => 'tabAutobot']);
    }

    public function getIsBlocked(): bool
    {
        return $this->status == Autobot::STATUS_BLOCKED;
    }

    public function getIsActive(): bool
    {
        return $this->status == Autobot::STATUS_ACTIVE;
    }

    public function getIsUsedForSigning(): bool
    {
        return $this->status == Autobot::STATUS_USED_FOR_SIGNING;
    }

    public static function hasUsedForSigningAutobot($terminalId)
    {
        return self::find()
            ->joinWith('controller')
            ->joinWith('controller.terminal')
            ->where([
                'terminal.terminalId' => $terminalId,
                'autobot.primary' => Autobot::AUTOBOT_PRIMARY,
                'autobot.status' => Autobot::STATUS_USED_FOR_SIGNING
            ])
            ->exists();
    }

    public function getController()
    {
        return $this->hasOne(Controller::class, ['id' => 'controllerId']);
    }

    public function getCode(): string
    {
        return "{$this->controller->terminal->terminalId}-{$this->fingerprint}";
    }

    public function getPassword(): ?string
    {
        $terminalData = Yii::$app->terminals->findTerminalData($this->controller->terminal->terminalId);
        return $terminalData['passwords'][$this->id] ?? null;
    }

    public function willNotExpireIn(int $minutes): bool
    {
        $expiryDate = new \DateTime($this->expirationDate);
        return DateHelper::getMinutesFromNow($expiryDate) > $minutes;
    }

    public function certificateWillNotExpireIn(int $minutes): bool
    {
        $x509 = X509FileModel::loadData($this->certificate);
        return DateHelper::getMinutesFromNow($x509->validTo) > $minutes;
    }

    private function ensureCanChangeStatus()
    {
        $isEditable = in_array(
            $this->status,
            [
                self::STATUS_CREATED,
                self::STATUS_ACTIVE,
                self::STATUS_USED_FOR_SIGNING,
                self::STATUS_WAITING_FOR_ACTIVATION,
            ]
        );
        if (!$isEditable) {
            throw new \Exception("Status '{$this->status}' cannot be changed");
        }
    }
}
