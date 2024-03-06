<?php

namespace backend\controllers;

use backend\models\signing\DocumentsSigningForm;
use common\base\BaseServiceController;
use common\helpers\ControllerCache;
use Yii;
use yii\web\Response;

class DocumentsSigningController extends BaseServiceController
{
    public function beforeAction($action)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    public function actionGetSignedData()
    {
        $documentsIds = Yii::$app->request->post('ids', []);
        $form = new DocumentsSigningForm(['documentsIds' => $documentsIds]);

        list($success, $documentsSignedData) = $form->extractDocumentsSignedData();

        $errorMessageHtml = $success
            ? null
            : $this->renderPartial('_signing-errors', ['errors' => $form->getErrorsList()]);

        return [
            'success'          => $success,
            'signedData'       => $documentsSignedData,
            'errorMessageHtml' => $errorMessageHtml
        ];
    }

    public function actionSaveSignatures()
    {
        $documentsIds = Yii::$app->request->post('ids', []);
        $signatures = Yii::$app->request->post('signatures', []);
        $certificateBody = Yii::$app->request->post('certificateBody');

        $form = new DocumentsSigningForm([
            'documentsIds' => $documentsIds,
            'signatures'   => $signatures,
            'certBody'     => $certificateBody,
        ]);

        list($success, $jobsIds) = $form->saveSignatures();

        if (!$success) {
            $flashMessage = $this->renderPartial('_signing-errors', ['errors' => $form->getErrorsList()]);
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', $flashMessage);
            // Перенаправить на предыдущую страницу
            return $this->redirect(Yii::$app->request->referrer);
        }

        return ['jobsIds' => $jobsIds];
    }

    public function actionGetSigningStatus()
    {
        $jobsIds = Yii::$app->request->post('jobsIds', []);
        $documentsCount = Yii::$app->request->post('documentsCount');
        $entriesSelectionCacheKey = Yii::$app->request->post('entriesSelectionCacheKey');
        $successRedirectUrl = Yii::$app->request->post('successRedirectUrl');

        $pendingJobsIds = array_values(
            array_filter(
                $jobsIds,
                function ($jobId) {
                    return Yii::$app->resque->isActualJob($jobId);
                }
            )
        );

        if (count($pendingJobsIds) > 0) {
            return ['pendingJobsIds' => $pendingJobsIds];
        }

        if ($entriesSelectionCacheKey) {
            $controllerCache = new ControllerCache($entriesSelectionCacheKey);
            $controllerCache->clear();
        }

        $flashMessage = $documentsCount > 1
            ? Yii::t('document', 'Documents were signed')
            : Yii::t('document', 'Document was signed');

        // Поместить в сессию флаг сообщения о результате подписания
        Yii::$app->session->setFlash('success', $flashMessage);

        $redirectUrl = Yii::$app->request->referrer;
        if ($successRedirectUrl) {
            $redirectUrl = $successRedirectUrl;
        }

        // Перенаправить на страницу перенаправления
        return $this->redirect($redirectUrl);
    }
}
