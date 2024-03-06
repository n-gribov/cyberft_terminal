<?php

namespace common\models;

use common\modules\certManager\models\Cert;
use common\models\UserAuthCert;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $email
 * @property string  $auth_key
 * @property string  $activateKey
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $password           Write-only password
 * @property integer $authType           Auth type
 * @property integer $isReset            Is reset mark
 * @property integer $failedLoginCount   Count of failed login
 * @property string  $passwordUpdateDate Date of update password
 * @property string  $lastName           Last name
 * @property string  $firstName          First name
 * @property string  $middleName         Middle name
 * @property string  $terminalId
 * @property integer $disableTerminalSelect
 * @property integer $ownerId
 * @property string $lastIp
 * @property integer|null $signatureLevel
 * @property integer|null $signatureNumber
 */
class User extends ActiveRecord implements IdentityInterface
{
	const PASSWORD_LENGTH   = 12;
    const STATUS_DELETED    = 0;
    const STATUS_ACTIVE     = 10;
    const STATUS_ACTIVATING = 5;
    const STATUS_ACTIVATED  = 7;
    const STATUS_APPROVE    = 8;
    const STATUS_INACTIVE   = 20;

    /**
     * Auth type: password
     */
    const AUTH_TYPE_PASSWORD = 0;

    /**
     * Auth type: key
     */
    const AUTH_TYPE_KEY = 1;

    /**
     * Is reset false value
     */
    const IS_RESET_FALSE = 0;

    /**
     * Is reset true value
     */
    const IS_RESET_TRUE = 1;
    const ROLE_ADMIN = 90;
    const ROLE_ADDITIONAL_ADMIN = 100;
    const ROLE_USER           = 10;
//	const ROLE_SIGNER = 20;
	const ROLE_CONTROLLER     = 30;
    const ROLE_LSO            = 50;
    const ROLE_RSO            = 60;
//    const ROLE_EDM_OPERATOR   = 40;
//    const ROLE_EDM_CONTROLLER = 41;

    private $_activationUserKey;
    public $terminalsList;

