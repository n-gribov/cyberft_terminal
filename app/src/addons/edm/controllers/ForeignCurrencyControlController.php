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
use yii\helpers\Url;
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

                return $this->redirect('/edm/foreign-currency-control/create');
            }

            FCCHelper::clearChildObjectCache($this->childObjectCacheKey);

            // Если создание на основе имеющегося документа
            if ($id = Yii::$app->request->get('id')) {
                $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
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
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);

        // если документ уже отправлен, с ним ничего нельзя делать
        if (!$document->isModifiable()) {
            Yii::$app->session->setFlash('error', 'Ошибка модификации документа');

            return $this->redirect([$this->fccJournalUrl]);
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
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
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

        return $this->render('view', compact('extModel', 'document', 'files', 'backUrl', 'statusReportsData', 'statusEvent'));
    }

    /**
     * Вызов печати документа
     * @param $id
     * @return string
     */
    public function actionPrint($id)
    {
        $this->layout = '/print';

        $document = $this->findDocument($id);

        $extModel = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $document->id]);
        $typeModel = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
        $statusReportsData = new DocumentStatusReportsData($document);
        $files = $typeModel->getAttachedFileList();

        return $this->render('print', compact('extModel', 'document', 'files', 'statusReportsData'));
    }

    /**
     * Удаление документа
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);

        if (!$document->isModifiable()) {
            Yii::$app->session->setFlash('error', 'Ошибка модификации документа');

            return $this->redirect([$this->fccJournalUrl]);
        }

        $model = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $document->id]);

        if ($model->delete() && $document->delete()) {
            if ($document->extModel->storedFileId) {
                Yii::$app->storage->remove($document->extModel->storedFileId);
            }
            Yii::$app->session->setFlash('success', Yii::t('document', 'Document deleted'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('document', 'Failed to delete document'));
        }

        return $this->redirect([$this->fccJournalUrl]);
    }

    /**
     * Отправка документа
     * @param $id
     * @return Response
     */
    public function actionSend($id)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);

        if (!$document->isModifiable()) {
            Yii::$app->session->setFlash('error',
                Yii::t('edm', 'Modification error. Foreign currency information is already sent')
            );
            return $this->redirect([$this->fccJournalUrl]);
        }

        $signResult = $this->signCryptoPro($document);

        if (!$signResult) {
            return $this->redirect([$this->fccJournalUrl]);
        }

        DocumentTransportHelper::processDocument($document, true);

        Yii::$app->session->setFlash('success', 'Документ отправлен');

        return $this->redirect(Url::to([$this->fccJournalUrl]));
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

        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = FCCHelper::processDocumentForm(
            $this, new ForeignCurrencyOperationInformationItem(),
            $this->singleChildObjectView, $this->multipleChildObjectsView, $this->childObjectCacheKey
        );

        return $result;
    }

    /**
     * Массовое удаление документов из журнала
     * UPD: Непонятно, зачем, пока комментирую
     * @return Response
     */
