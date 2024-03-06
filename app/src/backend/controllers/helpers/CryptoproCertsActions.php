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
        // Получить из БД сертификат с указанным id
        $model = $this->findModel($id);
        return $this->proccessCertForm($model);
    }

    public function actionView($id)
    {
        // Вывести страницу
        return $this->render('@backend/views/cryptopro-certs/view', [
            // Получить из БД сертификат с указанным id
            'model' => $this->findModel($id),
        ]);
    }

    public function actionDelete($id)
    {
        // Получить из БД сертификат с указанным id и удалить его из БД
        $this->findModel($id)->delete();

        // Перенаправить на страницу индекса
        return $this->redirect($this->journalLink);
    }

    public function actionDownload($id)
    {
        // Получить из БД сертификат с указанным id
        $model = $this->findModel($id);

        if (!empty($model->certData)) {
            CryptoProHelper::downloadCertificate($model->ownerName, $model->keyId, $model->certData);
        } else {
            throw new NotFoundHttpException('The requested file does not exist');
        }
    }

    public function actionChangeCertStatus()
    {
        // Получаем параметры запроса
        $id = Yii::$app->request->get('id');
        $status = Yii::$app->request->get('status');

        //  если параметры не найдены
        if (empty($id) || empty($status)) {
            throw new InvalidParamException();
        }

        // Находим сертификат по id
        $certModel = $this->certModel;
        $cert = $certModel::findOne($id);
        $cert->status = $status;

        // Если модель успешно сохранена в БД
        if ($cert->save()) {
            CryptoProHelper::addCertificateFromTerminal($cert);

            // Поместить в сессию флаг сообщения об изменении статуса сертификата
            Yii::$app->session->setFlash('success', Yii::t('app/message', 'The certificate status is changed successfully'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке изменения статуса сертификата
            Yii::$app->session->setFlash('error', Yii::t('app/message', 'The certificate status change failed'));
        }
 
        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $cert->id]);
    }

    private function proccessCertForm($model)
    {
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Загрузить данные модели из формы в браузере
            $model->load(Yii::$app->request->post());
            // hack for VTB
            if ($model->formName() == 'VTBCryptoproCert') {
                $model->senderTerminalAddress = $model->terminal->terminalId;

                Yii::info('model term addr: ' . $model->senderTerminalAddress);
            }
            if ($model->isNewRecord) {
                // Создание нового сертификата
                $model->certificate = UploadedFile::getInstance($model, 'certificate');

                // Если модель успешно сохранена в БД
                if ($model->save()) {
                    // Поместить в сессию флаг сообщения об успешном создании сертификата
                    Yii::$app->session->setFlash('success', Yii::t('app/cert', 'Certificate created'));

                    // Зарегистрировать событие добавления нового сертификата для ключа КриптоПро в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('AddIsoCertificate', [
                        'id' => $model->id,
                        'fingerprint' => $model->keyId,
                        'senderTerminal' => $model->senderTerminalAddress
                    ]);

                    // Перенаправить на страницу просмотра
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    // Поместить в сессию флаг сообщения об ошибке создании сертификата
                    Yii::$app->session->setFlash('error', Yii::t('app/message', 'Creating error'));
                }
            } else {
                // Редактирование сертификата
                $model->status = $model::STATUS_NOT_READY;

                // Если модель успешно сохранена в БД
                if ($model->save()) {
                    // Поместить в сессию флаг сообщения об успешном сохранении сертификата
                    Yii::$app->session->setFlash('success', Yii::t('app/message', 'Edit successful'));
                } else {
                    // Поместить в сессию флаг сообщения об ошибке сохранения сертификата
                    Yii::$app->session->setFlash('error', Yii::t('app/iso20022', 'Error! Update failure'));
                }
            }
        }

        $sendersList = $this->getSendersList();
        $receiversLists = $this->getReceiversList();

        // Вывести форму
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
     * Метод ищет адрес участника по адресу терминала
     */
    private function getParticipantByAddress($address)
    {
        $participantTerminalId = Address::truncateAddress($address);

        // Поиск наименования в справочнике участников
        $participant = BICDirParticipant::findOne(['participantBIC' => $participantTerminalId]);

        return $participant;
    }

    /**
     * Метод ищет модель сертификата в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    public function findModel($id)
    {
        // Получить из БД сертификат с указанным id
        $certModel = $this->certModel;
        $model = $certModel::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $model;
    }
}
