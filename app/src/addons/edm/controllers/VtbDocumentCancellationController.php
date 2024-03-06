<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestForm;
use addons\edm\services\VTBDocumentCancellationService;
use common\base\BaseServiceController;
use common\document\Document;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

class VtbDocumentCancellationController extends BaseServiceController
{
    /**
     * @var VTBDocumentCancellationService
     */
    private $cancellationService;

    public function __construct($id, $module, VTBDocumentCancellationService $cancellationService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->cancellationService = $cancellationService;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['send-prepare-cancellation-request', 'proceed-cancellation'],
                        'roles' => ['documentCreate'],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID],
                    ],
                ],
            ],
        ];
    }

    public function actionSendPrepareCancellationRequest($id)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $document = $this->findDocument($id);
        $form = new VTBPrepareCancellationRequestForm(['document' => $document]);

        // Если данные модели успешно загружены из формы в браузере
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $requestDocument = $this->cancellationService->sendPrepareCancellationRequest($form);
                return [
                    'success' => true,
                    'prepareCancellationRequestDocumentId' => $requestDocument->id,
                ];
            } catch (\Exception $exception) {
                Yii::error($exception);
                $errorMessage = $exception instanceof \DomainException
                    ? $exception->getMessage()
                    : Yii::t('document', 'Failed to send document');
                return [
                    'success' => false,
                    'errorMessage' => $errorMessage,
                ];
            }
        }

        return [
            'success' => false,
            'errorMessage' => $form->getFirstErrorMessage(),
        ];
    }

    public function actionProceedCancellation($prepareCancellationRequestDocumentId)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cancellationStatus = $this->cancellationService->proceedCancellation($prepareCancellationRequestDocumentId);
        if ($cancellationStatus->getStatus() === VTBDocumentCancellationService\CancellationStatus::SIGNATURE_REQUIRED) {
            $cancellationRequestDocument = $cancellationStatus->getCancellationRequestDocument();
            if (!$cancellationRequestDocument->isSignableByUserLevel()) {
                // Поместить в сессию флаг сообщения о создании запроса на отзыв
                Yii::$app->session->setFlash('info', Yii::t('edm', 'Call-off request has been created and is waiting to be signed'));
            }
            // Перенаправить на страницу просмотра
            return $this->redirect([
                '/edm/vtb-documents/view',
                'id' => $cancellationRequestDocument->id,
                'triggerSigning' => 1
            ]);
        }

        return ['status' => $cancellationStatus->getStatus()];
    }

    private function findDocument($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(Document::className(), $id);
    }
}
