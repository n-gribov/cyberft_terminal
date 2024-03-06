<?php

namespace backend\controllers;

use common\base\Controller;
use common\commands\CommandAcceptAR;
use common\commands\CommandAR;
use common\commands\search\CommandAcceptARSearch;
use common\commands\search\CommandARSearch;
use common\models\form\CommandRejectForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * Класс контроллера обслуживает запросы на одобрение команд
 * Команда это действие, направленное на изменение настроек
 *
 * @package backend
 * @subpackage controllers
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 */
class ApproveController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules'=> [
                    [
                        'allow' => true,
                        'roles' => ['commonApprove'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Метод выводист индексную страницу
     *
     * @return string
     */
    public function actionIndex()
    {
        // Получить из БД модель команды
        $searchModel = new CommandAcceptARSearch();

        // Вывести страницу
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * Метод выводит страницу со списком команд, ожидающих одобрения
     *
     * @return string
     */
    public function actionForApproving()
    {
        // Получить из БД модель команды
        $searchModel = new CommandARSearch();

        // Вывести страницу
        return $this->render('forApproving/index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * Метод выводит страницу с формой одобрения команды
     *
     * @param integer $id Command ID
     * @return string
     */
    public function actionView($id)
    {
        // Получить из БД команду с указанным id
        $model = $this->findModel($id);

        // Вывести страницу
        return $this->render('forApproving/view', ['model' => $model]);
    }

    /**
     * Метод осуществляет одобрение команды
     *
     * @param integer $id Command ID
     * @return mixed
     */
    public function actionAccept($id)
    {
        // Одобрить команду в компоненте CommandBus
        $result = Yii::$app->commandBus->addCommandAccept(
            $id,
            ['acceptResult' => CommandAcceptAR::ACCEPT_RESULT_ACCEPTED]
        );
        if ($result) {
            // Поместить в сессию флаг сообщения об успешном одобрении команды
            Yii::$app->session->setFlash('success', Yii::t('app', 'Command was approved'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке одобрения команды
            Yii::$app->session->setFlash('error', Yii::t('app', 'Error of approving'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect(['/approve/for-approving']);
    }

    /**
     * Метод осуществляет отказ в одобрении команды
     *
     * @return mixed
     */
    public function actionReject()
    {
        // модель формы отказа
        $model = new CommandRejectForm();
        // Если отправлены данные POST и модель загружена
        if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
            // Получить id команды
            $model->commandId = \Yii::$app->request->post('commandId');
            // Осуществить отказ
            $rejectResult = $model->reject();
            if ($rejectResult) {
                // Поместить в сессию флаг сообщения об успешной отмене команды
                Yii::$app->session->setFlash('success', Yii::t('app', 'Command was rejected'));
                // Перенаправить на страницу просмотра
                return $this->redirect(['/approve/view', 'id' => $model->commandId]);
            } else {
                // Поместить в сессию флаг сообщения об ошибке отмены команды
                Yii::warning('Reject command status: error. Info[' . json_encode($model->getErrors()) . ']');
            }
        }

        // Поместить в сессию флаг сообщения об ошибке отмены команды
        Yii::$app->session->setFlash('error', Yii::t('app', 'Error of rejecting'));

        // Перенаправить на страницу индекса
        return $this->redirect(['/approve/for-approving']);
    }

    /**
     * Метод ищет модель команды в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Найти в БД команду по id
        $model = CommandAR::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
        }
        
        return $model;
    }
}