<?php
namespace common\base\interfaces;

use common\components\Settings;
use common\document\Document;
use common\document\DocumentTypeGroup;
use common\document\DocumentTypeGroupResolver;
use common\models\cyberxml\CyberXmlDocument;
use yii\console\Application;
use yii\web\User;

/**
 * Для реализации блоков приложения, которые отвечают за работу с типом
 * или группой типов документов
 *
 * @author fuzz
 */
interface BlockInterface
{
	/**
	 * Инициализация блока, регистрация сервисов
	 */
	public function setUp($config = null);

	/**
	 * Регистрация консольных контроллеров блока в приложении
	 *
	 * @param Application $app
	 * (!) Закомментировано до полной реализации во всех аддонах
	 */
	public function registerConsoleControllers(Application $app);

	/**
	 * Передача блоку управления документом
	 * @param CyberXmlDocument $document сохраненный в storage документ
	 * @param int $messageId идентификатор сообщения в TransportModule
	 */
	public function registerMessage(CyberXmlDocument $document, $messageId);
	
	/**
	 * Получить конфиг
	 * @return mixed
	 */
	public function getConfig();

    /**
	 * Получить изменяемые настройки
	 * @return Settings
	 */
	public function getSettings($terminalId = null);

    /**
     * Возвращает список типов документов которые должны быть верифицированны пользователями
     * @return mixed
     */
    public function getUserVerificationDocumentType();

    /**
     * Обработка внутриблоковых статусов документа до, во время или после транспортировки
     * @param Document $document
     */
    public function processDocument(Document $document, $sender = null, $receiver = null);

    /**
     * Обработка входящего документа
     * @param int $documentId
     */
    public function processIncomingDocument($documentId, $documentType);

    /**
     * Обработка изменения статуса документа
     * @param Document $document
     */
    public function onDocumentStatuschange(Document $document);

    /**
     * Возвращает расширенную модель
     *
     * @param integer $id
     */
    public function getUserExtModel($id = null);

    public function hasUserAccessSettings(): bool;

    public function getName(): string;

    /**
     * @return DocumentTypeGroup[]
     */
    public function getDocumentTypeGroups(): array;

    /**
     * @return DocumentTypeGroup|null
     */
    public function getDocumentTypeGroupById($documentTypeGroupId);

    /**
     * @return DocumentTypeGroupResolver|null
     */
    public function createDocumentTypeGroupResolver(): ?DocumentTypeGroupResolver;

    /**
     * @param User $user
     * @return string[]
     */
    public function getDeletableDocumentTypes(User $user): array;

    /**
     * @param User $user
     * @return string[]
     */
    public function getSignableDocumentTypes(User $user): array;
}
