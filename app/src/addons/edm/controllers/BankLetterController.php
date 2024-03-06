<?php

namespace addons\edm\controllers;

use addons\edm\models\BankLetter\AttachedFileSession;
use addons\edm\EdmModule;
use addons\edm\models\BankLetter\BankLetterForm;
use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\BankLetter\BankLetterViewModel;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\ISO20022\models\Auth026Type;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\ControllerCache;
use common\helpers\DocumentHelper;
use common\helpers\UserHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use SimpleXMLElement;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
* Класс контроллера обслуживает базовые запросы к странице с письмами в банк
*/
class BankLetterController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'get-form', 'render-attached-files', 'download-attachment', 'mark-all-as-read'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::BANK_LETTER,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'validate', 'upload-attached-file', 'send', 'update'],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::BANK_LETTER,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'], // permission is checked in action
                    ]
                ],
            ],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = [
            'class' => 'common\actions\documents\DeleteAction',
            'serviceId' => EdmModule::SERVICE_ID,
        ];

        return $actions;
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        // Модель фильтра для поиска в списке писем
        $filterModel = new BankLetterSearch();
        // Поставщик данных для списка писем
        $dataProvider = $filterModel->search(Yii::$app->request->queryParams);
        // Форма создания нового письма в банк
        $newLetterForm = new BankLetterForm(['user' => Yii::$app->user->identity]);
        // Получить закешированные записи из компонента controllerCache
        $cachedEntries = (new ControllerCache('bankLetters'))->get();
        // Получить список помеченных документов из закешированных записей
        $selectedDocumentsIds = array_keys($cachedEntries['entries']);

        // Вывести страницу с данными о письмах в банк
        return $this->render(
            'index',
            compact('newLetterForm', 'dataProvider', 'filterModel', 'selectedDocumentsIds')
        );
    }

    /**
     * Метод обрабатывает просмотр письма в банк
     * @param type $id идентификатор письма
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        // Найти в БД документ с указанным id
        $document = $this->findDocument($id);

        // Если документ не был просмотрен, пометить как просмотренный и сохранить в БД
        if (!$document->viewed) {
            $document->viewed = 1;
            $document->save(false, ['viewed']);
        }
        // Зарегистрировать событие просмотра документа в модуле мониторинга
        Yii::$app->monitoring->log(
            'user:viewDocument',
            'document',
            $id,
            [
                'userId' => Yii::$app->user->id,
                'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
            ]
        );

        try {
            // Создать модель просмотра письма из данных документа
            $letter = BankLetterViewModel::create($document);
        } catch (\Exception $ex) {
            // В случае ошибки записать в лог текст ошибки
            \Yii::info("Letter $id is malformed: " . $ex->getMessage());
            return null;
        }

        // Вернуть контент для модального окна
        return $this->renderPartial(
            '_viewModal',
            compact('document', 'letter')
        );
    }

    /**
     * Метод создаёт письмо в банк
     * @return type
     */
    public function actionCreate()
    {
        // Включить формат вывода JSON
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $model = new BankLetterForm(['user' => Yii::$app->user->identity]);

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $document = null;
            try {
                $document = $model->createDocument();
                Yii::$app->resque->enqueue('common\jobs\ExtractSignDataJob', ['id' => $document->id]);
            } catch (\Exception $exception) {
                Yii::error("Failed to create document, caused by: $exception");
                if ($document !== null) {
                    $document->updateStatus(Document::STATUS_CREATING_ERROR);
                }

                return [];
            }

            // Поместить в сессию id сохранённого документа
            Yii::$app->session->setFlash('savedDocumentId', $document->id);

            // Перенаправить на страницу индекса
            return $this->redirect(Url::to('/edm/bank-letter/index'));
        }

        $validationErrors = [];
        foreach ($model->getErrors() as $attribute => $errors) {
            $validationErrors[Html::getInputId($model, $attribute)] = $errors;
        }

        return ['validationErrors' => $validationErrors];
    }

    public function actionUpdate()
    {
        // Включить формат вывода JSON
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $model = new BankLetterForm(['user' => Yii::$app->user->identity]);

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $document = $this->findDocument($model->documentId);
            try {
                $model->updateDocument($document);
                Yii::$app->resque->enqueue('common\jobs\ExtractSignDataJob', ['id' => $document->id]);
            } catch (\Exception $exception) {
                Yii::error("Failed to update document, caused by: $exception");

                return [];
            }
            // Поместить в сессию флаг id сохранённого документа
            Yii::$app->session->setFlash('savedDocumentId', $document->id);

            // Перенаправить на страницу индекса
            return $this->redirect(Url::to('/edm/bank-letter/index'));
        }

        $validationErrors = [];
        foreach ($model->getErrors() as $attribute => $errors) {
            $validationErrors[Html::getInputId($model, $attribute)] = $errors;
        }

        return ['validationErrors' => $validationErrors];
    }

    public function actionGetForm(int $id)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $document = $this->findDocument($id);
        $form = BankLetterForm::fromDocument(Yii::$app->user->identity, $document);
        return array_merge(
            $form->toArray(),
            ['hasSignatures' => $document->signaturesCount > 0]
        );
    }

    public function actionValidate()
    {
        // Включить формат вывода JSON
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $model = new BankLetterForm(['user' => Yii::$app->user->identity]);
        // Загрузить данные модели из формы в браузере
        $model->load(Yii::$app->request->post());

        return ActiveForm::validate($model);
    }

    public function actionSend($id)
    {
        $document = $this->findDocument($id);
        if ($document->status === Document::STATUS_CREATING && $document->signaturesRequired == $document->signaturesCount) {
            $document->updateStatus(Document::STATUS_ACCEPTED);
            // Обработать документ в модуле аддона
            Yii::$app->getModule('edm')->processDocument($document);
            // Отправить документ на обработку в транспортном уровне
            DocumentTransportHelper::processDocument($document, true);
            DocumentHelper::waitForDocumentsToLeaveStatus([$document->id], Document::STATUS_SERVICE_PROCESSING);
            // Поместить в сессию флаг сообщения об успешной отправке документа
            Yii::$app->session->setFlash('success', Yii::t('document', 'Document was sent'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки документа
            Yii::$app->session->setFlash('error', Yii::t('document', 'Failed to send document'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('/edm/bank-letter/index');
    }

    public function actionDownloadAttachment($id, $fileId)
    {
        $document = $this->findDocument($id);
        if ($document === null) {
            throw new NotFoundHttpException('Document not found');
        }

        $extModel = $document->extModel;
        $storedFile = null;
        $fileName = null;
        $attachments = $extModel->getStoredFileList();
        if (array_key_exists($fileId, $attachments)) {
            $storedFile = Yii::$app->storage->get($fileId);
            $fileName = $attachments[$fileId];
        }

        if ($storedFile === null) {
            throw new NotFoundHttpException('Attachment not found');
        }

        try {
            Yii::$app->response->sendFile($storedFile->getRealPath(), $fileName);
        } catch (\Exception $exception) {
            Yii::warning("Failed to send attachment for document {$document->id}, stored file: $fileName, caused by $exception");

            throw new NotFoundHttpException();
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
        return $this->renderNestedItemsListFromRequest(
            '_attachedFilesGridView',
            AttachedFileSession::class
        );
    }

    public function actionMarkAllAsRead()
    {
        Document::updateAll(['viewed' => 1], ['id' => BankLetterSearch::getUnreadIds()]);
        // Перенаправить на предыдущую страницу
        return $this->redirect(Yii::$app->request->referrer);
    }

    private function renderNestedItemsListFromRequest($view, $itemClass, $params = [])
    {
        $jsonList = Yii::$app->request->post('list', '[]');

        return $this->renderPartial($view, [
            'models' => $itemClass::createListFromJson($jsonList),
            'params' => $params,
        ]);
    }

    private function findDocument($id)
    {
        $searchModel = new BankLetterSearch(['id' => $id]);
        $query = BankLetterSearch::find();
        $searchModel->applyQueryFilters(['id' => $id], $query);

        $document = $query->one();
        if ($document === null) {
            throw new NotFoundHttpException();
        }
        return $document;
    }

}
