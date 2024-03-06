<?php

namespace addons\swiftfin\helpers;

use addons\swiftfin\models\SwiftFinDictBank;
use addons\swiftfin\models\containers\swift\SwtContainer;
use addons\swiftfin\models\SwiftFinDocumentExt;
use addons\swiftfin\models\SwiftFinType;
use addons\swiftfin\models\SwiftFinUserExt;
use addons\swiftfin\models\SwiftFinUserExtAuthorization;
use addons\swiftfin\SwiftfinModule;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\models\User;
use common\settings\AppSettings;
use DOMDocument;
use Exception;
use Yii;
use yii\base\ErrorException;

/**
 * SfiftFin helper class
 *
 * @package addons
 * @subpackage swiftfin
 */
class SwiftfinHelper
{
    const FILE_FORMAT_UNKNOWN       = 'unknown';
    const FILE_FORMAT_SWIFT         = 'swift';
    const FILE_FORMAT_SWIFT_PACKAGE = 'swiftSwa';
    const FILE_FORMAT_ANY_CONTENT   = 'anyContent';
    const FILE_FORMAT_CYBERXML      = 'cyberxml';

    /**
     * Функция определяет формат входного файла
     * @param string $path Путь к файлу
     * @return string FILE_FORMAT_SWIFT_PACKAGE | FILE_FORMAT_SWIFT | FILE_FORMAT_CYBERXML | FILE_FORMAT_UNKNOWN
     */
    public static function determineFileFormat($path)
    {
        try {
            $content = file_get_contents($path);
        } catch (Exception $ex) {
            \Yii::warning($ex->getMessage());
            return self::FILE_FORMAT_UNKNOWN;
        }

        return static::determineStringFormat($content);
    }

    /**
     * Determine string foramt
     *
     * @param string $content Data
     * @return string Return FILE_FORMAT_SWIFT_PACKAGE | FILE_FORMAT_SWIFT | FILE_FORMAT_CYBERXML | FILE_FORMAT_UNKNOWN
     */
    public static function determineStringFormat($content)
    {
        $count = preg_match_all("/(\{1:.{25}\}\s*\{2:.{17,48}\})/s", $content);

        if ($count > 1) {
            return self::FILE_FORMAT_SWIFT_PACKAGE; // SWA
        } else if ($count == 1) {
            $count = preg_match_all("/(\{1:.{25}\}\s*\{2:.{17,48}\}\s*(\{3:.+\}){0,1}\s*\{4:.+\})/s", $content);
            if ($count > 0) {
                return self::FILE_FORMAT_SWIFT; // SWT
            }
        }

        return self::checkCyberXmlDocument($content); // CyberXml or unknown
    }

    /**
     * Check on CyberXml document
     *
     * @param string $content File content
     * @return string Return file format type
     */
    protected function checkCyberXmlDocument($content)
    {
        try {
            libxml_use_internal_errors(true);
            $dom = new DOMDocument(CyberXmlDocument::XML_VERSION, CyberXmlDocument::XML_ENCODING);
            $dom->loadXML($content, LIBXML_PARSEHUGE);

            if ($dom->schemaValidate(Yii::getAlias(CyberXmlDocument::XSD_SCHEMA))) {
                return self::FILE_FORMAT_CYBERXML;
            } else {
                return self::FILE_FORMAT_UNKNOWN;
            }
        } catch (Exception $ex) {
            \Yii::warning($ex->getMessage());

            return self::FILE_FORMAT_UNKNOWN;
        }
    }

