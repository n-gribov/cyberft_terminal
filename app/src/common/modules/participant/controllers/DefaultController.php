<?php

namespace common\modules\participant\controllers;

use common\base\Controller as BaseController;
use common\modules\participant\jobs\LoadDirectoryJob;
use common\modules\participant\models\BICDirParticipant;
use common\modules\participant\models\BICDirParticipantSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class DefaultController extends BaseController
{
    public function behaviors()
    {
        return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
				],
			],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new BICDirParticipantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $event = Yii::$app->monitoring->getLastEvent('participant:ParticipantUpdated');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'urlParams' => $this->getSearchParams('BICDirParticipantSearch'),
            'event' => $event
        ]);
    }

    public function actionView($participantBIC)
    {
        return $this->render('view', [
            'model' => $this->findModel($participantBIC),
        ]);
    }

    public function actionUpdate($participantBIC)
    {
        $model = $this->findModel($participantBIC);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'participantBIC' => $participantBIC]);
        } else {
            Yii::info('Failed to save participant, errors: ' . var_export($model->errors, true));
            return $this->render('update', ['model' => $model]);
        }
    }

    protected function findModel($participantBIC)
    {
        if (($model = BICDirParticipant::findOne(['participantBIC' => $participantBIC])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getSearchParams($prefix)
    {
        $urlParams = [$prefix => []];
        $queryParams = Yii::$app->request->queryParams;
        if (isset($queryParams[$prefix])) {
            foreach($queryParams[$prefix] as $param => $value) {
                if (!empty($value)) {
                    $urlParams[$prefix][$param] = $value;
                }
            }
        }

        return $urlParams;
    }

    public function actionSendRequest()
    {
        if (Yii::$app->request->isPost) {
            Yii::$app->resque->enqueue(LoadDirectoryJob::class, ['forceUpdate' => true]);
            Yii::$app->session->setFlash('info', Yii::t('app/participant', 'Update request was sent'));
        }
        return $this->redirect(['index']);
    }
}
