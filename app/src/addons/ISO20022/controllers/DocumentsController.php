<?php
namespace addons\ISO20022\controllers;

use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022Search;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\UserHelper;
use common\helpers\ZipHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\participant\helpers\ParticipantHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DocumentsController extends BaseServiceController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => ISO20022Module::SERVICE_ID],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = [
            'class' => 'common\actions\documents\DeleteAction',
            'serviceId' => ISO20022Module::SERVICE_ID,
        ];
        return $actions;
    }

    public function actionList($type, $page, $q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $searchModel = new ISO20022Search();


    	switch ($page)
        {
            case 'index':
                $dataProvider = $searchModel->search([]);
                break;
            case 'freeFormat':
            	$dataProvider = $searchModel->searchFreeFormat([]);
            	break;
            case 'payments':
            	$dataProvider = $searchModel->searchPayments([]);
            	break;
            case 'statements':
            	$dataProvider = $searchModel->searchStatements([]);
            	break;
            case 'foreign-currency-control':
            	$dataProvider = $searchModel->searchForeignCurrencyControl([]);
            	break;

        }

        switch ($type)
        {
            case 'sender':
                $out['results'] = ParticipantHelper::getSenderListForDocumentSearch($dataProvider, $q);
                break;
            case 'receiver':
                $out['results'] = ParticipantHelper::getReceiverListForDocumentSearch($dataProvider, $q);
                break;

    	}

        return $out;
    }

    public function actionIndex()
    {
        $searchModel = new ISO20022Search();
        $model = new Document();
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $settingsTypeCodes = $this->module->settings->typeCodes;

        return $this->render('index',
            [
                'model'        => $model,
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'urlParams'    => $this->getSearchUrl('ISO20022Search'),
                'filterStatus' => !empty(Yii::$app->request->queryParams),
                'listType' => 'isoIndex',
                'settingsTypeCodes' => $settingsTypeCodes,
            ]);
    }

    public function actionForeignCurrencyControl()
    {
        $searchModel = new ISO20022Search();
        $model = new Document();
        $dataProvider = $searchModel->searchForeignCurrencyControl(Yii::$app->request->queryParams);

        $settingsTypeCodes = $this->module->settings->typeCodes;

        return $this->render('foreignCurrencyControl',
            [
                'model'        => $model,
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'urlParams'    => $this->getSearchUrl('ISO20022Search'),
                'filterStatus' => !empty(Yii::$app->request->queryParams),
                'listType' => 'isoIndex',
                'settingsTypeCodes' => $settingsTypeCodes,
            ]);
    }

    /**
     * Журнал документов свободного формата - auth.026, auth.024
     */
    public function actionFreeFormat()
    {
        $searchModel = new ISO20022Search();
        $model = new Document();

        $settingsTypeCodes = $this->module->settings->typeCodes;

        return $this->render('freeFormat', [
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $searchModel->searchFreeFormat(Yii::$app->request->queryParams),
            'urlParams'    => $this->getSearchUrl('ISO20022Search'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'settingsTypeCodes' => $settingsTypeCodes,
            'listType' => 'isoFree',
        ]);
    }

    /**
     * Журнал выписок - camt.053, camt.054
     */
    public function actionStatements()
    {
        $searchModel = new ISO20022Search();
        $model = new Document();

        return $this->render('statements', [
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $searchModel->searchStatements(Yii::$app->request->queryParams),
            'urlParams'    => $this->getSearchUrl('ISO20022Search'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'isoStatements',
        ]);
    }

    /*
     * Журнал платежных документов - pain.001
     */
    public function actionPayments()
    {
        $searchModel = new ISO20022Search();
        $model = new Document();
    	$dataProvider = $searchModel->searchPayments(Yii::$app->request->queryParams);

        return $this->render('payments', [
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'urlParams'    => $this->getSearchUrl('ISO20022Search'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'isoPayments',
        ]);
    }

    public function actionView($id, $mode = '')
    {
        $document = $this->findModel($id);

        $referencingDataProvider = new ActiveDataProvider([
            'query' => $document->findReferencingDocuments(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // Регистрация события просмотра документа
        // только если это новый просмотр (т.е. не переход по вкладкам)

        if (empty($mode)) {
            $previousUrl = Url::previous();
            $currentUrl = Url::current();

            if (empty($previousUrl) || $previousUrl !== $currentUrl) {
                Url::remember();
            }

            if ($previousUrl !== $currentUrl) {

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
            }
        }

        // Получаем подписи документа
        $extModel = $document->extModel;
        $showSignaturesMask = is_null($extModel) ? 0 : Document::SIGNATURES_ALL;

        return $this->render('view', [
            'model' => $document,
            'mode' => $mode,
            'urlParams' => $this->getSearchUrl('ISO20022Search'),
            'referencingDataProvider' => $referencingDataProvider,
            'showSignaturesMask' => $showSignaturesMask
        ]);
    }

    public function actionDownloadAttachment($id)
    {
        $document = $this->findModel($id);

        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);

        $attachments = [];
        if ($typeModel->useZipContent) {
            $zipArchive = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
            $files = $zipArchive->getFileList();
            foreach ($files as $index => $file) {
                if (substr($file, 0, 7) == 'attach_') {
                    $fileName = substr($file, 7);
                    $attachments[] = [$fileName, $zipArchive->getFromIndex($index)];
                }
            }
        } else if ($typeModel instanceof Auth026Type && !empty($typeModel->embeddedAttachments)) {
            $filesNames = ArrayHelper::getColumn($typeModel->getAttachedFileList(), 'name');
            $attachments = array_map(null, $filesNames, $typeModel->embeddedAttachments);
        }

        $downloadContent = null;
        $downloadName = null;
        if (count($attachments) == 1) {
            $downloadContent = $attachments[0][1];
            $downloadName = mb_convert_encoding($attachments[0][0], 'utf8', 'cp866');
        } else if (count($attachments) > 1) {
            $zip = ZipHelper::createTempArchiveFileZip();
            foreach ($attachments as $fileData) {
                list($fileName, $downloadContent) = $fileData;
                $zip->addFromString($downloadContent, $fileName);
            }
            $downloadContent = $zip->asString();
            $zip->purge();
            $downloadName = $typeModel->zipFilename;
        } else {
            return $this->redirect(['view', 'id' => $id]);
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->sendContentAsFile(
            $downloadContent,
            $downloadName,
            ['mimeType' => 'application/octet-stream']
        );
    }

    public function actionDownloadAttachmentByNumber($id, $pos = 0)
    {
        $document = $this->findModel($id);

        /** @var Auth026Type $typeModel */
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        $attachedFiles = $typeModel->getAttachedFileList();

        try {
            if (!isset($attachedFiles[$pos])) {
                throw new \Exception("File offset $pos not found");
            }

            $file = $attachedFiles[$pos];

            if ($typeModel->useZipContent) {
                $zip = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
                $zipFiles = $zip->getFileList('cp866');

                $fileIndex = array_search($file['path'], $zipFiles);
                if ($fileIndex === false) {
                    throw new \Exception('Zip archive does not contain file ' . $file['path']);
                }

                $content = $zip->getFromIndex($fileIndex);
                $zip->purge();
            } else {
                $content = $typeModel->embeddedAttachments[$pos];
            }

            Yii::$app->response->sendContentAsFile($content, $file['name']);
        } catch (\Exception $exception) {
            Yii::warning("Failed to send attachment, caused by: $exception");

            throw new NotFoundHttpException();
        }
    }

    /**
     * @param int $id
     * @return Document
     */
    protected function findModel($id)
    {
        return Yii::$app->terminalAccess->findModel(ISO20022Search::className(), $id);
    }

}