<?php
namespace common\modules\monitor\controllers;

use common\base\Controller;
use common\helpers\CertsHelper;
use common\helpers\MonitorLogHelper;
use common\models\UserTerminal;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;
use Swift_TransportException;
use common\models\User;
use common\base\DynamicModel;
use yii\web\ForbiddenHttpException;
use common\models\Terminal;
use yii\web\NotFoundHttpException;

class NotificationsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $tabMode = Yii::$app->request->get('tabMode');

        $data = [];

        if (!$tabMode || $tabMode == 'tabNotificationsSettings') {
            // Данные по настройкам оповещений
            $data = $this->notificationSettingsData();
        } else if ($tabMode == 'tabMailNotificationsSettings') {
            // Данные по настройкам email-оповещений
            $data = $this->mailNotificationsSettingsData();
        } else if ($tabMode == 'tabFinZipEmailImportSettings') {
            $data = $this->finZipEmailImportSettingsData();
        }

        // Вывести страницу
        return $this->render('index', ['params' => $data]);
    }

    /**
     * Данные по настройкам оповещений
     */
    protected function notificationSettingsData()
    {
        $data = [];
        $checkers = [];

        foreach($this->module->checkers as $checkerCode) {
            $checker = $this->module->getChecker($checkerCode);
            $checker->loadData();
            $checkers[$checker->code] = $checker;
        }

        $data['checkerDataProvider'] =  new ArrayDataProvider(['allModels' => $checkers]);

        return $data;
    }

    /**
     * Действие обновления настроек оповещений
     */
    public function actionNotificationsUpdate()
    {
        $post = Yii::$app->request->post();

        foreach($this->module->checkers as $checkerCode) {
            $checker = $this->module->getChecker($checkerCode);
            $checker->loadData();
            // Сохранить модель в БД
            $checker->save();

            // Запись настроек чекера
            MonitorLogHelper::saveCheckerSettings($checker, $post);
        }

        // Зарегистрировать событие изменения настроек оповещений в модуле мониторинга
        Yii::$app->monitoring->extUserLog('EditNotifySettings');

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Данные по настройкам email-оповещений
     */
    protected function mailNotificationsSettingsData()
    {
        $data = [];
        $settings = Yii::$app->settings->get('monitor:Notification');

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Загрузить данные модели из формы в браузере
            $settings->load(Yii::$app->request->post());
            if ($settings->validate()) {
                $settings->save();
                // Зарегистрировать событие изменения настроек почтового сервера для оповещений в модуле мониторинга
                Yii::$app->monitoring->extUserLog('EditMailNotifySettings');

                if (Yii::$app->request->post('testNow') == 1) {

                    $testAddress = Yii::$app->request->post('testAddress');
                    $emailValidate = (new EmailValidator())->validate($testAddress);

                    if ($emailValidate === true) {

                        $msgData = [
                            'subject' => Yii::t('monitor/mailer', 'Test message'),
                            'view' => '@common/modules/monitor/views/mailer/test'
                        ];

                        $result = false;

                        try {
                            $result = Yii::$app->mailNotifier->sendMessage($msgData, [$testAddress]);
                        } catch(Swift_TransportException $ex) {
                            // Поместить в сессию флаг сообщения об ошибке
                            Yii::$app->session->setFlash('error', $ex->getMessage());
                        }

                        if ($result) {
                            // Поместить в сессию флаг сообщения об успешно отправленном тестовом сообщении
                            Yii::$app->session->setFlash(
                                'success',
                                Yii::t('monitor/mailer', 'Test message has been sent to the provided address')
                            );
                        } else {
                            // Поместить в сессию флаг сообщения об ошибке отправки тестового сообщения
                            Yii::$app->session->addFlash(
                                'error',
                                Yii::t('monitor/mailer', 'Could not sent test message - please check mail settings')
                            );
                        }
                    } else {
                        // Поместить в сессию флаг сообщения об ошибке отправки тестового сообщения
                        Yii::$app->session->addFlash(
                            'error',
                            Yii::t('monitor/mailer', 'Could not sent test message - please check test address email')
                        );
                    }
                }
            }
        }

        $data['model'] = $settings;

        return $data;
    }

    /**
     * Данные по списку рассылок оповещений
     */
    protected function mailingSettingsData($checkerType, $terminalId = null)
    {
        if ($terminalId) {
            $terminal = $this->findTerminal($terminalId);
            $settings = Yii::$app->settings->get('monitor:Notification', $terminal->terminalId);

            $userIdsByTerminal = UserTerminal::getUsers($terminalId);
            $users = User::findAll($userIdsByTerminal);
        } else {
            $settings = Yii::$app->settings->get('monitor:Notification');
            $users = User::find()->all();
        }

        $data = [];
        $dropDownData        = [];
        $substituteEmailData = [];
        $userEmails = [];

        // addressList format: id => email
        $addressList = $settings->addressList;

        // Список используемых адресов получателей для события
        $usedUsersByType = [];
        if (isset($addressList[$checkerType])) {
            $usedUsersByType = $addressList[$checkerType];
        }

        // Формирование списка доступных
        // пользователей для выбора из базы
        foreach($users as $user) {
            if (!array_key_exists($user->email, $usedUsersByType)) {
                $name = $user->getName();
                $dropDownData[$user->id] = $name;
                $substituteEmailData[$user->id] = $user->email;
            }
        }

        foreach($usedUsersByType as $email => $name) {
            $modelData = [
                'email' => $email
            ];
            $modelData['name'] = is_array($name) ? $name['name'] : $name;
            $userEmails[] = new DynamicModel($modelData);
        }

        $data['usedUsers'] = new ArrayDataProvider(['allModels' => $userEmails]);
        $data['dropDownData'] = $dropDownData;
        $data['substituteEmailData'] = $substituteEmailData;
        $data['checkerType'] = $checkerType;
        $data['terminalId'] = $terminalId;

        return $data;
    }

    private function finZipEmailImportSettingsData()
    {
        $module = \Yii::$app->getModule('finzip');
        $settings = $module->settings;

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Загрузить данные модели из формы в браузере
            $settings->load(Yii::$app->request->post());
            if ($settings->validate() && $settings->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении настроек
                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Settings saved'));
            }
        }

        $senderTerminalsSelectOptions = ArrayHelper::map(
            Terminal::find()
                ->where(['status' => Terminal::STATUS_ACTIVE])
                ->select('terminalId')
                ->asArray()
                ->all(),
            'terminalId',
            'terminalId'
        );

        $receiverTerminalsSelectOptions = ArrayHelper::map(
            CertsHelper::getCerts(null),
            'terminal',
            'terminal'
        );

        return [
            'model' => $settings,
            'senderTerminalsSelectOptions' => $senderTerminalsSelectOptions,
            'receiverTerminalsSelectOptions' => $receiverTerminalsSelectOptions,
        ];
    }

    /**
     * Удаление получателя из списка рассылки
     */
    public function actionMailingUserDelete($email, $checkerType, $terminalId = null)
    {
        if ($terminalId) {
            $terminal = $this->findTerminal($terminalId);
            $settings = Yii::$app->settings->get('monitor:Notification', $terminal->terminalId);
        } else {
            $settings = Yii::$app->settings->get('monitor:Notification');
        }

        $addressList = $settings->addressList;
        if (isset($addressList[$checkerType])) {
            if (array_key_exists($email, $addressList[$checkerType])) {
                unset($addressList[$checkerType][$email]);
                $settings->addressList = $addressList;
                $settings->save();
            }
        }

        $data = $this->mailingSettingsData($checkerType, $terminalId);

        // Получение заголовка события оповещения
        return $this->renderAjax('_mailingSettings', ['data' => $data]);
    }

    /**
     * Получение содержимого для модального
     * окна управления получателями уведомлений
     * @param $checkerType
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionGetMailingSettingsModal($checkerType, $terminalId)
    {
        if (!Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException;
        }

        $data = $this->mailingSettingsData($checkerType, $terminalId);

        return $this->renderAjax('_mailingSettings', ['data' => $data]);
    }

    /**
     * Добавление пользователя в получатели уведомлений по событию
     */
    public function actionMailingUserAdd()
    {
        $userName = Yii::$app->request->post('addUserName');
        $email  = Yii::$app->request->post('addUserEmail');
        $checkerType  = Yii::$app->request->post('addUserCheckerType');
        $terminalId  = Yii::$app->request->post('addUserTerminalId');

        if ($terminalId) {
            $terminal = $this->findTerminal($terminalId);
            $settings = Yii::$app->settings->get('monitor:Notification', $terminal->terminalId);
        } else {
            $settings = Yii::$app->settings->get('monitor:Notification');
        }

        $userId = Yii::$app->request->post('selectUserId');

        if (!empty($userName) && !empty($email)) {
            /**
             * @todo get userdropdown id
             */
            if ($userId) {
                $settings->addressList[$checkerType][$email] = ['userId' => $userId, 'name' => $userName];
            } else {
                $settings->addressList[$checkerType][$email] = $userName;
            }

            if ($settings->save()) {
                // Зарегистрировать событие изменения настроек рассылки в модуле мониторинга
                Yii::$app->monitoring->extUserLog('EditMailListSettings');
            }
        }

        $data = $this->mailingSettingsData($checkerType, $terminalId);

        return $this->renderAjax('_mailingSettings', ['data' => $data]);
    }

    protected function findTerminal($id)
    {
        // Получить из БД терминал с указанным id
        $model = Terminal::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist');
        }
        return $model;
    }

}
