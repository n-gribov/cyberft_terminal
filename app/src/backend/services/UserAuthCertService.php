<?php

namespace backend\services;

use common\helpers\CryptoProHelper;
use common\helpers\FileHelper;
use common\models\UserAuthCert;
use phpseclib\File\X509;
use Yii;

class UserAuthCertService
{
    private const GOST_ALGORITHMS = [
        '1.2.643.2.2.4', // ГОСТ Р 34.10-94
        '1.2.643.2.2.3', // ГОСТ Р 34.10-2001
        '1.2.643.7.1.1.3.2', // ГОСТ Р 34.10-2012 для ключей длины 256 бит
        '1.2.643.7.1.1.3.3', // ГОСТ Р 34.10-2012 для ключей длины 512 бит
    ];

    public function saveCertificate(UserAuthCert $authCert): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Сохранить модель в БД
            $isSaved = $authCert->save();
            if (!$isSaved) {
                throw new \RuntimeException('Failed to save user certificate to database, errors: ' . var_export($authCert->getErrors(), true));
            }
            if ($this->isGostCertificate($authCert->certificate)) {
                Yii::info("Installing user authorization certificate {$authCert->fingerprint} to CryptoPro");
                $this->installCertificateToCryptoPro($authCert->certificate);
            }
            $transaction->commit();
        } catch (\Exception $exception) {
            Yii::info("Failed to save user certificate, caused by: $exception");
            $transaction->rollBack();
            return false;
        }

        return true;
    }

    public function deleteCertificate(UserAuthCert $authCert): bool
    {
        if ($this->isGostCertificate($authCert->certificate)) {
            Yii::info("Deleting certificate {$authCert->fingerprint} from CryptoPro");
            CryptoProHelper::deleteCertificate(strtolower($authCert->fingerprint));
        }
        return $authCert->delete() === 1;
    }

    public function isGostCertificate(string $certificateBody): bool
    {
        $algorithmOid = $this->extractSignatureAlgorithmOid($certificateBody);
        return $this->isGostAlgorithm($algorithmOid);
    }

    public function isGostAlgorithm(string $algorithmOid): bool
    {
        return in_array($algorithmOid, self::GOST_ALGORITHMS);
    }

    private function extractSignatureAlgorithmOid(string $certificateBody): string
    {
        $x509 = new X509();
        $certificateData = $x509->loadX509($certificateBody);
        if ($certificateData === false) {
            throw new \RuntimeException('Failed to load certificate');
        }
        $algorithmOid = $x509->getOID($certificateData['signatureAlgorithm']['algorithm']);
        if (!$algorithmOid) {
            throw new \RuntimeException('Failed to detect signature algorithm');
        }
        return $algorithmOid;
    }

    public function installCertificateToCryptoPro(string $certificate)
    {
        $tempFile = Yii::getAlias('@temp/') . FileHelper::uniqueName();
        file_put_contents($tempFile, $certificate);
        $container = CryptoProHelper::installCertificate($tempFile);
        if ($container === false) {
            throw new \RuntimeException('Failed to install certificate to CryptoPro');
        }
    }
}
