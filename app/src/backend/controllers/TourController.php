<?php
namespace backend\controllers;

use common\base\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class TourController extends Controller
{
    /**
     * @var array Список названий всех доступных туров
     */
    public static $tourIds = [
        'adminDashboard'
    ];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['commonTour'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Контроллер отвечает только на ajax-запросы, приходящие от модуля
     * bootstro.js
     * Служит для создания содержимого туров по страницам системы.
     */
    public function actionIndex($id)
    {
        if (Yii::$app->request->isAjax) {
            // Включить формат вывода JSON
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (in_array($id, self::$tourIds)) {
                return json_decode($this->renderPartial($id));
            } else {
                return json_decode('{"success":true,"result":[]}');
            }
        } else {
            throw new ForbiddenHttpException(\Yii::t('app', "This request must not be called directly"));
        }
    }
}
