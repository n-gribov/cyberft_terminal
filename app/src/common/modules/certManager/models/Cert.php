<?php

namespace common\modules\certManager\models;

use addons\swiftfin\models\Document;
use common\base\interfaces\AttrShortcutInterface;
use common\components\TerminalId;
use common\helpers\Address;
use common\helpers\Countries;
use common\models\User;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\participant\models\BICDirParticipant;
use common\validators\TerminalIdValidator;
use DateTime;
use Yii;
use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property integer $id
 * @property integer $ownerId - идентификатор пользователя которому принадлежит сертификат
 * @property integer $userId  - идентификатор загрузившего пользователя
 * @property string  $certId
 * @property string  $validFrom
 * @property string  $validBefore
 * @property string  $useBefore

 * @property string $participantId
 * @property TerminalId $terminalId      - Идентификатор должен иметь 12 символов и использовать только латинские символы;
 * @property string $participantCode     - Первые 4 символа: уникальные для Участника, пример CYCP;
 * @property string $countryCode         - Следующие 2 символа: страна, для РФ – RU;
 * @property string $sevenSymbol         - Седьмой символ: месторасположение или дополнительный символ для уникального названия компании, например M (Москва);
 * @property string $delimiter           - Изначально разделитель, но может быть любым символом
 * @property string $terminalCode        - Девятый символ является кодом терминала.
 * @property string $participantUnitCode - Последние 3 символа: подразделения компании.

 * @property string  $fingerprint
 * @property string  $status
 * @property string  $filePath
 * @property string  $country
 * @property string  $state
 * @property string  $email
 * @property string  $role
 * @property integer $signAccess
 * @property string  $statusDescription
 * @property string  $lastName    Last name
 * @property string  $firstName   First name
 * @property string  $middleName  Middle name
 * @property int $autoUpdate

 * @property X509FileModel $certificate
 * @property CertSignDocument[] $certSignDocuments
 * @property Document[] $documents
 * @property User $user
 * @property User $owner
 */
class Cert extends ActiveRecord implements AttrShortcutInterface
{
    const CERT_ID_DIVIDER = '-';

    const STATUS_C0 = 'C0'; // Сертификат зарегистрирован в БД Терминала, но не загружен в хранилище
    const STATUS_C1 = 'C1'; // сертификат установлен
    const STATUS_C2 = 'C2'; // сертификат установлен вручную ГА
    const STATUS_C7 = 'C7'; // сертификат временно заблокирован
    const STATUS_C8 = 'C8'; // сертификат заблокирован но еще не удален из рабочей папки
    const STATUS_C9 = 'C9'; // сертификат отозван или заблокирован
    // Кастомные статусы согласно задаче CYB-2978
    const STATUS_C10 = 'C10'; // сертификат активирован
    const STATUS_C11 = 'C11'; // сертификат не активен
    const STATUS_C12 = 'C12'; // сертификат заблокирован

    // const ROLE_UNDEFINED  = 0;
    // const ROLE_OPERATOR   = 1;
    const ROLE_SIGNER     = 2;
    // const ROLE_CONTROLLER = 3;
    const ROLE_SIGNER_BOT = 255;

    const TYPE_UNDEFINED  = 'Undefined';
    const TYPE_OPENSSL  = 'Open SSL';
    const TYPE_CRYPTOPRO  = 'Crypto Pro';

    const SCENARIO_AUTO_IMPORT = 'autoImport';

    /**
     * @var X509FileModel
     */
    private $_certificate;

