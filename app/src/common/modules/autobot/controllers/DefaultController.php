<?php

namespace common\modules\autobot\controllers;

use common\base\Controller as BaseController;
use common\base\traits\ChecksTerminalAccess;
use common\models\Terminal;
use common\models\User;
use common\models\UserTerminal;
use common\modules\autobot\forms\CreateAutobotForm;
use common\modules\autobot\forms\ImportAutobotForm;
use common\modules\autobot\models\Autobot;
use common\modules\autobot\models\Controller;
use common\modules\autobot\services\AutobotService;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use ZipArchive;

class DefaultController extends BaseController
{
    use ChecksTerminalAccess;

    /**
     * @var AutobotService
     */
    private $autobotService;

    public function __construct($id, $module, $config = [], AutobotService $autobotService)
    {
        parent::__construct($id, $module, $config);
        $this->autobotService = $autobotService;
    }

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
                    'process-settings' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Детальный просмотр информации о ключе автобота по его id
     * @param int $id
     * @return string
     */
    public function actionView($id)
    {
        // Получить из БД ключ автоподписанта с указанным id
        $model = $this->findModel($id);
        $this->checkSettingsAdditionalAdmin($model);

        // Вывести страницу
        return $this->render('view', compact('model'));
    }

    public function actionCreate($controllerId)
    {
        $controller = Controller::findOne($controllerId);
        if ($controller === null) {
            throw new NotFoundHttpException();
        }

        $this->ensureUserHasTerminalAccess($controller->terminalId);

        if ($controller->isUpdateRequired) {
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Please, fill in all required fields in controller settings'));
            
            // Перенаправить на страницу автоподписанта
            return $this->redirect(['/autobot/terminals/index', 'id' => $controller->terminalId, 'tabMode' => 'tabAutobot']);
        }

        $form = new CreateAutobotForm();
        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->autobotService->createWithNewKey($controller, $form->password);
                // Поместить в сессию флаг сообщения об успешном создании ключа
                Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Key created'));
                // Перенаправить на страницу автоподписанта
                return $this->redirect(['/autobot/terminals/index', 'id' => $controller->terminalId, 'tabMode' => 'tabAutobot']);
            } catch (\DomainException $exception) {
                // Поместить в сессию флаг сообщения об ошибке
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->renderAjax('_createModal', ['model' => $form]);
    }

    public function actionImport($controllerId)
    {
        $controller = Controller::findOne($controllerId);
        if ($controller === null) {
            throw new NotFoundHttpException();
        }
        $this->ensureUserHasTerminalAccess($controller->terminalId);

        if ($controller->isUpdateRequired) {
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Please, fill in all required fields in controller settings'));
            // Перенаправить на страницу автоподписанта
            return $this->redirect(['/autobot/terminals/index', 'id' => $controller->terminalId, 'tabMode' => 'tabAutobot']);
        }

        $form = new ImportAutobotForm();
        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->autobotService->createWithImportedKey($controller, $form);
                // Поместить в сессию флаг сообщения об успешном импорте ключа
                Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Key imported'));
                // Перенаправить на страницу автоподписанта
                return $this->redirect(['/autobot/terminals/index', 'id' => $controller->terminalId, 'tabMode' => 'tabAutobot']);
            } catch (\DomainException $exception) {
                // Поместить в сессию флаг сообщения об ошибке
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->renderAjax('_importModal', ['model' => $form]);
    }

    public function actionProcessSettings($id)
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Получить из БД ключ автоподписанта
        $model = $this->findModel($id);

        // Редактирование ключа только в статусе неактивен
        if (!$model->isBlocked) {
            // Поместить в сессию флаг сообщения о недоступности редактирования ключа
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Key editing is not available in the current status'));
            // Перенаправить на страницу просмотра
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $this->checkSettingsAdditionalAdmin($model);

        $result = [];

        // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Зарегистрировать событие изменения ключа контроллера в модуле мониторинга
            Yii::$app->monitoring->extUserLog('EditControllerKey', ['id' => $model->id]);
            $result['status'] = 'ok';
        } else {
            $result['status'] = 'error';
            $result['content'] = $this->getSettingsForm($model);
        }