    /**
     * @param Document $document
     * @param int $userId If not null, check for specified user id
     * @return boolean
     */
    public static function isAuthorizable(Document $document, $userId = null)
    {
        /**
         * Первичные проверки, документ может быть авторизован
         * в состоянии CREATING (когда не требуется сверка)
         * или USER_VERIFIED (уже сверен) или собственном STATUS_SERVICE_PROCESSING
         */

        $extModel = SwiftFinDocumentExt::findOne(['documentId' => $document->id]);

        if ($document->status !== Document::STATUS_CREATING
            && $document->status !== Document::STATUS_USER_VERIFIED
            && $document->status !== Document::STATUS_SERVICE_PROCESSING
            || $extModel->extStatus === SwiftFinDocumentExt::STATUS_AUTHORIZED
            || $extModel->extStatus === SwiftFinDocumentExt::STATUS_INAUTHORIZATION) {

            return false;
        }

        /**
         * если задан $userId, придется все равно его пробивать, так что пробуем сразу отсечь
         */
        $extUser = null;

        if (!is_null($userId)) {
            $extUser = SwiftFinUserExt::find()
                ->from(SwiftFinUserExt::tableName() . ' AS ux')
                ->innerJoin(User::tableName() . ' AS u',
                    [
                        'and',
                        ['u.id' => $userId],
                        ['u.status' => User::STATUS_ACTIVE],
                        'u.id = ux.userId',
                        ['ux.role' => [SwiftFinUserExt::ROLE_AUTHORIZER,
                                SwiftFinUserExt::ROLE_PREAUTHORIZER]
                        ]
                    ]
                )
                ->one();

            if (empty($extUser)) {
                /**
                 * такого чувака нет либо он не пекётся о таких вещах
                 */
                return false;
            }

            // если он пытается авторизовать собственный документ
            if ($extModel->userId && $extModel->userId == $userId) {
                return false;
            }

            /**
             * если он преавторизатор, а документ уже прошел преавторизацию,
             * ему также нечего ловить
             */
            if ($extUser->role == SwiftFinUserExt::ROLE_PREAUTHORIZER
                && $extModel->extStatus === SwiftFinDocumentExt::STATUS_AUTHORIZATION
            ) {
                return false;
            }
        }

        /**
         * Документ пре- и авторизуем, если существуют какие-то активные авторизаторы
         * либо совсем без условий, либо с условиями, подходящими под документ
         */
        $query = SwiftFinUserExt::find()
            ->from(SwiftFinUserExt::tableName() . ' AS ux')
            ->select(['ux.userId'])
            ->innerJoin(User::tableName() . ' AS u', [
                'and',
                'u.id = ux.userId',
                ['u.status' => User::STATUS_ACTIVE],
                ['ux.Role' => SwiftFinUserExt::ROLE_AUTHORIZER],
                ['!=', 'ux.canAccess', 0]
            ])
            ->leftJoin(SwiftFinUserExtAuthorization::tableName() . ' AS uxa',
                'uxa.userExtId = ux.id'
            )
            ->where([
                'or',
                ['uxa.userExtId' => NULL],
                [
                    'and',
                    ['uxa.currency' => $extModel->currency],
                    ['uxa.docType' => $document->type],
                    ['<=', 'uxa.minSum', $extModel->sum],
                    [
                        'or',
                        ['>=', 'uxa.maxSum', $extModel->sum],
                        ['uxa.maxSum' => 0],
                    ]
                ]
            ]);

        $uxList = $query->all();

        if (!count($uxList)) {
            // Авторизаторов нет, делать тут нечего
            return false;
        }

        /**
         * Варианты следующие:
         * 1. мы нашли авторизаторов и юзер не задан
         * 2. мы нашли авторизаторов и юзер - преавторизатор
         * 3. мы нашли авторизаторов и юзер-авторизатор в их числе
         * 4. мы нашли авторизаторов и юзер-авторизатор не в их числе
         */

        if (is_null($userId)) {
            // вариант 1
            return true;
        }

        if ($extUser->role == SwiftFinUserExt::ROLE_PREAUTHORIZER) {
            // вариант 2, возвращаем правду смело, т.к. остальные проверки для
            // преавторизатора были сделаны выше
            return true;
        }

        /**
         * Остались только авторизаторы. Они могут авторизовать документ
         * только в статусе AUTHORIZATION
         */
        if ($extModel->extStatus !== SwiftFinDocumentExt::STATUS_AUTHORIZATION) {
            return false;
        }

        // наконец, варианты 3 и 4
        foreach($uxList as $ux) {
            if ($ux->userId == $userId) {
                // наш человек
                return true;
            }
        }

        return false;
    }