    /**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::className(),
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['failedLoginCount', 'default', 'value' => 0],
            ['status', 'in', 'range' => array_keys($this->getStatusLabels())],

			['authType', 'default', 'value' => self::AUTH_TYPE_PASSWORD],
			['authType', 'in', 'range' => array_keys($this->getAuthTypeLabels())],

			['isReset', 'default', 'value' => self::IS_RESET_FALSE],
			['isReset', 'in', 'range' => [self::IS_RESET_FALSE, self::IS_RESET_TRUE]],
			['disableTerminalSelect', 'boolean'],

			['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => array_keys($this->getEditableRoleLabels()), 'except' => ['setPassword', 'create', 'install']],
            ['role', 'in', 'range' => array_keys($this->getRoleLabels()), 'on' => ['setPassword', 'create', 'install']],
            [['signatureNumber', 'signatureLevel', 'failedLoginCount'], 'integer'],
            ['signatureNumber', 'in', 'range' => array_keys($this->getSignatureNumberLabels())],
            ['signatureLevel', 'in', 'range' => array_keys($this->getSignatureLevelLabels())],
			[['lastName', 'firstName', 'middleName'], 'required', 'except' => ['setPassword', 'install']],
            [['email', 'lastName', 'firstName', 'middleName'], 'string', 'max' => 45],
			[['email'], 'required'],
            [['email'], 'email'],
			[['email'], 'unique'],
            ['ownerId', 'safe'],
            ['terminalId',
                'safe',
                'when' => function($model) {
                    return $model->role != self::ROLE_ADMIN;
                },
                'whenClient' => 'function (attribute, value) {
                    return rolesList.val() != ' . self::ROLE_ADMIN . ';}'
            ],
		];
	}

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[] = 'create';
        $scenarios[] = 'setPassword';
        $scenarios[] = 'install';

        return $scenarios;
    }

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('app', 'ID'),
            'email'                 => Yii::t('app/user', 'Email'),
            'roleLabel'             => Yii::t('app/user', 'Role'),
            'role'                  => Yii::t('app/user', 'Role'),
            'signatureLevel'        => Yii::t('app/user', 'Signature level'),
            'signatureLevelLabel'   => Yii::t('app/user', 'Signature level'),
            'signatureNumber'       => Yii::t('app/user', 'Signature number'),
            'signatureNumberLabel'  => Yii::t('app/user', 'Signature number'),
            'status'                => Yii::t('app/user', 'Status'),
            'statusLabel'           => Yii::t('app/user', 'Status'),
            'created_at'            => Yii::t('app/user', 'Created'),
            'updated_at'            => Yii::t('app/user', 'Changed'),
            'authType'              => Yii::t('app/user', 'Type of authorization'),
            'activationUserKey'     => Yii::t('app/user', 'Activation key'),
            'lastName'              => Yii::t('app/user', 'Last name'),
            'firstName'             => Yii::t('app/user', 'First name'),
            'middleName'            => Yii::t('app/user', 'Middle name'),
            'ownerId'               => Yii::t('app/user', 'Owner administrator'),
            'terminalId'            => Yii::t('app', 'Terminal'),
            'disableTerminalSelect' => Yii::t('app/user', 'Disable terminal select'),
        ];
    }

	public function getSignatureNumberLabels(): array
    {
        return [
            null => Yii::t('app/user', 'Not a signer'),
            1    => Yii::t('app/user', 'Signature order') . ' 1',
            2    => Yii::t('app/user', 'Signature order') . ' 2',
            3    => Yii::t('app/user', 'Signature order') . ' 3',
            4    => Yii::t('app/user', 'Signature order') . ' 4',
            5    => Yii::t('app/user', 'Signature order') . ' 5',
            6    => Yii::t('app/user', 'Signature order') . ' 6',
            7    => Yii::t('app/user', 'Signature order') . ' 7',
        ];
    }

	public function getSignatureLevelLabels(): array
    {
        return [
            1    => Yii::t('app/user', 'Level') . ' 1',
            2    => Yii::t('app/user', 'Level') . ' 2',
            3    => Yii::t('app/user', 'Level') . ' 3',
            4    => Yii::t('app/user', 'Level') . ' 4',
            5    => Yii::t('app/user', 'Level') . ' 5',
            6    => Yii::t('app/user', 'Level') . ' 6',
            7    => Yii::t('app/user', 'Level') . ' 7',
        ];
    }

	public function getSignatureNumberLabel(): string
	{
		$labels = $this->getSignatureNumberLabels();

		return isset($labels[$this->signatureNumber]) ? $labels[$this->signatureNumber] : Yii::t('app/user', 'Not a signer');
	}

	public function getSignatureLevelLabel(): ?string
	{
		$labels = $this->getSignatureLevelLabels();

		return isset($labels[$this->signatureLevel]) ? $labels[$this->signatureLevel] : null;
	}

	/**
	 * @return array
	 */
	public function getRoleLabels()
    {
        return [
            self::ROLE_USER           => Yii::t('app', 'User'),
            self::ROLE_ADMIN          => Yii::t('app', 'Administrator'),
            self::ROLE_ADDITIONAL_ADMIN => Yii::t('app', 'Additional administrator'),
//			self::ROLE_SIGNER => Yii::t('app', 'Signer'),
			self::ROLE_CONTROLLER => Yii::t('app', 'Controller'),
//            self::ROLE_EDM_OPERATOR   => Yii::t('app', 'Operator'),
//            self::ROLE_EDM_CONTROLLER => Yii::t('app', 'Controller'),
            self::ROLE_LSO            => Yii::t('app', 'LSO security officer'),
            self::ROLE_RSO            => Yii::t('app', 'RSO security officer'),
        ];
    }

    public function getEditableRoleLabels()
    {
        return [
            self::ROLE_USER           => Yii::t('app', 'User'),
            self::ROLE_ADMIN          => Yii::t('app', 'Administrator'),
            self::ROLE_ADDITIONAL_ADMIN => Yii::t('app', 'Additional administrator'),
            self::ROLE_CONTROLLER => Yii::t('app', 'Controller'),
//            self::ROLE_EDM_OPERATOR   => Yii::t('app', 'Operator'),
//            self::ROLE_EDM_CONTROLLER => Yii::t('app', 'Controller'),
        ];
    }

    /**
	 * @return string|null
	 */
	public function getRoleLabel()
	{
		$labels = $this->getRoleLabels();

		return isset($labels[$this->role]) ? $labels[$this->role] : Yii::t('app', 'Undefined');
	}

