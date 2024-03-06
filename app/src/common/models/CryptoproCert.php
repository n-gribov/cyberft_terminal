<?php

namespace common\models;

use addons\fileact\models\FileactCryptoproCert;
use addons\ISO20022\models\ISO20022CryptoproCert;
use common\helpers\CryptoProHelper;
use common\modules\certManager\components\ssl\X509FileModel;
use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\base\InvalidArgumentException;

/**
 * This is the model class for table "iso20022_CryptoproCert".
 *
 * @package addons
 * @subpackage fileact
 *
 * @property integer $id          Row ID
 * @property string  $terminalId  Terminal ID
 * @property string  $keyId       Key fingerprint
 * @property string  $ownerName   User, owner
 * @property string  $certData    Certificate data
 * @property string  $validBefore Certificate valid date
 * @property string  $serialNumber
 * @property string  $senderTerminalAddress Sender terminal Id
 * @property X509FileModel $certificate
 */
class CryptoproCert extends ActiveRecord
{
    const STATUS_NOT_READY = 'notReady';
    const STATUS_READY = 'ready';

    protected static $_table = null;
    /**
     * @var X509FileModel
     */
    protected $_certificate;

    public static function tableName()
    {
        return static::$_table ?: parent::tableName();
    }

    public static function getInstance($moduleName)
    {
        // пока в качестве костыля, чтобы в дальнейшем избавиться от разных моделей для каждого модуля

        if ($moduleName == 'ISO20022') {
            return new ISO20022CryptoproCert();
        } else if ($moduleName == 'fileact') {
            return new FileactCryptoproCert();
        } else if ($moduleName == 'VTB') {
            /** @var \addons\VTB\VTBModule $module */
            $module = Yii::$app->addon->getModule($moduleName);
            if ($module) {
                return $module->getCryptoProCertModel();
            }

            throw new InvalidArgumentException('VTB module not found');
        }

        return new static();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['certificate', 'validateCertificate', 'on' => 'create'],
            [['certData', 'status', 'keyId', 'ownerName'], 'string'],
            ['serialNumber', 'string'],
            [['status'], 'default', 'value' => self::STATUS_NOT_READY],
            ['terminalId', 'integer'],
            [['validBefore', 'terminalId', 'senderTerminalAddress'], 'required'],
            ['terminalId', 'exist', 'targetClass' => 'common\models\Terminal', 'targetAttribute' => 'id'],
            ['senderTerminalAddress', 'string', 'max' => 12] // missing in vtb
        ];
    }

    public static function getActiveLabels()
    {
        return [
            self::STATUS_READY       => Yii::t('app/fileact', 'Active'),
            self::STATUS_NOT_READY   => Yii::t('app/fileact', 'Inactive'),
        ];
    }

    public function getActiveLabel()
    {
        return (!is_null($this->status) && array_key_exists($this->status, self::getActiveLabels()))
            ? self::getActiveLabels()[$this->status]
            : $this->status;
    }

    public static function getStatusLabels()
    {
        return [
            self::STATUS_READY     => Yii::t('app/iso20022', 'Ready to use'),
            self::STATUS_NOT_READY => Yii::t('app/iso20022', 'Not ready'),
        ];
    }

    public function getStatusLabel()
    {
        return (!is_null($this->status) && array_key_exists($this->status, self::getStatusLabels()))
            ? self::getStatusLabels()[$this->status]
            : $this->status;
    }

    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app/iso20022', 'ID'),
            'terminalId' => Yii::t('app/cert', 'Recipient terminal'),
            'ownerName'  => Yii::t('app/cert', 'Owner Name'),
            'certData'   => Yii::t('app/iso20022', 'Certificate data'),
            'keyId'   => Yii::t('app/iso20022', 'Fingerprint'),
            'serialNumber' => Yii::t('app/iso20022', 'Serial Number'),
            'status' => Yii::t('app/iso20022', 'Status'),
            'validBefore' => Yii::t('app/fileact', 'Expire date'),
            'certificate' => Yii::t('app/iso20022', 'Certificate'),
            'senderTerminalAddress' => Yii::t('app/iso20022', 'Sender terminal address'), // missing in vtb
        ];
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }

    /**
     * Функция возвращает объект - сертификат X509. Если сертификат еще не считан,
     * то он подгружается из модели Cert
     * @return X509FileModel
     *
     */
    public function getCertificate()
    {
        if (!$this->_certificate && $this->certData) {
            $this->_certificate = X509FileModel::loadData($this->certData);
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
        } else if (is_a($value, UploadedFile::className())) {
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

        $this->_certificate = $x509FileModel;
        $this->_certificate->setNewFile($file); // Activate newFile mode
        $this->terminalId = Yii::$app->terminal->address;

        return true;
    }

    /**
     *
     * @return string PEM
     */
    public function getCertificateContent()
    {
        return $this->certData;
    }

    public function validateCertificate()
    {
        if (!$this->_certificate) {
            $this->addError('certificate', Yii::t('app/cert', 'Please, specify the certificate'));

            return false;
            // прокидываем валидацию файла сертификата
        } else if (!$this->_certificate->validate()) {
            $this->addError(
                'certificate',
                implode("\n", $this->getCertificate()->getErrors('newFile'))
            );

            return false;
        }

        $subj = $this->_certificate->getSubject();
        $this->keyId = $this->_certificate->getFingerprint();
        $this->validBefore = $this->_certificate->getValidTo()->format('Y-m-d H:i:s');
        $this->certData	= $this->_certificate->getBody();
        $this->ownerName = $subj['CN'];
        $this->serialNumber = $this->_certificate->getSerialNumber(); // missing in fileact and vtb

        return true;
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            if (!$this->certificate->validate()) {
                $this->addError('certificate', $this->certificate->getErrorsSummary());

                return false;
            }
        }

        $this->certData = $this->certificate->getBody();

        return parent::beforeSave($insert);
    }

    public function getErrorsSummary($ignoreLabel = false)
    {
        $str = '';
        foreach ($this->errors as $field => $errors) {
            $str .= (!$ignoreLabel ? $this->getAttributeLabel($field).": ".(count($errors) > 1 ? "\n" : null) : null);
            $str .= implode("\n", $errors);
        }
        return $str;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Если это добавление нового сертификата,
        // то добавляем его в certmgr
        if ($insert) {
            CryptoProHelper::addCertificateFromTerminal($this);
        }
    }

}