    /**
     * @param Document $doc
     * @return bool|string
     * @throws ErrorException
     * @throws \yii\base\Exception
     */
	public static function exportDeliveredReport($doc)
	{
        if ($doc->status != Document::STATUS_DELIVERED
            || $doc->typeGroup != SwiftfinModule::SERVICE_ID) {
            return false;
        }

        $model = SwiftfinModule::getInstance()->mtDispatcher->instantiateMt('011');
        if (empty($model)) {
            throw new ErrorException(
                    'Error: could not instantiate MT011 model for export');
        }

        $storedFile = Yii::$app->storage->get($doc->getValidStoredFileId());

        if (empty($storedFile)) {
            throw new ErrorException(
                    'Error: could not find stored file for document ID '
                    . $doc->id
            );
        }

        $exportResource = static::getExportResource($doc->receiver, SwiftfinModule::RESOURCE_EXPORT_DELIVERY);
        if (empty($exportResource)) {
            throw new ErrorException(
                    'Error: could not allocate resource '
                    . SwiftfinModule::SERVICE_ID . '/' . SwiftfinModule::RESOURCE_EXPORT_DELIVERY
            );
        }

        $source = SwiftFinType::createFromData($storedFile->data)->getSource();

        $sender = $source->getSender();
        $recipient = $source->getRecipient();
        $sessionNumber = $source->getSessionNumber();
        $inputSequenceNumber = $source->getInputSequenceNumber();
        $model->getNode('175')->setValue($source->getInputTime());
        $model->getNode('106')->setValue($source->getMIR());

        // 108 сейчас не полностью обработан из-за отсутствия во всех тестах блока Message User Reference
        // по спекам можно в любом случае идти по ветке "взять из тега 20" (OperationReference)
        $model->getNode('108')->setValue($source->getOperationReference());

        $model->getNode('A')->getNode('175')->setValue($source->getOutputTime());
        $model->getNode('107')->setValue($source->getOutputDate() . $recipient . $sessionNumber . $inputSequenceNumber);
        $container = new SwtContainer();

        $container->setContent($model->getBody());

        $container->sender = $recipient;
        $container->recipient = $sender;
        $container->setContentType('011');
        $container->direction = SwtContainer::DIRECTION_OUT;
        $container->sessionNumber = $sessionNumber;
        $container->inputSequenceNumber = $inputSequenceNumber;

        $container->updateMessageFields();

        return $exportResource->putData($container->getRawText(), SwiftfinModule::$exportExtension);

	}

	/**
	 * Функция экспортирует MT010 для недоставленных документов
	 * в соответствии с настройками терминала
	 * @param Document $doc
	 * @return boolean success or fail
	 */
	public static function exportUndeliveredReport($doc)
	{
        if ($doc->typeGroup != SwiftfinModule::SERVICE_ID) {
            return false;
        }

        $model = SwiftfinModule::getInstance()->mtDispatcher->instantiateMt('010');
        if (empty($model)) {
            throw new ErrorException(
                    'Error: could not instantiate MT010 model for export');
        }

        $storedFile = Yii::$app->storage->get($doc->getValidStoredFileId());

        if (empty($storedFile)) {
            throw new ErrorException(
                    'Error: could not find stored file for document ID '
                    . $doc->id
            );
        }

        $exportResource = static::getExportResource($doc->receiver, SwiftfinModule::RESOURCE_EXPORT_DELIVERY);
        if (empty($exportResource)) {
            throw new ErrorException(
                    'Error: could not allocate resource '
                    . SwiftfinModule::SERVICE_ID . '/' . SwiftfinModule::RESOURCE_EXPORT_DELIVERY
            );
        }

        $source = SwiftFinType::createFromData($storedFile->data)->getSource();

        $sender = $source->getSender();
        $recipient = $source->getRecipient();
        $sessionNumber = $source->getSessionNumber();
        $inputSequenceNumber = $source->getInputSequenceNumber();
        $model->getNode('106')->setValue($source->getMIR());

        // 108 сейчас не полностью обработан из-за отсутствия во всех тестах блока Message User Reference
        // по спекам можно в любом случае идти по ветке "взять из тега 20" (OperationReference)
        $model->getNode('108')->setValue($source->getOperationReference());

        $model->getNode('431')->setValue('7'); // delivery not attempted
        $model->getNode('102')->setValue($recipient);

        $model->getNode('104')->setValue('N'); // normal priority
        $container = new SwtContainer();

        $container->setContent($model->getBody());
        $container->setContentType('010');
        $container->sender = $recipient;
        $container->recipient = $sender;
        $container->direction = SwtContainer::DIRECTION_OUT;
        $container->sessionNumber = $sessionNumber;
        $container->inputSequenceNumber = $inputSequenceNumber;

        $container->updateMessageFields();

        $exportResource->putData($container->getRawText(), SwiftfinModule::$exportExtension);

		return true;
	}

    /**
     * Создание ack-документа
     */
    public static function createAck($mtText, $terminalAdress) {

        $content = '';

        // Получаем значение поля 1
        preg_match_all('#{1:.+?}#m', $mtText, $array);

        $field01 = $array[0][0];
        $field01 = str_replace("F01", "F21", $field01);

        $content .= $field01;

        // Получение текущей даты
        $date = date('ymdHi');
        $content .= "{4:{177:" . $date . "}{451:0}";

        // Получаем значение поля 3
        preg_match_all('#{3:.+?}#m', $mtText, $array);

        if (isset($array[0][0])) {

            $field03 = $array[0][0];
            $field03 = str_replace("{3:", "", $field03);
            $field03 = str_replace("}", "", $field03);

            $content .= $field03 . "}}";
        } else {
            $content .= "}";
        }

        $content .= "\r\n";

        // Добавляем текст исходного документа

        // Убираем лишние символы
        $mtText = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $mtText);