        return $result;
    }

    /**
     * Удаление ключа автобота по его id
     * @param int $id
     * @return string
     */
    public function actionDelete($id)
    {
        // Получить из БД ключ автоподписанта
        $autobot = $this->findModel($id);
        $this->checkSettingsAdditionalAdmin($autobot);

        try {
            // Удалить автоподписанта из БД
            $this->autobotService->delete($autobot);
            Yii::$app->session->set('success', Yii::t('app/terminal', 'Deleted controller key "{name}"', ['name' => $autobot->name]));
            // Зарегистрировать событие в модуле мониторинга
            Yii::$app->monitoring->extUserLog('DeleteControllerKey', ['id' => $id]);
        } catch (\Exception $exception) {
            Yii::$app->errorHandler->logException($exception);
            $errorMessage = $exception instanceof \DomainException
                ? $exception->getMessage()
                : Yii::t('app/autobot', 'Failed to delete controller key');
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', $errorMessage);
        }
        // Перенаправить на страницу автоподписанта
        return $this->redirect($autobot->getListUrl());
    }

    /**
     * Выгрузка файла сертификата
     */
    public function actionDownload($id)
    {
        // Получить из БД ключ автоподписанта
        $model = $this->findModel($id);
        $this->checkSettingsAdditionalAdmin($model);

        // Формируем ответ
        if ($model) {
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_RAW;
            $response->sendContentAsFile(
                $model->certificate,
                $model->controller->terminal->terminalId . '-' . $model->fingerprint . '.crt',
                ['mimeType' => 'application/x-x509-ca-cert']
            );
        } else {
            throw new BadRequestHttpException(Yii::t('app', "Can't send file"));
        }
    }

    public function actionDownloadArchive($id)
    {
        // Получить из БД ключ автоподписанта
        $model = $this->findModel($id);
        $this->checkSettingsAdditionalAdmin($model);

        // Формируем ответ
        $formatedDate = date('Ymd', strtotime($model->expirationDate));

        if ($model->ownerSurname && $model->ownerName) {
            $basename = "{$model->ownerSurname}-{$model->ownerName}-";
        } else if ($model->ownerSurname) {
            $basename = "{$model->ownerSurname}-";
        } else if ($model->ownerName) {
            $basename = "{$model->ownerName}-";
        } else {
            $basename = '';
        }

        $basename .= $formatedDate;

        $privateKeyFilename = $basename . '-private-key.key';
        $publicKeyFilename = $basename . '-public-key.pub';
        $certFilename = $basename . '-cert.pem';
        $zipFilename = $basename . '.zip';

        $zipTempFilename = Yii::getAlias('@temp/' . time() . '.zip');

        $zip = new ZipArchive();
        $zip->open($zipTempFilename, ZipArchive::CREATE);
        $zip->addFromString($privateKeyFilename, $model->privateKey);
        $zip->addFromString($publicKeyFilename, $model->publicKey);
        $zip->addFromString($certFilename, $model->certificate);
        $zip->close();

        $data = file_get_contents($zipTempFilename);

        unlink($zipTempFilename);

        $filename = $zipFilename;

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->sendContentAsFile($data, $filename);
    }

    public function actionControl()
    {
        $terminals = Yii::$app->exchange;
        $activeTab = null;

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Имя управляемого терминала
            $action = Yii::$app->request->post('action');
            // Имя управляемого терминала
            $terminalId = Yii::$app->request->post('terminalId');
            // Контролируем активную закладку
            $activeTab = $terminalId;

            if ('start' === $action) {
                // Запуск автопроцессов
                if (false === $terminals->isRunning($terminalId)) {
                    $this->start($terminalId);
                } else {
                    // Поместить в сессию флаг сообщения о запущенном процессе
                    Yii::$app->session->setFlash(
                        'error',
                        Yii::t('app/autobot', 'Automatic process already started for terminal {terminal}', ['terminal' => $terminalId])
                    );
                }
            } else if ('stop' === $action) {
                // Останов автопроцессов
                if (true === $terminals->isRunning($terminalId)) {
                    $this->stop($terminalId);

                    // Зарегистрировать событие остановки обмена с CyberFT в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('StopAutoprocesses');
                } else {
                    // Поместить в сессию флаг сообщения об остановленном процессе
                    Yii::$app->session->setFlash(
                        'error',
                        Yii::t('app/autobot', 'Automatic process already stopped for terminal {terminal}', ['terminal' => $terminalId])
                    );
                }
            }
        }

        // Перенаправить на страницу автопроцессов
        return $this->redirect(['autoprocesses', 'activeTab' => $activeTab]);
    }

    protected function start($terminalId)
    {
        $terminals = Yii::$app->exchange;

        // Подсчитываем число автоботов, участвовавших в запросе
        $count = count(Yii::$app->request->post('Autobot', []));
        // Готовим массив моделей автоботов для загрузки данных из запроса
        $autobots = [];
        for ($i = 0; $i < $count; $i++) {
            $newAutobot = new Autobot();
            $newAutobot->setScenario('control');
            $autobots[] = $newAutobot;
        }
        // Грузим полученные данные автоботов
        Model::loadMultiple($autobots, Yii::$app->request->post());
        // Получаем массив паролей, введенных в grid-форму
        $passwords = Yii::$app->request->post('keyPassword');

        // Формируем вектор id автоботов и паролей для последующей проверки
        $keys = [];
        for ($i = 0; $i < $count; $i++) {
            $keys[$autobots[$i]->id] = $passwords[$i];
        }
        // Верифицируем все пароли при помощи компонента Terminals
        try {
            if (true === $terminals->verifyKeyPasswords($terminalId, $keys)) {
                $terminals->start($terminalId, $keys);

                // Поместить в сессию флаг сообщения о запуске процесса
                Yii::$app->session->setFlash(
                    'success',
                    Yii::t('app/autobot', 'Automatic process started for terminal {terminal}', ['terminal' => $terminalId])
                );

                // Зарегистрировать событие запуска обмена с CyberFT в модуле мониторинга
                Yii::$app->monitoring->extUserLog('StartAutoprocesses');
            }
        } catch (Exception $ex) {
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', $ex->getMessage());
        }
    }

    protected function stop($terminalId)
    {
        $terminals = Yii::$app->exchange;

        $terminals->stop($terminalId);

        // Поместить в сессию флаг сообщения об остановке процесса
        Yii::$app->session->setFlash(
            'success',
            Yii::t('app/autobot', "Automatic process stopped for terminal {terminal}", ['terminal' => $terminalId])
        );
    }

    /*
     * Функция получает список
     * доступных типов ключей контроллера
     * для указанного терминала
     */
    public function actionGetKeysTypes($terminalId)
    {
        // Здесь обрабатываются только get ajax запросы
        if (Yii::$app->request->isAjax && Yii::$app->request->isGet) {

            // Ищем терминал по его TerminalId
            $terminal = Terminal::findOne(['terminalId' => $terminalId]);

            if (!$terminal) {
                // Если указанный терминал не найден, то завершаем процедуру
                return json_encode([]);
            }

            // Массив с доступными типами ключей для терминала
            $typesKeys = [];

            // Массив с типами ключей
            // для терминала и их название
            $typesKeysAutobot = [
                Autobot::AUTOBOT_PRIMARY => Yii::t('app/autobot', 'Primary key'),
                Autobot::AUTOBOT_ADDITIONAL => Yii::t('app/autobot', 'Additional key')
            ];

            // Проверяем у указанного терминала
            // наличие основного ключа контролера
            $keys = Autobot::find()
                ->joinWith('controller.terminal')
                ->where([
                    'terminal.terminalId' => $terminalId,
                    'primary' => Autobot::AUTOBOT_PRIMARY
                ])
                ->all();

            // Если у терминала не найден основной ключ,
            // значит ему для выбора типа доступен ТОЛЬКО он
            if (empty($keys)) {
                $typesKeys[Autobot::AUTOBOT_PRIMARY] =  $typesKeysAutobot[Autobot::AUTOBOT_PRIMARY];
            } else {
                // Если основной ключ найден,
                // то доступен только дополнительный ключ
                $typesKeys[Autobot::AUTOBOT_ADDITIONAL] =  $typesKeysAutobot[Autobot::AUTOBOT_ADDITIONAL];
            }

            // Возвращаем сериализованный массив с типами ключей контролера
            return json_encode($typesKeys);
        }
    }

    /**
     * Получение списка терминалов
     * для отображения в формах
     * управления ключами
     */
    private function getTerminalsList()
    {
        $terminals = [];
        // Получить модель пользователя из активной сессии
        $adminIdentity = Yii::$app->user->identity;
        $terminalsList = UserTerminal::getUserTerminalIds($adminIdentity->id);

        foreach (Yii::$app->exchange->addresses as $terminalId) {
            // Для доп. админа отображаем информацию только о доступных ему терминалах
            if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN
                    && !in_array($terminalId, $terminalsList)
            ) {
                continue;
            }

            // Формируем наименование терминала
            // Получаем наименование из локального справочника терминалов
            $terminalName = $terminalId;

            $terminal = Terminal::findOne(['terminalId' => $terminalId]);

            if ($terminal && $terminal->title) {
                $terminalName .= ' (' . $terminal->title . ')';
            }

            $terminals[$terminalId] = $terminalName;
        }

        return $terminals;
    }


    public function actionActivateAutobot()
    {
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        $status = 'error';
        $msg = '';

        $password = Yii::$app->request->post('password');
        $keyId = Yii::$app->request->post('keyId');

        if (empty($password)) {
            $msg = Yii::t('app/cert', 'Password is empty');
        } else if (empty($keyId)) {
            $msg = 'Ошибка определения ключа контролера';
        } else {
            // Получить из БД ключ автоподписанта
            $autobot = $this->findModel($keyId);
            $result = $autobot->isCorrectPassword($password);

            if ($result) {
                $autobot->activate($password);
                $status = 'ok';
            } else {
                $msg = 'Неправильный пароль ключа';
            }
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($status == 'ok') {
            // Поместить в сессию флаг сообщения об успешной активации ключа
            Yii::$app->session->setFlash('success', 'Ключ успешно активирован');
        }

        return ['status' => $status, 'msg' => $msg];
    }

    public function actionBlock($id)
    {
        // Получить из БД ключ автоподписанта
        $model = $this->findModel($id);
        try {
            $this->autobotService->block($model);
            Yii::$app->session->set('success', Yii::t('app/autobot', 'Key is successfully blocked'));
        } catch (\Exception $exception) {
            Yii::$app->errorHandler->logException($exception);
            $errorMessage = $exception instanceof \DomainException
                ? $exception->getMessage()
                : Yii::t('app/autobot', 'Failed to block controller key');
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', $errorMessage);
        }
        
        // Перенаправить на предыдущую страницу
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUseForSigning($id)
    {
        // Получить из БД ключ автоподписанта
        $model = $this->findModel($id);

        if ($model->isExpired()) {
            // Поместить в сессию флаг сообщения об истёкшем ключе
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Expired key is not allowed to be used for signing'));
        } else if ($model->primary == false) {
            // Поместить в сессию флаг сообщения о недоступности опции для ключей автоподписания
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'This option is not available for auto-signing keys'));
        } else {
            $model->useForSigning();
        }

        // Перенаправить на предыдущую страницу
        return $this->redirect(Yii::$app->request->referrer);
    }

    private function getSettingsForm($model)
    {
        // Список терминалов для выбора
        $terminals = $this->getTerminalsList();

        $users = User::findAll(['role' => User::ROLE_CONTROLLER]);
        $userList = ArrayHelper::merge([null => ''], ArrayHelper::map($users, 'id', 'name'));

        return $this->renderAjax('settings', [
            'userList' => $userList,
            'model' => $model,
            'terminalIds' => $terminals,
        ]);
    }

    private function checkSettingsAdditionalAdmin($model)
    {
        // Получить модель пользователя из активной сессии
        $adminIdentity = Yii::$app->user->identity;
        $terminalsList = UserTerminal::getUserTerminalIds($adminIdentity->id);

        // Для доп. админа проверяем доступность редактирования ключа

        // Если ключ относится к недоступному доп. админу терминалу
        if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN
            && array_search($model->terminalId, $terminalsList) === false
        ) {
            throw new ForbiddenHttpException();
        }
    }

    /**
     * Метод ищет модель автоподписанта в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Найти автоподписанта в БД по указанному id
        if (($model = Autobot::findOne($id)) !== null) {
            $this->ensureUserHasTerminalAccess($model->controller->terminal->terminalId);
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }
}
