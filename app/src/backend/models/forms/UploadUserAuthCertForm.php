<?php

namespace backend\models\forms;

use common\base\Model;
use common\modules\certManager\components\ssl\X509FileModel;
use Yii;
use yii\web\UploadedFile;

class UploadUserAuthCertForm extends Model
{
    /** @var UploadedFile */
    public $certificateFile;

    /** @var X509FileModel */
    public $certificate;
    
    public $certId;
    
    public $isView;

    public function rules()
    {
        return [
            ['certificateFile', 'required'],
            ['certificateFile', 'safe'],
            ['certificateFile', 'file'],
            ['certificateFile', 'validateCertificateFile'],
            ['certId', 'safe'],
            ['isView', 'safe'],
        ];
    }

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            return $this->loadKeyCertificate();
        }
        return false;
    }

    private function loadKeyCertificate(): bool
    {
        $this->certificate = null;

        $this->certificateFile = UploadedFile::getInstance($this, 'certificateFile');
        if ($this->certificateFile === null) {
            Yii::error('Failed to load uploaded file');
            return false;
        }

        $certificate = file_get_contents($this->certificateFile->tempName);
        if ($certificate === false) {
            Yii::error('Failed to read uploaded file');
            return false;
        }

        try {
            $this->certificate = X509FileModel::loadData($certificate);
        } catch (\Throwable $exception) {
            Yii::info("Failed to load certificate from file, caused by: $exception");
        }

        return true;
    }

    public function validateCertificateFile($attribute, $params = [])
    {
        if (!$this->certificate) {
            $this->addError($attribute, Yii::t('app/user', 'Invalid certificate file format'));
        }
    }
}
