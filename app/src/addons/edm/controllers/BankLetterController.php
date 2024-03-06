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

    public function actionIndex()
    {
        $filterModel = new BankLetterSearch();
        $dataProvider = $filterModel->search(Yii::$app->request->queryParams);
        $newLetterForm = new BankLetterForm(['user' => Yii::$app->user->identity]);

        $cachedEntries = (new ControllerCache('bankLetters'))->get();
        $selectedDocumentsIds = array_keys($cachedEntries['entries']);

        return $this->render(
            'index',
            compact('newLetterForm', 'dataProvider', 'filterModel', 'selectedDocumentsIds')
        );
    }

    public function actionView($id)
    {
        $document = $this->findDocument($id);

        if ($document === null) {
            throw new NotFoundHttpException();
        }

        if (!$document->viewed) {
            $document->viewed = 1;
            $document->save(false, ['viewed']);
        }

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
            $letter = BankLetterViewModel::create($document);
        } catch (\Exception $ex) {
            \Yii::info("Letter $id is malformed:");
            \Yii::info($ex->getMessage());
            return null;
        }

        if ($document->type === Auth026Type::TYPE) {
            $content = (string)CyberXmlDocument::getTypeModel($document->actualStoredFileId);
            $tp = '';
            if (!empty($content)) {
                $xml = new SimpleXMLElement($content);
                $tp = $xml->CcyCtrlReqOrLttr->ReqOrLttr->Tp;
            }
        } else {
            $tp = null;
        }

        return $this->renderPartial(
            '_viewModal',
            compact('document', 'letter', 'tp')
        );
    }

    public function actionCreate()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $model = new BankLetterForm(['user' => Yii::$app->user->identity]);

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

            Yii::$app->session->setFlash('savedDocumentId', $document->id);

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
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $model = new BankLetterForm(['user' => Yii::$app->user->identity]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $document = $this->findDocument($model->documentId);
            try {
                $model->updateDocument($document);
                Yii::$app->resque->enqueue('common\jobs\ExtractSignDataJob', ['id' => $document->id]);
            } catch (\Exception $exception) {
                Yii::error("Failed to update document, caused by: $exception");

                return [];
            }

            Yii::$app->session->setFlash('savedDocumentId', $document->id);

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
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $model = new BankLetterForm(['user' => Yii::$app->user->identity]);
        $model->load(Yii::$app->request->post());

        return ActiveForm::validate($model);
    }

    public function actionSend($id)
    {
        $document = $this->findDocument($id);
        if ($document->status === Document::STATUS_CREATING && $document->signaturesRequired == $document->signaturesCount) {
            $document->updateStatus(Document::STATUS_ACCEPTED);
            Yii::$app->getModule('edm')->processDocument($document);
            DocumentTransportHelper::processDocument($document, true);
            DocumentHelper::waitForDocumentsToLeaveStatus([$document->id], Document::STATUS_SERVICE_PROCESSING);
            Yii::$app->session->setFlash('success', Yii::t('document', 'Document was sent'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('document', 'Failed to send document'));
        }

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
        return $this->redirect(Yii::$app->request->referrer);
    }

    private function renderNestedItemsListFromRequest($view, $itemClass, $params = [])
    {
        $jsonList = Yii::$app->request->post('list', '[]');

        return $this->renderPartial(
            $view,
            [
                'models' => $itemClass::createListFromJson($jsonList),
                'params' => $params,
            ]
        );
    }

    private function findDocument($id)
    {
        $searchModel = new BankLetterSearch(['id' => $id]);
        $query = BankLetterSearch::find();
        $searchModel->applyQueryFilters(['id' => $id], $query);

        return $query->one();
    }

}
