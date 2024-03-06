<?php

namespace common\modules\participant\jobs;

use common\base\RegularJob;
use common\components\processingApi\api\DirectoryApi;
use common\components\processingApi\models\directory\Directory;
use common\components\processingApi\SignatureKey;
use common\modules\autobot\models\Autobot;
use common\modules\certManager\services\UpdateCertsFromCyberftDirectory;
use common\modules\participant\services\UpdateParticipantsFromCyberftDirectory;
use common\settings\AppSettings;
use Yii;

class LoadDirectoryJob extends RegularJob
{
    private $forceUpdate = false;
    private $useDelay = false;

    public function setUp()
    {
        parent::setUp();
        $this->forceUpdate = boolval($this->args['forceUpdate'] ?? false);
        $this->useDelay = boolval($this->args['useDelay'] ?? false);
    }

    public function perform()
    {
        if ($this->useDelay) {
            sleep(rand(0, 10));
        }
        $this->log('Loading CyberFT directory from processing...');
        $directory = $this->getDirectory();
        if (!$directory->isModified()) {
            $this->log('Directory is not modified');
            return;
        }

        $this->updateCerts($directory);
        $this->updateParticipants($directory);

        $this->saveLastDirectoryVersion($directory->getVersion());
    }

    private function updateCerts(Directory $directory): void
    {
        $command = new UpdateCertsFromCyberftDirectory($directory);
        $command->run();
    }

    private function updateParticipants(Directory $directory): void
    {
        $command = new UpdateParticipantsFromCyberftDirectory($directory);
        $command->run();
    }

    private function getDirectory(): Directory
    {
        /** @var DirectoryApi $api */
        $api = Yii::$app->processingApiFactory->create(DirectoryApi::class);
        $version = $this->forceUpdate ? null : $this->getLastDirectoryVersion();
        return $api->get($version, $this->getSignatureKey());
    }

    private function getSignatureKey(): SignatureKey
    {
        /** @var Autobot $autobot */
        $autobot = Yii::$app->terminals->findAutobotUsedForSigning(Yii::$app->terminals->getDefaultTerminalId());
        return new SignatureKey([
            'fingerprint'     => $autobot->fingerprint,
            'terminalAddress' => $autobot->controller->terminal->terminalId,
            'body'            => $autobot->privateKey,
            'password'        => $autobot->getPassword(),
        ]);
    }

    private function getLastDirectoryVersion(): ?string
    {
        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        return $appSettings->cyberftDirectoryVersion ?: null;
    }

    private function saveLastDirectoryVersion(string $version): void
    {
        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        $appSettings->cyberftDirectoryVersion = $version;
        $appSettings->save();
    }
}
