<?php
namespace backend\controllers;

use addons\finzip\FinZipModule;
use addons\finzip\models\FinZipDocumentExt;
use common\base\Controller;
use common\components\storage\StoredFile;
use yii\helpers\Url;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use Yii;

class StorageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        // Accessible for authorized users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Download file from storage
     *
     * @param string $id File ID
     * @param string|null $name File name
     * @throws BadRequestHttpException
     */
    public function actionDownload($id, $name = null)
    {
        if (is_null($id)) {
            throw new BadRequestHttpException(Yii::t('app', "Can't send file"));
        }

        // Get file from storage and check it
        $storedFile = Yii::$app->storage->get($id);

        if (!$this->checkFile($storedFile)) {
            throw new NotFoundHttpException(Yii::t('app', "Can't send file"));
        }

        // Send file
        $finfo = new \finfo(FILEINFO_MIME);
        $fileName = $this->checkFileName($name, $storedFile);
        $data = $storedFile->data;

        if ($storedFile->isEncrypted) {
            if ($storedFile->serviceId == FinZipModule::SERVICE_ID) {
                $extModel = FinZipDocumentExt::findOne(['zipStoredFileId' => $id]);

                if ($extModel) {
                    $document = $extModel->document;
                    Yii::$app->exchange->setCurrentTerminalId($document->originTerminalId);

                    try {
                        if ($extModel && $document) {
                            $data = Yii::$app->storage->decryptStoredFile($storedFile->id);
                        }
                    } catch(\Exception $e) {}
                }
            }
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->sendContentAsFile(
            $data, $fileName, ['mimeType' => $finfo->file($storedFile->getRealPath())]
        );

        return;
    }

    /**
     * Get file status
     *
     * @param string|null $id File Id
     * @throws BadRequestHttpException
     */
    public function actionStatus($id)
    {
        if (is_null($id)) {
            throw new BadRequestHttpException(Yii::t('app', "Can't send file"));
        }

        /**
         * Get file from storage and check
         */
        $file = \Yii::$app->storage->get($id);
        if (is_null($file)) {
            throw new BadRequestHttpException(Yii::t('app', "Can't send file"));
        }

        $errorStatus = true;
        $message = \Yii::t('app', 'File processing error');

        if ($file->status === StoredFile::STATUS_READY) {
            // Перенаправить на страницу скачивания файла
            return $this->redirect(Url::to(['download', 'id' => $id]));
        } else if ($file->status === StoredFile::STATUS_PROCESSING) {
            $message	 = Yii::t('app', 'The document is creating. Do not close the page until the document is loaded.');
            $errorStatus = false;
        }

        $this->layout = 'blank';

        return $this->render(
            'status',
            [
                'message' => $message,
                'status' => $errorStatus,
            ]
        );
    }

    /**
     * Check store file instance
     *
     * @param StoredFile|null $storedFile Stored file instance
     * @return boolean
     */
    private function checkFile($storedFile)
    {
        if (is_null($storedFile)) {
            \Yii::warning('Empty StoredFile instance. Maybe wrong ID?');
            return false;
        }

        if ($storedFile->status !== StoredFile::STATUS_READY) {
            \Yii::warning('Wrong file status');
            return false;
        }

        $path = $storedFile->getRealPath();
        if (!is_file($path) || !is_readable($path)) {
            \Yii::warning('File system problem');
            return false;
        }

        return true;
    }

    /**
     * Check download file name
     *
     * @param StoredFile $storedFile StoredFile instance
     * @return string
     */
    private function checkFileName($name, $storedFile)
    {
        if (!is_null($name)) {
            return $name;
        }

        if (isset($storedFile->contextValue['fileName'])) {
            return $storedFile->contextValue['fileName'];
        }

        return (!empty($storedFile->originalFilename) ? $storedFile->originalFilename : 'File' . $storedFile->id . '.xml');
    }
}
