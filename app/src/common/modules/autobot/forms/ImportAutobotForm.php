<?php

namespace common\modules\autobot\forms;

use Yii;
use yii\web\UploadedFile;

/**
 *
 * @property null|string $privateKey
 * @property null|string $certificate
 * @property null|string $publicKey
 */
class ImportAutobotForm extends CreateAutobotForm
{
    /** @var UploadedFile */
    public $publicKeyFile;

    /** @var UploadedFile */
    public $privateKeyFile;

    /** @var UploadedFile */
    public $certificateFile;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['publicKeyFile', 'privateKeyFile', 'certificateFile'], 'required'],
                [['publicKeyFile', 'privateKeyFile', 'certificateFile'], 'safe'],
                [['publicKeyFile', 'privateKeyFile', 'certificateFile'], 'file'],
                ['password', 'validatePassword'],
            ]
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'publicKeyFile' => Yii::t('app/autobot', 'Public key'),
                'privateKeyFile' => Yii::t('app/autobot', 'Private key'),
                'certificateFile' => Yii::t('app/autobot', 'Certificate'),
            ]
        );
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        $this->publicKeyFile = UploadedFile::getInstance($this, 'publicKeyFile');
        $this->privateKeyFile = UploadedFile::getInstance($this, 'privateKeyFile');
        $this->certificateFile = UploadedFile::getInstance($this, 'certificateFile');
        return $result;
    }

    public function validatePassword($attribute, $params)
    {
        $keyResource = openssl_pkey_get_private($this->privateKey, $this->password);
        if ($keyResource === false) {
            $this->addError('password', Yii::t('app/autobot', 'Unable to open private key with specified password'));
        } else {
            openssl_free_key($keyResource);
        }
    }

    public function getPublicKey(): ?string
    {
        return $this->getUploadedFileContent('publicKeyFile');
    }

    public function getPrivateKey(): ?string
    {
        return $this->getUploadedFileContent('privateKeyFile');
    }

    public function getCertificate(): ?string
    {
        return $this->getUploadedFileContent('certificateFile');
    }

    private function getUploadedFileContent(string $uploadedFileAttributeName): ?string
    {
        if ($this->$uploadedFileAttributeName === null) {
            return null;
        }

        $content = file_get_contents($this->$uploadedFileAttributeName->tempName);
        return $content === false ? null : $content;
    }
}
