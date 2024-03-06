<?php

namespace common\models\form;

use backend\services\UserAuthCertService;
use common\helpers\DsgostSigner;
use common\models\User;
use common\models\UserAuthCert;
use Exception;
use Yii;
use yii\base\Model;

class LoginKeyForm extends Model
{
    /**
     * Remember time
     */
    const REMEMBER_TIME = 28800;

    /**
     * @var UserAuthCert $_userAuthCertificate User auth certificate
     */
    private $_userAuthCertificate;

    /**
     * Login
     *
     * @param string $passcode    Passcode
     * @param string $fingerprint Fingerprint
     * @param string $signature   Signature
     * @return boolean|string Return TRUE is success or error message
     * @throws Exception
     */
    public function login($passcode, $fingerprint, $signature)
    {
        try {
            $cert = $this->getAuthCert($fingerprint);
            if (empty($cert)) {
                throw new \DomainException(Yii::t('app/user', 'Can not find certificate'));
            }

            if ((int)$cert->user->authType !== User::AUTH_TYPE_KEY) {
                throw new \DomainException(Yii::t('app', 'You can login only with password.'));
            }

            if (empty($passcode)) {
                throw new \Exception('Got empty passcode');
            }

            if (!$this->verifySignature($cert, $passcode, $signature)) {
                Yii::info('Got invalid signature');
                throw new \DomainException(Yii::t('app/user', 'Authorization failed'));
            }

            if (!Yii::$app->user->login($cert->user, self::REMEMBER_TIME)) {
                Yii::info('Login has failed');
                throw new \DomainException(Yii::t('app/user', 'Authorization failed'));
            }

            if ($cert->user->status == User::STATUS_INACTIVE || $cert->user->status == User::STATUS_ACTIVATING) {
                throw new \DomainException(Yii::t('app', 'Your account is blocked or not activated! Contact your administrator!'));
            }

            return true;
        } catch (\DomainException $exception) {
            return $exception->getMessage();
        } catch (\Exception $exception) {
            Yii::info("Login by key failed, caused by: $exception");
            return Yii::t('app/user', 'Authorization failed');
        }
    }

    /**
     * Get user
     *
     * @return User|NULL User instanse or NULL
     * @throws Exception
     */
    public function getUser()
    {
        try {
            if(empty($this->_userAuthCertificate)) {
                throw new Exception('User auth certificate is empty!');
            }

            return $this->_userAuthCertificate->user;
        } catch (Exception $ex) {
            Yii::warning($ex->getMessage());

            return null;
        }
    }

    /**
     * Get user auth certificate
     *
     * @param string $fingerprint Certificate fingerprint
     * @return UserAuthCert
     */
    protected function getAuthCert($fingerprint)
    {
        if (empty($this->_userAuthCertificate)) {
            $this->_userAuthCertificate = UserAuthCert::findOne(['fingerprint' => $fingerprint]);
        }

        return $this->_userAuthCertificate;
    }

    private function verifySignature(UserAuthCert $cert, string $passcode, string $signature): bool
    {
        $authCertService = new UserAuthCertService();
        if ($authCertService->isGostCertificate($cert->certificate)) {
            $authCertService->installCertificateToCryptoPro($cert->certificate);
            return $this->verifyGostSignature($cert, $passcode, $signature, true)
                || $this->verifyGostSignature($cert, $passcode, $signature, false);
        } else {
            return $this->verifyRsaSignature($cert, $passcode, $signature);
        }
    }

    private function verifyGostSignature(UserAuthCert $cert, string $passcode, string $signature, bool $reverseByteOrder): bool
    {
        $signer = new DsgostSigner($reverseByteOrder);
        return $signer->verify($passcode, base64_decode($signature), strtolower($cert->fingerprint));
    }

    private function verifyRsaSignature(UserAuthCert $cert, string $passcode, string $signature): bool
    {
        return Yii::$app->cryptography->verify($passcode, base64_decode($signature), $cert->certificate, 'sha256');
    }
}
