<?php

namespace common\modules\certManager\controllers;

use common\base\Controller;
use common\base\traits\ChecksTerminalAccess;
use common\models\User;
use common\models\UserAuthCert;
use common\modules\autobot\models\Autobot;
use common\modules\certManager\models\Cert;
use common\modules\certManager\models\CertAcknowledgementActForm;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class AcknowledgementActController extends Controller
{
    use ChecksTerminalAccess;

    public function actionCreate($certId = null, $autobotId = null, $userAuthCertId = null)
    {
        $this->ensureUserHasAccess($certId, $autobotId, $userAuthCertId);
        $model = $this->buildForm($certId, $autobotId, $userAuthCertId);

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Загрузить данные модели из формы в браузере
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $isGenerated = $model->generateAct();
                if ($isGenerated) {
                    if ($autobotId) {
                        $model->saveControllerActData();
                    }

                    Yii::$app->response->sendContentAsFile(
                        $model->getActFileContent(),
                        $model->getActFileName()
                    );

                    return null;
                } else {
                    // Поместить в сессию флаг сообщения об ошибке
                    Yii::$app->session->setFlash('error', 'Не удалось создать файл акта');
                }
            }
        }

        return $this->render('create', compact('model'));
    }

    private function buildForm($certId, $autobotId, $userAuthCertId): CertAcknowledgementActForm
    {
        if ($certId) {
            return CertAcknowledgementActForm::buildForCert($certId);
        } else if ($autobotId) {
            return CertAcknowledgementActForm::buildForAutobot($autobotId, Yii::$app->user->identity);
        } else if ($userAuthCertId) {
            return CertAcknowledgementActForm::buildForUserAuthCert($userAuthCertId);
        }
        throw new \InvalidArgumentException('Neither certId nor autobotId nor userCertId is provided');
    }

    private function ensureUserHasAccess($certId, $autobotId, $userAuthCertId)
    {
        if ($certId) {
            $cert = Cert::findOne($certId);
            $this->ensureUserHasTerminalAccess($cert->getTerminalAddress());
        }

        if ($autobotId) {
            $autobot = Autobot::findOne($autobotId);
            $this->ensureUserHasTerminalAccess($autobot->controller->terminal->terminalId);
        }

        if ($userAuthCertId) {
            // Получить модель пользователя из активной сессии
            $user = Yii::$app->user->identity;
            if ($user->role == User::ROLE_ADMIN) {
                return;
            }
            $userAuthCert = UserAuthCert::findOne($userAuthCertId);
            if ($userAuthCert === null) {
                throw new NotFoundHttpException();
            }
            if ($userAuthCert->userId == $user->id) {
                return;
            }
            if ($user->role == User::ROLE_ADMIN && $userAuthCert->user->ownerId == $user->id) {
                return;
            }
            throw new ForbiddenHttpException();
        }
    }
}
