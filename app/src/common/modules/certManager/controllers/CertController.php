<?php

namespace common\modules\certManager\controllers;

use backend\controllers\helpers\TerminalCodes;
use backend\models\forms\UploadUserAuthCertForm;
use common\base\Controller;
use common\components\cryptography\drivers\DriverCryptoPro;
use common\helpers\CryptoProHelper;
use common\models\CryptoproKey;
use common\models\User;
use common\models\UserAuthCertSearch;
use common\models\UserTerminal;
use common\modules\certManager\models\Cert;
use common\modules\certManager\models\CertSearch;
use common\modules\certManager\models\UserKeyForm;
use common\modules\certManager\Module;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class CertController extends Controller
{
    use TerminalCodes;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    $this->traitBehaviorsRules,
                    [
                        'allow' => true,
                            'actions' => [
                            'userkeys', 'user-key-download',
                            'user-remove-key', 'delete-cert',
                            'deactivate-key', 'get-activation-form',
                            'activate-cert', 'activate-key',
                            'download-cert', 'view-cert',
                            'create-cert',
                        ],
                    'roles' => ['commonMyKeysCertificates'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete', 'update'],
                        'roles' => ['user']
                    ],
                    [
                            'allow' => true,
                            'roles' => ['commonCertificates'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'activate-key' => ['post'],
                    'toggle-cert-status' => ['post']
                ],
            ],
        ];
    }

    /**
     * Lists all Cert models.
     * @return mixed
     */
    public function actionIndex($role = Cert::ROLE_SIGNER)
    {
        $searchModel = new CertSearch(['role' => $role]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Получение наименований организаций-участников и их id
        $participants = [];
        $participantIds = [];

        // Все сертификаты
        $certs = Cert::find()->with('participant')->all();

        // Получить модель пользователя из активной сессии
        $currentUser = Yii::$app->user->identity;
        $allowedParticipantIds = UserTerminal::getUserTerminalIds(Yii::$app->user->id);

        foreach($certs as $cert) {
            $certTerminal = $cert->participantCode . $cert->countryCode . $cert->sevenSymbol .
                    $cert->delimiter . $cert->terminalCode . $cert->participantUnitCode;

            if ($currentUser->role == User::ROLE_USER && !in_array($certTerminal, $allowedParticipantIds)) {
                continue;
            }

            $terminalAddress = $cert->terminalAddress;
            $participantIds[$terminalAddress] = $terminalAddress;

            if ($cert->participant) {
                $participants[$terminalAddress] = $cert->participant->name;
            }
        }

        // Вывести страницу
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'participants' => $participants,
            'participantIds' => $participantIds
        ]);
    }

    public function actionUserkeys()
    {
        $id = Yii::$app->user->id;
        $model = User::findOne($id);

        $certSearchModel = new UserAuthCertSearch();
        $certDataProvider = $certSearchModel->search(Yii::$app->request->queryParams, $id);

        $uploadCertForm = new UploadUserAuthCertForm();

        // Вывести страницу ключей пользователя
        return $this->render('userkeys', [
            'model' => $model,
            'certDataProvider' => $certDataProvider,
            'certSearchModel' => $certSearchModel,
            'uploadCertForm' => $uploadCertForm,
        ]);
    }

    /**
     * Генерирует ключи для пользователя
     * @return string
     */
    public function actionUserCreateKey()
    {
        $keyFilePrefix = $this->getUserKeyPrefix();
        $certManager = Yii::$app->getModule('certManager');

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $model = new UserKeyForm();
            // Загрузить данные модели из формы в браузере
            $model->load(Yii::$app->request->post());
            $privateKeyPassword = Yii::$app->request->post('password');
            if (empty($privateKeyPassword) || ($privateKeyPassword !== Yii::$app->request->post('password_repeat'))) {
                // Поместить в сессию флаг сообщения о несовпадающих паролях
                Yii::$app->session->setFlash('error', Yii::t('app/terminal', 'Passwords do not match'));
                // Перенаправить на страницу пользовательских ключей
                return $this->redirect(['userkeys']);
            }

            $keys = $certManager->generateUserKeys($keyFilePrefix, $privateKeyPassword, $model->getAttributes());
            if ($keys === false) {
                // Поместить в сессию флаг сообщения об ошибке создания ключей
                Yii::$app->session->setFlash('error', Yii::t('app', 'There was an error creating keys'));
                // Перенаправить на страницу пользовательских ключей
                return $this->redirect(['userkeys']);
            }
            // Поместить в сессию флаг сообщения об успешном создании ключей
            Yii::$app->session->setFlash('success', Yii::t('app', 'User keys successfully created'));
        }

        // Перенаправить на страницу пользовательских ключей
        return $this->redirect(['userkeys']);
    }

    /**
     * Удаляет сгенерированные ранее ключи пользователя
     * @return string
     */
    public function actionUserRemoveKey()
    {
        $keyFilePrefix = $this->getUserKeyPrefix();
        $path = Module::getUserKeyStoragePath();
        $publicKey = $path . '/' . $keyFilePrefix . '.pub';
        $privateKey = $path . '/' . $keyFilePrefix . '.key';
        $certificate = $path . '/' . $keyFilePrefix . '.crt';

        if (file_exists($publicKey) && is_writable($publicKey)) {
            unlink($publicKey);
        }
        if (file_exists($privateKey) && is_writable($privateKey)) {
            unlink($privateKey);
        }
        if (file_exists($certificate) && is_writable($certificate)) {
            unlink($certificate);
        }

        // Перенаправить на страницу пользовательских ключей
        return $this->redirect(['userkeys']);
    }

    /**
     * Скачивает файл пользовательского ключа
     * @param string $type Расширение файла: pub, key, crt
     * @throws BadRequestHttpException
     */
    public function actionUserKeyDownload($type)
    {
        $allowed_types = ['key' => true, 'pub' => true, 'crt' => true];

        $keyFileName = $this->getUserKeyPrefix();
        $certManager = Yii::$app->getModule('certManager');
        $path = Module::getUserKeyStoragePath();

        $file = $path . '/' . $keyFileName . '.' . $type;

        if (!isset($allowed_types[$type]) || !file_exists($file) || !is_readable($file)) {
            throw new BadRequestHttpException(Yii::t('app', "Can't send file"));
        }

        $keyFileName .= '-'
            .  $certManager->getCertFingerprint($path . '/' . $keyFileName . '.crt')
            . ".$type";

        Yii::$app->response->sendFile($file, $keyFileName);
    }

    /**
     * Displays a single Cert model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // Вывести страницу просмотра
        return $this->render('view', [
            // Получить из БД сертификат с указанным id
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cert();
        $model->setScenario('create');

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Загрузить данные модели из формы в браузере
            $model->load(Yii::$app->request->post());
            $model->certificate = UploadedFile::getInstance($model, 'certificate');
            $model->userId = Yii::$app->user->getId();

            if (!$model->hasErrors()) {

                $this->switchActiveCerts($model);

                // Если модель успешно сохранена в БД
                if ($model->save()) {
                    // Зарегистрировать событие создания нового сертификата в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('CreateCert', ['id' => $model->id]);

                    // Перенаправить на страницу просмотра
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else if (!$model->hasErrors('certificate') && !$model->hasErrors('terminalId')) {
                $model->addError('certificate', Yii::t('app/cert', 'An unexpected error occurred. Failed to perform the operation.'));
            }
        }

        // Вывести страницу создания сертификата
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        // Получить из БД сертификат с указанным id
        $model = $this->findModel($id);

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post())) {
            $this->switchActiveCerts($model);
            // Сохранить модель в БД
            $model->save();
            // Зарегистрировать событие редактирования сертификата в модуле мониторинга
            Yii::$app->monitoring->extUserLog('EditCert', ['id' => $model->id]);

            // Перенаправить на страницу просмотра
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // Вывести страницу редактирования сертификата
            return $this->render('update', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        // Зарегистрировать событие удаления сертификата в модуле мониторинга
        Yii::$app->monitoring->extUserLog('DeleteCert', ['id' => $id]);

        // Получить из БД сертификат с указанным id
        $model = $this->findModel($id);
        // Удалить сертификат из БД
        $model->delete();

        // Перенаправить на страницу индекса
        return $this->redirect(['index', 'role' => $model->role]);
    }


    private function getUserKeyPrefix()
    {
        return 'user' . Yii::$app->user->id;
    }

    /**
     * Метод для изменения статуса сертификата
     */
    public function actionToggleCertStatus()
    {
        if (Yii::$app->user->can('commonCertificatesStatusManagement') && Yii::$app->request->isPost) {
            // Получаем параметры для обновления данных сертификата
            $model = Cert::findOne(Yii::$app->request->post('certId'));
            $errorMsg = null;

            if (!$model) {
                $errorMsg = 'Сертификат не найден';
            } else {
                $certStatus = $model->status;
                if ($certStatus == Cert::STATUS_C11 || $certStatus == Cert::STATUS_C12) {
                    $certStatus = Cert::STATUS_C10;
                } else if ($certStatus == Cert::STATUS_C10) {
                    $certStatus = Cert::STATUS_C12;
                }

                $changeReason = Yii::$app->request->post('changeReason');

                // Проверяем параметры на наличие
                if (empty($certStatus)) {
                    $errorMsg = 'Не определен новый статус сертификата';
                } else if (empty($changeReason)) {
                    $errorMsg = 'Не заполнена причина смены статуса сертификата';
                }
            }

            if ($errorMsg) {
                // Поместить в сессию флаг сообщения об ошибке
                Yii::$app->session->setFlash('error', $errorMsg);

                // Перенаправить на предыдущую страницу
                return $this->redirect(Yii::$app->request->referrer);
            }

            // При отсутствии ошибок меняем статус сертифbката
            $model->status = $certStatus;
            $model->statusDescription = $changeReason;
            $this->switchActiveCerts($model);

            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Зарегистрировать событие в модуле мониторинга
                Yii::$app->monitoring->extUserLog('ChangeCertStatus', [
                    'id' => $model->id,
                    'status' => $model->getStatusLabel(),
                    'reason' => $changeReason,
                    'certName' => $model->certId
                ]);

                // Поместить в сессию флаг сообщения об успешном изменении статуса сертификата
                Yii::$app->session->setFlash('success', Yii::t('app/message', 'The certificate status is changed successfully'));

                // Перенаправить на предыдущую страницу
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        // Поместить в сессию флаг сообщения об ошибке изменения статуса сертификата
        Yii::$app->session->setFlash('error', Yii::t('app/message', 'The certificate status change failed'));

        // Перенаправить на предыдущую страницу
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionGetActivationForm()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException;
        }

        $id = Yii::$app->request->get('id');

        // Получить из БД ключ с указанным id
        $model = CryptoproKey::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }

        return $this->renderAjax('_certActivation', [
            'model' => $model
        ]);
    }

    public function actionActivateKey()
    {
        $msg = null;
        $id = Yii::$app->request->post('id');
        if (!$id) {
            $msg = Yii::t('app/cert', 'Finding key in terminal base failed');
        }

        $password = Yii::$app->request->post('password');

        if (!($model = CryptoproKey::findOne($id))) {
            $msg = Yii::t('app/cert', 'Finding key in terminal base failed');
        }

        if ($msg) {
            return json_encode([
                'status' => 'error',
                'text' => $msg
            ]);
        }

        $collection = CryptoProHelper::getCertInfo(null,
            [DriverCryptoPro::SERIAL => CryptoProHelper::driver()->prefixSerial($model->serialNumber)]
        );

        $container = $collection->first();

        if ($container) {
            $containerName = $container[DriverCryptoPro::CONTAINER];

            if (!CryptoProHelper::checkContainerPassword($containerName, $password)) {
                return json_encode([
                    'status' => 'error',
                    'text' => Yii::t('app/cert', 'The password is wrong')
                ]);
            }

            // Активация ключа
            $model->status = CryptoproKey::STATUS_READY;
            $passwordKey = getenv('COOKIE_VALIDATION_KEY');
            $model->password = base64_encode(Yii::$app->security->encryptByKey($password, $passwordKey));
            $model->active = 1;
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешной активации ключа
                Yii::$app->session->setFlash('success', Yii::t('app/cert', 'Key activated'));
            } else {
                // Поместить в сессию флаг сообщения об ошибке активации ключа
                Yii::$app->session->setFlash('error', Yii::t('app/cert', 'Error saving key status'));
            }

            // Перенаправить на страницу ключей пользователя
            return $this->redirect(['/certManager/cert/userkeys', 'tabMode' => 'tabCryptoProKeys']);
        }

        return json_encode([
            'status' => 'error',
            'text' => Yii::t('app/cert', 'Container not found')
        ]);
    }

    public function actionExportCertsDbo()
    {
        $hasErrors = false;
        $msg = '';

        // Пользователь может управлять сертификатами
        $canManageCerts = Yii::$app->user->can('commonCertificates');

        if (!$canManageCerts) {
            $hasErrors = true;
            $msg = 'Управление сертификатами запрещено';
        }

        // Пользователь может выгружать сертификаты
        $canExportCerts = Module::checkCertsExports();

        if (!$canExportCerts) {
            $hasErrors = true;
            $msg = 'Выгрузка сертификатов запрещена';
        }

        // Указан путь к скрипту выгрузки
        $scriptPath = getenv('CERT_EXPORT_PATH');

        if (!$scriptPath) {
            $hasErrors = true;
            $msg = 'Не указан путь к приложению выгрузки сертификатов';
        }

        // Указан путь к клиент-листу
        $client_listPath = getenv('CLIENT_LIST');

        if (!$client_listPath) {
            $hasErrors = true;
            $msg = 'Не указан путь к клиент-листу';
        }

        if (is_dir($client_listPath)) {
            $hasErrors = true;
            $msg = 'Клиент-лист должен быть файлом, а не директорией';
        }

        if ($hasErrors) {
            $status = 'error';
        } else {
            // Выполнение скрипта выгрузки
            $certList = Cert::find()
                ->select(['id', 'userId', 'type', 'certType', 'validFrom', 'validBefore',
                    'useBefore', 'participantCode', 'countryCode', 'sevenSymbol', 'delimiter',
                    'terminalCode', 'participantUnitCode', 'fingerprint', 'status', 'email',
                    'phone', 'post', 'role', 'signAccess', 'ownerId', 'lastName', 'firstName',
                    'middleName', 'body'])
                ->asArray()->all();

            $fp = fopen($scriptPath . 'certs.csv', 'w');
            $count = 0;
            foreach($certList as $cert) {
                foreach ($cert as $key => $value) {
                    $cert[$key] = iconv('UTF-8', 'windows-1251', $value);
                }

                if (!$count) {
                    fputcsv($fp, array_keys($cert), ';');
                }
                fputcsv($fp, $cert, ';');
                $count++;
            }
            fclose($fp);

            copy($client_listPath, $scriptPath . 'client_list.pm');

            $status = 'success';
            $msg = 'Сертификаты успешно выгружены';
        }

        // Поместить в сессию флаг сообщения о результате операции
        Yii::$app->session->setFlash($status, $msg);

        // Перенаправить на страницу сертификатов
        return $this->redirect('/certManager/cert');
    }

    /**
     * Проверка наличия сертификата контролера для указанного терминала
     * @param $terminalId
     * @param $fingerprint
     * @return array|mixed
     */
    public function actionCheckControllerCertByTerminal($terminalId, $fingerprint = null)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $certs = CertSearch::searchActiveControllerCertsByTerminalId($terminalId, $fingerprint);

        if (empty($certs)) {
            return '';
        }

        $cert = $certs[0];

        $result['terminalId'] = $terminalId;
        $result['participantName'] = '';
        $result['fullName'] = $cert->fullName;
        $result['fingerprint'] = $cert->fingerprint;
        $result['useBefore'] = date('d-m-Y H:i:s', strtotime($cert->useBefore));

        return $result;
    }

    /**
     * Деактивация текущего активного сертификата и активация нового
     * @param $model
     */
    protected function switchActiveCerts(Cert $model)
    {
        if ($model->role == Cert::ROLE_SIGNER_BOT
            && ($model->isActive() || $model->isNewRecord)
        ) {
            $certs = CertSearch::searchActiveControllerCertsByTerminalId($model->terminalAddress, $model->fingerprint);
            // Деактивация существующих сертификатов
            if (count($certs)) {
                foreach($certs as $cert) {
                    $cert->setActive(false);
                    $cert->save(false);
                }

                $model->setActive(true);
            }
        }
    }

    /**
     * Метод ищет модель сертификата в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Получить из БД сертификат с указанным id
        $model = Cert::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $model;
    }
}