	/**
	 * @return array
	 */
	public function getStatusLabels()
    {
        $labels = [
            self::STATUS_INACTIVE   => Yii::t('app/user', 'Inactive'),
            self::STATUS_ACTIVATING => Yii::t('app/user', 'Approval required'),
            self::STATUS_ACTIVATED  => Yii::t('app/user', 'Activated'),
            self::STATUS_ACTIVE     => Yii::t('app/user', 'Active'),
            self::STATUS_DELETED    => Yii::t('app/user', 'Deleted'),
            self::STATUS_APPROVE    => Yii::t('app/user', 'Approval required'),
        ];

        /**
         * изолировать для консольной команды, иначе упадет при добавлении офицера
         */

        if (!Yii::$app->user->getIdentity(false)) {
            return $labels;
        }

        /*
         * Добро пожаловать в ад
         */
        if (Yii::$app->user->identity->isSecurityOfficer() && in_array($this->status, [self::STATUS_APPROVE, self::STATUS_ACTIVATING])) {
            if (self::ROLE_LSO == Yii::$app->user->identity->role) {
                if (!Yii::$app->user->identity->canSettingApprove($this->id)) {
                    $labels[self::STATUS_APPROVE] = Yii::t('app/user', 'RSO approval required');
                } else {
                    $labels[self::STATUS_APPROVE] = Yii::t('app/user', 'LSO approval required');
                }
            } else {
                if (!Yii::$app->user->identity->canSettingApprove($this->id)) {
                    $labels[self::STATUS_APPROVE] = Yii::t('app/user', 'LSO approval required');
                } else {
                    $labels[self::STATUS_APPROVE] = Yii::t('app/user', 'RSO approval required');
                }
            }

            if (self::ROLE_LSO == Yii::$app->user->identity->role) {
                if (!Yii::$app->user->identity->canActivateApprove($this->id)) {
                    $labels[self::STATUS_ACTIVATING] = Yii::t('app/user', 'RSO approval required');
                } else {
                    $labels[self::STATUS_ACTIVATING] = Yii::t('app/user', 'LSO approval required');
                }
            } else {
                if (!Yii::$app->user->identity->canActivateApprove($this->id)) {
                    $labels[self::STATUS_ACTIVATING] = Yii::t('app/user', 'LSO approval required');
                } else {
                    $labels[self::STATUS_ACTIVATING] = Yii::t('app/user', 'RSO approval required');
                }
            }
        }

        if ( self::STATUS_ACTIVATING  == $this->status
             && Yii::$app->commandBus->isCommandForPerform('User', $this->id, 'UserActivate') ) {
            $labels[self::STATUS_ACTIVATING] = Yii::t('app/user', 'Activating');
        }

        return $labels;
    }


    /**
	 * @return string
	 */
	public function getStatusLabel()
    {
		$labels = $this->getStatusLabels();

		return isset($labels[$this->status]) ? $labels[$this->status] : Yii::t('app', 'Undefined');
	}

	/**
	 * Get auth type labels
	 *
	 * @return array
	 */
	public function getAuthTypeLabels()
    {
        return [
            self::AUTH_TYPE_PASSWORD => \Yii::t('app/user', 'By password'),
            self::AUTH_TYPE_KEY      => \Yii::t('app/user', 'By key'),
        ];
    }

    /**
	 * Get auth type label
	 *
	 * @return string
	 */
	public function getAuthTypeLabel()
	{
		$labels = $this->getAuthTypeLabels();

		return isset($labels[$this->authType]) ? $labels[$this->authType] : Yii::t('app', 'Undefined');
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($this->isNewRecord) {
				$this->auth_key = Yii::$app->getSecurity()->generateRandomString();
                $this->activateKey = substr(str_replace(['-', '_'], '', Yii::$app->getSecurity()->generateRandomString(10)), 0, 4);
			}

			return true;
		}

