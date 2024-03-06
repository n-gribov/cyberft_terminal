<?php

namespace common\modules\autobot\controllers;

use common\components\processingApi\api\TerminalApi;
use common\components\processingApi\api\ProcessingApi;
use common\components\processingApi\exceptions\ApiException;
use common\components\processingApi\models\TerminalStompCredentials;
use common\components\processingApi\SignatureKey;
use common\modules\autobot\models\Autobot;
use common\modules\certManager\models\Cert;
use common\modules\participant\jobs\LoadDirectoryJob;
use common\settings\AppSettings;
use DomainException;
use Exception;
use Ramsey\Uuid\Uuid;
use Yii;

trait TerminalsExchange
{
    /**
     * Управление запуском/остановкой обмена терминала
     */
    public function actionTerminalControl()
    {
        $terminals = Yii::$app->terminals;

        if (Yii::$app->request->isPost) {
            // Имя управляемого терминала
            $action = Yii::$app->request->post('action');
            // Имя управляемого терминала
            $terminalId = Yii::$app->request->post('terminalId');

            if ('start' === $action) {
                // Проверка наличия ключа, используемого для подписания
                $hasUsedForSigningAutobot = Autobot::hasUsedForSigningAutobot($terminalId);

                if (!$hasUsedForSigningAutobot) {
                    Yii::$app->session->setFlash(
                        'error',
                        Yii::t(
                            'app/autobot',
                            'Unable to start exchange! Terminal {terminalId} does not have a Controller key defined for signing outgoing documents!',
                            ['terminalId' => $terminalId]
                        )
                    );
                } elseif (false === $terminals->isRunning($terminalId)) {
                    $this->start($terminalId);
                } else {
                    Yii::$app->session->setFlash(
                        'error',
                        Yii::t(
                            'app/autobot',
                            'Automatic process already started for terminal {terminal}',
                            ['terminal' => $terminalId]
                        )
                    );
                }
            } elseif ('stop' === $action) {
                // Останов автопроцессов
                if (true === $terminals->isRunning($terminalId)) {
                    $this->stop($terminalId);

                    // Регистрация события остановки обмена с CyberFT
                    Yii::$app->monitoring->extUserLog('StopAutoprocesses');
                } else {
                    Yii::$app->session->setFlash(
                        'error',
                        Yii::t(
                            'app/autobot',
                            'Automatic process already stopped for terminal {terminal}',
                            ['terminal' => $terminalId]
                        )
                    );
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function saveCredentialsInProcessing($terminalAddress, $password): array
    {
        $salt = bin2hex(random_bytes(16));
        $hash = hash('sha256', $password . $salt);

        // Получение данных для подписания
        $primaryAutobot = Yii::$app->terminals->findAutobotUsedForSigning($terminalAddress);
        $terminalData = Yii::$app->terminals->findTerminalData($terminalAddress);

        if ($primaryAutobot === null || empty($terminalData)) {
            return [
                false,
                Yii::t(
                    'app/autobot',
                    'Unable to start exchange! Terminal {terminalId} does not have a Controller key defined for signing outgoing documents!',
                    ['terminalId' => $terminalAddress]
                )
            ];
        }

        $keyPassword = $terminalData['passwords'][$primaryAutobot->id];
        $signatureKey = new SignatureKey([
            'fingerprint'     => $primaryAutobot->fingerprint,
            'terminalAddress' => $primaryAutobot->controller->terminal->terminalId,
            'body'            => $primaryAutobot->privateKey,
            'password'        => $keyPassword,
        ]);

        $credentials = new TerminalStompCredentials([
            'terminal'     => $primaryAutobot->controller->terminal->terminalId,
            'passwordSalt' => $salt,
            'passwordHash' => $hash,
        ]);

        /** @var TerminalApi $api */
        $api = Yii::$app->processingApiFactory->create(TerminalApi::class);
        Yii::info("Sending STOMP broker password update request for terminal $terminalAddress to processing");
        try {
            $api->updateStompCredentials($credentials, $signatureKey);
        } catch (ApiException $exception) {
            switch ($exception->getCode()) {
                case '403':
                    return [false, Yii::t('app/autobot', 'Failed to start exchange. Your controller key is probably missing in provider processing. To resolve this issue please contact CyberFT support via email <a href="mailto:support@cyberft.ru">support@cyberft.ru</a>')];
                default:
                    return [false, Yii::t('app/autobot', 'Failed to save new password in processing')];
            }
        }

        return [true, null];
    }

    /**
     * Запуск обмена терминала
     */
    protected function start($terminalAddress)
    {
        $isStarted = false;
        try {
            $appSettings = Yii::$app->settings->get('app', $terminalAddress);
            $stompPassword = (string)Uuid::uuid4();
            list($isSaved, $errorMessage) = $this->saveCredentialsInProcessing($terminalAddress, $stompPassword);
            if ($isSaved) {
                $appSettings->stomp[$terminalAddress] = [
                    'login' => $terminalAddress,
                    'password' => $stompPassword,
                ];
                $appSettings->save();
                Yii::info("Stomp password for terminal $terminalAddress has been updated");
            } else {
                Yii::info("Stomp password for terminal $terminalAddress has not been updated, error message: $errorMessage");
                $hasPassword = !empty($appSettings->stomp[$terminalAddress]['password']);
                if ($hasPassword) {
                    Yii::info('Will try to connect with old password');
                } else {
                    throw new DomainException($errorMessage);
                }
            }

            // Добавление данных по всем активным основным и дополнительным ключам контролёра
            $terminals = Yii::$app->terminals;
            $terminalData = $terminals->findTerminalData($terminalAddress);
            $terminals->start($terminalAddress, $terminalData['passwords']);

            Yii::$app->session->setFlash(
                'success',
                Yii::t('app/autobot', "Automatic process started for terminal {terminal}", ['terminal' => $terminalAddress])
            );

            Yii::$app->monitoring->extUserLog('StartAutoprocesses');
            $isStarted = true;
        } catch (DomainException $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        } catch (Exception $exception) {
            Yii::error("Failed to start terminal $terminalAddress, caused by: $exception");
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Failed to start exchange'));
        }

        if ($isStarted) {
            try {
                $this->importProcessingCert();
                $this->importControllersCerts();
            } catch (Exception $exception) {
                Yii::error("Got exception while performing terminal post-start actions: $exception");
            }
        }
    }

    /**
     * Остановка обмена терминала
     */
    protected function stop($terminalId)
    {
        $terminals = Yii::$app->terminals;

        $terminals->stop($terminalId);

        Yii::$app->session->setFlash(
            'success',
            Yii::t('app/autobot', "Automatic process stopped for terminal {terminal}", ['terminal' => $terminalId])
        );
    }

    /**
     * Добавление сертификата процессинга
     */
    protected function importProcessingCert(): void
    {
        // Получение сертификата процессинга
        /** @var ProcessingApi $processingApi */
        $processingApi = Yii::$app->processingApiFactory->create(ProcessingApi::class);
        $certificateData = $processingApi->getCertificate();

        // Сравнение названия процессинга в сертификате и в настройках
        $terminalAddressFromSettings = Yii::$app->settings->get('app')->processing['address'];
        $terminalAddressFromCertificate = $certificateData->terminal;

        if ($terminalAddressFromCertificate !== $terminalAddressFromSettings) {
            throw new \Exception('Cannot import processing certificate: certificate terminal address does not match processing address');
        }

        // Добавление или обновление сертификата процессинга
        $fingerprint = substr($certificateData->code, strpos($certificateData->code, '-') + strlen('-'));
        $model = Cert::findByAddress($terminalAddressFromSettings, $fingerprint);

        if (!$model) {
            $model = new Cert();
        }

        $model->setScenario(Cert::SCENARIO_AUTO_IMPORT);
        $model->body = $certificateData->certificate;
        $model->terminalId = $certificateData->terminal;
        $model->userId = Yii::$app->user->getId();
        $model->status = Cert::STATUS_C10;

        // Добавление имени владельца сертификата процессинга
        $ownerNameArr = explode(' ', $certificateData->ownerName);
        while (count($ownerNameArr) < 3) {
            $ownerNameArr[] = null; // Если ФИО не полное то записывается null
        }

        $model->lastName = $ownerNameArr[0];
        $model->firstName = $ownerNameArr[1];
        $model->middleName = $ownerNameArr[2];

        $model->loadAttributesFromCertificate();
        $model->useBefore = $certificateData->endDate->format('Y-m-d H:i:s');

        $isSaved = $model->save();
        if (!$isSaved) {
            throw new \Exception('Cannot save processing certificate, errors: ' . var_export($model->getErrors()));
        }
    }

    private function importControllersCerts(): void
    {
        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        $isFirstImport = empty($appSettings->cyberftDirectoryVersion);
        if ($isFirstImport) {
            Yii::$app->resque->enqueue(LoadDirectoryJob::class);
        }
    }
}
