<?php

namespace backend\controllers;

use addons\edm\models\UserAuthCertBeneficiary;
use backend\models\forms\UploadUserAuthCertForm;
use backend\services\UserAuthCertService;
use common\base\Controller;
use common\helpers\UserHelper;
use common\models\User;
use common\models\UserAuthCert;
use common\modules\certManager\models\Cert;
use common\modules\participant\models\BICDirParticipant;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class UserAuthCertController extends Controller
{
    /**
     * @var UserAuthCertService
     */
    private $authCertService;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [
                            'commonUsers',
                            'commonMyKeysCertificates',
                            'additionalAdmin',
                            'commonCertificates'
                        ]
                    ],
                ],
            ],
        ];
    }

    private function findUserAuthCertModel($id)
    {
        $model = UserAuthCert::findOne($id);
        $userModel = $model->user;
        $ownerId = $userModel->ownerId;

        return $this->checkUserPermissions($model->userId, $model, $ownerId);
    }

    private function findUserModel($id)
    {
        $model = User::findOne($id);
        $ownerId = $model->ownerId;

        return $this->checkUserPermissions($id, $model, $ownerId);
    }

    private function checkUserPermissions($id, $model, $ownerId)
    {
        $executorModel = User::findOne(Yii::$app->user->id);
        $executorRole = $executorModel->role;

        if ($model == null) {
            throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
        }

        if ($executorRole === User::ROLE_ADMIN) {
            return $model;
        } else if ($executorRole === User::ROLE_USER) {
            if ($id == Yii::$app->user->id) {
                return $model;
            } else {
                throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
            }
        } else if ($executorRole === User::ROLE_ADDITIONAL_ADMIN) {
                if ($ownerId == Yii::$app->user->id or $id == Yii::$app->user->id) {
                    return $model;
                } else {
                    throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
                }
        } else {
            throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
        }
    }

    private function getRedirectPath($userId)
    {
        $executorModel = User::findOne(Yii::$app->user->id);
        $executorRole = $executorModel->role;

        if ($executorRole === User::ROLE_USER) {
            return ['/certManager/cert/userkeys'];
        } else if ($executorRole === User::ROLE_ADMIN || $executorRole === User::ROLE_ADDITIONAL_ADMIN) {
            return ['/user/view', 'id' => $userId, 'tabMode' => 'certs'];
        }
    }

    private function getRenderViewCertPath()
    {
        $executorModel = User::findOne(Yii::$app->user->id);
        $executorRole = $executorModel->role;

        if ($executorRole === User::ROLE_USER) {
            return '@common/modules/certManager/views/cert/viewUserAuthCert';
        } else if ($executorRole === User::ROLE_ADMIN || $executorRole === User::ROLE_ADDITIONAL_ADMIN) {
            return '/user/viewUserAuthCert';
        }
    }

    public function actionDownload($id)
    {
        $model = $this->findUserAuthCertModel($id);
        return Yii::$app->response->sendContentAsFile($model->certificate, "{$model->fingerprint}.crt");
    }

    public function actionView($id)
    {
        $view = $this->getRenderViewCertPath();

        $model = $this->findUserAuthCertModel($id);
        $userId = $model->userId;
        $data = $this->getKeyTerminalData($id, $userId);
        $data['model'] = $model;
        $data['uploadCertForm'] = new UploadUserAuthCertForm();

        // Вывести страницу
        return $this->render($view, $data);
    }

    public function actionPreview($model, $userId = null)
    {
        $view = $this->getRenderViewCertPath();
        $data = $this->getKeyTerminalData(null, $userId);

        $data['model'] = $model;
        $data['uploadCertForm'] = new UploadUserAuthCertForm();

        return $this->render($view, $data);
    }

    public function actionDelete($id)
    {
        $model = $this->findUserAuthCertModel($id);
        $userId = $model->userId;
        $redirect = $this->getRedirectPath($userId);

        $this->setAuthCertService();

        if ($this->authCertService->deleteCertificate($model) === false) {
            // Поместить в сессию флаг сообщения об ошибке удаления сертификата
            \Yii::$app->session->addFlash('error', \Yii::t('app/user', 'Failed to delete certificate'));
        } else {
            // Поместить в сессию флаг сообщения об успешном удалении сертификата
            \Yii::$app->session->addFlash('success', \Yii::t('app/user', 'Certificate was deleted'));

            UserAuthCertBeneficiary::deleteAll(['keyId' => $id]);

            $count = UserAuthCert::find()->where(['userId' => $userId])->count();

            if ($count == 1) {
                $cert = UserAuthCert::findOne(['userId' => $userId]);
                $cert->status = 'active';
                // Сохранить модель в БД
                $cert->save();
            }

            UserHelper::sendUserToSecurityOfficersAcceptance($userId);
        }

        // Перенаправить на страницу перенаправления
        return $this->redirect($redirect);
    }

    public function actionCreate($id)
    {
        $user = $this->findUserModel($id);

        // Возможность работы с сертификатом
        if (!UserHelper::userProfileAccess($user, true)) {
            throw new ForbiddenHttpException();
        }

        $this->setAuthCertService();

        $form = new UploadUserAuthCertForm();
        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                $expiryDate = $form->certificate->getValidTo();
                $expiryDate = $expiryDate ? $expiryDate->format('Y-m-d') : null;

                if (!$expiryDate || (strtotime($expiryDate) < mktime())) {
                    $redirect = $this->getRedirectPath($id);

                    // Поместить в сессию флаг сообщения об истёкшем сертификате
                    Yii::$app->session->setFlash(
                        'error',
                        Yii::t('app/user', 'The certificate has expired! Could not load expired certificate!')
                    );

                    // Перенаправить на страницу перенаправления
                    return $this->redirect($redirect);
                }
                // Если указан certId, значит у нас кейс замены сертификата
                if ($form->certId) {
                    $redirect = $this->getRedirectPath($id);
                    $model = UserAuthCert::findOne(['id' => $form->certId]);
                    $model->certificate = $form->certificate->getBody();
                    $model->fingerprint = $form->certificate->getFingerprint();
                    $model->expiryDate = $expiryDate;
                } else {
                    $model = new UserAuthCert([
                        'userId' => $user->id,
                        'certificate' => $form->certificate->getBody(),
                        'fingerprint' => $form->certificate->getFingerprint(),
                        'expiryDate' => $expiryDate,
                    ]);

                    return $this->actionPreview($model, $id);
                }
                if ($this->authCertService->saveCertificate($model)) {
                    if ($form->certId) {
                        // Поместить в сессию флаг сообщения об успешном сохранении сертификата
                        Yii::$app->session->setFlash('success', \Yii::t('app/user', 'Certificate was updated'));
                    } else {
                        // Поместить в сессию флаг сообщения об успешном создании сертификата
                        Yii::$app->session->setFlash('success', \Yii::t('app/user', 'Certificate was created'));

                    }

                    UserHelper::sendUserToSecurityOfficersAcceptance(Yii::$app->user->id);

                    // Перенаправить на страницу перенаправления
                    return $this->redirect($redirect);
                } else {
                    Yii::info('Failed to save certificate, errors: ' . var_export($model->getErrors(), true));
                    $errorMessage = $model->getErrorSummary(false)[0] ?? Yii::t('app/user', 'Certificate create error');
                    // Поместить в сессию флаг сообщения об ошибке
                    Yii::$app->session->setFlash('error', $errorMessage);

                    // Перенаправить на страницу перенаправления
                    return $this->redirect($redirect);
                }
                return;
            } else {
                Yii::info('Certificate validation failed, errors: ' . var_export($form->getErrors(), true));
                // Поместить в сессию флаг сообщения об ошибке
                Yii::$app->session->setFlash('error', $form->getErrorsSummary(true));
            }
        }

        $redirect = $this->getRedirectPath($id);

        // Перенаправить на страницу перенаправления
        return $this->redirect($redirect);
    }

    public function actionCreateFromPreview()
    {
        $post = Yii::$app->request->post();
        $beneficiaries = json_decode($post['beneficiarySelectedInput']);
        if (!is_array($beneficiaries)) {
            $beneficiaries = [];
        }

        $userAuthCert = $post['UserAuthCert'];

        $model = new UserAuthCert([
            'userId' => $userAuthCert['userId'],
            'certificate' => $userAuthCert['certificate'],
            'fingerprint' => $userAuthCert['fingerprint'],
            'expiryDate' => $userAuthCert['expiryDate'],
        ]);

        $this->setAuthCertService();

        if ($this->authCertService->saveCertificate($model)) {
            // Поместить в сессию флаг сообщения об успешном создании сертификата
            Yii::$app->session->setFlash('success', \Yii::t('app/user', 'Certificate was created'));

            UserHelper::sendUserToSecurityOfficersAcceptance(Yii::$app->user->id);

            $id = $model->getPrimaryKey();

            foreach ($beneficiaries as $item) {
                $modelBeneficiary = new UserAuthCertBeneficiary();
                $modelBeneficiary->keyId = $id;
                $modelBeneficiary->terminalId = $item->terminalId;
                // Сохранить модель в БД
                $modelBeneficiary->save();
            }

            $redirect = $this->getRedirectPath($userAuthCert['userId'], true, $id);

            $count = UserAuthCert::find()->where(['userId' => $model->userId])->count();
            if ($count == 1) {
                $model->status = 'active';
                // Сохранить модель в БД
                $model->save();
            } else {
                if (count($beneficiaries) == 0) {
                    $model->status = 'inactive';
                    // Сохранить модель в БД
                    $model->save();
                } else {
                    $activeNoBeneficiaryCerts = UserAuthCert::findAll(['status' => 'active']);
                    foreach ($activeNoBeneficiaryCerts as $cert) {
                        $count = UserAuthCertBeneficiary::find()
                                    ->where(['keyId' => $cert->id])
                                    ->count();
                        if ($count == 0) {
                            $cert->status = 'inactive';
                            // Сохранить модель в БД
                            $cert->save();
                        }
                    }

                    $model->status = 'active';
                    // Сохранить модель в БД
                    $model->save();
                }
            }

            // Перенаправить на страницу перенаправления
            return $this->redirect($redirect);
        } else {
            Yii::info('Failed to save certificate, errors: ' . var_export($model->getErrors(), true));
            $errorMessage = $model->getErrorSummary(false)[0] ?? Yii::t('app/user', 'Certificate create error');
            // Поместить в сессию флаг сообщения об ошибке создания сертификата
            Yii::$app->session->setFlash('error', $errorMessage);

            $redirect = $this->getRedirectPath($userAuthCert['userId']);

            // Перенаправить на страницу перенаправления
            return $this->redirect($redirect);
        }
        return;
    }

    private function getKeyTerminalData($keyId = null, $userId = null)
    {
        $usedList = [];
        $selectedList = [];

        $certLinks = UserAuthCertBeneficiary::find()->all();
        // Если не указан id пользователя, получить его из активной сессии
        $userId = $userId ?: Yii::$app->user->identity->id;

        foreach($certLinks as $link) {
            $userAuthCert = UserAuthCert::findOne($link->keyId);
            if ($userAuthCert) {
                if ($link->keyId == $keyId) {
                    $selectedList[] = $link->terminalId;
                } else if ($userAuthCert->userId == $userId) {
                    $usedList[] = $link->terminalId;
                }
            }
        }

        // find beneficiaries by their certs
        $beneficiaries = Cert::findAll([
            'status' => Cert::STATUS_C10,
            'role' => Cert::ROLE_SIGNER_BOT
        ]);

        $beneficiarySelected = [];
        $beneficiaryAll = [];

        foreach ($beneficiaries as $beneficiary) {
            $terminalId = $beneficiary->terminalId->value;

            // get beneficiary name from participant bic
            $participantBIC = $beneficiary->terminalId->bic . $beneficiary->participantUnitCode;
            $bicDirParticipant = BICDirParticipant::find()
                ->where(['participantBIC' => $participantBIC])
                ->andWhere(['!=', 'type', BICDirParticipant::TYPE_PROVIDER])
                ->one();

            if (!$bicDirParticipant) {
                continue;
            }

            $title = $bicDirParticipant->name;
            // if beneficiary already linked to key, put it into selected list, else put into all list

            if (in_array($terminalId, $selectedList)) {
                $beneficiarySelected[] = ['terminalId' => $terminalId, 'title' => $title];
            }
            if (!in_array($terminalId, $usedList)) {
                $beneficiaryAll[] = ['terminalId' => $terminalId, 'title' => $title];
            }
        }
        return [
            'keyId' => $keyId,
            'beneficiarySelected' => json_encode($beneficiarySelected),
            'beneficiaryAll' => json_encode($beneficiaryAll),
        ];
    }

    public function actionSaveBeneficiaries($id)
    {
        $data = Yii::$app->request->post();
        $dataInput = json_decode($data['beneficiarySelectedInput']);

        UserAuthCertBeneficiary::deleteAll(['keyId' => $id]);

        $cert = UserAuthCert::findOne($id);

        if (count($dataInput) != 0) {
            foreach ($dataInput as $item) {
                $model = new UserAuthCertBeneficiary();
                $model->keyId = $id;
                $model->terminalId = $item->terminalId;
                // Сохранить модель в БД
                $model->save();
            }
            $cert->status = 'active';
            // Сохранить модель в БД
            $cert->save();

            $activeNoBeneficiaryCerts = UserAuthCert::findAll(['status' => 'active']);
            foreach ($activeNoBeneficiaryCerts as $cert) {
                $count = UserAuthCertBeneficiary::find()
                            ->where(['keyId' => $cert->id])
                            ->count();
                if ($count == 0) {
                    $cert->status = 'inactive';
                    // Сохранить модель в БД
                    $cert->save();
                }
            }
        } else {
            $cert->status = 'inactive';
            // Сохранить модель в БД
            $cert->save();
        }

        // Поместить в сессию флаг сообщения об успешном сохранении сертификата
        Yii::$app->session->setFlash('success', Yii::t('app/cert', 'Certificate data was successfully saved'));

        $redirect = $this->getRedirectPath($cert->userId);
        // Перенаправить на страницу перенаправления
        return $this->redirect($redirect);
    }

    private function setAuthCertService()
    {
        if ($this->authCertService === null) {
            $this->authCertService = new UserAuthCertService();
        }
    }
}
