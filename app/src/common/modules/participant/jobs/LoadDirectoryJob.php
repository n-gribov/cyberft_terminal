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

/**
 * Класс задания загрузки справочника участников
 */
class LoadDirectoryJob extends RegularJob
{
    // Флаг принудительного обновления
    private $forceUpdate = false;
    // Флаг использования задержки
    private $useDelay = false;

    /**
     * Метод настраивает задание
     */
    public function setUp()
    {
        parent::setUp();
        // Получить флаги из аргументов задания
        $this->forceUpdate = boolval($this->args['forceUpdate'] ?? false);
        $this->useDelay = boolval($this->args['useDelay'] ?? false);
    }

    /**
     * Метод запускает задание
     * @return type
     */
    public function perform()
    {
        // Если используется задержка, спать случайный интервал времени
        if ($this->useDelay) {
            sleep(rand(0, 10));
        }
        $this->log('Loading CyberFT directory from processing...');
        // Получить справочник
        $directory = $this->getDirectory();
        // Если справочник не получен, залогировать проблему и вернуться
        if (!$directory) {
            $this->log('Could not get directory, check signature key');
            return;
        }
        // Если справочник не менялся, залогировать и вернуться
        if (!$directory->isModified()) {
            $this->log('Directory is not modified');
            return;
        }
        // Обновить сертификаты из справочника
        $this->updateCerts($directory);
        // Обновить участников из справочника
        $this->updateParticipants($directory);
        // Сохранить последнюю версию справочника
        $this->saveLastDirectoryVersion($directory->getVersion());
    }

    /**
     * Метод обновляет сертификаты из справочника
     * @param Directory $directory
     * @return void
     */
    private function updateCerts(Directory $directory): void
    {
        // Создать команду обновления сертификатов
        $command = new UpdateCertsFromCyberftDirectory($directory);
        // Запустить команду
        $command->run();
    }

    /**
     * Метод обновляет участников из справочника
     * @param Directory $directory
     * @return void
     */
    private function updateParticipants(Directory $directory): void
    {
        // Создать команду обновления участников
        $command = new UpdateParticipantsFromCyberftDirectory($directory);
        // Запустить команду
        $command->run();
    }

    /**
     * Метод получает справочник через API
     * @return Directory|null
     */
    private function getDirectory(): ?Directory
    {
        // Получить API
        /** @var DirectoryApi $api */
        $api = Yii::$app->processingApiFactory->create(DirectoryApi::class);
        // Установить версию справочника (при принудительном исполнении не устанавливать)
        $version = $this->forceUpdate ? null : $this->getLastDirectoryVersion();
        // Получить ключ для подписания API-запроса
        $signatureKey = $this->getSignatureKey();
        if (!$signatureKey) {
            return null;
        }
        // Получить справочник из API
        return $api->get($version, $signatureKey);
    }

    /**
     * Метод получает ключ подписания запроса к API
     * @return SignatureKey|null
     */
    private function getSignatureKey(): ?SignatureKey
    {
        // Получить ключ автоподписанта
        /** @var Autobot $autobot */
        $autobot = Yii::$app->exchange->findAutobotUsedForSigning(
            Yii::$app->exchange->getDefaultTerminalId()
        );
        // Если ключ не получен, вернуть отшибку
        if (!$autobot) {
            return null;
        }
        // Сформировать и вернуть ключ подписания запроса к API
        return new SignatureKey([
            'fingerprint'     => $autobot->fingerprint,
            'terminalAddress' => $autobot->controller->terminal->terminalId,
            'body'            => $autobot->privateKey,
            'password'        => $autobot->getPassword(),
        ]);
    }

    /**
     * Метод получает последнюю версию справочника
     * @return string|null
     */
    private function getLastDirectoryVersion(): ?string
    {
        // Получить модель настроек приложения
        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        // Вернуть версию справочника из модели настроек, если есть
        return $appSettings->cyberftDirectoryVersion ?: null;
    }

    /**
     * Метод сохраняет последнюю версию справочника
     * @param string $version
     * @return void
     */
    private function saveLastDirectoryVersion(string $version): void
    {
        // Получить модель настроек приложения
        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        // Записать версию в модель настроек приложения
        $appSettings->cyberftDirectoryVersion = $version;
        // Сохранить модель в БД
        $appSettings->save();
    }
}
