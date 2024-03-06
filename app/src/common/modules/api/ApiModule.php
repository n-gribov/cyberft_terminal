<?php

namespace common\modules\api;

use common\modules\api\models\ApiDocumentExportQueue;
use common\settings\AppSettings;
use Yii;
use yii\base\Module;

/**
 * api module definition class
 */
class ApiModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'common\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    
    public static function addToExportQueue($uuid, $path, $receiverTerminalAddress)
    {
        $record = new ApiDocumentExportQueue();
        $record->uuid = $uuid;
        $record->path = $path;
        $record->terminalAddress = $receiverTerminalAddress;
        // Сохранить модель в БД
        $isSaved = $record->save();
        if (!$isSaved) {
            Yii::error('Failed to add document to export queue, errors: ' . var_export($record->getErrors(), true));
        }
    }

    public static function addToExportQueueIfRequired($uuid, $path, $receiverTerminalAddress)
    {
        if (self::isApiEnabled($receiverTerminalAddress)) {
            self::addToExportQueue($uuid, $path, $receiverTerminalAddress);
        }
    }

    public static function isApiEnabled(?string $terminalAddress): bool
    {
        if ($terminalAddress) {
            /** @var AppSettings $terminalSettings */
            $terminalSettings = Yii::$app->settings->get('app', $terminalAddress);
            if ($terminalSettings->enableApi) {
                return true;
            }
        }

        /** @var AppSettings $globalSettings */
        $globalSettings = Yii::$app->settings->get('app');
        return (bool)$globalSettings->enableApi;
    }

    public static function reserveDocumentUuid(string $documentUuid): bool
    {
        $key = "apiDocumentImportRequest_$documentUuid";
        if (Yii::$app->redis->get($key)) {
            return false;
        }
        Yii::$app->redis->setex($key, 3600, 1);
        return true;
    }

    public static function storeApiImportErrors(string $documentUuid, array $errors): void
    {
        Yii::$app->redis->setex(
            self::createApiImportErrorsStorageKey($documentUuid),
            600,
            json_encode($errors)
        );
    }

    public static function retrieveApiImportErrors(string $documentUuid): array
    {
        return json_decode(
            Yii::$app->redis->get(self::createApiImportErrorsStorageKey($documentUuid)) ?: '[]'
        );
    }

    private static function createApiImportErrorsStorageKey(string $documentUuid): string
    {
        if (empty($documentUuid)) {
            throw new \InvalidArgumentException('Document uuid must not be empty');
        }
        return "apiImportErrors_$documentUuid";
    }
}
