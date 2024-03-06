<?php
namespace addons\edm\controllers;

use addons\edm\controllers\helpers\FCCHelper;
use addons\edm\EdmModule;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\ForeignCurrencyControl\AttachedFileSession;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationItem;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\document\DocumentStatusReportsData;
use common\helpers\ControllerCache;
use common\helpers\DocumentHelper;
use common\helpers\UserHelper;
use common\helpers\Uuid;
use common\helpers\WizardCacheHelper;
use common\helpers\ZipHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class ForeignCurrencyControlController extends BaseServiceController
{
    private $foreignCurrencyInformationCache;
    private $childObjectCacheKey = 'edm/fcc';
    // Путь к шаблонам для отрисовки связанных объектов
    private $singleChildObjectView = '@addons/edm/views/foreign-currency-control/operation';
    private $multipleChildObjectsView = '@addons/edm/views/foreign-currency-control/_operations';
    private $fccJournalUrl = '/edm/documents/foreign-currency-control-index?tabMode=tabFCI';

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->foreignCurrencyInformationCache = new ControllerCache('foreignCurrencyInformation');
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'print', 'render-attached-files', 'download-attachment'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'create', 'update', 'add-operation', 'upload-attached-file',
                            'process-operation-form', 'delete-operation', 'update-operation'
                        ],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'delete-foreign-currency-informations'],
                        'roles' => [DocumentPermission::DELETE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['send', 'after-signing', 'before-signing', 'reject-signing'],
                        'roles' => [DocumentPermission::CREATE, DocumentPermission::SIGN],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'before-signing' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Вызов формы создания документа
     * @return string|Response
     */
    public function actionCreate()
    {
        $extModel = new ForeignCurrencyOperationInformationExt();
        $extModel->scenario = ForeignCurrencyOperationInformationExt::SCENARIO_UI_CREATE;

        // При открытии страницы очищаем кэш операций и указываем начальные данные для документа
        if (Yii::$app->request->isGet) {
            // Очистка кэша создания документа и редирект
            if (Yii::$app->request->get('clearWizardCache')) {
                WizardCacheHelper::deleteFCCWizardCache();

                // Перенаправить на страницу создания
                return $this->redirect('/edm/foreign-currency-control/create');
            }

            FCCHelper::clearChildObjectCache($this->childObjectCacheKey);

            // Если создание на основе имеющегося документа
            if ($id = Yii::$app->request->get('id')) {
                // Получить из БД документ с указанным id
                $document = $this->findModel($id);
                $extModel = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $document->id]);
                $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
                $this->fillRelatedData($extModel, $typeModel);
            }

            $extModel->number = DocumentHelper::getDayUniqueCount('fci');
            $extModel->date = date('d.m.Y');

            if ($cacheData = WizardCacheHelper::getFCCWizardCache()) {
                if (isset($cacheData['model'])) {
                    $extModel = $cacheData['model'];
                }

                if (isset($cacheData['operations'])) {
                    $items = $cacheData['operations'];
                    FCCHelper::setChildObjectCache($this->childObjectCacheKey, $items);
                    $extModel->operations = $items;
                }
            }
        }

        return $this->processForeignCurrencyControl($extModel);
    }

    /**
     * Вызов формы редактирования документа
     * @param $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        // если документ уже отправлен, с ним ничего нельзя делать
        if (!$document->isModifiable()) {
            // Поместить в сессию флаг сообщения об ошибке модификации документа
            Yii::$app->session->setFlash('error', 'Ошибка модификации документа');

            // Перенаправить на страницу индекса
            return $this->redirect($this->fccJournalUrl);
        }

        $extModel = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $document->id]);
        $extModel->scenario = ForeignCurrencyOperationInformationExt::SCENARIO_UI_UPDATE;

        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        // При открытии страницы перезагружаем кеш операций из экст-модели
        if (Yii::$app->request->isGet) {
            $this->fillRelatedData($extModel, $typeModel);
        }

        return $this->processForeignCurrencyControl($extModel);
    }

    /**
     * Получение шаблона для формы добавления новой операции
     * AJAX
     * @return string
     */
    public function actionAddOperation()
    {
        $model = new ForeignCurrencyOperationInformationItem();
        $model->docDate = date('d.m.Y');
        $model->number = DocumentHelper::getDayUniqueCount('fcc');
        $content = $this->renderAjax($this->singleChildObjectView, ['model' => $model, 'uuid' => null]);

        return $content;
    }

    /**
     * Удаление операции из списка справки
     * AJAX
     * @param $uuid
     * @return string
     */
    public function actionDeleteOperation($uuid)
    {
        $cacheKey = $this->childObjectCacheKey;

        return FCCHelper::deleteChildObjectFromDocumentList($this, $uuid, $cacheKey, $this->multipleChildObjectsView);
    }

    /**
     * Вызов формы обновления операции из списка справки
     * AJAX
     * @param $uuid
     * @return string
     */
    public function actionUpdateOperation($uuid)
    {
        return FCCHelper::updateChildObjectFromDocumentList(
           $this, $uuid, $this->childObjectCacheKey, $this->singleChildObjectView
        );
    }

    /**
     * Вызов страницы просмотра документа
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);
        $extModel = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $document->id]);
        $typeModel = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
        $files = $typeModel->getAttachedFileList();
        $backUrl = Yii::$app->request->get('backUrl');

        if (!$backUrl) {
            $backUrl = '/edm/documents/foreign-currency-control-index?tabMode=tabFCI';
        }

        $statusReportsData = new DocumentStatusReportsData($document);
        $statusEvent = null;

        if (Document::STATUS_SIGNING_REJECTED == $document->status) {
            $statusEvent = Yii::$app->monitoring->getLastEvent('edm:registerSigningRejected', ['entityId' => $id]);
        }

        // Вывести страницу
        return $this->render(
            'view',
            compact('extModel', 'document', 'files', 'backUrl', 'statusReportsData', 'statusEvent')
        );
    }

    /**
     * Вызов печати документа
     * @param $id
     * @return string
     */
    public function actionPrint($id)
    {
        $this->layout = '/print';
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        $extModel = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $document->id]);
        $typeModel = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
        $statusReportsData = new DocumentStatusReportsData($document);
        $files = $typeModel->getAttachedFileList();

        // Вывести страницу
        return $this->render('print', compact('extModel', 'document', 'files', 'statusReportsData'));
    }

    /**
     * Удаление документа
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        if (!$document->isModifiable()) {
            // Поместить в сессию флаг сообщения об ошибке модификации документа
            Yii::$app->session->setFlash('error', 'Ошибка модификации документа');

            // Перенаправить на страницу индекса
            return $this->redirect($this->fccJournalUrl);
        }

        $model = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $document->id]);

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
        return $this->redirect($this->fccJournalUrl);
    }

    /**
     * Отправка документа
     * @param $id
     * @return Response
     */
    public function actionSend($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        if (!$document->isModifiable()) {
            // Поместить в сессию флаг сообщения об ошибке модификации документа
            Yii::$app->session->setFlash(
                'error',
                Yii::t('edm', 'Modification error. Foreign currency information is already sent')
            );
            // Перенаправить на страницу индекса
            return $this->redirect($this->fccJournalUrl);
        }

        $signResult = $this->signCryptoPro($document);

        if (!$signResult) {
            // Перенаправить на страницу индекса
            return $this->redirect($this->fccJournalUrl);
        }

        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($document, true);

        // Поместить в сессию флаг сообщения об успешной отправке документа
        Yii::$app->session->setFlash('success', 'Документ отправлен');

        // Перенаправить на страницу индекса
        return $this->redirect($this->fccJournalUrl);
    }

    /**
     * Обработка формы добавления операции
     * AJAX
     * @return array
     * @throws MethodNotAllowedHttpException
     */
    public function actionProcessOperationForm()
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = FCCHelper::processDocumentForm(
            $this, new ForeignCurrencyOperationInformationItem(),
            $this->singleChildObjectView, $this->multipleChildObjectsView, $this->childObjectCacheKey
        );

        return $result;
    }

    /**
     * Обработка справки о валютных операциях
     * @param ForeignCurrencyOperationInformationExt $extModel
     * @return string|Response
     */
    private function processForeignCurrencyControl($extModel)
    {
        if (!Yii::$app->request->isPost || $this->validateForm($extModel) === false) {

            $organizationsDb = Yii::$app->terminalAccess->query(DictOrganization::class)->all();
            $organizations = ArrayHelper::map($organizationsDb, 'id', 'name');

            $userAllowedAccounts = EdmPayerAccountUser::getUserAllowAccounts(Yii::$app->user->identity->id);

            $accounts = EdmPayerAccount::findAll([
                'organizationId' => array_keys($organizations),
                'id' => $userAllowedAccounts
            ]);
            $orgList = [];
            $usedNames = [];
            foreach($accounts as $acc) {
                $name = $acc->payerName;
                $orgId = $acc->organizationId;
                if (!$name) {
                    $name = $organizations[$orgId];
                } else if ($name != $organizations[$orgId]) {
                    $orgId .= '_' . $acc->id;
                }
                if (!isset($usedNames[$name])) {
                    $usedNames[$name] = true;
                    $orgList[$orgId] = $name;
                }
            }
            if (!$extModel->organizationId) {
                if (count($orgList) == 1) {
                    $extModel->organizationId = array_keys($organizations)[0];
                }
            }

            // Вывести форму
            return $this->render('_form', [
                'model' => $extModel, 'organizations' => $orgList,
            ]);
        }

        // Создание ISO20022 содержимого
        $auth024Type = ISO20022Helper::createAuth024FromFCOI($extModel);

        /**
         * @todo @fixme временно отключена валидация
         */
        //$auth024Type->validateXSD();

        if ($auth024Type->errors) {
            // Поместить в сессию флаг сообщения об ошибке XSD-валидации
            Yii::$app->session->setFlash('error', Yii::t('app/iso20022', 'Auth.024 validation against XSD-scheme failed'));
            Yii::info('Auth.024 validation against XSD-scheme failed');
            Yii::info($auth024Type->getErrorsSummary());

            // Вывести форму
            return $this->render('_form', ['model' => $extModel]);
        }

        if (empty($extModel->documentId)) {
            // Создание CyberXml-документа
            $context = $this->createCyberXml($extModel, $auth024Type);

            if (!$context) {
                // Поместить в сессию флаг сообщения об ошибке создания документа
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create foreign currency information'));

                // Вывести форму
                return $this->render('_form', ['model' => $extModel]);
            }

            // Получить документ из контекста
            $document = $context['document'];
            // Модификация ext-модели
            $extModel->documentId = $document->id;
        } else {
            // Изменение текущего конверта
            $document = $extModel->document;
            FCCHelper::updateCyberXml($document, $extModel, $auth024Type);
        }

        // Сохранить модель в БД
        $extModel->save();
        DocumentTransportHelper::extractSignData($document);
        WizardCacheHelper::deleteFCCWizardCache();

        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $extModel->documentId]);
    }

    public function actionBeforeSigning($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        if (!$document->isModifiable()) {
            // Поместить в сессию флаг сообщения об ошибке модификации документа
            Yii::$app->session->setFlash(
                'error',
                Yii::t('edm', 'Modification error. Foreign currency information is already sent')
            );

            // Перенаправить на страницу индекса
            return $this->redirect($this->fccJournalUrl);
        }

        if ($document->extModel->extStatus == ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING) {
            if (!$this->signCryptoPro($document)) {
                // Перенаправить на страницу индекса
                return $this->redirect($this->fccJournalUrl);
            }

            DocumentTransportHelper::extractSignData($document);
        }

        return true;
    }

    public function actionRejectSigning()
    {
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');

            $model = Document::findOne($id);
            $businessStatusComment = (string) Yii::$app->request->post('businessStatusComment');

            if (empty($businessStatusComment)) {
                // Поместить в сессию флаг сообщения об необходимости описать причину отказа
                Yii::$app->session->addFlash('warning', Yii::t('edm', 'Please provide reject reason'));

                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $id]);
            }

            $model->status = Document::STATUS_SIGNING_REJECTED;

            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Зарегистрировать событие отказа в подписании реестра в модуле мониторинга
                Yii::$app->monitoring->log(
                    'edm:registerSigningRejected',
                    $model->type,
                    $id,
                    [
                        'userId' => Yii::$app->user->id,
                        'reason' => $businessStatusComment,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );

                // Зарегистрировать событие отмены подписания документа в модуле мониторинга
                Yii::$app->monitoring->extUserLog('RejectSigningDocument', ['documentId' => $id]);
            }

            // Перенаправить на страницу просмотра
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    private function signCryptoPro($document)
    {
        if (!FCCHelper::signCryptoPro($document)) {
            // Поместить в сессию флаг сообщения об ошибке подписания Криптопро
            Yii::$app->session->setFlash('error', Yii::t('document', 'CryptoPro signing error'));
            // Зарегистрировать событие ошибки подписания Криптопро в модуле мониторинга
            Yii::$app->monitoring->log('document:CryptoProSigningError', 'document', $document->id, [
                'terminalId' => $document->terminalId
            ]);
            Yii::error('CryptoPro signing failed');

            return false;
        }

        return true;
    }

    /**
     * Действие после подписания документа
     * @param $id
     * @return Response
     */
    public function actionAfterSigning()
    {
        if (Yii::$app->session->hasFlash('success')) {
            $messageType = 'success';
        } else if (Yii::$app->session->hasFlash('error')) {
            $messageType = 'error';
        } else {
            $messageType = 'info';
        }

        // Поместить в сессию флаг сообщения о результате подписания
        Yii::$app->session->setFlash($messageType, Yii::$app->session->getFlash($messageType));

        // Перенаправить на страницу индекса
        return $this->redirect($this->fccJournalUrl);
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

    /**
     * Валидация формы документа
     * @param ForeignCurrencyOperationInformationExt $extModel
     * @return bool
     */
    private function validateForm($extModel)
    {
        // Загрузить данные модели из формы в браузере
        $extModel->load(Yii::$app->request->post());
        $p = strpos($extModel->organizationId, '_');
        if ($p !== false) {
            $extModel->organizationId = substr($extModel->organizationId, 0, $p);
        }

        // Загрузка операций валютной справки
        $operations = FCCHelper::getChildObjectCache($this->childObjectCacheKey);
        $extModel->loadOperations($operations);

        // Валидация формы
        if (!$extModel->validate()) {
            if ($extModel->hasErrors('operations')) {
                // Если не указаны операции, прерываем обработку формы
                // Поместить в сессию флаг сообщения об отсутствии операций
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Operations for foreign currency information are not specified'));
            } else {
                // Поместить в сессию флаг сообщения об ошибке создания документа
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create foreign currency information'));
                Yii::info(var_export($extModel->getErrors(), true));
            }

            return false;
        }

        $operationsCheckResult = true;

        // Проверка на наличие ошибок в операциях
        foreach($extModel->operations as $operation) {
            if ($operation->hasErrors()) {
                Yii::info(var_export($operation->getErrors(), true));
                $operationsCheckResult = false;
            }
        }

        if (!$operationsCheckResult ) {
            // Поместить в сессию флаг сообщения об ошибке создания документа
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create foreign currency information'));
        }

        return $operationsCheckResult;
    }

    private function createCyberXml(ForeignCurrencyOperationInformationExt $model, Auth024Type $typeModel)
    {
        $organization = $model->organization;
        $account = $model->account;
        $terminal = Terminal::findOne($organization->terminalId);

        $params = [
            'sender' => $terminal->terminalId,
            'receiver' => $account->bank->terminalId,
            'terminal' => $terminal,
            'account' => $account,
            'attachFile' => null
        ];

        return FCCHelper::createCyberXml($typeModel, $params);
    }

    private function fillRelatedData($extModel, Auth024Type $typeModel = null)
    {
        $items = [];

        /**
         * Получить список ForeignCurrencyOperationInformationItem
         * через AR-связь и помещаем их в массив
         */
        foreach($extModel->items as $item) {
            $items[Uuid::generate()] = $item;
        }

        /**
         * Получить вложения из зип-контента тайпмодели.
         * Они сохраняются в temp-директории и в сессии.
         * Добавить вложения для каждого item, полученного из ext-модели
         */
        if ($typeModel) {
            $attachedFiles = $this->getAttachedFileList($typeModel);
            $pos = 0;
            foreach($items as $uuid => $item) {
                $items[$uuid]->attachedFiles = $attachedFiles[$pos++] ?? [];
            }
        }

        FCCHelper::setChildObjectCache($this->childObjectCacheKey, $items);
        $extModel->operations = $items;
    }

    private function getAttachedFileList(Auth024Type $typeModel)
    {
        $attachedFiles = $typeModel->getAttachedFileList();

        $zip = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
        $zipFiles = array_flip($zip->getFileList('cp866'));

        foreach($attachedFiles as $entry => $files) {
            foreach($files as $pos => $file) {
                if (isset($zipFiles[$file['path']])) {
                    $attachedFile = AttachedFileSession::createFromStream(
                        $zip->getStream($zip->getNameFromIndex($zipFiles[$file['path']])), $file['name']
                    );
                    $attachedFiles[$entry][$pos] = $attachedFile;
                }
            }
        }
        // Удалить архив
        $zip->purge();

        return $attachedFiles;
    }

    public function actionDownloadAttachment($id, $item, $pos)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);
        $typeModel = $document->getCyberXml()->getContent()->getTypeModel();
        $attachedFiles = $typeModel->getAttachedFileList();

        try {
            if (!isset($attachedFiles[$item][$pos])) {
                throw new \Exception("File offset $item:$pos not found");
            }

            $zip = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
            $zipFiles = $zip->getFileList('cp866');

            $file = $attachedFiles[$item][$pos];
            if (!in_array($file['path'], $zipFiles)) {
                throw new \Exception('Zip archive does not contain file ' . $file['path']);
            }

            $content = $zip->getFromName($file['path']);

            $zip->purge();

            Yii::$app->response->sendContentAsFile(
                $content, $file['name']
            );
        } catch (\Exception $exception) {
            Yii::warning("Failed to send attachment, caused by: $exception");

            throw new NotFoundHttpException();
        }
    }

    /**
     * Метод ищет модель документа в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id): Document
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);

        return $document;
    }
}