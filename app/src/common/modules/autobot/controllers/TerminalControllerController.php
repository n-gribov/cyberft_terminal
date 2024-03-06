<?php

namespace common\modules\autobot\controllers;

use common\base\Controller;
use common\base\traits\ChecksTerminalAccess;
use common\models\Country;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

class TerminalControllerController extends Controller
{
    use ChecksTerminalAccess;

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
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreate($terminalId)
    {
        $this->ensureUserHasTerminalAccess($terminalId);
        $model = new \common\modules\autobot\models\Controller(['terminalId' => $terminalId, 'country' => 'RU']);

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Controller is successfully created'));
                return $this->redirect(['/autobot/terminals/index', 'id' => $terminalId, 'tabMode' => 'tabAutobot']);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Failed to create controller'));
            }
        }

        $countries = Country::find()->orderBy('alfa2Code')->all();
        return $this->renderAjax('_controllerModal', compact('model', 'countries'));
    }

    public function actionUpdate($id)
    {
        $model = \common\modules\autobot\models\Controller::findOne($id);

        $this->ensureUserHasTerminalAccess($model->terminalId);
        if (!$model->isEditable) {
            throw new ForbiddenHttpException();
        }

        if (empty($model->country)) {
            $model->country = 'RU';
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Controller is successfully updated'));
                return $this->redirect(['/autobot/terminals/index', 'id' => $model->terminalId, 'tabMode' => 'tabAutobot']);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Failed to update controller'));
            }
        }

        $countries = Country::find()->orderBy('alfa2Code')->all();
        return $this->renderAjax('_controllerModal', compact('model', 'countries'));
    }

    public function actionDelete($id)
    {
        $model = \common\modules\autobot\models\Controller::findOne($id);

        $this->ensureUserHasTerminalAccess($model->terminalId);
        if (!$model->isDeletable) {
            throw new ForbiddenHttpException();
        }

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Controller is successfully deleted'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Failed to delete controller'));
        }
        return $this->redirect(['/autobot/terminals/index', 'id' => $model->terminalId, 'tabMode' => 'tabAutobot']);
    }

}
