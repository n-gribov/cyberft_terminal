<?php

namespace common\modules\autobot\controllers;

use addons\swiftfin\SwiftfinModule;
use common\base\Controller as BaseController;
use common\models\Terminal;
use common\models\User;
use common\models\UserTerminal;
use common\modules\autobot\models\Autobot;
use common\modules\autobot\models\ProxySettingsForm;
use common\modules\autobot\models\search\MultiprocessesSearch;
use common\settings\ExportSettings;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class MultiprocessesController extends BaseController
{
    use IncomingVerification;
    use Exchange;
    use AdditionalSettings;
    use ProcessingSettings;
    use ApiIntegrationSettings;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'       => ['index'],
                        'allow'         => false,
                        'matchCallback' => function ($rule, $action) {
                            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == User::ROLE_ADMIN) {
                                return false;
                            }
                            $tabId = Yii::$app->request->get('tabMode');
                            return !empty($tabId) && $tabId != 'tabProcessing';
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['commonSettings'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'stop-exchange' => ['post'],
                    'stop-terminal' => ['post'],
                    'stomp' => ['post'],
                    'start-terminal-exchange' => ['post']
                ],
            ],
        ];
    }

    /**
     * Выводит список ключей терминалов
     * @return string
     */
    public function actionIndex()
    {
        $tabMode = Yii::$app->request->get('tabMode');

        $data = [];

        if (!$tabMode || $tabMode == 'tabProcessing') {
            // Данные про процессам обмена
            $data = $this->processingData();
        } elseif ($tabMode == 'tabExportXml') {
            // Экспорт xml
            $data = $this->exportXmlData();
        } elseif ($tabMode == 'tabSecurity') {
            // Безопасность
            $data = $this->securityData();
        } elseif ($tabMode == 'tabVerificationRule') {
            // Верификация входящих
            $data = $this->verificationData();
        } elseif ($tabMode == 'tabAdditionalSettings') {
            // Дополнительные настройки
            $data = $this->additionalSettingsData();
        } elseif ($tabMode == 'tabProcessingSettings') {
            // Настройки подключения к процессингу
            $data = $this->processingSettingsData();
        } elseif ($tabMode === 'tabProxy') {
            $data = $this->proxyData();
        } elseif ($tabMode == 'tabApiIntegration') {
            $data = $this->apiIntegrationSettings();
        }

        return $this->render(
            'index',
            ['params' => $data]
        );
    }

    /**
     * Получение данных по процессам обмена терминалов
     */
    protected function processingData()
    {
        $adminIdentity = Yii::$app->user->identity;

        // Массив для формирования dataProvider
        $allModels = [];

        // Получаем список всех терминалов
        $terminals = Terminal::find();

        // Для доп. админа только доступные ему терминалы
        if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {
            $allTerminalsList = UserTerminal::getUserTerminalIndexes($adminIdentity->id);

            if ($allTerminalsList) {
                // Если не доступно ни одного терминала, устанавливаем невыполнимое условие
                $terminals->andWhere(['id' => $allTerminalsList]);
            } else {
                $terminals->andWhere('0=1');
            }
        }

        $terminals = $terminals->all();

        foreach ($terminals as $terminal) {
            // Получение статуса наличия password hash для терминала
            $settings = Yii::$app->settings->get('app', $terminal->terminalId);

            // Получение наименования организации из справочника участников
            $allModels[] = [
                'id' => $terminal->id,
                'terminalId' => $terminal->terminalId,
                'organization' => $terminal->title,
                'hasActiveControllerKeys' => $this->hasTerminalActiveControllerKeys($terminal->terminalId),
                'hasUseForSigningControllerKey' => Autobot::hasUsedForSigningAutobot($terminal->terminalId),
                'exchangeStatus' => Yii::$app->terminals->isRunning($terminal->terminalId),
                'status' => $terminal->status == Terminal::STATUS_ACTIVE,
            ];
        }

        // Сортировка по имени терминала
        uasort($allModels, function ($a, $b) {
            if ($a['terminalId'] == $b['terminalId']) {
                return 0;
            }

            return $a['terminalId'] > $b['terminalId'];
        });

        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
            'pagination' => false
        ]);

        $searchModel = new MultiprocessesSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams, $dataProvider);

        return [
            'searchModel' => $searchModel,
            'queryParams' => $queryParams,
            'dataProvider' => $dataProvider
        ];
    }

    /**
     * Получение данных по экспорту в xml
     */
    protected function exportXmlData()
    {
        $addons = Yii::$app->addon->getRegisteredAddons();

        $swiftfinModule = Yii::$app->addon->getModule(SwiftfinModule::SERVICE_ID);
        $swiftfinSettings = $swiftfinModule->settings;

        $data = [];

        $allModels = [];

        if (Yii::$app->request->isPost) {
            // Обработка запроса
            $post = Yii::$app->request->post();

            $swiftfinSettingsPost = Yii::$app->request->post('SwiftfinSettings');
            $swiftfinFormatExport = $swiftfinSettingsPost['exportIsActive'];

            $isExport = isset($post['exportXml']);

            foreach ($addons as $serviceId => $addon) {
                if ($isExport) {
                    $value = in_array($addon::SERVICE_ID, $post['exportXml']);
                } else {
                    $value = false;
                }

                $module =  Yii::$app->addon->getModule($addon::SERVICE_ID);
                $settings = $module->settings;
                $settings->exportXml = $value;
                $settings->save();
            }

            $app = Yii::$app->settings->get('app');
            $app->exportStatusReports = array_key_exists('exportStatusReports', $post['AppSettings']) && $post['AppSettings']['exportStatusReports'];
            $app->save();

            // Дополнительные настройки модулей

            // Swiftfin

            $swiftfinSettings->exportIsActive = $swiftfinFormatExport;
            $swiftfinSettings->save();

            if ($swiftfinFormatExport) {
                // Регистрация события активации экспорта документов
                Yii::$app->monitoring->extUserLog('ActivateSwiftDocumentExport');
            } else {
                // Регистрация события деактивации экспорта документов
                Yii::$app->monitoring->extUserLog('DeactivateSwiftDocumentExport');
            }

            Yii::$app->session->setFlash('info', Yii::t('app', 'XML export settings saved'));
        }

        $getModuleTitle = function ($serviceId) {
            $exportSettings = new ExportSettings();
            return $exportSettings->getAttributeLabel("{$serviceId}ExportXml");
        };

        foreach ($addons as $serviceId => $addon) {
            $module =  Yii::$app->addon->getModule($addon::SERVICE_ID);

            $allModels[] = [
                'title' => $getModuleTitle($serviceId),
                'module' => $serviceId,
                'exportXml' => $module->settings->exportXml
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $allModels,
        ]);

        // Дополнительные настройки экспорта Swiftfin
        $data['swiftfinSettings'] = $swiftfinSettings;

        // Настройки экспорта
        $data['dataProvider'] = $dataProvider;

        return $data;
    }

    /**
     * Получение данных по настройкам безопасности
     */
    protected function securityData()
    {
        $model = Yii::$app->settings->get('Security');

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    Yii::$app->session->setFlash(
                        'success',
                        Yii::t('app/user', 'Settings updated')
                    );

                    // Регистрация события изменения настроек безопасности
                    Yii::$app->monitoring->extUserLog('EditSecuritySettings');
                }
            }
        }

        return $model;
    }

    /**
     * Получение данных по верификации входящих
     */
    protected function verificationData()
    {
        $data = [];

        $sender = Yii::$app->request->get('sender');
        $currency = Yii::$app->request->get('currency');

        if ($sender && $currency) {
            // Действие edit
            $data = $this->verificationDataEdit($sender, $currency);
            $data['template'] = "_verificationEdit";
        } else {
            // Действие index
            $data = $this->verificationDataIndex();
            $data['template'] = "_verificationIndex";
        }

        return $data;
    }

    private function proxyData()
    {
        $envFilePath = Yii::getAlias('@projectRoot/.env');
        $model = ProxySettingsForm::readFromEnvFile($envFilePath);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::info('Will update proxy settings in .env file');
            $isSaved = $model->saveToEnv($envFilePath);
            if ($isSaved) {
                $model = ProxySettingsForm::readFromEnvFile($envFilePath);
                Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Settings updated'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Settings update error'));
            }
        }

        return ['settingsForm' => $model];
    }
}
