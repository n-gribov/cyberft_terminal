<?php

namespace backend\controllers;

use Yii;
use common\models\Participant;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SwiftRouteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['commonParticipants'],
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

    /**
     * Lists all Participant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Participant::find(),
        ]);

        // Вывести страницу
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Participant model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // Вывести страницу
        return $this->render('view', [
            // Получить из БД участника с указанным id
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Participant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Participant();

        // Если данные модели успешно загружены из формы в браузере и модель сохранена в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Перенаправить на страницу просмотра
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // Вывести страницу создания
            return $this->render('create', compact('model'));
        }
    }

    /**
     * Updates an existing Participant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Получить из БД участника с указанным id
        $model = $this->findModel($id);

        // Если данные модели успешно загружены из формы в браузере и модель сохранена в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Перенаправить на страницу просмотра
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // Вывести страницу редактирования
            return $this->render('update', compact('model'));
        }
    }

    /**
     * Deletes an existing Participant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // Получить из БД участника с указанным id и удалить его из БД
        $this->findModel($id)->delete();

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Метод ищет модель участника в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Получить из БД участника с указанным id
        $model = Participant::findOne($id);
        if ($model == null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $model;
    }
}
