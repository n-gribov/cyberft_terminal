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

        // Вывести страницу
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'urlParams' => $this->getSearchParams('BICDirParticipantSearch'),
            'event' => $event
        ]);
    }

    public function actionView($participantBIC)
    {
        // Вывести страницу
        return $this->render('view', [
            // Получить из БД участника с указанным БИК
            'model' => $this->findModel($participantBIC),
        ]);
    }

    public function actionUpdate($participantBIC)
    {
        // Получить из БД участника с указанным БИК
        $model = $this->findModel($participantBIC);

        // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Перенаправить на страницу просмотра
            return $this->redirect(['view', 'participantBIC' => $participantBIC]);
        } else {
            Yii::info('Failed to save participant, errors: ' . var_export($model->errors, true));
            // Вывести страницу
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * Метод формирует список поисковых параметров по префиксу
     * @param type $prefix
     * @return type
     */
    protected function getSearchParams($prefix)
    {
        $urlParams = [$prefix => []];
        // Список всех параметров запроса
        $queryParams = Yii::$app->request->queryParams;
        if (isset($queryParams[$prefix])) {
            // Если в списке есть искомый префикс
            foreach($queryParams[$prefix] as $param => $value) {
                // Все непустые параметры в этом префиксе сохранить в выходной список
                if (!empty($value)) {
                    $urlParams[$prefix][$param] = $value;
                }
            }
        }

        return $urlParams;
    }

    public function actionSendRequest()
    {
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            Yii::$app->resque->enqueue(LoadDirectoryJob::class, ['forceUpdate' => true]);
            // Поместить в сессию флаг сообщения об успешной отправке запроса
            Yii::$app->session->setFlash('info', Yii::t('app/participant', 'Update request was sent'));
        }
        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Метод ищет модель участника в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($participantBIC)
    {
        // Получить из БД участника с указанным БИК
        $model = BICDirParticipant::findOne(['participantBIC' => $participantBIC]);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $model;
    }
}
