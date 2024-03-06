<?php
namespace addons\edm\controllers\helpers;

use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\document\Document;
use common\helpers\CryptoProHelper;
use common\helpers\DocumentHelper;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use Yii;

/**
 * Хэлпер для обобщения действий,
 * которые совершаются в контроллерах документов валютного контроля
 * Связанные данные документов валютного контроля:
 * - операции для валютной справки
 * - документы для справки о подтверждающих документах
 * Class FCCHelper
 * @package addons\edm\controllers\helpers
 */
class FCCHelper
{
    /**
     * Очистка кэша связанных данных
     * @param $key
     */
    public static function clearChildObjectCache($key)
    {
        if (Yii::$app->cache->exists($key . '-' . Yii::$app->session->id)) {
            // Удалить кеш
            Yii::$app->cache->delete($key . '-' . Yii::$app->session->id);
        }
    }

    /**
     * Получение кэша связанных данных
     * @param $key
     * @return array|mixed
     */
    public static function getChildObjectCache($key)
    {
        if (Yii::$app->cache->exists($key . '-' . Yii::$app->session->id)) {
            $data = Yii::$app->cache->get($key . '-' . Yii::$app->session->id);
        } else {
            $data = [];
        }

        return $data;
    }

    /**
     * Установка кэша связанных данных
     * @param $key
     * @param $data
     */
    public static function setChildObjectCache($key, $data)
    {
        Yii::$app->cache->set($key . '-' . Yii::$app->session->id, $data);
    }

    /**
     * Обработка формы создания/редактирования документов валютного контроля
     * @param $controller
     * @param $model
     * @param $singleObjectView
     * @param $manyObjectsView
     * @param $cacheKey
     * @param array $addParams
     * @return array
     */
    public static function processDocumentForm($controller, $model, $singleObjectView, $manyObjectsView, $cacheKey, $addParams = [])
    {
        $post = Yii::$app->request->post();
        $model->load($post);
        $model->validate();

        $uuid = isset($post['uuid']) ? $post['uuid'] : null;

        $result = [];

        // Если были ошибки
        if ($model->errors) {
            // Возврат формы с ошибками
            $result['status'] = 'error';
            $params = [
                'model' => $model,
                'uuid' => $uuid
            ];
            $result['content'] = $controller->renderAjax($singleObjectView, $params);
        } else {
            // Запись операции в кэш
            $result['status'] = 'ok';

            $childObjectData = static::getChildObjectCache($cacheKey);

            if ($uuid) {
                if ($childObjectData[$uuid]) {
                    $childObjectData[$uuid] = $model;
                }
            } else {
                $childObjectData[Uuid::generate()] = $model;
            }

            static::setChildObjectCache($cacheKey, $childObjectData);

            $result['opcount'] = count($childObjectData);
            $result['content'] = $controller->renderAjax(
                $manyObjectsView,
                array_merge(['childObjectData' => $childObjectData], $addParams)
            );
        }

        return $result;
    }

    /**
     * Удаление добавленного вложенного объекта из списка на форме документов валютного контроля
     * @param $controller
     * @param $uuid
     * @param $cacheKey
     * @param $multipleObjectsView
     * @return mixed
     */
    public static function deleteChildObjectFromDocumentList(
        $controller, $uuid, $cacheKey, $multipleObjectsView, $params = []
    ) {
        $childObjectData = static::getChildObjectCache($cacheKey);

        if (isset($childObjectData[$uuid])) {
            unset($childObjectData[$uuid]);
        }

        static::setChildObjectCache($cacheKey, $childObjectData);

        $content = $controller->renderAjax(
            $multipleObjectsView,
            // Использование дополнительных параметров для вывода шаблона
            array_merge(['childObjectData' => $childObjectData], $params)
        );

        return $content;
    }

    /**
     * Получение формы обновления вложенного
     * объекта из списка на форме документов валютного контроля
     * @param $controller
     * @param $uuid
     * @param $cacheKey
     * @param $singleObjectView
     * @return string
     */
    public static function updateChildObjectFromDocumentList($controller, $uuid, $cacheKey, $singleObjectView)
    {
        $childObjectData = static::getChildObjectCache($cacheKey);

        $content = '';

        if (isset($childObjectData[$uuid])) {
            $model = $childObjectData[$uuid];
            $content = $controller->renderAjax($singleObjectView, ['model' => $model, 'uuid' => $uuid]);
        }

        return $content;
    }

    /**
     * Получение количества подписей, требуемых для подписания документа
     * По счету или из общих настроек
     * @param $senderTerminal
     * @param $account
     * @return int
     */
    public static function getRequireSignatures($senderTerminal, $account = null)
    {
        $requireSignatures = 0;

        $edmModule = Yii::$app->addon->getModule('edm');

        if ($account) {
            if ($account->requireSignQty) {
                $requireSignatures = $account->requireSignQty;
            } else if ($edmModule->isSignatureRequired(Document::ORIGIN_WEB, $senderTerminal)) {
                $requireSignatures = $edmModule->getSignaturesNumber($senderTerminal);
            }
        } else {
            if ($edmModule->isSignatureRequired(Document::ORIGIN_WEB, $senderTerminal)) {
                $requireSignatures = $edmModule->getSignaturesNumber($senderTerminal);
            }
        }

        return $requireSignatures;
    }

