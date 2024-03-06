<?php

namespace backend\controllers\helpers;

use yii\base\InvalidParamException;
use yii\web\UploadedFile;
use Yii;
use common\modules\certManager\models\Cert;
use common\helpers\Address;
use common\modules\participant\models\BICDirParticipant;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\helpers\CryptoProHelper;
use common\models\Terminal;

trait CryptoproCertsActions
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

    public function actionCreate()
    {
        $model = $this->certModel;
        $model->setScenario('create');

        return $this->proccessCertForm($model);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        return $this->proccessCertForm($model);
    }

    public function actionView($id)
    {
        return $this->render('@backend/views/cryptopro-certs/view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect($this->journalLink);
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
        $id = Yii::$app->request->get('id');
        $status = Yii::$app->request->get('status');

        //  если параметры не найдены
        if (empty($id) || empty($status)) {
            throw new InvalidParamException;
        }

        // Находим сертификат по id
        $certModel = $this->certModel;
        $cert = $certModel::findOne($id);
        $cert->status = $status;

        if ($cert->save()) {
            CryptoProHelper::addCertificateFromTerminal($cert);

            Yii::$app->session->setFlash('success', Yii::t('app/message', 'The certificate status is changed successfully'));
            return $this->redirect(['view', 'id' => $cert->id]);

        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/message', 'The certificate status change failed'));
            return $this->redirect(['view', 'id' => $cert->id]);
        }
    }

    private function findModel($id)
    {
        $certModel = $this->certModel;
        if (($model = $certModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function proccessCertForm($model)
    {
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // hack for VTB
            if ($model->formName() == 'VTBCryptoproCert') {
                $model->senderTerminalAddress = $model->terminal->terminalId;

                Yii::info('model term addr: ' . $model->senderTerminalAddress);
            }
            if ($model->isNewRecord) {
                // Создание нового сертификата
                $model->certificate = UploadedFile::getInstance($model, 'certificate');

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app/cert', 'Certificate created'));

                    // Регистрация события добавления нового сертификата для ключа КриптоПро
                    Yii::$app->monitoring->extUserLog('AddIsoCertificate', [
                        'id' => $model->id,
                        'fingerprint' => $model->keyId,
                        'senderTerminal' => $model->senderTerminalAddress
                    ]);

                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app/message', 'Creating error'));
                }
            } else {
                // Редактирование сертификата
                $model->status = $model::STATUS_NOT_READY;

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app/message', 'Edit error'));
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('app/iso20022', 'Error! Update failure'));
                }
            }
        }

        $sendersList = $this->getSendersList();
        $receiversLists = $this->getReceiversList();

        return $this->render('@backend/views/cryptopro-certs/_form', [
            'model' => $model,
            'sendersList' => $sendersList,
            'receiversLists' => $receiversLists
        ]);
    }

    /**
     * Получение списка терминалов-отправителей
     * @return array
     */
    private function getSendersList()
    {
        $terminals = [];

        // Получение всех активных сертификатов контролера
        $certs = Cert::find()->where(['status' => Cert::STATUS_C10, 'role' => Cert::ROLE_SIGNER_BOT])->all();

        foreach($certs as $cert) {
            $terminalId = (string) $cert->terminalId;

            $participant = $this->getParticipantByAddress($terminalId);

            if ($participant) {
                $terminalTitle = $terminalId . " (" . $participant->name . ")";
            } else {
                $terminalTitle = $terminalId;
            }

            $terminals[$terminalId] = $terminalTitle;
        }

        return $terminals;
    }

    private function getReceiversList()
    {
        $terminals = [];

        $senders = Terminal::getList('id', 'terminalId');

        foreach($senders as $id => $sender) {
            $participant = $this->getParticipantByAddress($sender);

            if ($participant) {
                $terminalTitle = $sender . " (" . $participant->name . ")";
            } else {
                $terminalTitle = $sender;
            }

            $terminals[$id] = $terminalTitle;
        }

        return $terminals;
    }

    /**
     * Получение адреса участника по адресу терминала
     * @param $address
     * @return null|static
     */
    private function getParticipantByAddress($address)
    {
        $participantTerminalId = Address::truncateAddress($address);

        // Поиск наименования в справочнике участников
        $participant = BICDirParticipant::findOne(['participantBIC' => $participantTerminalId]);

        return $participant;
    }
}