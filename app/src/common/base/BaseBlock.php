<?php
/**
 * Базовый класс аддона
 */
namespace common\base;

use addons\swiftfin\models\SwiftFinUserExt;
use common\base\interfaces\BlockInterface;
use common\base\interfaces\SigningInterface;
use common\document\Document;
use common\document\DocumentPermission;
use common\document\DocumentTypeGroup;
use common\document\DocumentTypeGroupResolver;
use common\models\cyberxml\CyberXmlDocument;
use RuntimeException;
use Yii;
use yii\base\Module;
use yii\console\Application;
use yii\web\User;

/**
 * Абстрактный класс, от которого наследуются все аддоны
 */
abstract class BaseBlock extends Module implements BlockInterface, SigningInterface
{
    // Класс расширенной модели пользвователя
    const EXT_USER_MODEL_CLASS = false;
    // Код настрооечных параметров
    const SETTINGS_CODE = '';
    // Идентификатор сервиса
    const SERVICE_ID = '';

    // Опции подписания
    public static $signaturesNumberOptions = [
        0 => 'Не требуется (Только автоподписант)',
        1 => 'Одна подпись',
        2 => 'Две подписи',
        3 => 'Три подписи',
        4 => 'Четыре подписи',
        5 => 'Пять подписей',
        6 => 'Шесть подписей',
        7 => 'Семь подписей',
    ];

    // Объект конфигурации
    protected $_config;
    // Объект настроек
    protected $_settings = [];
    // Объект настроек по умолчанию
    protected $_defaultSettings;
    // Кеш расширенных моделей пользователей
    protected $_cachedUserExtModels = [];

    /**
     * Метод регистрирует сообщение в локальной таблице.
     * @param CyberXmlDocument $document
     * @param integer $messageId
     * @return boolean
     */
    public abstract function registerMessage(CyberXmlDocument $document, $messageId);

    /**
     * Метод получает документ по id
     */
    public abstract function getDocument($id);

    /**
     * Метод настраивает конфигурацию
     * @param type $config
     */
    public function setUp($config = null)
    {
        $this->_config = $config;
    }

    /**
     * Метод регистрирует консольные контроллеры
     * @param Application $app
     */
    public function registerConsoleControllers(Application $app)
    {
        if (Yii::$app instanceof Application) {
            Yii::$app->controllerMap[$this->id] = [
                'class' => 'addons\\' . $this->id . '\console\DefaultController',
                'module' => $this,
            ];
        }
    }

    /**
     * Метод получает настройки
     * @param $terminalId ид терминала
     */
    public function getSettings($terminalId = null)
    {
        // если не указан ид терминала, получить настройки по умолчанию
        if (is_null($terminalId)) {
            if (!$this->_defaultSettings) {
                $this->_defaultSettings = Yii::$app->settings->get(static::SETTINGS_CODE);
            }

            return $this->_defaultSettings;
        }

        // Получить настройки для указанного терминала
        if (!isset($this->_settings[$terminalId])) {
            $this->_settings[$terminalId] = Yii::$app->settings->get(static::SETTINGS_CODE, $terminalId);
        }

        // Вернуть настройки для указанного терминала
        return $this->_settings[$terminalId];
    }

    /**
     * Метод получает конфигурацию
     * @return type
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Метод получает идентификатор сервиса
     * @return type
     */
    public function getServiceId()
    {
        return $this->_config->serviceName;
    }

    /**
     * Метод получает типы документов
     * @return type
     */
    public function getDocTypes()
    {
        return $this->_config->docTypes;
    }

    /**
     * Метод логирует сообщение
     * @param string $msg
     */
    public function log($msg)
    {
        $msg = $this->getServiceId() . ': ' . $msg;
        Yii::info($msg);
    }

    /**
     * Метод сохраняет файл в нужный ресурс и получает storedFile
     * @param type $path
     * @param type $resourceId
     * @param type $filename
     * @return Object
     */
    public function storeFile($path, $resourceId, $filename = '')
    {
        return Yii::$app->storage->putFile($path, $this->serviceId, $resourceId, $filename);
    }

    /**
     * Метод сохраняет данные в нужныё ресурс
     * @param type $data
     * @param type $resourceId
     * @param type $filename
     * @return type
     */
    public function storeData($data, $resourceId, $filename = '')
    {
        return Yii::$app->storage->putData($data, $this->serviceId, $resourceId, $filename);
    }

    public function storeDataTemp($data, $filename = '')
    {
        $fileInfo = Yii::$app->registry->getTempResource(static::SERVICE_ID)->putData($data, $filename);

        return $fileInfo['path'];
    }

    public function getDataViews($docType)
    {
        $views = $this->_config->docTypes[$docType]['views'];
        if (empty($views)) {
            return [];
        }

        return $views;
    }

    public function isSignatureRequired($origin, $terminalId = null)
    {
        return $this->getSettings($terminalId)->signaturesNumber > 0;
    }

    public function isAutoSignatureRequired($origin, $terminalId = null)
    {
        return (bool) $this->getSettings($terminalId)->useAutosigning;
    }

    public function getSignaturesNumber($terminalId = null)
    {
        return $this->getSettings($terminalId)->signaturesNumber;
    }

    public function getUserVerificationDocumentType()
    {
        return null;
    }

