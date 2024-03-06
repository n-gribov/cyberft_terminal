<?php

namespace addons\VTB\controllers;

use backend\controllers\helpers\CryptoproCertsActions;
use common\models\CryptoproCert;
use yii\web\Controller;

class CryptoproCertController extends Controller
{
    use CryptoproCertsActions;

    private $certModel;
    private $journalLink;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->certModel = CryptoproCert::getInstance('VTB');
        $this->journalLink = '/VTB/settings?tabMode=tabCryptoPro&subTabMode=subTabCryptoProCerts';
    }

}
/*
use common\helpers\CryptoProHelper;

use common\models\CryptoproCert;
use common\models\CryptoproCertSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class CryptoproCertController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['documentManage'],
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

    public function actionIndex()
    {
        $searchModel = CryptoproCertSearch::getInstance('VTB');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = CryptoproCert::getInstance('VTB');
        $model->setScenario('create');

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->certificate = UploadedFile::getInstance($model, 'certificate');

            if (!$model->hasErrors() && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/iso20022', 'Certificate created'));

                // Регистрация события добавления нового сертификата для ключа КриптоПро
                Yii::$app->monitoring->extUserLog('AddVtbCertificate',
                    [
                        'id' => $model->id,
                        'fingerprint' => $model->keyId,
                        'terminal' => $model->terminal->terminalId
                    ]
                );

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                if ($model->hasErrors()) {
                    Yii::info('Validation errors: ' . var_export($model->errors, true));
                }
                Yii::$app->session->setFlash('error', Yii::t('app/iso20022', 'Error! Creating failure'));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!empty(Yii::$app->request->isPost)) {
            $model->load(Yii::$app->request->post());

            $model->status = CryptoproCert::STATUS_NOT_READY;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/iso20022', 'Certificate updated'));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app/iso20022', 'Error! Update failure'));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Url::to('index'));
    }

    protected function findModel($id)
    {
        $certModel = CryptoproCert::getInstance('VTB');
        if (($certModel::findOne($id)) !== null) {
            return $certModel;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);

        if (!empty($model->certData)) {
            CryptoProHelper::downloadCertificate($model->ownerName, $model->keyId, $model->certData);
        } else {
            throw new NotFoundHttpException('The requested file does not exist.');
        }
    }

    public function actionChangeCertStatus()
    {
        // Получаем параметры запроса
        $get = Yii::$app->request->get();

        // Получаем необходимые параметры из запроса
        $id = $get['id'];
        $status = $get['status'];

        // Если параметры id-сертификата и новый статус доступны,
        // производим смену статуса
        if ($id && $status) {

            $certModel = CryptoproCert::getInstance('VTB');
            // Меняем статус сертификата
            if ($certModel::updateAll(['status' => $status], ['id' => $id])) {

                // Если сертификат успешно обновлен, то добавляем его в certmgr

                // Находим сертификат по id
                $cert = $certModel::findOne($id);

                // Добавляем
                if ($cert) {
                    CryptoProHelper::addCertificateFromTerminal($cert);
                }

                Yii::$app->session->setFlash('success', Yii::t('app/message', 'The certificate status is changed successfully'));
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/message', 'The certificate status change failed'));
                return $this->redirect(Yii::$app->request->referrer);
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/message', 'The certificate status change failed'));
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

}
*/