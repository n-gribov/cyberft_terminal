<?php

namespace common\modules\api\controllers;

use addons\edm\models\IBank\IBankDocumentsPack;
use addons\edm\states\out\EdmOutState;
use common\document\Document;
use common\helpers\FileHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\api\ApiModule;
use common\modules\api\models\ApiDocumentExportQueue;
use common\modules\transport\helpers\FormatDetectorCyberXml;
use common\states\StateRunner;
use Yii;
use yii\httpclient\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\ForbiddenHttpException;
use function GuzzleHttp\json_decode;

class V1Controller extends BaseController
{
    public function actionImport()
    {
        $rawBody = Yii::$app->request->rawBody;
        
        $requestArray = json_decode($rawBody, true);
        
        $uuid = $requestArray['uuid'] ?? null;
        $fileName = $requestArray['fileName'] ?? null;
        $body = $requestArray['body'] ?? '';

        if (empty($body) || empty($uuid)) {
            throw new BadRequestHttpException();
        }
        
        // Здесь будет проверка на то, что нет документа или запроса с таким же uuid, иначе вернется ошибка 409.
        $documentExists = Document::find()->where(['uuid' => $uuid])->count();
        if ($documentExists || !ApiModule::reserveDocumentUuid($uuid)) {
            throw new ConflictHttpException('Document with the same uuid already exists');
        }

        $documentBody = base64_decode($body);
        
        $tmpPath = Yii::getAlias('@temp/') . FileHelper::uniqueName();
        if (file_put_contents($tmpPath, $documentBody) === false) {
            throw new Exception('Could not prepare output file');        
        }

        $model = FormatDetectorCyberXml::detect($tmpPath);
        if ($model instanceof CyberXmlDocument) {
            if (!$model->validateXSD()) {
                Yii::info('Got invalid CyberXml document, errors: ' . var_export($model->getErrors(), true));
                return $this->returnUnprocessableEntityError([Yii::t('other', 'Source document validation against XSD failed')]);
            }
            if ($this->getAuthorizedTerminalAddress() !== null) {
                throw new ForbiddenHttpException('Document in CyberXml format cannot be imported when using terminal access token for single terminal');
            }
            if ($model->docId != $uuid) {
                return $this->returnUnprocessableEntityError([Yii::t('document', 'Uuid does not match DocId from document header')]);
            }
        }

        if ($model instanceof IBankDocumentsPack && $this->getAuthorizedTerminalAddress() !== null) {
            throw new ForbiddenHttpException('Document in iBank format cannot be imported when using terminal access token for single terminal');
        }

        $isImported = StateRunner::run(
            new EdmOutState([
                'filePath'         => $tmpPath,
                'apiUuid'          => $uuid,
                'apiFileName'      => $fileName,
                'origin'           => Document::ORIGIN_API,
                'authorizedSender' => $this->getAuthorizedTerminalAddress(),
            ])
        );

        if (!$isImported) {
            return $this->returnUnprocessableEntityError(ApiModule::retrieveApiImportErrors($uuid));
        }
        
        Yii::$app->response->statusCode = 204;
    }
    
    public function actionExport($limit = 10)
    {
        $autoConfirm = Yii::$app->request->get('autoConfirm');

        $recordsQuery = ApiDocumentExportQueue::find()
            ->orderBy(['id' => SORT_ASC])
            ->limit($limit);
        if ($this->getAuthorizedTerminalAddress()) {
            $recordsQuery->andWhere(['terminalAddress' => $this->getAuthorizedTerminalAddress()]);
        }

        $result['documents'] = [];
        
        foreach ($recordsQuery->all() as $r) {
            $content = file_get_contents($r->path);
            $body = base64_encode($content);
            
            $result['documents'][] = [
                'uuid' => $r->uuid,
                'body' => $body
            ];
            
            if ($autoConfirm === 'true') {
                $r->deleteWithFile();
            }
        }
        
        return $result;
    }
    
    public function actionConfirmExport()
    {
        $rawBody = Yii::$app->request->rawBody;
        
        $uuidList = json_decode($rawBody, true);
        
        foreach ($uuidList['uuid'] as $uuid) {
            $recordQuery = ApiDocumentExportQueue::find()->where(['uuid' => $uuid]);
            if ($this->getAuthorizedTerminalAddress()) {
                $recordQuery->andWhere(['terminalAddress' => $this->getAuthorizedTerminalAddress()]);
            }
            $record = $recordQuery->one();

            if ($record) {
                $record->deleteWithFile();
            }
        }
        
        Yii::$app->response->statusCode = 204;
    }

    private function returnUnprocessableEntityError(?array $errors)
    {
        Yii::info('Returning 422 Unprocessable Entity, errors: ' . var_export($errors, true));
        Yii::$app->response->statusCode = 422;
        return compact('errors');
    }
}