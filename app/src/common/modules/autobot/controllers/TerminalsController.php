<?php

namespace common\modules\autobot\controllers;

use backend\models\search\UserSearch;
use common\base\Controller;
use common\base\traits\ChecksTerminalAccess;
use common\helpers\Address;
use common\helpers\FileHelper;
use common\models\CryptoproKeySearch;
use common\models\Terminal;
use common\models\TerminalRemoteId;
use common\modules\autobot\models\Controller as Controller2;
use common\modules\certManager\models\Cert;
use common\modules\participant\models\BICDirParticipant;
use common\settings\AppSettings;
use common\settings\ExportSettings;
use common\settings\Sbbol2IntegrationSettings;
use common\settings\VTBIntegrationSettings;
use InvalidArgumentException;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

class TerminalsController extends Controller
{
    use TerminalsExchange;
    use SigningSettings;
    use AdditionalSettings;
    use NotificationsSettings;
    use ApiIntegrationSettings;
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

    public function actionIndex($id)
    {
        $terminal = Terminal::findOne($id);

        if (!$terminal) {
            throw new NotFoundHttpException;
        }

        $this->ensureUserHasTerminalAccess($terminal->id);

        $tabMode = Yii::$app->request->get('tabMode');

        $data = [];

        if (!$tabMode || $tabMode == 'tabTerminal') {
            // Данные по настройкам терминала
            $data = $this->terminalData($terminal);
        } else if ($tabMode == 'tabAutobot') {
            // Данные по ключам контролера
            $data = $this->autobotData($terminal);
        } else if ($tabMode == 'tabSigning') {
            // Настройки подписания документов
            $data = $this->signingData($terminal);
        } else if ($tabMode == 'tabExportXml') {
            // Настройки экспорта документов
            $data = $this->exportXmlData($terminal);
        } else if ($tabMode == 'tabUsers') {
            // Список пользователей, привязанных к терминалу
            $data = $this->usersData($terminal);
        } else if ($tabMode == 'tabAdditionalSettings') {
            // Дополнительные настройки
            $data = $this->additionalSettingsData($terminal);
        } else if ($tabMode == 'tabNotifications') {
            // Настройки оповещений
            $data = $this->notificationSettingsData($terminal);
        } else if ($tabMode == 'tabVTBIntegration') {
            $data = $this->vtbIntegrationSettings($terminal);
        } else if ($tabMode == 'tabSbbol2Integration') {
            $data = $this->sbbol2IntegrationSettings($terminal);
        } else if ($tabMode == 'tabApiIntegration') {
            $data = $this->apiIntegrationSettings($terminal);
        }

        // Вывести страницу
        return $this->render('index', [
            'params' => $data,
            'terminal' => $terminal
        ]);
    }

    public function actionCreate()
    {
        $model = new Terminal();

        // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Зарегистрировать событие создания нового терминала в модуле мониторинга
            Yii::$app->monitoring->extUserLog('CreateTerminal', ['id' => $model->id]);

            // Перенаправить на страницу индекса
            return $this->redirect(['/autobot/terminals/index', 'id' => $model->id]);
        } else {
            // Вывести страницу
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        // Получить из БД терминал
        $model = $this->findModel($id);

        // Удалить терминал из БД
        if (!$model->delete()) {
            if (isset($model->errors['terminalDelete'])) {
                // Преобразовать ошибки удаления терминала в удобный для вывода вид
                $errorsTitle = Yii::t('app/terminal', 'Deleting terminal failed');
                $errors = implode('<br/>', $model->errors['terminalDelete']);
                // Поместить в сессию флаг сообщения об ошибках модели
                Yii::$app->session->addFlash('error', $errorsTitle . '<br/>' . $errors);
            } else {
                // Поместить в сессию флаг сообщения об ошибке удаления терминала
                Yii::$app->session->addFlash(
                    'error',
                    Yii::t('app/terminal', 'Error occurred while deleting terminal: {error}', [
                        'error' => join(PHP_EOL, $model->getFirstErrors())
                    ])
                );
            }

            // Перенаправить на страницу индекса
            return $this->redirect(['/autobot/terminals/index', 'id' => $id]);
        }

        // Поместить в сессию флаг сообщения об успешном удалении терминала
        Yii::$app->session->addFlash('success', Yii::t('app/terminal', 'Terminal was successfully deleted'));
        // Перенаправить на страницу индекса мультипроцессов
        return $this->redirect(['/autobot/multiprocesses/index']);
    }

