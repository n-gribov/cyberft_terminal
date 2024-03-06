<?php

namespace addons\edm\controllers;

use addons\edm\controllers\helpers\FCCHelper;
use addons\edm\EdmModule;
use addons\edm\models\ConfirmingDocumentInformation\AttachedFileSession;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationItem;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Auth025Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\ControllerCache;
use common\helpers\DocumentHelper;
use common\helpers\Uuid;
use common\helpers\WizardCacheHelper;
use common\helpers\ZipHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\modules\certManager\models\Cert;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Документ Справка о подтверждающих документах
 * Class ConfirmingDocumentInformationController
 * @package addons\edm\controllers
 */
class ConfirmingDocumentInformationController extends BaseServiceController
{
    private $cdiCache;
    private $childObjectCacheKey = 'edm/cdi';
    // Путь к шаблонам для отрисовки связанных объектов
    private $singleChildObjectView = '@addons/edm/views/confirming-document-information/document';
    private $manyChildObjectsView = '@addons/edm/views/confirming-document-information/_documents';
    private $cdiJournalUrl = '/edm/documents/foreign-currency-control-index?tabMode=tabCDI';

    public function __construct($id, $module, array $config = [])
    {
        // Инициализация кэша для реализации функционала
        // выделения и удаления нескольких документов в журнале
        parent::__construct($id, $module, $config);
        $this->cdiCache = new ControllerCache('confirmingDocumentInformation');
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'print', 'download-attachment'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'create', 'update', 'add-document', 'process-document-form', 'delete-document', 'update-document',
                            'render-attached-files', 'upload-attached-file', 'download-temporary-attachment',
                        ],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'delete-cdi'],
                        'roles' => [DocumentPermission::DELETE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['send', 'before-signing'],
                        'roles' => [DocumentPermission::CREATE, DocumentPermission::SIGN],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'before-signing' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Создание нового документа
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new ConfirmingDocumentInformationExt();
        $model->generateUuid();
        $model->scenario = ConfirmingDocumentInformationExt::SCENARIO_UI_CREATE;

        if (Yii::$app->request->isGet) {
            // Очистка кэша создания документа и редирект
            if (Yii::$app->request->get('clearWizardCache')) {
                WizardCacheHelper::deleteCDIWizardCache();

                // Перенаправить на страницу создания
                return $this->redirect('/edm/confirming-document-information/create');
            }

            FCCHelper::clearChildObjectCache($this->childObjectCacheKey);

            // Если создание на основе имеющегося документа
            $id = Yii::$app->request->get('id');
            if ($id) {
                // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
                $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);
                $model = ConfirmingDocumentInformationExt::findOne(['documentId' => $document->id]);
                $model->generateUuid();
                $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);
                /** @var Auth025Type $typeModel */
                $typeModel = $cyxDocument->getContent()->getTypeModel();
                $this->fillRelatedData($model, $typeModel);
            }

            $model->number = DocumentHelper::getDayUniqueCount('cdi');
            $model->date = date('d.m.Y');
            $cachedData = WizardCacheHelper::getCDIWizardCache();
            if ($cachedData) {
                if (isset($cachedData['model'])) {
                    $model = $cachedData['model'];
                }

                if (isset($cachedData['documents'])) {
                    $documents = $cachedData['documents'];
                    $model->documents = $documents;

                    FCCHelper::setChildObjectCache($this->childObjectCacheKey, $documents);
                }
            }
        }

        return $this->processConfirmingDocumentInformation($model);
    }

    /**
     * Редактирование существующего документа
     * @param $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);

        // если документ уже отправлен, с ним ничего нельзя делать
        if (!$document->isModifiable()) {
            // Поместить в сессию флаг сообщения об ошибке модификации
            Yii::$app->session->setFlash('error', 'Ошибка модификации');
            // Перенаправить на страницу индекса
            return $this->redirect($this->cdiJournalUrl);
        }

        $model = ConfirmingDocumentInformationExt::findOne(['documentId' => $id]);
        $model->scenario = ConfirmingDocumentInformationExt::SCENARIO_UI_UPDATE;

        // При открытии страницы очищаем кэш операций
        if (Yii::$app->request->isGet) {
            $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);
            /** @var Auth025Type $typeModel */
            $typeModel = $cyxDocument->getContent()->getTypeModel();
            $this->fillRelatedData($model, $typeModel);
        }

        return $this->processConfirmingDocumentInformation($model);
    }

    /**
     * Удаление документа
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);

        // если документ уже отправлен, с ним ничего нельзя делать
        if (!$document->isModifiable()) {
            // Поместить в сессию флаг сообщения об ошибке модификации
            Yii::$app->session->setFlash('error', 'Ошибка модификации');
            // Перенаправить на страницу индекса
            return $this->redirect($this->cdiJournalUrl);
        }

        $model = ConfirmingDocumentInformationExt::findOne(['documentId' => $id]);

        // Удалить экст-модель и документ из БД
        if ($model->delete() && $document->delete()) {
            if ($document->extModel->storedFileId) {
                Yii::$app->storage->remove($document->extModel->storedFileId);
            }
            // Поместить в сессию флаг сообщения об успешном удалении документа
            Yii::$app->session->setFlash('success', Yii::t('document', 'Document deleted'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке удаления документа
            Yii::$app->session->setFlash('error', Yii::t('document', 'Failed to delete document'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect($this->cdiJournalUrl);
    }

    /**
     * Просмотр документа
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
        $model = ConfirmingDocumentInformationExt::findOne(['documentId' => $id]);
        $backUrl = Yii::$app->request->get('backUrl');

        if (!$backUrl) {
            $backUrl = $this->cdiJournalUrl;
        }

        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);
        /** @var Auth025Type $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();
        $attachedFiles = $typeModel->getAttachedFileList();

        $signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);

        // Вывести страницу
        return $this->render(
            'view',
            compact('model', 'document', 'backUrl', 'typeModel', 'attachedFiles', 'signatures')
        );
    }

    /**
     * Печать документа
     * @param $id
     * @return string
     */
    public function actionPrint($id)
    {
        $this->layout = '/print';

        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
        $model = ConfirmingDocumentInformationExt::findOne(['documentId' => $document->id]);
        $signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);

        // Вывести страницу
        return $this->render('print', compact('model', 'signatures'));
    }

    /**
     * Отправка документа
     * @param $id
     * @return Response
     */
    public function actionSend($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);

        if (!$document->isModifiable()) {
            // Поместить в сессию флаг сообщения об ошибке модификации документа
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Modify error. Confirming document information is already sent'));

            // Перенаправить на страницу индекса
            return $this->redirect($this->cdiJournalUrl);
        }

        $signResult = $this->signCryptoPro($document);

        if (!$signResult) {
            // Перенаправить на страницу индекса
            return $this->redirect([$this->fccJournalUrl]);
        }
        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($document, true);

        // Поместить в сессию флаг сообщения об успешной отправке документа
        Yii::$app->session->setFlash('success', 'Документ отправлен');

        // Перенаправить на страницу индекса
        return $this->redirect($this->cdiJournalUrl);
    }

    private function signCryptoPro($document)
    {
        $signResult = FCCHelper::signCryptoPro($document);

        if (!$signResult) {
            // Поместить в сессию флаг сообщения об ошибке подписания Криптопро
            Yii::$app->session->setFlash('error', Yii::t('document', 'CryptoPro signing error'));
            // Зарегистрировать событие ошибки подисания Криптопро в модуле мониторинга
            Yii::$app->monitoring->log('document:CryptoProSigningError', 'document', $document->id, [
                'terminalId' => $document->terminalId
            ]);

            Yii::error('CryptoPro signing failed');

            return false;
        }

        return true;
    }

    /**
     * Массовое удаление выбранных в журнале документов
     * @return Response
     */
    public function actionDeleteCdi()
    {
        $cached = $this->cdiCache->get();
        $idList = array_keys($cached['entries']);

        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

        ISO20022DocumentExt::deleteAll(['documentId' => $idList]);
        ConfirmingDocumentInformationItem::deleteAll(['documentId' => $idList]);
        ConfirmingDocumentInformationExt::deleteAll(['documentId' => $idList]);
        Document::deleteAll(['id' => $idList]);

        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();

        $this->cdiCache->clear();

        // Поместить в сессию флаг сообщения об успешном удалении документов
        Yii::$app->session->setFlash(
            'info',
            Yii::t('edm', 'Deleted {count} confirming document informations', ['count' => count($idList)])
        );

        // Перенаправить на страницу индекса
        return $this->redirect($this->cdiJournalUrl);
    }

    /**
     * Получение шаблона формы нового документа
     * AJAX
     * @return string
     */
    public function actionAddDocument()
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        $model = new ConfirmingDocumentInformationItem();
        $model->date = date('d.m.Y');
        $model->number = DocumentHelper::getDayUniqueCount('cdiItem');

        return $this->renderAjax($this->singleChildObjectView, ['model' => $model, 'uuid' => null]);
    }

    /**
     * Обработка формы добавления операции
     * AJAX
     * @return array
     */
    public function actionProcessDocumentForm()
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = FCCHelper::processDocumentForm(
            $this, new ConfirmingDocumentInformationItem(),
            $this->singleChildObjectView, $this->manyChildObjectsView, $this->childObjectCacheKey
        );

        return $result;
    }

    /**
     * Удаление документа из списка справки
     * AJAX
     * @param $uuid
     * @return string
     */
    public function actionDeleteDocument($uuid)
    {
        $cacheKey = $this->childObjectCacheKey;
        return FCCHelper::deleteChildObjectFromDocumentList($this, $uuid, $cacheKey, $this->manyChildObjectsView);
    }

    /**
     * Получение шаблона формы редактирования документа из справки
     * @param $uuid
     * @return string
     */
    public function actionUpdateDocument($uuid)
    {
        $cacheKey = $this->childObjectCacheKey;

        return FCCHelper::updateChildObjectFromDocumentList($this, $uuid, $cacheKey, $this->singleChildObjectView);
    }