    /**
     * Проверка: возможно ли редактировать/удалять документ валютного контроля
     * @param $document
     * @return bool
     */
    public static function isDocumentCanModify($document)
    {
        // Если статус документа не указан, модификация возможна
        if (empty($document->status)) {
            return true;
        }

        // Статусы, при которых модификация невозможна
        $deniedStatuses = [
            Document::STATUS_DELIVERED,
            Document::STATUS_REJECTED,
            Document::STATUS_SENT,
            Document::STATUS_SENDING,
            Document::STATUS_PROCESSING_ERROR,
            Document::STATUS_DELIVERING,
        ];

        // Если статус документа находится в списке запрещенных для модификации статусов
        if (in_array($document->status, $deniedStatuses)) {
            return false;
        } else {
            return true;
        }
    }

    public static function signCryptoPro($document)
    {
        $module = Yii::$app->getModule(ISO20022Module::SERVICE_ID);
        $extModel = $document->extModel;

        if (!$module->settings['enableCryptoProSign']) {
            $extModel->extStatus = null;
            // Сохранить модель в БД
            $extModel->save();

            return true;
        }

        $cyx = CyberXmlDocument::read($document->actualStoredFileId);

        // Подписание документа
        $typeModel = $cyx->getContent()->getTypeModel();

        $typeModel->removeSignatures(); // WHY?

        $signedTypeModel = CryptoProHelper::sign('ISO20022', $typeModel, true);

        if (!$signedTypeModel) {
            $extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_SIGNING_ERROR;
            // Сохранить модель в БД
            $extModel->save();
            $document->updateStatus(Document::STATUS_PROCESSING_ERROR);

            return false;
        }

        // Изменение конверта документа
        //$typeModel = ISO20022Helper::modifyModelZipContent($signedTypeModel);

        /** @fixme @todo заменить на setTypeModel() */

        $oldCyxDoc = CyberXmlDocument::read($document->actualStoredFileId);

        $newCyxDoc = CyberXmlDocument::loadTypeModel($typeModel);
        $newCyxDoc->docId = $oldCyxDoc->docId;
        $newCyxDoc->docDate = $oldCyxDoc->docDate;
        $newCyxDoc->senderId = $oldCyxDoc->senderId;
        $newCyxDoc->receiverId = $oldCyxDoc->receiverId;

        $storedFile = Yii::$app->storage->get($document->actualStoredFileId);
        $storedFile->updateData($newCyxDoc->saveXML());

        $extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_SIGNING_SUCCESS;

        // Сохранить модель в БД и вернуть резултат сохранения
        return $extModel->save();
    }

    public static function updateCyberXml(Document $document, $extModel, $typeModel)
    {
        $typeModel->sender = $document->sender;

        /** @fixme @todo заменить на setTypeModel() */
        $oldCyxDoc = CyberXmlDocument::read($document->actualStoredFileId);

        $newCyxDoc = CyberXmlDocument::loadTypeModel($typeModel);
        $newCyxDoc->docId = $oldCyxDoc->docId;
        $newCyxDoc->docDate = $oldCyxDoc->docDate;
        $newCyxDoc->senderId = $oldCyxDoc->senderId;
        $newCyxDoc->receiverId = $oldCyxDoc->receiverId;

        // Модификация CYX-конверта
        $storedCyxFile = Yii::$app->storage->get($document->actualStoredFileId);
        $storedCyxFile->updateData($newCyxDoc->saveXML());

        // Сброс количества проставленных подписей документа
        $document->signaturesCount = 0;
        $document->save(false);

        $extModel->extStatus = ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING;
        // Сохранить модель в БД
        $extModel->save();
    }

    public static function createCyberXml($typeModel, $params)
    {
        $sender = $params['sender'];
        $receiver = $params['receiver'];
        $terminal = $params['terminal'];
        $account = $params['account'];

        $typeModel->sender = $sender;

        if ($typeModel->type !== Auth026Type::TYPE){
            $fileName = $typeModel->fileName;
        } else {
            $fileName = reset($typeModel->fileNames);
        }
        // Атрибуты документа
        $documentAttributes = [
            'type' => $typeModel->getType(),
            'direction' => Document::DIRECTION_OUT,
            'origin' => Document::ORIGIN_WEB,
            'terminalId' => $terminal->id,
            'sender' => $sender,
            'receiver' => $receiver,
        ];

        // Атрибуты расширяющей модели
        $extModelAttributes = [
            'fileName' => $fileName,
            'extStatus' => ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING,
            'msgId' => $typeModel->msgId,
        ];

        // Получение количества подписей, требуемых по счету
        $requireSignatures = static::getRequireSignatures($terminal->terminalId, $account);

        if ($requireSignatures > 0) {
            $documentAttributes['signaturesRequired'] = $requireSignatures;
        }

        // Создать контекст документа
        return DocumentHelper::createDocumentContext(
            $typeModel,
            $documentAttributes,
            $extModelAttributes,
            $terminal->id
        );
    }

}