        $content .= $mtText;

        $resourceExport = static::getExportResource($terminalAdress, SwiftfinModule::RESOURCE_EXPORT_SWIFT);
        $resourceExport->putData($content, SwiftfinModule::$exportExtension);
    }

    /**
     * Cоздание nak-документа
     */
    public static function createNak($mtText, $terminalAdress)
    {
        $content = "";

        // Получаем значение поля 1
        preg_match_all('#{1:.+?}#m', $mtText, $array);

        $field01 = $array[0][0];
        $field01 = str_replace("F01", "F21", $field01);

        $content .= $field01;

        // Получение текущей даты
        $date = date('ymdHi');
        $content .= "{4:{177:" . $date . "}{451:1}{405:T02}";

        // Получаем значение поля 3
        preg_match_all('#{3:.+?}#m', $mtText, $array);

        if (isset($array[0][0])) {

            $field03 = $array[0][0];
            $field03 = str_replace("{3:", "", $field03);
            $field03 = str_replace("{", "", $field03);
            $field03 = str_replace("}", "", $field03);

            $content .= "{" . $field03 . "}}";
        } else {
            $content .= "}";
        }

        $content .= "\r\n";

        // Добавляем текст исходного документа

        // Убираем лишние символы
        $mtText = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $mtText);

        $content .= $mtText;

        $resourceExport = static::getExportResource($terminalAdress, SwiftfinModule::RESOURCE_EXPORT_SWIFT);
        $resourceExport->putData($content, SwiftfinModule::$exportExtension);
    }

    /**
     * Проверка существования референса в рамках
     * исходящих документов swift указанного терминала
     */
    public static function checkOperationReferenceExisted($reference, $terminalId)
    {
        // Ничего не проверяем, если референс не указан
        if (!$reference) {
            return false;
        }

        $referenceDuplicate = Document::find()
            ->from(Document::tableName() . ' doc')
            ->innerJoin(SwiftFinDocumentExt::tableName() . ' ext', [
                'and',
                'ext.documentId = doc.id',
                ['doc.sender' => $terminalId],
                ['doc.direction' => Document::DIRECTION_OUT],
                ['doc.typeGroup' => SwiftfinModule::SERVICE_ID],
                ['!=' , 'doc.status', Document::STATUS_DELETED],
                ['ext.operationReference' => $reference]
            ])->count();

        return $referenceDuplicate;
    }

    /**
     * Получение списка банков
     * @param $q
     * @param int $limit
     * @return array
     */
    public static function getBanksList($q, $limit = 10)
    {
        $q = strtoupper($q);

        $out = ['results' => [['swiftCode' => $q, 'branchCode' => '', 'name' => '(нет в справочнике)', 'id' => $q, 'address' => '']]];
        if (!is_null($q)) {

            $query = SwiftFinDictBank::find()
                ->select(['swiftCode', 'branchCode', 'name', 'address'])
                ->where(['like', 'fullCode', $q]);

            if ($limit) {
                $query->limit($limit);
            }

            $items = $query->all();

            foreach ($items as $item) {
                $attributes = $item->getAttributes(['swiftCode', 'branchCode', 'name', 'address']);
                $attributes['id'] = $item->swiftCode . $item->branchCode;
                $out['results'][] = $attributes;
            }

        }
        return $out;
    }

    /**
     * Получение имени/адреса swift банка по коду
     * @param $code
     * @return string
     */
    public static function getBankInfo($code)
    {
        $strLength = strlen($code);

        $swiftCode = substr($code, 0, $strLength - 3);
        $branchCode = substr($code, $strLength - 3, $strLength - 1);

        $bank = SwiftFinDictBank::findOne(['swiftCode' => $swiftCode, 'branchCode' => $branchCode]);

        if ($bank) {
            $bankInfo = [
                'name' => $bank->name,
                'address' => $bank->address
            ];
        } else {
            $bankInfo = [
                'name' => '',
                'address' => ''
            ];
        }

        return $bankInfo;
    }

    private static function getExportResource($terminalAddress, $dirId)
    {
        return static::shouldUseGlobalExportSettings($terminalAddress)
            ? Yii::$app->registry->getExportResource(SwiftfinModule::SERVICE_ID, $dirId)
            : Yii::$app->registry->getTerminalExportResource(SwiftfinModule::SERVICE_ID, $terminalAddress, $dirId);
    }

    private static function shouldUseGlobalExportSettings($terminalAddress): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $terminalAddress);
        return (bool)$terminalSettings->useGlobalExportSettings;
    }
}