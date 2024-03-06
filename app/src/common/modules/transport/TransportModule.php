<?php

namespace common\modules\transport;

use common\components\storage\StoredFile;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;
use yii\console\Application as ConsoleApplication;

class TransportModule extends Module implements BootstrapInterface
{
    const SERVICE_ID = 'transport';
    const STORAGE_RESOURCE_IN = 'in';
    const STORAGE_RESOURCE_OUT = 'out';
    const STORAGE_RESOURCE_OUT_ENC = 'outEnc';
    const STORAGE_RESOURCE_ZIP = 'zip';
    const EXPORT_RESOURCE = 'transport';
    const IMPORT_RESOURCE = 'transport';
    const ERROR_RESOURCE = 'error';
    const DEFAULT_QUEUE_NAME = 'default';

    // Дублирование экспорта документов в ресурс транспорта
    public $modeExportDuplicate = false;

    public function bootstrap($app)
    {
        Yii::setAlias('@transportStorage', '@storage/transport');

        /**
         * Настройки контроллеров для консоли
         */
        if ($app instanceof ConsoleApplication) {
            $app->controllerMap[$this->id] = [
                'class'  => 'common\modules\transport\console\DefaultController',
                'module' => $this,
            ];
        }

        Yii::$app->registry->registerStorageResources(
            static::SERVICE_ID,
            '@transportStorage',
            [
                static::STORAGE_RESOURCE_IN  => [
                    'directory' => 'in'
                ],
                static::STORAGE_RESOURCE_OUT => [
                    'directory' => 'out'
                ],
                static::STORAGE_RESOURCE_OUT_ENC => [
                    'directory' => 'out',
                    'adapterClass' => 'common\components\storage\EncryptedPartitionedAdapter'
                ],
                static::STORAGE_RESOURCE_ZIP => [
                    'directory' => 'zip',
                    'usePartition' => false,
                ],
            ]
        );

        Yii::$app->registry->registerImportResources(
            static::SERVICE_ID,
            '@import/transport',
            [
                'default' => [
                    'directory' => '',
                    'usePartition' => false,
                ],
                static::ERROR_RESOURCE => [
                    'directory' => 'error',
                    'usePartition' => false,
                ],
            ]
        );

        Yii::$app->registry->registerExportResources(
            static::SERVICE_ID,
            '@export',
            [
                'default' => [
                    'directory' => 'transport',
                    'usePartition' => false,
                    'useUniqueName' => false,
                ],
                static::ERROR_RESOURCE => [
                    'directory' => 'transport/error',
                    'usePartition' => false,
                    'useUniqueName' => false,
                ]
            ]
        );

        Yii::$app->registry->registerRegularJob('common\modules\transport\jobs\ImportJob', 10);
        Yii::$app->registry->registerRegularJob('common\modules\transport\jobs\RegularExportCheck', 60);

        Yii::$app->registry->registerType('CFTAck', [
            'module'       => static::SERVICE_ID,
            'contentClass' => 'common\modules\transport\models\CFTAckCyberXmlContent',
            'typeModelClass' => 'common\modules\transport\models\CFTAckType',
        ]);

        Yii::$app->registry->registerType('CFTChkAck', [
            'module'       => static::SERVICE_ID,
            'contentClass' => 'common\modules\transport\models\CFTChkAckCyberXmlContent',
            'typeModelClass' => 'common\modules\transport\models\CFTChkAckType',
        ]);

        Yii::$app->registry->registerType('CFTResend', [
            'module'       => static::SERVICE_ID,
            'contentClass' => 'common\modules\transport\models\CFTResendCyberXmlContent',
            'typeModelClass' => 'common\modules\transport\models\CFTResendType',
        ]);

        Yii::$app->registry->registerType('CFTStatusReport', [
            'module'       => static::SERVICE_ID,
            'contentClass' => 'common\modules\transport\models\CFTStatusReportCyberXmlContent',
            'typeModelClass' => 'common\modules\transport\models\CFTStatusReportType',
        ]);

        Yii::$app->registry->registerType('StatusReport', [
            'module'       => static::SERVICE_ID,
            'contentClass' => 'common\modules\transport\models\StatusReportCyberXmlContent',
            'typeModelClass' => 'common\modules\transport\models\StatusReportType',
        ]);

        // Регистрируем задания для работы с Document
        Yii::$app->registry->registerRegularJob('common\modules\transport\jobs\RegularProcessIO', 30);
        Yii::$app->registry->registerRegularJob('common\modules\transport\jobs\RegularJobCleanup', 24 * 3600);

        // Регистриуем тар-задание
        Yii::$app->registry->registerRegularJob('common\modules\transport\jobs\RegularTarPack', 3600);
    }

    /**
     * Get service ID
     *
     * @return string
     */
    public function getServiceId()
    {
        return static::SERVICE_ID;
    }

    public function storeDataOut($data, $filename = '')
    {
        return $this->storeData($data, static::STORAGE_RESOURCE_OUT, $filename);
    }

    /**
     * Save file into storage
     *
     * @param string $path      File path
     * @param string $resource  Resource
     * @param string $filename  File name
     * @return StoredFile|null
     */
    public function storeFile($path, $resource, $filename = '')
    {
        return Yii::$app->storage->putFile($path, static::SERVICE_ID, $resource, $filename);
    }

    /**
     * Save data into storage
     *
     * @param string $data      Data to save
     * @param string $resource  Resource
     * @param string $filename  File name
     * @return StoredFile|null
     */
    public function storeData($data, $resource, $filename = '')
    {
        return Yii::$app->storage->putData($data, static::SERVICE_ID, $resource, $filename);
    }

    /**
     * Get stomp settings
     *
     * @param integer $terminalId Terminal ID
     * @param bool $noCache
     * @return array Return ['login', 'password']
     */
    public function getStompSettings($terminalId, $noCache = false)
    {
        $settings = $noCache
            ? Yii::$app->settings->getVolatile('app', $terminalId)
            : Yii::$app->settings->get('app', $terminalId);

        $stompSettings = $settings->stomp;
        if (isset($stompSettings[$terminalId])) {
            $attributes = $stompSettings[$terminalId];
            return [
                'login'    => $attributes['login'],
                'password' => $attributes['password'],
                'status' => isset($attributes['status']) ? $attributes['status'] : '',
            ];
        }

        return [
            'login'    => '',
            'password' => '',
            'status' => '',
        ];
    }

    public function registerMessage(CyberXmlDocument $cyx, $documentId)
    {
        return true;
    }

    public function processDocument(Document $document, $sender = null, $receiver = null)
    {
        if ($document->status !== Document::STATUS_ACCEPTED) {
            return $document->updateStatus(Document::STATUS_ACCEPTED);
        }

        return true;
    }

    public function isAutoSignatureRequired($origin, $terminalId = null)
    {
        return false;
    }
}
