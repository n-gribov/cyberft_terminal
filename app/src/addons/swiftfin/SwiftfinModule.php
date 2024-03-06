<?php
namespace addons\swiftfin;

use addons\swiftfin\components\MtDispatcher;
use addons\swiftfin\helpers\SwiftfinHelper;
use addons\swiftfin\models\SwiftFinDocumentExt;
use addons\swiftfin\models\SwiftFinSearch;
use addons\swiftfin\models\SwiftFinUserExt;
use common\base\BaseBlock;
use common\components\storage\StoredFile;
use common\document\Document;
use common\document\DocumentPermission;
use common\models\cyberxml\CyberXmlDocument;
use common\models\User;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * @property MtDispatcher $mtDispatcher
 *
 */
class SwiftfinModule extends BaseBlock
{
    const EXT_USER_MODEL_CLASS         = '\addons\swiftfin\models\SwiftFinUserExt';
    const SERVICE_ID                   = 'swiftfin';
    const RESOURCE_STORAGE_SRC_OUT     = 'out/sources';
    const RESOURCE_STORAGE_OUT         = 'out/xml';
    const RESOURCE_STORAGE_INVALID_OUT = 'out/invalid';
    const RESOURCE_STORAGE_IN          = 'in/xml';
    const RESOURCE_STORAGE_SRC_IN      = 'in/sources';
    const RESOURCE_STORAGE_INVALID_IN  = 'in/invalid';
    const RESOURCE_IMPORT_SWIFT        = 'swift';
    const RESOURCE_IMPORT_XML          = 'xml';
    const RESOURCE_EXPORT_SWIFT        = 'swift';
    const RESOURCE_EXPORT_DELIVERY     = 'delivery';

    const SETTINGS_CODE                = 'swiftfin:Swiftfin';

    /**
     * @var boolean Флаг разрешения экспорта Swiftfin-документов
     */
    public static $exportIsActive = true;

    /**
     *
     * @var string Расширение для файлов экспорта документов
     */
    public static $exportExtension = 'swt';

    protected $_mtDispatcher;

    public function setUp($config = null)
    {
        parent::setUp($config);
        $this->registerTypes();
    }

    public function registerTypes()
    {
        $mtDispatcher = $this->getMtDispatcher();
        $types        = $mtDispatcher->getRegisteredTypes();
        foreach ($types as $type => $params) {
            Yii::$app->registry->registerType(
                $type,
                [
                    'module' => static::SERVICE_ID,
                    'contentClass' => $params['contentClass'],
                    'extModelClass' => $params['extModelClass'],
                    'typeModelClass' => $params['typeModelClass'],
                    'dataViews' => $params['dataViews'],
                    'dataView' => '@addons/swiftfin/views/documents/_view',
                    'jobs' => [
                        'export' => '\addons\swiftfin\jobs\ExportJob',
                    ],
                ]
            );
        }
    }

    /**
     * Регистрирует входящее сообщение в модуле
     * @param CyberXmlDocument $cyx
     * @param integer $documentId
     */
    public function registerMessage(CyberXmlDocument $cyx, $documentId)
    {
        try {
            Yii::$app->resque->enqueue(
                    '\addons\swiftfin\jobs\ExportJob',
                    ['documentId' => $documentId]
            );

            Yii::$app->resque->enqueue(
                    '\addons\swiftfin\jobs\PrintJob',
                    ['documentId' => $documentId]
            );

            return true;

        } catch (Exception $ex) {
            $this->log('Exception: ' . $ex->getMessage());

            return false;
        }
    }

    /**
     * Get document class name
     *
     * @return string
     */
    public function getDocumentClassName()
    {
        return Document::className();
    }

    /**
     * @return MtDispatcher
     * @throws InvalidConfigException
     */
    public function getMtDispatcher()
    {
        if (!isset($this->_mtDispatcher)) {
            $this->_mtDispatcher = Yii::createObject(
                array_merge(
                    ['class' => 'addons\swiftfin\components\MtDispatcher'],
                    include($this->getBasePath() . '/config/mtDispatcher.php')
                )
            );
        }

        return $this->_mtDispatcher;
    }