//	private function createAuth025Type($model)
//    {
//        // Получение xml-содержимого auth.025 из справки о подтверждающих документах
//        $content = ISO20022Helper::createAuth025FromCDI($model);
//
//        // Создание модели auth.025 из xml-содержимого
//        $typeModel = new Auth025Type();
//        $typeModel->loadFromString($content);
//
//        // Валидация по XSD-схеме
//        /**
//         * @todo Валидация временно не используется. Потом раскомментировать!
//         * @see CYB-4079
//         */
//        // $typeModel->validateXSD();
//
//        return $typeModel;
//    }

    private function createCyberXml(ConfirmingDocumentInformationExt $model, Auth025Type $typeModel)
    {
        // Получение организации
        $organization = $model->organization;

        // Получение банка организации
        $bank = null;
        $account = null;
        try {
            $bank = $model->getBank();
            $account = $organization->getAccounts([$bank->bik])->one();
        } catch (\Exception $ex) {
            throw new \Exception("Failed to get organization's ($organization->id) bank or account");
        }

        $terminal = Terminal::findOne($organization->terminalId);

        $params = [
            'sender' => $terminal->terminalId,
            'receiver' => $bank->terminalId,
            'terminal' => $terminal,
            'account' => $account,
//            'attachFile' => null
        ];

        return FCCHelper::createCyberXml($typeModel, $params);
    }

    /**
     * Обработка формы создания/редактирования документа
     * @param $extModel
     * @return string|Response
     */
    private function processConfirmingDocumentInformation($extModel)
    {
        if (!Yii::$app->request->isPost || !$this->validateForm($extModel)) {
            // Вывести форму
            return $this->render('_form', ['model' => $extModel]);
        }

        // Создание ISO20022 содержимого
        $auth025Type = ISO20022Helper::createAuth025FromCDI($extModel);

        if ($auth025Type->errors) {
            // Поместить в сессию флаг сообщения об ошибке XSD-валидации
            Yii::$app->session->setFlash('error', Yii::t('app/iso20022', 'Auth.025 validation against XSD-scheme failed'));
            Yii::info('Auth.025 validation against XSD-scheme failed');
            Yii::info($auth025Type->errors);

            // Вывести форму
            return $this->render('_form', ['model' => $extModel]);
        }

        $document = null;
        if (empty($extModel->documentId)) {
            // Создание CyberXml-документа
            $context = $this->createCyberXml($extModel, $auth025Type);

            if (!$context) {
                // Поместить в сессию флаг сообщения об ошибке создания документа
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create confirming document information'));

                // Вывести форму
                return $this->render('_form', ['model' => $extModel]);
            }

            // Получить документ из контекста
            $document = $context['document'];
            // Модификация ext-модели
            $extModel->documentId = $document->id;
        } else {
            $document = $extModel->document;
            $this->updateDocument($extModel);
            FCCHelper::updateCyberXml($document, $extModel, $auth025Type);
        }

        // Сохранить модель в БД
        $extModel->save();
        DocumentTransportHelper::extractSignData($document);
        WizardCacheHelper::deleteCDIWizardCache();

        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $document->id]);
    }

    private function validateForm($model)
    {
        // Загрузить данные модели из формы в браузере
        $model->load(Yii::$app->request->post());

        // Загрузить документ(ы)
        $documents = FCCHelper::getChildObjectCache($this->childObjectCacheKey);
        $model->loadDocuments($documents);

        // Валидировать форму
        if (!$model->validate()) {
            if ($model->hasErrors('documents')) {
                // Если не указаны операции, прерываем обработку формы
                // Поместить в сессию флаг сообщения об отсутствии документов
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Not specified documents for confirming document information'));
            } else {
                // Поместить в сессию флаг сообщения об ошибке создания документа
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create confirming document information'));
            }

            return false;
        }

        $documentsHasErrors = false;

        // Проверить наличие ошибок в документах
        foreach($model->documents as $document) {
            if ($document->hasErrors()) {
                $documentsHasErrors = true;
            }
        }

        if ($documentsHasErrors) {
            // Поместить в сессию флаг сообщения об ошибке создания документа
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create confirming document information'));

            return false;
        }

        return true;
    }

    public function actionBeforeSigning($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);

        if (!$document->isModifiable()) {
            // Поместить в сессию флаг сообщения об ошибке модификации документа
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Modify error. Confirming document information is already sent'));

            // Перенаправить на страницу индекса
            return $this->redirect($this->cdiJournalUrl);
        }

        if ($document->extModel->extStatus == ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING) {
            if (!$this->signCryptoPro($document)) {
                // Перенаправить на страницу индекса
                return $this->redirect($this->cdiJournalUrl);
            }

            DocumentTransportHelper::extractSignData($document);
        }

        return true;
    }

    private function fillRelatedData(ConfirmingDocumentInformationExt $model, Auth025Type $typeModel)
    {
        $documents = [];

        $tempResource = Yii::$app->registry->getTempResource(EdmModule::SERVICE_ID);
        $tmpDirPath = $tempResource->createDir(uniqid('cdi_', true));
        $attachedFiles = $typeModel->extractAttachedFiles($tmpDirPath);

        foreach($model->items as $itemIndex => $item) {
            $item->loadAttachedFiles($attachedFiles[$itemIndex] ?? []);
            $documents[Uuid::generate()] = $item;
        }

        FCCHelper::setChildObjectCache($this->childObjectCacheKey, $documents);

        $model->documents = $documents;
    }

    private function updateDocument(ConfirmingDocumentInformationExt $extModel): void
    {
        $organization = $extModel->organization;
        $terminal = Terminal::findOne($organization->terminalId);

        $extModel->document->setAttributes(
            [
                'terminalId' => $terminal->id,
                'sender'     => $terminal->terminalId,
                'receiver'   => $extModel->bank->terminalId,
            ],
            false,
        );
        // Сохранить модель в БД
        $isSaved = $extModel->document->save();
        if (!$isSaved) {
            throw new \Exception("Failed to update document $extModel->documentId");
        }
    }


    public function actionUploadAttachedFile()
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $uploadedFile = UploadedFile::getInstanceByName('file');
        $attachedFile = AttachedFileSession::createFromUploadedFile($uploadedFile);

        return $attachedFile->getAttributes(['id', 'name']);
    }

    public function actionRenderAttachedFiles()
    {
        $jsonList = Yii::$app->request->post('list', '[]');

        return $this->renderPartial(
            '@common/views/document/_attachedFiles',
            [
                'modelClass' => AttachedFileSession::class,
                'models' => AttachedFileSession::createListFromJson($jsonList),
                'params' => [],
            ]
        );
    }

    public function actionDownloadAttachment(int $id, int $itemIndex, int $attachmentIndex)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);
        /** @var Auth025Type $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();
        $attachedFile = $typeModel->getAttachedFileList()[$itemIndex][$attachmentIndex] ?? null;
        // Если вложение не найдено, выбросить исключение
        if ($attachedFile === null) {
            throw new NotFoundHttpException();
        }

        // Если модель использует сжатие в zip
        if ($typeModel->useZipContent) {
            $zip = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
            $zipFiles = $zip->getFileList('cp866');
            $fileIndex = array_search($attachedFile->path, $zipFiles);
            if ($fileIndex === false) {
                Yii::info("Zip archive does not contain file {$attachedFile->path}");
                throw new NotFoundHttpException();
            }

            $content = $zip->getFromIndex($fileIndex);
            $zip->purge();
        } else {
            $content = $typeModel->getEmbeddedAttachmentContent($itemIndex, $attachmentIndex);
        }

        Yii::$app->response->sendContentAsFile($content, $attachedFile->name);
    }

    public function actionDownloadTemporaryAttachment(string $documentUuid, string $attachmentUuid)
    {
        $documentsCache = FCCHelper::getChildObjectCache($this->childObjectCacheKey);
        /** @var ConfirmingDocumentInformationItem $document */
        $document = $documentsCache[$documentUuid] ?? null;
        if ($document === null) {
            Yii::info("Document $documentUuid is not found in cache");
            throw new NotFoundHttpException();
        }
        foreach ($document->attachedFiles as $attachedFile) {
            if ($attachedFile->id === $attachmentUuid) {
                Yii::$app->response->sendFile($attachedFile->getPath(), $attachedFile->name);
                return;
            }
        }
        throw new NotFoundHttpException();
    }
}