		return false;
	}

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Главному администратору при создании даем права управлени сертификатами
        if ($insert && self::ROLE_ADMIN == $this->role && $this->id == 1) {
            $commonExtUser = new CommonUserExt();
            $commonExtUser->userId = $this->id;
            $commonExtUser->type = CommonUserExt::CERTIFICATES;
            $commonExtUser->canAccess = 1;
            $commonExtUser->save();
        }

        if (!$insert && array_key_exists('password_hash', $changedAttributes)) {
            $prevPassword = new UserPreviousPassword([
                'userId' => $this->id,
                'passwordHash' => $changedAttributes['password_hash'],
            ]);
            $isSaved = $prevPassword->save();
            if (!$isSaved) {
                Yii::warning('Failed to store previous password, errors: ' . var_export($prevPassword->getErrors(), true));
            }
        }
    }

	/**
	 * @return null|Cert
	 */
	public function getActiveCert()
	{
		return $this
			->hasMany(Cert::className(), ['id' => 'certId'])
//			->where() @todo добавить условия по которым сертификат будет считаться гарантированно активным
			->viaTable('userHasCert', ['userId' => 'id'])
			->one();
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
    {
		return static::findOne(['id' => $id, 'status' => [self::STATUS_ACTIVATED, self::STATUS_ACTIVE]]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findByLogin($login)
    {
		return static::findOne([
            'email' => $login,
            'status' => [self::STATUS_ACTIVATED, self::STATUS_ACTIVE, self::STATUS_ACTIVATING, self::STATUS_INACTIVE]
        ]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
    {
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
    {
		return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
    {
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
    {
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
    {
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                'password_reset_token' => $token,
                'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire    = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts     = explode('_', $token);
        $timestamp = (int) end($parts);

        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

	/**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

	/**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

	/**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        $this->passwordUpdateDate = date('Y-m-d H:i:s');
		/**
		 * Set reset status
		 */
		$this->isReset = self::IS_RESET_TRUE;
    }

    public function checkPasswordExpired()
    {
        $passwordUpdateDate = new \DateTime($this->passwordUpdateDate);
        $currentDate = new \DateTime(date(date('Y-m-d H:i:s')));
        $diff = $passwordUpdateDate->diff($currentDate)->days;

        $expiredDaysCount = Yii::$app->settings->get('Security')->passwordExpireDaysCount;

        if ($expiredDaysCount && $diff >= $expiredDaysCount) {
            $this->isReset = self::IS_RESET_TRUE;
            $this->save(false, ['isReset']);

            return true;
        }

        return false;
    }

	/**
	 * Get user screen name
	 *
	 * @return string
	 */
	public function getScreenName()
	{
		return !empty($this->getName()) ? $this->getName() : $this->email;
	}

	/**
	 * Get user roles
	 *
	 * @return array
	 */
	public function getRoles()
	{
		$roles = Yii::$app->authManager->getRolesByUser($this->id);

		return !empty($roles) ? array_keys($roles) : [];
	}

	function __toString()
	{
		return $this->getName();
	}

	/**
	 * Функция ищет Пользователя исключительно по его email
	 * @param string $email
	 * @return User
	 */
	public static function findByEmailOnly($email)
    {
		return static::findOne(['email' => $email]);
	}

	/**
	 * Get user certifications for auth action
	 *
	 * @return array|NULL
	 */
	public function getUserCerts()
	{
		$cert = new UserAuthCert();

		return $cert::find()->where(['userId' => $this->getId()])->all();
	}

	/**
	 * Get isReset property
	 *
	 * @return boolean
	 */
	public function getIsReset()
	{
		return ($this->isReset === 1);
	}

    public function getServiceExtModel($serviceId)
    {
        if ($module = Yii::$app->getModule($serviceId)) {
            if ($this->id) {
                return $module->getUserExtModel($this->id);
            } else {
                return $module->getUserExtModel();
            }
        }

        return null;
    }

    /**
     * Проверяем нет ли непогашенных задолженностей у пациента
     * @return bool|string
     */
    public function checkUserResponsibility()
    {
        $currentUrl = Url::to('');
        $activateUrl = Url::toRoute('/site/activate-account');
        $passwordResetUrl = Url::toRoute('/site/set-password');

        if (self::STATUS_ACTIVATED == $this->status && $currentUrl != $activateUrl) {
            return $activateUrl;
        } elseif (self::STATUS_ACTIVE == $this->status && $this->getIsReset() && $currentUrl != $passwordResetUrl) {
            return $passwordResetUrl;
        }

        return false;
    }

    /**
     * Get user name
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->lastName . ' '. $this->firstName .' '.$this->middleName;

        return trim(str_replace('  ', ' ', $name));
    }

    public function validateActivateKey($key)
    {
        if ($this->activateKey === $key) {
            return true;
        } else {
            return false;
        }
    }

    public function canActivateApprove($id)
    {
        if (!Yii::$app->commandBus->checkExistCommandAccept($this->id, 'UserActivate',
            [
                //'status' => 'forAcceptance',
                'entity' => 'user',
                'entityId' => $id,
            ]))
        {
            return true;
        }

        return false;
    }

    public function canSettingApprove($id)
    {
        if (!Yii::$app->commandBus->checkExistCommandAccept($this->id, 'UserSettingApprove',
            [
                //'status' => 'forAcceptance',
                'entity' => 'user',
                'entityId' => $id,
            ]))
        {
            return true;
        }

        return false;
    }

    public function isSecurityOfficer()
    {
        if (in_array($this->role, [self::ROLE_RSO, self::ROLE_LSO])) {
            return true;
        } else {
            return false;
        }
    }

    public static function getNotDeletedRoleCount($role)
    {
        return self::find()
                ->where(['role' => $role])
                ->andWhere(['!=', 'status', self::STATUS_DELETED])
                ->count();
    }

    public static function getNotDeletedSecureOfficersCount()
    {
        $count = 0;

        if (self::getNotDeletedRoleCount(self::ROLE_LSO) > 0) {
            $count++;
        }
        if (self::getNotDeletedRoleCount(self::ROLE_RSO) > 0) {
            $count++;
        }

        return $count;
    }

    public static function canUseSecurityOfficers()
    {
        if (self::getNotDeletedSecureOfficersCount() == 2) {
            return true;
        } else {
            return false;
        }
    }

    public function updateStatus($status)
    {
        if (!is_null($status)) {
            $this->status = $status;
            $this->updated_at = strtotime('now');

            return $this->save(false, ['status']);
        }

        return false;
    }

    public function getActivationUserKey()
    {
        if (in_array($this->status, [self::STATUS_ACTIVATED, self::STATUS_ACTIVATING])
            && Yii::$app->user->identity->isSecurityOfficer()
            && self::canUseSecurityOfficers()
        ) {
            if ($this->id != Yii::$app->user->id) {
                if (!Yii::$app->user->identity->canActivateApprove($this->id)) {
                    if (Yii::$app->user->identity->role === self::ROLE_LSO) {
                        $this->_activationUserKey = substr($this->activateKey, 0, 2) . '**';
                    } elseif (Yii::$app->user->identity->role === self::ROLE_RSO) {
                        $this->_activationUserKey = '**' . substr($this->activateKey, -2);
                    }
                }
            }
        } else {
            $this->_activationUserKey = null;
        }

        return $this->_activationUserKey;
    }

    public static function findByRole($roles = [])
    {
        return self::find()
                ->andwhere(['in', 'role', $roles])
                ->andWhere(['status' => self::STATUS_ACTIVE])->all();
    }

    public function getTerminals()
    {
        return $this->hasMany(UserTerminal::className(), ['userId' => 'id']);
    }

    public function getOwner()
    {
        return $this->hasOne(static::className(), ['id' => 'ownerId']);
    }

    /**
     * Функция проверяет, является ли
     * пользователь главным администратором
     */
    public function isMainAdmin()
    {
        // Первоначально главный администратор, пользователь
        // у которого роль администратор и id = 1
        if ($this->role == self::ROLE_ADMIN && $this->id == 1) {
            return true;
        } else {
            // Вторая попытка получения главного администратора
            // Главный администратор - пользователь,
            // у которого роль администратор,
            // активный статус и который создан раньше остальных

            // Находим пользователя, удовлетворяющего этим условиям
            $mainAdmin = User::find()
                ->where(['role' => User::ROLE_ADMIN, 'status' => User::STATUS_ACTIVE])
                ->orderBy(['created_at' => 'asc'])
                ->one();
            // Если такой пользователь не найден, то возвращаем false
            if (empty($mainAdmin)) {
                return false;
            }

            // Проверяем, является ли найденный пользователь текущим
            // и возвращем результаты проверки

            return $mainAdmin->id == $this->id;
        }
    }

}