    /**
     * Фунцкция возвращает дополнительные закладки для формы просмотра документа
     * определенного типа.
     * Если дополнительных закладок нет, то возвращается пустой массив.
     * @param string $typeCode
     * @return array
     */
    public function getDataViews($typeCode)
    {
        $views = Yii::$app->registry->getTypeRegisteredAttribute(static::getDocType($typeCode), static::SERVICE_ID, 'dataViews');
        if (empty($views)) {
            $views = [];
        }

        return ArrayHelper::merge($this->_config->defaultDataViews, $views);
    }

    /**
     * Поиск документа по id.
     * Возвращает Document или null, если ничего не найдено.
     * @param integer $id
     * @return Document
     * @todo На переходном этапе ищется документ, который по завершении должен
     * стать Swiftfin.
     */
    public function getDocument($id)
    {
        return SwiftFinSearch::findOne($id);
    }

    /**
     * Функция отрабатывает смену статуса документа
     * @param Document $document
     * @param string $messageStatus
     * @return void
     */
    public function onDocumentStatusChange(Document $document)
    {
        // Сделать экспорт MT011 если статус DELIVERED (CYB-830)
        if (Document::STATUS_DELIVERED === $document->status) {
            if ($this->settings->deliveryExport) {
                SwiftfinHelper::exportDeliveredReport($document);
            }

            // Получаем текст исходный текст документа
            $typeModel = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
            $content = (string) $typeModel;

            // Создание документа ACK
            SwiftfinHelper::createAck($content, $document->sender);
        } else if (Document::STATUS_UNDELIVERED === $document->status ||
                Document::STATUS_REJECTED === $document->status) {

            // Получаем текст исходный текст документа
            $typeModel = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
            $content = (string) $typeModel;

            // Создание документа NAK
            SwiftfinHelper::createNak($content, $document->sender);
        } else {
            parent::onDocumentStatusChange($document);
        }
    }

    /**
     * Возвращает docType для указанного typeCode
     * @return string
     */
    public static function getDocType($typeCode)
    {
        return 'MT'.preg_replace('/[^0-9]/', '', $typeCode);
    }

    /**
     * Возвращает docType для указанного typeCode
     * @return string
     */
    public static function getTypeCode($docType)
    {
        return 'swift/'.preg_replace('/[^0-9]/', '', $docType);
    }

    /**
     * Save data into storage. Using out folder
     *
     * @param string $path Data to save
     * @param string $filename File name
     * @return StoredFile|NULL
     */
    public function storeFileOut($path, $filename = '')
    {
        return $this->storeFile($path, self::RESOURCE_STORAGE_OUT, $filename);
    }

    /**
     * Save data into storage. Using out folder
     *
     * @param string $data Data to save
     * @param string $filename File name
     * @return StoredFile|NULL
     */
    public function storeDataOut($data, $filename = '')
    {
        return $this->storeData($data, self::RESOURCE_STORAGE_OUT, $filename);
    }

