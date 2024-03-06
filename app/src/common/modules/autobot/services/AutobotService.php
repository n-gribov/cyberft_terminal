<?php

namespace common\modules\autobot\services;

use common\components\processingApi\api\CertificateApi;
use common\components\processingApi\exceptions\ApiException;
use common\components\processingApi\models\Certificate;
use common\components\processingApi\SignatureKey;
use common\modules\autobot\forms\ImportAutobotForm;
use common\modules\autobot\models\Autobot;
use common\modules\autobot\models\Controller;
use common\modules\certManager\components\ssl\Exception;
use Yii;
use yii\db\Expression;

class AutobotService
{
    public function createWithNewKey(Controller $controller, string $keyPassword): void
    {
        $defaultErrorMessage = Yii::t('app/autobot', 'There was an error while creating keys');

        $autobot = new Autobot([
            'controllerId'        => $controller->id,
            'primary'             => 1,
            'ownerSurname'        => $controller->lastName,
            'ownerName'           => trim(implode(' ', [$controller->firstName, $controller->middleName])),
            'organizationName'    => $controller->terminal->title ?: $controller->terminal->terminalId,
            'countryName'         => $controller->country,
            'stateOrProvinceName' => $controller->stateOrProvince,
            'localityName'        => $controller->locality
        ]);

        $keyIsGenerated = $autobot->generate($keyPassword, $controller->terminal->terminalId);
        if (!$keyIsGenerated) {
            Yii::info('Autobot keys generation failed');
            throw new \DomainException($defaultErrorMessage);
        };

        if (!$autobot->validate()) {
            Yii::info('Autobot model validation failed, errors' . var_export($autobot->getErrors(), true));
            throw new \DomainException($defaultErrorMessage);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $isSaved = $autobot->save();
            if (!$isSaved) {
                throw new \Exception('Failed to save autobot');
            }
            $certificate = $this->registerKeyInProcessing($autobot, $keyPassword);
            $this->updateAutobotStatus($autobot, $certificate, $keyPassword);
            Yii::$app->monitoring->extUserLog('CreateControllerKey', ['id' => $autobot->id]);
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::$app->errorHandler->logException($exception);
            $errorMessage = $exception instanceof \DomainException ? $exception->getMessage() : $defaultErrorMessage;
            throw new \DomainException($errorMessage);
        }
    }

    public function createWithImportedKey(Controller $controller, ImportAutobotForm $form): void
    {
        $defaultErrorMessage = Yii::t('app/autobot', 'There was an error while creating keys');

        $autobot = new Autobot([
            'controllerId' => $controller->id,
            'primary' => 1,
            'publicKey' => $form->publicKey,
            'privateKey' => $form->privateKey,
            'certificate' => $form->certificate,
        ]);

        $certificateData = openssl_x509_parse($autobot->certificate, false);
        if (empty($certificateData) || !array_key_exists('subject', $certificateData)) {
            throw new \DomainException(Yii::t('app/autobot', 'Invalid certificate'));
        }

        $certManager = Yii::$app->getModule('certManager');
        $certificateSubject = $certificateData['subject'];
        $autobot->setAttributes(
            [
                'countryName'         => $certificateSubject['countryName'] ?? null,
                'stateOrProvinceName' => $certificateSubject['stateOrProvinceName'] ?? null,
                'localityName'        => $certificateSubject['localityName'] ?? null,
                'organizationName'    => $certificateSubject['organizationName'] ?? null,
                'ownerSurname'        => $certificateSubject['surname'] ?? null,
                'ownerName'           => $certificateSubject['givenName'] ?? null,
                'commonName'          => $certificateSubject['commonName'] ?? null,
                'fingerprint'         => $certManager->getCertFingerprint($autobot->certificate),
                'expirationDate'      => date('Y-m-d 00:00:00', $certificateData['validTo_time_t']),
            ],
            false
        );

        if (!$autobot->validate()) {
            Yii::info('Autobot model validation failed, errors' . var_export($autobot->getErrors(), true));
            throw new \DomainException($defaultErrorMessage);
        }

        $certificateFromProcessing = $this->getCertificateFromProcessing($autobot, $form->password);
        if ($certificateFromProcessing !== null && $certificateFromProcessing->status === Certificate::STATUS_BLOCKED) {
            Yii::info('Certificate cannot be imported because it is blocked in CyberFT processing');
            throw new \DomainException(Yii::t('app/autobot', 'Certificate cannot be imported because it is blocked in CyberFT processing'));
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $isSaved = $autobot->save();
            if (!$isSaved) {
                throw new \Exception('Failed to save autobot');
            }
            if ($certificateFromProcessing === null) {
                $certificateFromProcessing = $this->registerKeyInProcessing($autobot, $form->password);
            }
            $this->updateAutobotStatus($autobot, $certificateFromProcessing, $form->password);
            Yii::$app->monitoring->extUserLog('CreateControllerKey', ['id' => $autobot->id]);
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            Yii::$app->errorHandler->logException($exception);
            $errorMessage = $exception instanceof \DomainException ? $exception->getMessage() : $defaultErrorMessage;
            throw new \DomainException($errorMessage);
        }
    }

    public function block(Autobot $autobot): void
    {
        $this->blockCertificateInProcessing($autobot);
        $autobot->block();
    }

    public function delete(Autobot $autobot): void
    {
        if (in_array($autobot->status, [Autobot::STATUS_ACTIVE, Autobot::STATUS_USED_FOR_SIGNING])) {
            throw new \DomainException(
                Yii::t('app/autobot', 'Failed to delete the active controller key')
            );
        }
        $autobot->delete();
    }

