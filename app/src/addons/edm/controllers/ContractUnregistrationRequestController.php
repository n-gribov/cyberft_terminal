<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm;
use addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm\AttachedFileSession;
use addons\edm\models\DictCurrency;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\edm\models\VTBContractUnReg\VTBContractUnRegType;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\helpers\DocumentTransportHelper;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class ContractUnregistrationRequestController extends BaseServiceController
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
                            'render-contracts',
                            'render-attached-files',
                        ],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
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
                            'documentTypeGroup' => EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
                        ],
                    ],
                ],
            ]
        ];
    }

    public function actionCreate()
    {
        $model = new ContractUnregistrationRequestForm([
            'user' => Yii::$app->user->identity,
            'documentDate' => (new DateTime())->format('d.m.Y'),
            'documentNumber' => VTBContractRequestExt::find()->where(['type' => VTBContractRequestExt::REQUEST_TYPE_UNREGISTERING])->max('number') + 1,
        ]);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $document = $model->createDocument();
                return $this->redirect(['view', 'id' => $document->id]);
            } catch (\Exception $exception) {
                Yii::warning("Failed to create document, caused by: $exception");
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to create document'));
            }
        }

        return $this->render(
            'create',
            compact('model')
        );
    }

    public function actionView($id)
    {
        $document = static::findDocument($id);
        if ($document === null) {
            throw new NotFoundHttpException("Document $id is not found or is not available to current user");
        }

        $model = ContractUnregistrationRequestForm::createFromDocument($document, Yii::$app->user->identity);

        return $this->render(
            'view',
            compact('model', 'document')
        );
    }

    public function actionPrint($id)
    {
        $this->layout = '/print';

        $document = static::findDocument($id);
        if ($document === null) {
            throw new NotFoundHttpException("Document $id is not found or is not available to current user");
        }

        /** @var VTBContractUnRegType $typeModel */
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        $model = ContractUnregistrationRequestForm::createFromDocument($document, Yii::$app->user->identity);

        return $this->render(
            'print',
            compact('model', 'typeModel')
        );
    }

    public function actionUpdate($id)
    {
        $document = static::findDocument($id);
        if ($document === null) {
            throw new NotFoundHttpException("Document $id is not found or is not available to current user");
        }
        if (!in_array($document->status, [Document::STATUS_CREATING, Document::STATUS_FORSIGNING])) {
            throw new NotFoundHttpException("Document $id is not editable");
        }

        $model = ContractUnregistrationRequestForm::createFromDocument($document, Yii::$app->user->identity, true);
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->updateDocument($document);
                Yii::$app->session->setFlash('success', Yii::t('document', 'Document is saved'));
                return $this->redirect(['view', 'id' => $document->id]);
            } catch (\Exception $exception) {
                Yii::warning("Failed to update document $id, caused by: $exception");
                Yii::$app->session->setFlash('error', Yii::t('document', 'Failed to save document'));
            }
        }

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
        return $this->redirect(['view', 'id' => $document->id]);
    }

    public function actionUploadAttachedFile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $uploadedFile = UploadedFile::getInstanceByName('file');
        $attachedFile = AttachedFileSession::createFromUploadedFile($uploadedFile);

        return $attachedFile->getAttributes(['id', 'name']);
    }

    public function actionRenderContracts()
    {
        return $this->renderNestedItemsListFromRequest(
            'form/grid-views/_contractsGridView',
            ContractUnregistrationRequestForm\Contract::class
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
     * @param integer $id
     * @return Document|null
     */
    protected function findDocument($id)
    {
        return Yii::$app->terminalAccess->findModel(Document::className(), $id);
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