    /**
     * Get user verification document type
     *
     * @param string $type Document type
     * @return array|string Return array of models or models class namespace
     */
    public function getUserVerificationDocumentType($type = null)
    {
        $map = [
            'MT101' => 'addons\swiftfin\models\verify\Mt101',
            'MT102STP' => 'addons\swiftfin\models\verify\Mt102stp',
            'MT103' => 'addons\swiftfin\models\verify\Mt103',
            'MT110' => 'addons\swiftfin\models\verify\Mt110',
            'MT195' => 'addons\swiftfin\models\verify\Mt195',
            'MT196' => 'addons\swiftfin\models\verify\Mt196',
            'MT202' => 'addons\swiftfin\models\verify\Mt202',
            'MT202COV' => 'addons\swiftfin\models\verify\Mt202cov',
            'MT205' => 'addons\swiftfin\models\verify\Mt205',
            'MT205COV' => 'addons\swiftfin\models\verify\Mt205COV',
            'MT207' => 'addons\swiftfin\models\verify\Mt207',
            'MT210' => 'addons\swiftfin\models\verify\Mt210',
            'MT256' => 'addons\swiftfin\models\verify\Mt256',
            'MT290' => 'addons\swiftfin\models\verify\Mt290',
            'MT292' => 'addons\swiftfin\models\verify\Mt292',
            'MT295' => 'addons\swiftfin\models\verify\Mt295',
            'MT296' => 'addons\swiftfin\models\verify\Mt296',
            'MT300' => 'addons\swiftfin\models\verify\Mt300',
            'MT320' => 'addons\swiftfin\models\verify\Mt320',
            'MT330' => 'addons\swiftfin\models\verify\Mt330',
            'MT540' => 'addons\swiftfin\models\verify\Mt540',
            'MT541' => 'addons\swiftfin\models\verify\Mt541',
            'MT542' => 'addons\swiftfin\models\verify\Mt542',
            'MT543' => 'addons\swiftfin\models\verify\Mt543',
            'MT544' => 'addons\swiftfin\models\verify\Mt544',
            'MT545' => 'addons\swiftfin\models\verify\Mt545',
            'MT546' => 'addons\swiftfin\models\verify\Mt546',
            'MT547' => 'addons\swiftfin\models\verify\Mt547',
            'MT548' => 'addons\swiftfin\models\verify\Mt548',
            'MT549' => 'addons\swiftfin\models\verify\Mt549',
            'MT700' => 'addons\swiftfin\models\verify\Mt700',
            'MT720' => 'addons\swiftfin\models\verify\Mt720',
            'MT750' => 'addons\swiftfin\models\verify\Mt750',
            'MT760' => 'addons\swiftfin\models\verify\Mt760',
            'MT900' => 'addons\swiftfin\models\verify\Mt900',
            'MT910' => 'addons\swiftfin\models\verify\Mt910',
            'MT940' => 'addons\swiftfin\models\verify\Mt940',
            'MT950' => 'addons\swiftfin\models\verify\Mt950',
        ];

        if (is_null($type)) {
            return $map;
        } else {
            $type = str_replace('swift/', 'MT', $type);

            if (isset($map[$type])) {
                return $map[$type];
            } else {
                return [];
            }
        }
    }

    /**
     * Check verification requirements for specified document type
     *
     * @param string $type Document type
     * @return boolean
     */
    public function isVerificationRequired($type = null, $terminalId = null)
    {
        if (is_null($type)) {
            return parent::isVerificationRequired($type, $terminalId);
        }

        $settings = $this->settings->userVerificationRules;
        if (empty($settings)) {
            return false;
        }

        $documentType = str_replace('swift/', 'MT', $type);

        return in_array($documentType, $settings);
    }

    /**
     * Get user verify doc type
     *
     * @return array
     */
    public function getUserVerifyDocType()
    {
        $verificationDocType = [];

        if (Yii::$app->user->identity->role == User::ROLE_USER) {
            foreach ($this->settings->userVerificationRules as $key => $value) {
                $verificationDocType[] = $value;
            }
        }

        return $verificationDocType;
    }

    public function getEnabledUsers()
    {
        $users = SwiftFinUserExt::find()
                ->innerJoin(
                    User::tableName(),
                    '`userId` = `'.User::tableName().'`.`id`'
                )
                ->andWhere(['!=', 'canAccess', 0])
                ->andWhere(['!=', User::tableName() . '.role', User::ROLE_ADMIN])
                ->all();

        return $users;
    }