    /**
     * Получение списка терминалов-получателей
     * для взаимодействия с терминалом-отправителем
     * @param $terminal
     * @return array
     */
    protected function getTerminalsReceivers($terminal)
    {
        $terminalsReceivers = [];
        $existedReceivers = [];

        // Проверка текущих кодов для терминала
        $remoteIds = $terminal->remoteIds;

        foreach($remoteIds as $remoteId) {
            if ($remoteId->terminalReceiver) {
                $existedReceivers[] = $remoteId->terminalReceiver;
            }
        }

        // Если получателей нет, но список кодов не пустой,
        // считаем, что один код добавлен для всех получателей
        if (empty($existedReceivers) && !empty($remoteIds)) {
            return $terminalsReceivers;
        }

        $certs = Cert::getAutobotCerts();

        foreach($certs as $cert) {
            $terminalId = (string) $cert->terminalId;

            // Если код для получателя уже добавлен, пропускаем его
            if (in_array($terminalId, $existedReceivers)) {
                continue;
            }

            $fixedAddress = Address::truncateAddress($terminalId);
            $participant = BICDirParticipant::findOne(['participantBIC' => $fixedAddress]);

            if ($participant) {
                $terminalTitle = $terminalId . ' (' . $participant->name . ')';
            } else {
                $terminalTitle = $terminalId;
            }

            $terminalsReceivers[$terminalId] = $terminalTitle;
        }

        return $terminalsReceivers;
    }

    public function actionTerminalAddRemoteId()
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        $terminalId = Yii::$app->request->post('terminalId');
        $receiver = Yii::$app->request->post('receiver');
        $remoteId = Yii::$app->request->post('remoteId');

        if (empty($terminalId) || empty($receiver) || empty($remoteId)) {
            throw new InvalidArgumentException;
        }

        // Запись кода
        $terminalRemoteId = new TerminalRemoteId;
        $terminalRemoteId->terminalId = $terminalId;
        $terminalRemoteId->terminalReceiver = $receiver;
        $terminalRemoteId->remoteId = $remoteId;