    /**
     * Get user extended model
     *
     * @param integer $id User ID
     * @return SwiftFinUserExt
     */
    public function getUserExtModel($id = null)
    {
        $cacheKey = $id ?: 0;
        if (isset($this->_cachedUserExtModels[$cacheKey])) {
            return $this->_cachedUserExtModels[$cacheKey];
        }

        $modelClass = static::EXT_USER_MODEL_CLASS;
        if (!class_exists($modelClass)) {
            throw new RuntimeException('Extended user model class not found or not specified');
        }

        $model = false;
        if (!empty($id)) {
            $model = $modelClass::findOne(['userId' => $id]);
        }

        if (empty($model)) {
            $model = new $modelClass();
        }

        $this->_cachedUserExtModels[$cacheKey] = $model;

        return $model;
    }

    /** Check on verification requirements for specified document type
     *
     * @param string $type
     * @return boolean
     */
    public function isVerificationRequired($type = null, $terminalId = null)
    {
        return false;
    }

    public function getEnabledUsers()
    {
        return null;
    }

    public function processDocument(Document $document, $sender = null, $receiver = null)
    {
        $status = $document->status;
        /**
         * Проверка на внешние факторы, которые могли установить статус FOR_SIGNING
         * Если таковой уже установлен, мы проверяем только наличие нужного количества подписей
         */
        if ($status == Document::STATUS_FORSIGNING) {
            if ($document->signaturesRequired <= $document->signaturesCount) {
                $status = Document::STATUS_ACCEPTED;
            }
        }

        // В прочих случаях пытаемся установить необходимость подписания
        if (
                $status != Document::STATUS_ACCEPTED        // не принят к исполнению
                && $status != Document::STATUS_FORSIGNING   // не требует подписания
                && $status != Document::STATUS_PENDING      // не находится в процессе extractSignDataJob
        ) {

            // дефолтный финальный статус
            $status = Document::STATUS_ACCEPTED;
            // Проверяем, не установлено ли уже количество подписей
            if ($document->signaturesRequired > 0) {
                if ($document->signaturesRequired > $document->signaturesCount) {
                    $status = Document::STATUS_FORSIGNING;
                }
            } else {
                // Иначе устанавливаем количество подписей из настроек подписания
                if ($this->isSignatureRequired(Document::ORIGIN_WEB, $document->sender) && $document->signaturesRequired = $this->getSignaturesNumber($document->sender)
                ) {
                    $status = Document::STATUS_FORSIGNING;
                    $document->save(false, ['signaturesRequired']);
                }
            }
        }

        // Обновление статуса через updateStatus необходимо для того, чтобы создавались события в монитор логе
        // Со статусом forSigning вызов updateStatus должен происходить каждый раз для правильной работы оповещений.
        if ($document->status != $status || $document->status == Document::STATUS_FORSIGNING) {
            return $document->updateStatus($status);
        }

        return true;
    }

    public function onDocumentStatusChange(Document $document)
    {
        if ($document->status === Document::STATUS_PROCESSED) {
            $this->processIncomingDocument($document->id, $document->type);
        }
    }

    /**
     * Processing incoming document
     *
     * @param integer $id   Document ID
     * @param string  $type Document type
     * @return mixed
     */
    public function processIncomingDocument($id, $type)
    {
        // Пробуем найти incoming задание для данного типа документа, если нашли, то запускаем.
        if (isset($this->_config->docTypes[$type]['jobs']['incoming'])) {
            $jobClass = $this->_config->docTypes[$type]['jobs']['incoming'];
            $result = Yii::$app->resque->enqueue(
                    $jobClass,
                    ['id' => $id]
            );

            if ($result === false) {
                Yii::warning("Error of enqueue job for document [{$id}]");

                return false;
            }

            $this->log('Queued ' . $jobClass . 'for ' . $type . ' ' . $id);

            return true;
        }

        return null;
    }

    public function hasUserAccessSettings(): bool
    {
        return true;
    }

    /**
     * @return DocumentTypeGroup[];
     */
    public function getDocumentTypeGroups(): array
    {
        $documentTypeGroups = $this->_config->documentTypeGroups ?? [];
        return array_map(
                function ($config) {
                    return new DocumentTypeGroup($config);
                },
                $documentTypeGroups
        );
    }

    public function getDocumentTypeGroupById($documentTypeGroupId)
    {
        foreach ($this->getDocumentTypeGroups() as $group) {
            if ($group->id === $documentTypeGroupId) {
                return $group;
            }
        }
        return null;
    }

    public function createDocumentTypeGroupResolver(): ?DocumentTypeGroupResolver
    {
        $documentTypeGroups = $this->getDocumentTypeGroups();
        return !empty($documentTypeGroups) ? new DocumentTypeGroupResolver($documentTypeGroups) : null;
    }

    public function getDeletableDocumentTypes(User $user): array
    {
        $userCanDeleteDocuments = $user->can(DocumentPermission::DELETE, ['serviceId' => static::SERVICE_ID]);
        if (!$userCanDeleteDocuments) {
            return [];
        }

        $types = $this->getConfig()->docTypes;
        if (empty($types)) {
            return [];
        }

        $deletableTypes = [];
        foreach (array_keys($types) as $type) {
            $extModelClass = Yii::$app->registry->getTypeExtModelClass($type);
            if (empty($extModelClass)) {
                continue;
            }

            $isDocumentDeletable = (new $extModelClass())->isDocumentDeletable($user->identity);
            if ($isDocumentDeletable) {
                $deletableTypes[] = $type;
            }
        }

        return $deletableTypes;
    }

    public function getSignableDocumentTypes(User $user): array
    {
        $userCanSignDocuments = $user->can(DocumentPermission::SIGN, ['serviceId' => static::SERVICE_ID]);
        if (!$userCanSignDocuments) {
            return [];
        }

        return array_keys($this->getConfig()->docTypes);
    }

}