    /**
     * @param Document $document
     * @return boolean if module processed status
     */
    public function processDocumentExtStatus($document)
    {
        if (SwiftfinHelper::isAuthorizable($document)) {
            $userPreAuthorizer = SwiftFinUserExt::find()
                ->from(SwiftFinUserExt::tableName() . ' AS ux')
                ->innerJoin(User::tableName() . ' AS u', [
                    'and',
                    'u.id = ux.userId',
                    ['u.status' => User::STATUS_ACTIVE],
                    ['ux.role' => SwiftFinUserExt::ROLE_PREAUTHORIZER]
                ])
                ->one();

            $userAuthorizer = SwiftFinUserExt::find()
                ->from(SwiftFinUserExt::tableName() . ' AS ux')
                ->leftJoin(User::tableName() . ' AS u', [
                    'and',
                    'u.id = ux.userId',
                    ['u.status' => User::STATUS_ACTIVE],
                    ['ux.role' => SwiftFinUserExt::ROLE_AUTHORIZER]
                ])
                ->one();

            if ($userPreAuthorizer) {
                $status = SwiftFinDocumentExt::STATUS_PREAUTHORIZATION;
            } else if ($userAuthorizer) {
                $status = SwiftFinDocumentExt::STATUS_AUTHORIZATION;
            } else {
                return false;
            }

            $extModel = $document->extModel;
            $extModel->extStatus = $status;
            $extModel->save();

            return true;
        }

        return false;
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
            } else {
                return true;
            }
        }

        if ($status !== Document::STATUS_ACCEPTED) {
            $status = Document::STATUS_ACCEPTED;

            /**
            * Определение автора документа
            * Только для исходящих документов
            * И если мы не в консольном приложении
            * И если возможно определить пользователя
            * И если автор уже не указан
            */
            if ((Yii::$app instanceof \yii\web\Application)
                && !empty(Yii::$app->user)
                && $document->direction == Document::DIRECTION_OUT
            ) {
                $extModel = $document->extModel;

                if (empty($extModel->userId)) {
                    $extModel->userId = Yii::$app->user->id;
                    $extModel->save();
                }
            }

            /**
             * Этапы отправки документа
             * 1. Верификация (сверка)
             * 2. Авторизация (приемка)
             * 3. Ожидание подписания
             * 4. Модификация (возможна всегда на этапах 1 - 3)
             * 5. Подписание
             * 6. Получение статуса ACCEPTED сигнализирует о готовности к отправке
             */

            // Документы типов MT9xx идут напрямую, игнорируя настройки

            if (substr($document->type, 0, 3) !== 'MT9') {

                // Проверяем, не установлено ли уже количество подписей
                if (!$document->signaturesRequired) {
                    // Иначе устанавливаем количество подписей из настроек подписания
                    if ($this->isSignatureRequired(Document::ORIGIN_WEB, $document->sender)) {
                        // необходимо заранее задать кол-во подписей -- wtf??
                        $document->signaturesRequired = $this->getSignaturesNumber($document->sender);
                        $document->save(false, ['signaturesRequired']);
                    }
                }

                if ($document->status !== Document::STATUS_USER_VERIFIED && $this->isVerificationRequired($document->type)) {
                    // шаг 1
                    $status = Document::STATUS_FOR_USER_VERIFICATION;
                } else if ($this->processDocumentExtStatus($document)) {
                    // шаг 2
                    $status = Document::STATUS_SERVICE_PROCESSING;
                } else if ($this->isSignatureRequired(Document::ORIGIN_WEB, $document->sender)) {
                    // шаг 3
                    if ($document->signaturesRequired > 0) {
                        $status = Document::STATUS_FORSIGNING;
                    }
                }
            }
        }

        if ($status != $document->status || $document->status == Document::STATUS_FORSIGNING) {
            $document->updateStatus($status);
        }

        return true;
    }

    public function getName(): string
    {
        return Yii::t('app', 'SwiftFin');
    }

    public function getDeletableDocumentTypes(\yii\web\User $user): array
    {
        $userCanDeleteDocuments = $user->can(DocumentPermission::DELETE, ['serviceId' => static::SERVICE_ID]);
        if (!$userCanDeleteDocuments) {
            return [];
        }

        if (!(new SwiftFinDocumentExt())->isDocumentDeletable($user->identity)) {
            return [];
        }

        return array_keys($this->mtDispatcher->getRegisteredTypes());
    }

    public function getSignableDocumentTypes(\yii\web\User $user): array
    {
        $userCanSignDocuments = $user->can(DocumentPermission::SIGN, ['serviceId' => static::SERVICE_ID]);
        if (!$userCanSignDocuments) {
            return [];
        }

        return array_keys($this->mtDispatcher->getRegisteredTypes());
    }
}