//    public function actionDeleteForeignCurrencyInformations()
//    {
//        $cached = $this->foreignCurrencyInformationCache->get();
//        $idList = array_keys($cached['entries']);
//
//        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
//
//        $extList = ISO20022DocumentExt::find()->select('storedFileId')->where(['documentId' => $idList])->asArray()->all();
//        $storedIds = array_values(
//            array_filter(
//                ArrayHelper::getColumn($extList, 'storedFileId')
//            )
//        );
//
//        foreach ($storedIds as $storedId) {
//            Yii::$app->storage->remove($storedId);
//        }
//
//        ISO20022DocumentExt::deleteAll(['documentId' => $idList]);
//        ForeignCurrencyOperationInformationItem::deleteAll(['documentId' => $idList]);
//        ForeignCurrencyOperationInformationExt::deleteAll(['documentId' => $idList]);
//        Document::deleteAll(['id' => $idList]);
//
//        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
//
//        $this->foreignCurrencyInformationCache->clear();
//
//        Yii::$app->session->setFlash('info',
//            Yii::t('edm', 'Deleted {count} foreign currency informations',
//                ['count' => count($idList)]));
//
//        return $this->redirect([$this->fccJournalUrl]);
//    }



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
            Yii::$app->session->setFlash('error', Yii::t('app/iso20022', 'Auth.024 validation against XSD-scheme failed'));
            Yii::info('Auth.024 validation against XSD-scheme failed');
            Yii::info($auth024Type->getErrorsSummary());

            return $this->render('_form', ['model' => $extModel]);
        }

        if (empty($extModel->documentId)) {
            // Создание CyberXml-документа
            $context = $this->createCyberXml($extModel, $auth024Type);

            if (!$context) {
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create foreign currency information'));

                return $this->render('_form', ['model' => $extModel]);
            }

            $document = $context['document'];
            // Модификация ext-модели
            $extModel->documentId = $document->id;
        } else {
            // Изменение текущего конверта
            $document = $extModel->document;
            FCCHelper::updateCyberXml($document, $extModel, $auth024Type);
        }

        $extModel->save();
        DocumentTransportHelper::extractSignData($document);

        WizardCacheHelper::deleteFCCWizardCache();

        return $this->redirect(['view', 'id' => $extModel->documentId]);
    }

    public function actionBeforeSigning($id)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);

        if (!$document->isModifiable()) {
            Yii::$app->session->setFlash('error',
                Yii::t('edm', 'Modification error. Foreign currency information is already sent')
            );

            return $this->redirect([$this->fccJournalUrl]);
        }

        if ($document->extModel->extStatus == ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING) {
            if (!$this->signCryptoPro($document)) {
                return $this->redirect([$this->fccJournalUrl]);
            }

            DocumentTransportHelper::extractSignData($document);
        }

        return true;
    }

    public function actionRejectSigning()
    {
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');

            $model = Document::findOne($id);
            $businessStatusComment = (string) Yii::$app->request->post('businessStatusComment');

            if (empty($businessStatusComment)) {
                Yii::$app->session->addFlash('warning', Yii::t('edm', 'Please provide reject reason'));

                return $this->redirect(['view', 'id' => $id]);
            }

            $model->status = Document::STATUS_SIGNING_REJECTED;

            if ($model->save()) {
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

                // Регистрация события отмены подписания документа
                Yii::$app->monitoring->extUserLog('RejectSigningDocument', ['documentId' => $id]);
            }

            return $this->redirect(['view', 'id' => $id]);
        }

    }

    private function signCryptoPro($document)
    {
        if (!FCCHelper::signCryptoPro($document)) {
            Yii::$app->session->setFlash('error', Yii::t('document', 'CryptoPro signing error'));
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

        Yii::$app->session->setFlash($messageType, Yii::$app->session->getFlash($messageType));

        return $this->redirect([$this->fccJournalUrl]);
    }

    public function actionUploadAttachedFile()
    {
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
        $extModel->load(Yii::$app->request->post());
        if ($p = strpos($extModel->organizationId, '_') !== false) {
            $extModel->organizationId = substr($extModel->organizationId, 0, $p);
        }

        // Загрузка операций валютной справки
        $operations = FCCHelper::getChildObjectCache($this->childObjectCacheKey);
        $extModel->loadOperations($operations);

        // Валидация формы
        if (!$extModel->validate()) {
            if ($extModel->hasErrors('operations')) {
                // Если не указаны операции, прерываем обработку формы
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Operations for foreign currency information are not specified'));
            } else {
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
         * получаем список ForeignCurrencyOperationInformationItem
         * через AR-связь и помещаем их в массив
         */
        foreach($extModel->items as $item) {
            $items[Uuid::generate()] = $item;
        }

        /**
         * получаем аттачменты из зип-контента тайпмодели.
         * они сохраняются в temp-директории и в сессии
         * для каждого item, полученного из ext-модели, добавляем аттачменты
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

        $zip->purge();

        return $attachedFiles;
    }

    public function actionDownloadAttachment($id, $item, $pos)
    {
        $document = $this->findDocument($id);
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

    private function findDocument($id): Document
    {
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);

        return $document;
    }
}