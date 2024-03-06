<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\models\DictCurrency;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\AttachedFileSession;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\edm\models\VTBCredReg\VTBCredRegType;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\modules\transport\models\StatusReportType;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class LoanAgreementRegistrationRequestController extends BaseServiceController
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'view',
                            'print',
                            'render-non-residents',
                            'render-payment-schedule',
                            'render-receipts',
                            'render-tranches',
                            'render-attached-files',
                        ],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'create',
                            'update',
                            'upload-attached-file',
                            'send',
                        ],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                        ],
                    ],
                ],
            ]
        ];
    }

    public function actionCreate()
    {
        $model = new LoanAgreementRegistrationRequestForm([
            'user' => Yii::$app->user->identity,
            'documentDate' => (new DateTime())->format('d.m.Y'),
            'documentNumber' => VTBContractRequestExt::find()->where(['type' => VTBContractRequestExt::REQUEST_TYPE_REGISTRATION])->max('number') + 1,
        ]);

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $document = $model->createDocument();
                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $document->id]);
            } catch (\Exception $exception) {
                Yii::warning("Failed to create document, caused by: $exception");
                // Поместить в сессию флаг сообщения об ошибке создания документа
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create document'));
            }
        }

        // Вывести страницу
        return $this->render(
            'create',
            [
                'model' => $model,
                'currencies' => DictCurrency::find()->all(),
            ]
        );
    }

    public function actionView($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        $model = LoanAgreementRegistrationRequestForm::createFromDocument($document, Yii::$app->user->identity);

        // Вывести страницу
        return $this->render(
            'view',
            compact('model', 'document')
        );
    }

    public function actionPrint($id)
    {
        $this->layout = '/print';

        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        /** @var VTBCredRegType $typeModel */
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        $model = LoanAgreementRegistrationRequestForm::createFromDocument($document, Yii::$app->user->identity);

        $statusReportDocuments = Document::findAll(['uuidReference' => $document->uuid, 'type' => StatusReportType::TYPE]);
        list($receiveDate, $acceptDate, $rejectDate, $rejectReason) = [null, null, null, null];
        foreach ($statusReportDocuments as $statusReportDocument) {
            /** @var StatusReportType $reportTypeModel */
            $reportTypeModel = CyberXmlDocument::getTypeModel($statusReportDocument->actualStoredFileId);

            switch ($reportTypeModel->statusCode) {
                case 'ACCP':
                    $receiveDate = new DateTime($statusReportDocument->dateCreate);
                    break;
                case 'ACSC':
                    $acceptDate = new DateTime($statusReportDocument->dateCreate);
                    break;
                case 'RJCT':
                    $rejectDate = new DateTime($statusReportDocument->dateCreate);
                    $rejectReason = $reportTypeModel->errorDescription;
                    break;
            }
        }

        // Дата представления может быть неизвестна, т.к. мы можем пропустить статус ACCP
        $receiveDate = $receiveDate ?? $acceptDate ?? $rejectDate;

        // Вывести страницу
        return $this->render(
            'print',
            compact('model', 'document', 'typeModel', 'receiveDate', 'acceptDate', 'rejectDate', 'rejectReason')
        );
    }

    public function actionUpdate($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        if (!in_array($document->status, [Document::STATUS_CREATING, Document::STATUS_FORSIGNING])) {
            throw new NotFoundHttpException("Document $id is not editable");
        }

        $model = LoanAgreementRegistrationRequestForm::createFromDocument($document, Yii::$app->user->identity, true);
        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->updateDocument($document);
                // Поместить в сессию флаг сообщения об успешном сохранении документа
                Yii::$app->session->setFlash('success', Yii::t('document', 'Document is saved'));
                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $document->id]);
            } catch (\Exception $exception) {
                // Поместить в сессию флаг сообщения об ошибке сохранения документа
                Yii::warning("Failed to update document $id, caused by: $exception");
                Yii::$app->session->setFlash('error', Yii::t('document', 'Failed to save document'));
            }
        }

        // Вывести страницу
        return $this->render(
            'update',
            [
                'model' => $model,
                'currencies' => DictCurrency::find()->all(),
            ]
        );
    }

    public function actionSend($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);
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
        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $document->id]);
    }

    public function actionUploadAttachedFile()
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $uploadedFile = UploadedFile::getInstanceByName('file');
        $attachedFile = AttachedFileSession::createFromUploadedFile($uploadedFile);

        return $attachedFile->getAttributes(['id', 'name']);
    }

    public function actionRenderNonResidents()
    {
        return $this->renderNestedItemsListFromRequest(
            'form/grid-views/_nonResidentsGridView',
            LoanAgreementRegistrationRequestForm\NonResident::class
        );
    }

    public function actionRenderPaymentSchedule()
    {
        return $this->renderNestedItemsListFromRequest(
            'form/grid-views/_paymentScheduleGridView',
            LoanAgreementRegistrationRequestForm\PaymentScheduleItem::class
        );
    }

    public function actionRenderReceipts()
    {
        return $this->renderNestedItemsListFromRequest(
            'form/grid-views/_receiptsGridView',
            LoanAgreementRegistrationRequestForm\Receipt::class
        );
    }

    public function actionRenderTranches()
    {
        return $this->renderNestedItemsListFromRequest(
            'form/grid-views/_tranchesGridView',
            LoanAgreementRegistrationRequestForm\Tranche::class,
            ['currencyName' => Yii::$app->request->post('currencyName')]
        );
    }

    public function actionRenderAttachedFiles()
    {
        return $this->renderNestedItemsListFromRequest(
            'form/grid-views/_attachedFilesGridView',
            AttachedFileSession::class
        );
    }

    /**
     * Метод ищет модель документа в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $model = Yii::$app->terminalAccess->findModel(Document::className(), $id);
 
        return $model;
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

}