        // Если модель успешно сохранена в БД
        if ($terminalRemoteId->save()) {
            // Обновление отображение
            $html = $this->renderRemoteIds($terminalId);
            return $html;
        } else {
            throw new InvalidArgumentException;
        }
    }

    public function actionTerminalDeleteRemoteId()
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        $terminal = Yii::$app->request->post('terminal');
        $receiver = Yii::$app->request->post('receiver');

        if (empty($terminal)) {
            throw new InvalidArgumentException;
        }

        if (empty($receiver)) {
            $receiver = null;
        }

        $terminalRemoteId = TerminalRemoteId::findOne(['terminalId' => $terminal, 'terminalReceiver' => $receiver]);

        // Удалить удалённый терминал из БД
        if ($terminalRemoteId && $terminalRemoteId->delete()) {
            // Обновление отображение
            $html = $this->renderRemoteIds($terminal);
            return $html;
        } else {
            throw new InvalidArgumentException;
        }
    }

    protected function renderRemoteIds($terminalId)
    {
        $terminal = Terminal::findOne($terminalId);

        if (!$terminal) {
            throw new InvalidArgumentException;
        }

        $data['model'] = $terminal;
        $data['remoteIds'] = $terminal->getRemoteIds();
        $data['terminalsReceivers'] = $this->getTerminalsReceivers($terminal);

        return $this->renderAjax('/terminals/_terminalRemoteIds', compact('data'));
    }

    /**
     * Данные по ключам контролера
     */
    protected function autobotData($terminal)
    {
        $controllers = Controller2::find()
            ->with('autobots')
            ->where(['terminalId' => $terminal->id])
            ->all();

        ArrayHelper::multisort(
            $controllers,
            ['isUsedForSigning', 'lastName', 'firstName', 'middleName'],
            [SORT_DESC, SORT_ASC, SORT_ASC, SORT_ASC]
        );

        return [
            'controllers' => $controllers,
            'terminalId'  => $terminal->id,
        ];
    }

    /**
     * Список пользователей, привязанных к терминалу
     */
    protected function usersData($terminal)
    {
        $data = [];

        $queryParams = Yii::$app->request->queryParams;
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchByTerminal($queryParams, $terminal->id);

        $data['searchModel'] = $searchModel;
        $data['dataProvider'] = $dataProvider;
        $data['terminalId'] = $terminal->id;

        return $data;
    }

    /**
     * Данные по настройкам терминала
     */
    protected function terminalData($terminal)
    {
        $data = [];

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД 
            if ($terminal->load(Yii::$app->request->post()) && $terminal->save()) {
                // Зарегистрировать событие изменения настроек терминала в модуле мониторинга
                Yii::$app->monitoring->extUserLog('EditTerminal', ['id' => $terminal->id]);
            }
        }

        $data['model'] = $terminal;
        $data['remoteIds'] = $terminal->getRemoteIds();
        $data['terminalsReceivers'] = $this->getTerminalsReceivers($terminal);

        return $data;
    }

    /**
     * Данные по настройкам экспорта документов
     */
    protected function exportXmlData($terminal)
    {
        $data = [];

        $addons = Yii::$app->addon->getRegisteredAddons();

        $terminalId = $terminal->terminalId;

        /** @var ExportSettings $exportSettings */
        $exportSettings = Yii::$app->settings->get('export', $terminalId);

        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app', $terminalId);

        // Сохранение настроек
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Загрузить данные модели из формы в браузере
            $appSettings->load(Yii::$app->request->post());
            // Сохранить модель в БД
            $appSettings->save();

            if (!$appSettings->useGlobalExportSettings) {
                $exportSettingsParams = Yii::$app->request->post('ExportSettings');
                $exportXml = Yii::$app->request->post('exportXml');

                // Определение флагов, которые включены
                // остальные считаем выключенными
                foreach ($addons as $serviceId => $addon) {
                    $moduleSetting = $serviceId . "ExportXml";

                    if ($exportXml && in_array($serviceId, $exportXml)) {
                        $exportSettings->$moduleSetting = true;
                    } else {
                        $exportSettings->$moduleSetting = false;
                    }
                }

                // Сохраняем настройки
                $exportSettings->useSwiftfinFormat = $exportSettingsParams['useSwiftfinFormat'];
                $exportSettings->exportStatusReports = array_key_exists(
                        'exportStatusReports',
                        $exportSettingsParams
                    ) && $exportSettingsParams['exportStatusReports'];
                // Сохранить модель в БД
                $exportSettings->save();

                // Создание структуры директорий экспорта
                if ($exportSettings->hasSettings()) {
                    $exportPath = Yii::getAlias('@export/' . $terminalId . '/');
                    FileHelper::createDirectory(Yii::getAlias($exportPath));

                    // Создание вложенных директорий сервисов
                    foreach ($addons as $serviceId => $addon) {
                        $addonPath = $exportPath . $serviceId;
                        FileHelper::createDirectory(Yii::getAlias($addonPath));
                    }

                    // Создание директории для автоэкспорта
                    $autoExportPath = $exportPath . $exportSettings->exportXmlPath;
                    FileHelper::createDirectory($autoExportPath);
                }
            }
        }

        // Добавление настроек экспорта по модулям
        $allModels = [];

        foreach ($addons as $serviceId => $addon) {
            $moduleSetting = $serviceId . 'ExportXml';
            $allModels[] = [
                'title' => $exportSettings->getAttributeLabel($moduleSetting),
                'module' => $serviceId,
                'exportXml' => $exportSettings->$moduleSetting
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
        ]);

        $data['dataProvider'] = $dataProvider;
        $data['exportSettings'] = $exportSettings;
        $data['appSettings'] = $appSettings;

        return $data;
    }

    private function vtbIntegrationSettings(Terminal $terminal)
    {
        /** @var VTBIntegrationSettings $settings */
        $settings = Yii::$app->settings->get('VTBIntegration', $terminal->id);
        if (Yii::$app->request->isPost && Yii::$app->request->post('action') === 'saveSettings') {
            // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД
            if ($settings->load(Yii::$app->request->post()) && $settings->save()) {
                // Зарегистрировать событие в модуле мониторинга
                Yii::$app->monitoring->extUserLog('EditTerminalVTBIntegrationSettings', ['id' => $terminal->id]);
            }
        }

        $keysModel = new CryptoproKeySearch();
        Yii::$app->cache->set('crypto-pro-back-link' . Yii::$app->session->id, Yii::$app->request->url);

        return [
            'settings'            => $settings,
            'cryptoproKeys'       => $keysModel->search(Yii::$app->request->post()),
            'cryptoproKeysSearch' => $keysModel,
        ];
    }

    private function sbbol2IntegrationSettings(Terminal $terminal)
    {
        /** @var Sbbol2IntegrationSettings $settings */
        $settings = Yii::$app->settings->get('Sbbol2Integration', $terminal->id);
        if (Yii::$app->request->isPost && Yii::$app->request->post('action') === 'saveSettings') {
            // Если данные модели успешно загружены из формы в браузере
            if ($settings->load(Yii::$app->request->post())) {
                // Сохранить модель в БД
                $settings->save();
            }
        }

        return [
            'settings' => $settings,
            'terminalId' => $terminal->id,
        ];
    }

    /**
     * Метод ищет модель терминала в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    private function findModel($id)
    {
        // Получить из БД терминал с указанным id
        $model = Terminal::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested model does not exist');
        }
        return $model;
    }
}
