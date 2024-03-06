<?php

namespace backend\controllers;

use common\base\Controller;
use common\commands\CommandAcceptAR;
use common\commands\CommandAR;
use common\commands\search\CommandAcceptARSearch;
use common\commands\search\CommandARSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * Approve controller class
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
                'class'        => AccessControl::className(),
                'rules'        => [
                    [
                        'allow' => true,
                        'roles' => ['commonApprove'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Approved log
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CommandAcceptARSearch();

        return $this->render('index',
                [
                'searchModel'  => $searchModel,
                'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * For approving log
     *
     * @return string
     */
    public function actionForApproving()
    {
        $searchModel = new CommandARSearch();

        return $this->render('forApproving/index',
                [
                'searchModel'  => $searchModel,
                'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
        ]);
    }

    /**
     * View approving command
     *
     * @param integer $id Command ID
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('forApproving/view', ['model' => $model]);
    }

    /**
     * Accept command action
     *
     * @param integer $id Command ID
     * @return mixed
     */
    public function actionAccept($id)
    {
        $result = Yii::$app->commandBus->addCommandAccept($id,
            ['acceptResult' => CommandAcceptAR::ACCEPT_RESULT_ACCEPTED]);
        if ($result) {
            Yii::$app->session->setFlash('success',
                Yii::t('app', 'Command was approved'));
        } else {
            Yii::$app->session->setFlash('error',
                Yii::t('app', 'Error of approving'));
        }

        return $this->redirect(['/approve/for-approving']);
    }

    /**
     * Reject action
     *
     * @return mixed
     */
    public function actionReject()
    {
        $model = new \common\models\form\CommandRejectForm();
        if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
            $model->commandId = \Yii::$app->request->post('commandId');
            $rejectResult     = $model->reject();
            if ($rejectResult) {
                Yii::$app->session->setFlash('success',
                    Yii::t('app', 'Command was rejected'));
                return $this->redirect(['/approve/view', 'id' => $model->commandId]);
            } else {
                Yii::warning('Reject command status: error. Info['.json_encode($model->getErrors()).']');
            }
        }

        Yii::$app->session->setFlash('error',
            Yii::t('app', 'Error of rejecting'));
        return $this->redirect(['/approve/for-approving']);
    }

    /**
     * Finds the CoomandAR model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoomandAR the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CommandAR::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app/user',
                'Requested page not found'));
        }
    }
}