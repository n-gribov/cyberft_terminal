<?php

namespace common\modules\monitor\controllers;

use common\base\Controller;
use common\models\Terminal;
use common\models\User;
use common\models\UserTerminal;
use common\modules\monitor\models\MonitorLogAR;
use common\modules\monitor\models\MonitorLogSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['commonSettings'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Search monitor log action
     *
     * @return mixed
     */
    public function actionLog()
    {
        $searchModel  = new MonitorLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $user = \Yii::$app->user->identity;

        // Список терминалов для фильтра в журнале
        if ($user->role == User::ROLE_ADMIN) {
            $query = Terminal::find()->all();
        } else {
            // Для доп. админов отбор только по доступным терминалам
            $terminalId = $user->terminalId;

            if (empty($terminalId) && $user->disableTerminalSelect) {
                $userTerminals = array_keys(UserTerminal::getUserTerminalIds($user->id));
            } else {
                $userTerminals = [$terminalId];
            }

            $query = Terminal::find()->where(['id' => $userTerminals])->all();
        }

        $terminals = ArrayHelper::map($query, 'id', 'terminalId');
        
        $eventCodes = MonitorLogAR::getEventCodeLabels();

        return $this->render('log', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'terminals' => $terminals,
            'eventCodes' => $eventCodes
        ]);
    }

}