<?php

namespace common\modules\autobot\controllers;

use backend\models\search\UserSearch;
use common\base\Controller;
use common\base\traits\ChecksTerminalAccess;
use common\helpers\FileHelper;
use common\models\CryptoproKeySearch;
use common\models\Terminal;
use common\settings\AppSettings;
use common\settings\ExportSettings;
use common\settings\Sbbol2IntegrationSettings;
use common\settings\VTBIntegrationSettings;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class TerminalsController extends Controller
{
    use TerminalsExchange;
    use SigningSettings;
    use AdditionalSettings;
    use NotificationsSettings;
    use TerminalsRemoteIds;
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

        return $this->render('index', [
            'params' => $data,
            'terminal' => $terminal
        ]);
    }

    public function actionCreate()
    {
        $model = new Terminal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // Регистрация события создания нового терминала
            Yii::$app->monitoring->extUserLog('CreateTerminal', ['id' => $model->id]);

            return $this->redirect(['/autobot/terminals/index', 'id' => $model->id]);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->delete()) {
            if (isset($model->errors['terminalDelete'])) {

                // Преобразовываем ошибки удаления терминала в удобный для вывода вид
                $errorsTitle = Yii::t('app/terminal', 'Deleting terminal failed');
                $errors = implode('<br/>', $model->errors['terminalDelete']);

                Yii::$app->session->addFlash('error', $errorsTitle . '<br/>' . $errors);
            } else {
                Yii::$app->session->addFlash(
                    'error',
                    Yii::t('app/terminal', 'Error occurred while deleting terminal: {error}', [
                        'error' => join(PHP_EOL, $model->getFirstErrors())
                    ])
                );
            }

            return $this->redirect(['/autobot/terminals/index', 'id' => $id]);
        }

        Yii::$app->session->addFlash('success', Yii::t('app/terminal', 'Terminal was successfully deleted'));
        return $this->redirect(['/autobot/multiprocesses/index']);
    }

    /**
     * Данные по ключам контролера
     */
    protected function autobotData($terminal)
    {
        $controllers = \common\modules\autobot\models\Controller::find()
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

        if (Yii::$app->request->isPost) {
            if ($terminal->load(Yii::$app->request->post()) && $terminal->save()) {
                // Регистрация события изменения настроек терминала
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
        if (Yii::$app->request->isPost) {
            $appSettings->load(Yii::$app->request->post());
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
            if ($settings->load(Yii::$app->request->post()) && $settings->save()) {
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
            if ($settings->load(Yii::$app->request->post())) {
                $settings->save();
            }
        }

        return [
            'settings' => $settings,
            'terminalId' => $terminal->id,
        ];
    }

    private function findModel($id)
    {
        if (($model = Terminal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested model does not exist.');
        }
    }
}