    private function updateAutobotStatus(Autobot $autobot, Certificate $certificateFromProcessing, string $keyPassword): void
    {
        if ($certificateFromProcessing->status === Certificate::STATUS_ACTIVE) {
            $autobot->activate($keyPassword);
        } elseif ($certificateFromProcessing->status === Certificate::STATUS_PENDING) {
            $autobot->makeWaitingForActivation($keyPassword);
        } else {
            throw new \Exception("Got unsupported certificate status from processing: {$certificateFromProcessing->status}");
        }
    }

    private function getCertificateFromProcessing(Autobot $autobot, string $keyPassword): ?Certificate
    {
        /** @var CertificateApi $api */
        $api = Yii::$app->processingApiFactory->create(CertificateApi::class);
        try {
            $signatureKey = new SignatureKey([
                'fingerprint'     => $autobot->fingerprint,
                'terminalAddress' => $autobot->controller->terminal->terminalId,
                'body'            => $autobot->privateKey,
                'password'        => $keyPassword,
            ]);
            return $api->get($autobot->controller->terminal->terminalId, $autobot->fingerprint, $signatureKey);
        } catch (ApiException $ex) {
            if ($ex->getCode() === 404) {
                return null;
            }
            Yii::$app->errorHandler->logException($ex);
            throw new \DomainException(Yii::t('app/autobot', 'Failed to get certificate status in CyberFT processing'));
        }
    }

    private function registerKeyInProcessing(Autobot $autobot, string $keyPassword): Certificate
    {
        if (!$autobot->certificateWillNotExpireIn(1)) {
            throw new \DomainException(Yii::t('app/autobot', 'Certificate is expired'));
        }
        /** @var CertificateApi $api */
        $api = Yii::$app->processingApiFactory->create(CertificateApi::class);

        $certificate = new Certificate([
            'terminal'    => $autobot->controller->terminal->terminalId,
            'ownerName'   => $autobot->controller->fullName,
            'certificate' => $autobot->certificate,
            'ownerRole'   => Certificate::ROLE_CONTROLLER,
            'startDate'   => new \DateTime(),
            'endDate'     => new \DateTime($autobot->expirationDate),
        ]);
        $signatureKey = $this->createSignatureKeyForRegistrationRequest($autobot, $keyPassword);

        try {
            return $api->register($certificate, $signatureKey);
        } catch (ApiException $ex) {
            throw new \DomainException(Yii::t('app/autobot', 'Failed to register certificate in CyberFT processing'));
        }
    }

    private function createSignatureKeyForRegistrationRequest(Autobot $newAutobot, string $newAutobotKeyPassword): SignatureKey
    {
        $activeAutobot = $this->findSignerAutobotForRegistrationRequest($newAutobot);
        if ($activeAutobot === null) {
            return new SignatureKey([
                'fingerprint'     => $newAutobot->fingerprint,
                'terminalAddress' => $newAutobot->controller->terminal->terminalId,
                'body'            => $newAutobot->privateKey,
                'password'        => $newAutobotKeyPassword,
            ]);
        } else {
            return new SignatureKey([
                'fingerprint'     => $activeAutobot->fingerprint,
                'terminalAddress' => $activeAutobot->controller->terminal->terminalId,
                'body'            => $activeAutobot->privateKey,
                'password'        => $this->getAutobotKeyPassword($activeAutobot),
            ]);
        }
    }

    private function findSignerAutobotForRegistrationRequest(Autobot $newAutobot): ?Autobot
    {
        $autobots = Autobot::find()
            ->where(['controllerId' => $newAutobot->controllerId])
            ->andWhere(['status' => [Autobot::STATUS_ACTIVE, Autobot::STATUS_USED_FOR_SIGNING]])
            ->orderBy(new Expression("case when status = 'usedForSigning' then 0 else 1 end asc, expirationDate desc"))
            ->all();
        foreach ($autobots as $autobot) {
            if ($autobot->willNotExpireIn(5) && $autobot->certificateWillNotExpireIn(5)) {
                return $autobot;
            }
        }
        return null;
    }

    private function getAutobotKeyPassword(Autobot $autobot): string
    {
        $password = $autobot->getPassword();
        if ($password === null) {
            throw new \Exception("Cannot get password for autobot $autobot->id");
        }
        return $password;
    }

    private function shouldBlockCertificateInProcessing(Autobot $autobot): bool
    {
        $keyPassword = $this->getAutobotKeyPassword($autobot);
        $certificateFromProcessing = $this->getCertificateFromProcessing($autobot, $keyPassword);
        return $certificateFromProcessing !== null && $certificateFromProcessing->status !== Certificate::STATUS_BLOCKED;
    }

    private function blockCertificateInProcessing(Autobot $autobot): void
    {
        if (!$this->shouldBlockCertificateInProcessing($autobot)) {
            return;
        }

        Yii::info("Will block certificate {$autobot->fingerprint} for {$autobot->controller->terminal->terminalId} in processing");
        $keyPassword = $this->getAutobotKeyPassword($autobot);

        /** @var CertificateApi $api */
        $api = Yii::$app->processingApiFactory->create(CertificateApi::class);
        try {
            $signatureKey = new SignatureKey([
                'fingerprint'     => $autobot->fingerprint,
                'terminalAddress' => $autobot->controller->terminal->terminalId,
                'body'            => $autobot->privateKey,
                'password'        => $keyPassword,
            ]);
            $api->block($autobot->controller->terminal->terminalId, $autobot->fingerprint, $signatureKey);
        } catch (Exception $exception) {
            Yii::$app->errorHandler->logException($exception);
            throw new \DomainException(Yii::t('app/autobot', 'Failed to block certificate in CyberFT processing'));
        }
    }
}
