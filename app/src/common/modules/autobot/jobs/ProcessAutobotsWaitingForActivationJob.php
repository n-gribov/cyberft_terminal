<?php

namespace common\modules\autobot\jobs;

use common\base\RegularJob;
use common\components\processingApi\api\CertificateApi;
use common\components\processingApi\exceptions\ApiException;
use common\components\processingApi\models\Certificate;
use common\components\processingApi\SignatureKey;
use common\modules\autobot\models\Autobot;
use Yii;

class ProcessAutobotsWaitingForActivationJob extends RegularJob
{
    public const MAX_RUNTIME = 3000;

    public function perform()
    {
        $autobots = $this->findAutobots();
        foreach ($autobots as $autobot) {
            $this->processAutobot($autobot);
        }
    }

    private function processAutobot(Autobot $autobot): void
    {
        $this->log("Processing waiting for activation certificate {$autobot->fingerprint} for {$autobot->controller->terminal->terminalId}");
        $keyPassword = $this->getAutobotKeyPassword($autobot);
        try {
            $certificate = $this->getCertificateFromProcessing($autobot, $keyPassword);
        } catch (ApiException $exception) {
            if ($exception->getCode() === 404) {
                $this->log("Certificate {$autobot->code} is not found in processing and will be blocked in terminal");
                $autobot->block();
            } else if ($exception->getCode() === 403) {
                $this->log("Failed to get certificate {$autobot->code} from processing, certificate will be blocked in terminal");
                $autobot->block();
            }
            return;
        }
        if ($certificate->status === 'blocked') {
            $this->log("Certificate {$autobot->code} is blocked in processing, certificate will be blocked in terminal");
            $autobot->block();
        } else if ($certificate->status === 'active') {
            $this->log("Certificate {$autobot->code} is active in processing, certificate will be activated in terminal");
            $autobot->activate($keyPassword);
        } else {
            $this->log("Certificate {$autobot->code} status in processing: {$certificate->status}");
        }
    }

    private function findAutobots(): array
    {
        return Autobot::find()->where(['status' => Autobot::STATUS_WAITING_FOR_ACTIVATION])->all();
    }

    private function getCertificateFromProcessing(Autobot $autobot, string $keyPassword): Certificate
    {
        /** @var CertificateApi $api */
        $api = Yii::$app->processingApiFactory->create(CertificateApi::class);
        $signatureKey = new SignatureKey([
            'fingerprint'     => $autobot->fingerprint,
            'terminalAddress' => $autobot->controller->terminal->terminalId,
            'body'            => $autobot->privateKey,
            'password'        => $keyPassword,
        ]);

        return $api->get($autobot->controller->terminal->terminalId, $autobot->fingerprint, $signatureKey);
    }

    private function getAutobotKeyPassword(Autobot $autobot): string
    {
        $terminalData = Yii::$app->exchange->findTerminalData($autobot->controller->terminal->terminalId);
        return $terminalData['passwords'][$autobot->id];
    }
}
