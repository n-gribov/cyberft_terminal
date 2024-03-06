<?php

namespace addons\finzip\controllers;

use addons\finzip\FinZipModule;
use addons\finzip\models\FinZipSearch;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\ControllerCache;
use common\modules\participant\helpers\ParticipantHelper;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use function GuzzleHttp\json_encode;

class DocumentsController extends BaseServiceController
{
    private $cache;

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['signing-index'],
                        'roles' => [DocumentPermission::SIGN],
                        'roleParams' => ['serviceId' => FinZipModule::SERVICE_ID],
                    ],
					[
						'allow' => true,
						'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => FinZipModule::SERVICE_ID],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
                    'select-entries' => ['post']
				],
			],
		];
	}

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->cache = new ControllerCache('FinZip');
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = [
            'class' => 'common\actions\documents\DeleteAction',
            'serviceId' => FinZipModule::SERVICE_ID,
        ];
        return $actions;
    }
    
    public function actionList($type, $page, $q = null)
    {
	Yii::$app->response->format = Response::FORMAT_JSON;
        $searchModel = new FinZipSearch();
	
	switch ($page) 
	{
		case 'index':
			$dataProvider = $searchModel->search([]);
			break;
		case 'signing-index':
			$dataProvider = $searchModel->searchForSigning([]);
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
        $searchModel = new FinZipSearch();
        $model = new Document();
        Url::remember();

        return $this->render('@addons/finzip/views/default/index', [
            'searchModel' => $searchModel,
            'model' => $model,
            'urlParams' => $this->getSearchUrl('FinZipSearch'),
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'finzipIndex',
        ]);
    }

	public function actionSigningIndex()
	{
		$searchModel	 = new FinZipSearch();
		$dataProvider	 = $searchModel->searchForSigning(Yii::$app->request->queryParams);

		Url::remember(Url::to());
        $cachedEntries = $this->cache->get();

		return $this->render('forSigning', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cachedEntries' => $cachedEntries,
            'urlParams'    => $this->getSearchUrl('FinZipSearch'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'finZipSigning',
            'controllerCacheKey' => $this->cache->getKey(),
		]);
	}

    public function actionSelectEntries()
    {
        $cachedEntries = $this->cache->get();

        $entries = Yii::$app->request->post('entries', []);

        foreach ($entries as $entry) {
            $id = $entry['id'];
            if ($entry['checked'] === 'true') {
                $cachedEntries['entries'][$id] = true;
            } else {
                unset($cachedEntries['entries'][$id]);
            }
        }

        $this->cache->set($cachedEntries);

        return json_encode(array_keys($cachedEntries['entries']));
    }

    public function actionGetSelectedEntriesIds()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cachedEntries = $this->cache->get();
        return array_keys($cachedEntries['entries']);
    }
}
