<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\models\CurrencyPayment\CurrencyPaymentDocumentSearch;
use addons\edm\models\CurrencyPayment\CurrencyPaymentSearch;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\ISO20022\models\Pain001Type;
use common\base\BaseServiceController;
use common\base\traits\AuthorizesDocumentPermission;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\ControllerCache;
use common\models\cyberxml\CyberXmlDocument;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
* Класс контроллера обслуживает действия с валютными платежами
*/

class CurrencyPaymentController extends BaseServiceController
{
    use AuthorizesDocumentPermission;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_PAYMENT,
                        ],
                    ],
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
     * Метод выводит индексную страницу
     * @return type
     */
    public function actionRegisterIndex()
    {
        // Очистить кеш документов
        $this->cleanDocumentsSelectionCaches();
        // Модель фильтрации поиска в списке документов
        $filterModel = new CurrencyPaymentDocumentSearch();
        // Поставщик данных для списка документов
        $dataProvider = $filterModel->search(Yii::$app->request->queryParams);
        // Закешированные документы
        $cachedEntries = (new ControllerCache('currencyRegisters'))->get();
        // Отмеченные документы в кешированных
        $selectedDocumentsIds = array_keys($cachedEntries['entries']);
        // Вывести страницу со списком документов
        return $this->render('register-index', compact('dataProvider', 'filterModel', 'selectedDocumentsIds'));
    }

    public function actionPaymentIndex()
    {
        $this->cleanDocumentsSelectionCaches();

        $filterModel = new CurrencyPaymentSearch();
        $dataProvider = $filterModel->search(Yii::$app->request->queryParams);
        $cachedEntries = (new ControllerCache('currencyPayments'))->get();
        $selectedDocumentsIds = array_keys($cachedEntries['entries']);

        // Вывести страницу
        return $this->render('payment-index', compact('dataProvider', 'filterModel', 'selectedDocumentsIds'));
    }

    public function actionViewRegister($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        /** @var Document $document */
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);

        $paymentsDataProvider = new ArrayDataProvider([
            'allModels' => $this->findPaymentsFromRegister($id),
            'modelClass' => CurrencyPaymentSearch::class,
            'pagination' => false,
        ]);

        $signingRejectionEvent = $document->status === Document::STATUS_SIGNING_REJECTED
            ? Yii::$app->monitoring->getLastEvent('edm:registerSigningRejected', ['entity' => $document->type])
            : null;

        // Вывести страницу
        return $this->render('view-register', compact('paymentsDataProvider', 'document', 'signingRejectionEvent'));
    }

    public function actionViewPayment($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        /** @var Document $document */
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);

        if ($document->type === VTBPayDocCurType::TYPE) {
            // Перенаправить на страницу просмотра
            $this->redirect(['/edm/vtb-documents/view', 'id' => $id]);
        }

        $swiftTypeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        $fcp = ForeignCurrencyOperationFactory::constructFCPFromSwift($swiftTypeModel);
        
        $extModel = $document->extModel;
        $businessStatus = $extModel->businessStatus;
        $businessStatusDescription = $extModel->businessStatusDescription;

        return $this->renderAjax('view-payment', [
            'model' => $fcp, 
            'document' => $document, 
            'businessStatus' => $businessStatus,
            'businessStatusDescription' => $businessStatusDescription
        ]);
    }

    public function actionViewRegisterPayment($id, $paymentId)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        /** @var Document $document */
        $document = Yii::$app->terminalAccess->findModel(Document::class, $id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);

        $paymentIndex = null;
        $payments = $this->findPaymentsFromRegister($id);
        foreach (array_values($payments) as $i => $payment) {
            if ($payment->extId == $paymentId) {
                $paymentIndex = $i;
                break;
            }
        }

        if ($paymentIndex === null) {
            throw new NotFoundHttpException("Cannot find payment wint id $paymentId in payment register $id");
        }

        if ($document->type === VTBRegisterCurType::TYPE) {
            // Перенаправить на страницу просмотра
            return $this->redirect(['/edm/vtb-documents/view-register-cur-pay-doc', 'id' => $id, 'paymentIndex' => $paymentIndex]);
        }

        /** @var Pain001Type $typeModel */
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        $paymentsTypeModels = ForeignCurrencyOperationFactory::constructForeignCurrencyPaymentsFromPain001($typeModel);
        $paymentTypeModel = $paymentsTypeModels[$paymentIndex] ?? null;
        
        if ($paymentTypeModel === null) {
            throw new NotFoundHttpException("Cannot find payment wint index $paymentIndex in payment register $id");
        }
        
        $operation = \addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt::findOne($paymentId);
        $businessStatus = $operation->businessStatus;
        $businessStatusDescription = $operation->businessStatusDescription;

        return $this->renderAjax('view-payment', [
            'model' => $paymentTypeModel, 
            'document' => $document, 
            'businessStatus' => $businessStatus,
            'businessStatusDescription' => $businessStatusDescription
        ]);
    }

    /**
     * @param $documentId
     * @return CurrencyPaymentSearch[]
     */
    private function findPaymentsFromRegister($documentId): array
    {
        $filterModel = new CurrencyPaymentSearch();
        $query = $filterModel->buildQuery([
            'CurrencyPaymentSearch' => [
                'id' => $documentId,
                'showDeleted' => true,
            ]
        ]);
        $query->orderBy('ext.id');
        return $query->all();
    }

    private function cleanDocumentsSelectionCaches(): void
    {
        $referrersToKeepCache = [
            '/edm/currency-payment/',
            '/edm/vtb-documents/',
            '/edm/documents/signing-index',
        ];
        foreach ($referrersToKeepCache as $route) {
            if (strpos(Yii::$app->request->referrer, $route) !== false) {
                return;
            }
        }
        (new ControllerCache('currencyPayments'))->clear();
        (new ControllerCache('currencyRegisters'))->clear();
    }
}