    /**
     * @var TerminalId
     */
    private $_terminalId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['certificate', 'validateCertificate', 'on' => ['create']],
            [['validFrom', 'validBefore', 'useBefore', 'fingerprint', 'terminalId', 'lastName', 'firstName', 'middleName'], 'required', 'on' => ['create', 'batchUpload']],
            [['validFrom', 'validBefore', 'useBefore'], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            [['userId', 'ownerId'], 'integer'],
            ['email', 'email'],
            ['terminalId', 'filter', 'filter' => 'strtoupper'],
            ['terminalId', TerminalIdValidator::className()],
            [
                    'terminalId', 'unique',
                    'targetAttribute' => [
                            'participantCode', 'countryCode', 'sevenSymbol', 'delimiter', 'terminalCode',
                            'participantUnitCode', 'fingerprint'
                    ],
                    'message' => Yii::t('app/cert', 'Certificate for the specified terminal is already registered')
            ],
            ['participantCode', 'string', 'max' => 4],
            ['countryCode', 'string', 'max' => 2],
            ['sevenSymbol', 'string', 'max' => 1],
            ['delimiter', 'string', 'max' => 1],
            ['terminalCode', 'string', 'max' => 1],
            ['participantUnitCode', 'string', 'max' => 3],
            ['role', 'default', 'value' => self::ROLE_SIGNER_BOT],
            ['role', 'in', 'range' => $this->roles()],
            [['fingerprint'], 'string', 'max' => 64],
            ['statusDescription', 'string'],
            [['fingerprint'], 'filter', 'filter' => 'strtoupper'],
            [['email', 'phone', 'post', 'lastName', 'firstName', 'middleName'], 'string', 'max' => 255],
            ['status', 'in',
                'range' => [
                    self::STATUS_C0, self::STATUS_C1, self::STATUS_C2, self::STATUS_C7,
                    self::STATUS_C8, self::STATUS_C9, self::STATUS_C10, self::STATUS_C11, self::STATUS_C12
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cert';
    }

    /**
     * Список наименований статусов
     * @return array
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_C10 => Yii::t('app/cert', 'Active'),
            self::STATUS_C11 => Yii::t('app/cert', 'Inactive'),
            self::STATUS_C12 => Yii::t('app/cert', 'Blocked'),
        ];
    }

    /**
     * Получение наименования статуса
     * @return string
     */
    public function getStatusLabel()
    {
        return (!is_null($this->status) && array_key_exists($this->status, self::getStatusLabels()))
            ? self::getStatusLabels()[$this->status]
            : $this->status;
    }

    /**
     * @param string $value
     * @return null|static
     */
    public static function findByCertId($value)
    {
        $idData = self::parseCertId($value);
        if (2 == count($idData)) { // передан certId
            $condition = array_merge(
                TerminalId::extract($idData[0])->toArray(),
                ['fingerprint' => $idData[1]]
            );

            return self::findOne($condition);
        } else if (intval($value)) { //передан id
            return self::findOne(['id' => $value]);
        }

        return null;
    }

    /**
     * Поиск сертфиката по id участника и fingerprint сертификата
     * @param $participantId
     * @param $fingerprint
     * @return static
     */
    public static function findByParticipantCertId($participantId, $fingerprint)
    {
        if (!($terminalId = TerminalId::extractParticipantId($participantId))) {
            return null;
        }
	$condition = $terminalId->toArray(true); // выбиваем пустые условия

        return self::findOne(
            array_merge($condition, ['fingerprint' => $fingerprint])
        );
    }

    /**
     * Поиск сертификатов по id участника
     * @param $participantId
     * @return static
     */
    public static function findByParticipant($participantId)
    {
        $terminalId = TerminalId::extractParticipantId($participantId);

        if (!$terminalId) {
            return null;
        }

        $condition = array_diff($terminalId->toArray(), ['' => null]); // Выбиваем пустые условия

        return self::find()
            ->andFilterWhere($condition)
            ->andFilterWhere(['status' => self::STATUS_C10])
            ->orderBy(['terminalCode' => SORT_ASC, 'participantUnitCode' => SORT_ASC])
            ->distinct();
    }

    /**
     * Поиск сертификатов по BIC участника
     * @param $participantBic
     * @return static
     */
    public static function findByBic($participantBic)
    {
        $terminalId = TerminalId::extractBic($participantBic);

        if (!$terminalId) {
            return null;
        }

        $condition = $terminalId->toArray(true); // Выбиваем пустые условия

        return self::find()
            ->andFilterWhere($condition)
            ->andFilterWhere(['status' => self::STATUS_C10])
            ->orderBy(['terminalCode' => SORT_ASC, 'participantUnitCode' => SORT_ASC])
            ->distinct();
    }

    /**
     * Поиск сертификата по любому адресу (terminalId, participantId, BIC) и фингерпринту
     *
     * @param $address
     * @param null $fingerprint
     * @param string $status
     * @return static
     * @throws ErrorException
     */
    public static function findByAddress($address, $fingerprint = null, $status = self::STATUS_C10)
    {
        if (!($terminalId = TerminalId::extract($address))) {
            throw new ErrorException(Yii::t('app/cert', "Terminal identifier '$address' has wrong format."));
        }

        $condition = $terminalId->toArray(true); // выбиваем пустые условия
        if ($fingerprint) {
            $condition['fingerprint'] = $fingerprint;
        }

        if ($status) {
            $condition['status'] = self::STATUS_C10;
        }

        return self::find()
            ->where($condition)
            ->orderBy(['terminalCode' => SORT_ASC, 'participantUnitCode' => SORT_ASC])
            ->one();
    }

    /**
     * Поиск всех сертификатов по любому адресу (terminalId, participantId, BIC)
     *
     * @param $address
     * @return static
     * @throws ErrorException
     */
    public static function findAllByAddress($address)
    {
        if (!($terminalId = TerminalId::extract($address))) {
            throw new ErrorException(Yii::t('app/cert', "Terminal identifier '$address' has wrong format."));
        }

        $condition = $terminalId->toArray(true); // выбиваем пустые условия

        return self::find()->where($condition)->all();
    }

    public static function getParticipantAddress($address)
    {
        $length = strlen($address);
        $cert = null;
        if ($length == 12) {
            $cert = static::findByAddress($address);
        } else if ($length == 11) {
            $cert = static::findByParticipant($address)->one();
        } else if ($length == 8) {
            $cert = static::findByBic($address)->one();
        }

        return $cert->terminalId;
    }

    /**
     * Поиск сертификата по любому адресу (terminalId, participantId, BIC) и роли
     *
     * @param $address
     * @param $role
     * @param $filterActive искать только активные серты
     * @return static
     * @throws ErrorException
     */
    public static function findByRole($address, $role, $filterActive = false)
    {
        $terminalId = self::addressToTerminalId($address);
        $condition = $terminalId->toArray(true); // выбиваем пустые условия
        $condition['role'] = $role;
        $query = static::find()->where($condition);
        if ($filterActive) {
            $query->andWhere(['status' => static::STATUS_C10])->orderBy(['id' => SORT_ASC]);
        }

        return $query->one();
    }

    protected static function addressToTerminalId($address)
    {
        if (!($terminalId = TerminalId::extract($address))) {
            throw new ErrorException(Yii::t('app/cert', "Terminal identifier '$address' has wrong format."));
        }

        return $terminalId;
    }

    /**
     * @param string $value
     * @return static
     */

    public static function findByPk($value)
    {
        return self::findOne(['id' => $value, 'status' => self::STATUS_C10]);
    }

    /**
     * @param string $value
     * @return static[]
     */
    public static function findAllByFingerprint($value)
    {
        return self::findAll(['fingerprint' => strtoupper($value)]);
    }

    /**
     * @param string $value
     * @return static[]
     */
    public static function findByFingerprint($value)
    {
        return self::findOne(['fingerprint' => strtoupper($value)]);
    }

    /**
     * @param string $value
     * @return array
     */
    protected static function parseCertId($value)
    {
        return explode(self::CERT_ID_DIVIDER, $value);
    }

    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            'batchUpload'   => [
                'type', 'validFrom', 'validBefore', 'useBefore', 'participantCode', 'countryCode', 'sevenSymbol',
                'delimiter', 'terminalCode', 'participantUnitCode', 'fingerprint',
                'status', 'email', 'phone', 'post', 'role', 'signAccess',
                'body', 'lastName', 'firstName', 'middleName', 'fullName'
            ],
            self::SCENARIO_AUTO_IMPORT => [
                'type', 'validFrom', 'validBefore', 'useBefore', 'participantCode', 'countryCode', 'sevenSymbol',
                'delimiter', 'terminalCode', 'participantUnitCode', 'fingerprint',
                'status', 'post', 'role', 'signAccess', 'body'
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => Yii::t('app/cert', 'Id'),
            'ownerId'             => Yii::t('app/cert', 'Owner Id'),
            'userId'              => Yii::t('app/cert', 'User Id'),
            'typeLabel'           => Yii::t('app/cert', 'Type'),
            'certId'              => Yii::t('app/cert', 'Participant Id'),
            'validFrom'           => Yii::t('app/cert', 'Generated'),
            'validBefore'         => Yii::t('app/cert', 'Expires'),
            'useBefore'           => Yii::t('app/cert', 'Expires on'),
            'validBeforeReal'     => Yii::t('app/cert', 'Block date'),
            'status'              => Yii::t('app/cert', 'Status'),
            'fingerprint'         => Yii::t('app/cert', 'Certificate Thumbprint'),
            'participantCode'     => Yii::t('app/cert', 'Participant code'),
            'countryCode'         => Yii::t('app/cert', 'Country code'),
            'sevenSymbol'         => Yii::t('app/cert', 'Seven symbol'),
            'terminalCode'        => Yii::t('app/cert', 'Terminal symbol'),
            'participantUnitCode' => Yii::t('app/cert', 'Participant unit code'),
            'terminalId'          => Yii::t('app/terminal', 'Terminal ID'),
            'participantId'       => Yii::t('app/cert', 'Participant Id'),
            'email'               => Yii::t('app/cert', 'Email'),
            'phone'               => Yii::t('app/cert', 'Phone'),
            'post'                => Yii::t('app/cert', 'Post'),
            'role'                => Yii::t('app/cert', 'Role'),
            'roleLabel'           => Yii::t('app/cert', 'Role'),
            'signAccess'          => Yii::t('app/cert', 'Sign Access'),
            'user'                => Yii::t('app/cert', 'Uploaded by'),
            'owner'               => Yii::t('app/cert', 'Cert owner'),
            'filePath'            => Yii::t('app/cert', 'File'),
            'country'             => Yii::t('app/cert', 'Country'),
            'certificate'         => Yii::t('app/cert', 'Certificate'),
            'lastName'            => Yii::t('app/cert', 'Last name'),
            'firstName'           => Yii::t('app/cert', 'First name'),
            'middleName'          => Yii::t('app/cert', 'Middle name'),
            'statusDescription'     => Yii::t('app/cert', 'Status description'),
        ];
    }

    public function attributeLabelShortcuts()
    {
        return [];
    }

    /**
     * @return array
     */
    public function roleLabels()
    {
        return [
            self::ROLE_SIGNER     => Yii::t('app/cert', 'Signer'),
            self::ROLE_SIGNER_BOT => Yii::t('app/cert', 'Controller'),
            // self::ROLE_OPERATOR   => Yii::t('app/cert', 'Operator'),
            // self::ROLE_UNDEFINED  => Yii::t('app/cert', 'Undefined'),
            // self::ROLE_CONTROLLER => Yii::t('app/cert', 'Controller'),
        ];
    }

    /**
     * @return string
     */
    public function getRoleLabel()
    {
        return $this->roleLabels()[$this->role];
    }

    /**
     * @return array
     */
    public function roles()
    {
        return array_keys($this->roleLabels());
    }

    /**
     * Вернет наиболее раннюю дату, по истечении которой сертификат не валиден
     * @return string
     */
    public function getValidBeforeReal()
    {
        if ((new DateTime($this->validBefore)) <= (new DateTime($this->useBefore))) {
            return $this->validBefore;
        } else {
            return $this->useBefore;
        }
    }

    public function isExpired()
    {
        // Если дата истечения сертификата не указана, это ошибка
        if ($this->validBefore == '0000-00-00 00:00:00') {
            return true;
        }

        // Если дата истечения просрочена, это ошибка
        // Используется дата истечения, задаваемая вручную, которая может отличаться
        // от истинной даты сертификата (validBefore), т.е. просроченный сертификат
        // может быть продлен вручную.
        $now = strtotime(date('c'));
        $expirationDate = strtotime($this->useBefore);

        if ($expirationDate < $now) {
            return true;
        }

        return false;
    }

    public function isActive()
    {
        return $this->status == static::STATUS_C10;
    }

    public function setActive($active)
    {
        $this->status = $active ? static::STATUS_C10 : static::STATUS_C11;
    }

    public function isValid()
    {
        return !$this->isExpired() && $this->isActive();
    }

    /**
     * @param $value
     */
    public function setTerminalId($value)
    {
        if (!$value) {
            return;
        }

        if (($id = TerminalId::extract($value))) {
            $this->setAttributes($id->toArray());
        } else {
            $this->addError('terminalId', Yii::t('app/cert', "Terminal identifier '$value' has wrong format."));
        }
    }

    /**
     * @return TerminalId
     */
    public function getTerminalId()
    {
        if (!is_object($this->_terminalId) || !is_a('TerminalId', $this->_terminalId)) {
            $this->_terminalId = new TerminalId();
        }
        // синхронизируем свойства сертификата и свойства объекта идентификатора
        $this->_terminalId->participantCode     = $this->participantCode;
        $this->_terminalId->countryCode         = $this->countryCode;
        $this->_terminalId->participantUnitCode = $this->participantUnitCode;
        $this->_terminalId->sevenSymbol         = $this->sevenSymbol;
        $this->_terminalId->delimiter           = $this->delimiter;
        $this->_terminalId->terminalCode        = $this->terminalCode;

        return $this->_terminalId;
    }

    /**
     * @return string
     */
    public function getParticipantId()
    {
        return $this->terminalId->getParticipantId();
    }

    /**
     * @return string
     */
    public function getBic()
    {
        return $this->terminalId->getBic();
    }

    /**
     * @return null|string
     */
    public function getCountry() {
        return Countries::getName($this->countryCode);
    }

    /**
     * @return ActiveQuery
     */
    public function getCertSignDocuments()
    {
        return $this->hasMany(CertSignDocument::className(), ['certId' => 'id']);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @return User
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'ownerId']);
    }

    /**
     * Функция возвращает объект - сертификат X509. Если сертификат еще не считан,
     * то он подгружается из модели Cert
     * @return X509FileModel
     *
     */
    public function getCertificate()
    {
        if (!$this->_certificate && $this->body) {
            $this->_certificate = X509FileModel::loadData($this->body);
        }

        return $this->_certificate;
    }

    /**
     * Функция присваивает сертификат.
     * Сертификат задается либо моделью класса X509FileModel, либо при помощи
     * предварительно загруженного файла (класс UploadedFile).
     * @param X509FileModel|UploadedFile $value Значение - сертификат
     */
    public function setCertificate($value)
    {
        if (is_a($value, X509FileModel::className())) {
            $this->_certificate = $value;
        } else if (is_a($value,UploadedFile::className())) {
            $this->_certificate = new X509FileModel();
            $this->_certificate->setNewFile($value);
        } else {
            $this->_certificate = null;
        }
    }

    /**
     * Функция присваивает сертификат, который задается именем файла.
     * Только для использования в консоли.
     * @param string $file Файл сертификата
     * @return bool
     */
    public function addCertificate($file)
    {
        $x509FileModel = X509FileModel::loadFile($file);

        if (!$x509FileModel->validate()) {
            $this->_certificate = null;
            $this->addError('certificate', Yii::t('app', 'Error: Certificate file "{file}" is invalid', ['file' => $file]));

            return false;
        }

        $this->_certificate = &$x509FileModel;
        $this->_certificate->setNewFile($file); // Activate newFile mode
        $this->terminalId = Yii::$app->terminal->address;
        $this->userId = null; // For certs added via console userId must be empty

        return true;
    }

    /**
     * Функция формирует строку-коммюнике из всех сообщений, которые накапливаются
     * в процессе жизнедеятельности этой модели. Используется в addCertificate().
     * @param bool $ignoreLabel
     * @return string
     */
    public function getErrorsSummary($ignoreLabel = false)
    {
        $str = '';
        foreach ($this->errors as $field => $errors) {
            $str .= (!$ignoreLabel ? $this->getAttributeLabel($field) . ": " . (count($errors) > 1 ? "\n" : null) : null);
            $str .= implode("\n", $errors);
        }

        return $str;
    }

    /**
     * @return string PEM
     */
    public function getCertificateContent()
    {
        return $this->body;
    }

    /**
     * Возвращает строку - ID сертификата.
     * Используется при формировании имени файла, в котором хранится сертификат,
     * помещенный в хранилище.
     * @return string
     */
    public function getCertId()
    {
        return $this->terminalId . self::CERT_ID_DIVIDER . $this->fingerprint;
    }

    /**
     * Функция валидирует сертификат, возвращая true в случае успеха и false
     * при возникновении ошибки.
     * А также заполняет некоторые атрибуты модели значениями из поля сертификата subject
     * @return boolean
     */
    public function validateCertificate()
    {
        if (!$this->_certificate) {
            $this->addError('certificate', Yii::t('app/cert', 'Please, specify the certificate'));
            return false;
        } else if ($this->_certificate && !$this->_certificate->validate()) {
            // прокидываем валидацию файла сертификата
            $this->addError(
                'certificate',
                implode("\n", $this->getCertificate()->getErrors('newFile'))
            );

            return false;
        }

        $this->loadAttributesFromCertificate();

        return true;
    }

    public function loadAttributesFromCertificate(): void
    {
        if (!$this->getCertificate()) {
            throw new \Exception('Got empty certificate');
        }
        $subj = $this->_certificate->getSubject();
        $this->fingerprint	= $this->_certificate->getFingerprint();
        $this->validFrom	= $this->_certificate->getValidFrom()->format('Y-m-d H:i:s');
        $this->validBefore	= $this->_certificate->getValidTo()->format('Y-m-d H:i:s');
        $this->useBefore	= $this->validBefore;
        $this->email		= (!$this->email && isset($subj['emailAddress']) ? $subj['emailAddress'] : $this->email);
        $this->body			= $this->certificate->getBody();
    }

    public static function populateRecord($record, $row)
    {
        parent::populateRecord($record, $row);
    }

    /**
     * Функция-обработчик события.
     * Проверяет сертификат перед сохранением в БД.
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            if (!$this->certificate->validate()) {
                $this->addError('certificate', $this->certificate->getErrorsSummary());
                return false;
            } else {
                // Для новых сертификатов если они не добавлены автоматически по умолчанию ставим статус "Не активен"
                if ($this->getScenario() !== self::SCENARIO_AUTO_IMPORT) {
                    $this->status = self::STATUS_C11;
                }
            }
        }

        $this->body = $this->certificate->getBody();

        return parent::beforeSave($insert);
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullName()
    {
        $fullName = $this->lastName . ' '. $this->firstName .' '.$this->middleName;

        return trim(str_replace('  ', ' ', $fullName));
    }

    /**
     * Set full name
     *
     * @return string
     */
    public function setFullName($fullName)
    {
        $fullNameParts = explode(' ', preg_replace('/\s+/', ' ', $fullName), 3);

        $this->lastName = $fullNameParts[0];
        $this->firstName = isset($fullNameParts[1]) ? $fullNameParts[1] : '';
        $this->middleName = isset($fullNameParts[2]) ? $fullNameParts[2] : '';
    }

    /**
     * Проверка актуальности сертификата
     *
     * @return type
     */
    public function getIsActive()
    {
        return strtotime($this->useBefore) > mktime();
    }

    /**
     * Сертификат истекает в ближайшее время
     * @return bool
     */
    public function isExpiringSoon($days = 30)
    {
        $dateNow = new DateTime();
        $dateExpiration = new DateTime($this->useBefore);
        $interval = $dateNow->diff($dateExpiration);

        return $interval->days <= $days;
    }

    public static function getAutobotCerts()
    {
        return self::find()->where(['role' => self::ROLE_SIGNER_BOT])->all();
    }

    public function getParticipant()
    {
        return $this->hasOne(BICDirParticipant::className(), ['participantBIC' => 'participantId']);
    }

    public static function formatCertAttributes($attributesString)
    {
        $attributes = array_reduce(
            preg_split('/\//', $attributesString, -1, PREG_SPLIT_NO_EMPTY),
            function ($attributes, $pairString) {
                if (strpos($pairString, '=') === false) {
                    return $attributes;
                }
                list($key, $value) = explode('=', $pairString, 2);
                $attributes[$key] = $value;
                return $attributes;
            },
            []
        );

        $attributesToRename= [
            'ST'          => 'S',
            'description' => 'Description',
            'street'      => 'STREET',
            'title'       => 'T',
            'GN'          => 'G'
        ];

        foreach ($attributesToRename as $oldKey => $newKey) {
            if (array_key_exists($oldKey, $attributes)) {
                $attributes[$newKey] = $attributes[$oldKey];
                unset($attributes[$oldKey]);
            }
        }

        $orderedKeys = ['CN', 'Description', 'T', 'S', 'STREET', 'G', 'SN', 'OU', 'O', 'L', 'C'];
        $unorderedKeys = array_diff(array_keys($attributes), $orderedKeys);

        return array_reduce(
            array_merge($orderedKeys, $unorderedKeys),
            function ($formattedAttributes, $key) use ($attributes) {
                if (array_key_exists($key, $attributes)) {
                    $formattedAttributes .= empty($formattedAttributes) ? '' : "\r\n";
                    $formattedAttributes .= $key . ' = ' . $attributes[$key];
                }
                return $formattedAttributes;
            },
            ''
        );
    }

    /**
     * 12-значное представление терминала, к которому привязан сертификат
     * @return string
     */
    function getTerminalAddress()
    {
        return Address::fixAddress($this->participantId, $this->terminalCode);
    }
}
