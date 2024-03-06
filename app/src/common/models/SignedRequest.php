<?php

namespace common\models;

use Yii;
use common\modules\certManager\Module as CertManagerModule;
use \common\modules\certManager\components\ssl\Exception as CertManagerException;
use yii\db\Exception as DbException;

/**
 * Это НЕ запрос на подписание сертификата, это некий подписанный реквест
 * @todo подумать над переносом в certManager
 */
class SignedRequest extends \common\base\Model
{
    public $format;
    public $app;
    public $fingerprint;
    public $isDetached;
    public $raw;
    public $sign;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['format', 'app', 'fingerprint', 'isDetached', 'raw', 'sign'], 'required'],
            ['sign', 'validateSign']
        ];
    }

    public function validateSign($attribute, $params)
    {
        Yii::$app->getModule('certManager');
        try {
            if (
            !CertManagerModule::getInstance()->ssl
                ->verifyPkcs7(
                    $this->raw,
                    $this->$attribute,
                    CertManagerModule::getCertId($this->fingerprint),
                    $this->isDetached
                )
            ) {
                $this->addError($attribute, Yii::t('app/cert', 'Incorrect signature'));
            } else {
                return true;
            }
        } catch (CertManagerException $e) {
            if (CertManagerException::ERROR_INVALID_CERT_ID === $e->getCode()) {
                $this->addError($attribute, Yii::t('app/cert', 'Invalid certificate fingerprint'));
            } else if (CertManagerException::ERROR_UNKNOWN_CERT_ID === $e->getCode()) {
                $this->addError($attribute, Yii::t('app/cert', 'Requested certificate is not registered in the Terminal'));
            } else if (CertManagerException::ERROR_BROKEN_CERT_PATH === $e->getCode()) {
                $this->addError($attribute, Yii::t('app/cert', 'Technical error. Key associated with the certificate was not found.'));
            }
        } catch (DbException $e) {
            $this->addError(
                $attribute,
                Yii::t(
                    'app/cert', 'Technical error. The signature cannot be verified, please try again later. \n{msg}',
                    [$e->getMessage()]
                )
            );
        } catch (\Exception $e) {
            $this->addError(
                $attribute, Yii::t('app/cert', 'Unknown signature verification error. \n{msg}', [$e->getMessage()])
            );
        }

        return false;
    }

}
