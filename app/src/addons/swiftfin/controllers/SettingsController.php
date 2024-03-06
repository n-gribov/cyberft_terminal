<?php

namespace addons\swiftfin\controllers;

use common\base\BaseServiceController;
use common\models\Participant;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class SettingsController extends BaseServiceController
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
                    'update-approvers' => ['post'],
					'switch-approval-enabled' => ['post'],
                ],
            ],
		];
	}

	/**
	 * Lists all Settings.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$tabMode = Yii::$app->request->get('tabMode');
		$dataProvider = null;
		switch($tabMode) {
			case 'tabPrint':
				$dataProvider = $this->setupPrint();
				break;
			case 'tabAccess':
				$dataProvider = new ArrayDataProvider([
					'allModels' => $this->module->getEnabledUsers()
				]);
				break;
            case 'tabUserVerification':
                $dataProvider = $this->setupUserVerification();
                break;
            case 'tabRouting':
                $dataProvider = new ActiveDataProvider([
                    'query' => Participant::find(),
                ]);
                break;
			case 'tabGeneral':
			default:
				$dataProvider = $this->setupGeneral();
				break;

		}

		return $this->render('index', [
			'tabMode' => $tabMode,
			'settings' => $this->module->settings,
			'dataProvider' => $dataProvider,
		]);
	}

	public function setupGeneral()
	{
        $settings = $this->module->settings;

        // Получение текущих значений настроек для логирования изменений
        $swiftRouting = $settings->swiftRouting;
        $deliveryExport = $settings->deliveryExport;

		if (Yii::$app->request->isPost && $settings->load(Yii::$app->request->post())) {

            $settings->validate();

			if ($settings->hasErrors()) {
				Yii::$app->session->setFlash('error', Yii::t('app/error', 'Error! Settings not saved!'));
			} else {

				if ($settings->save()) {

                    if (!$swiftRouting && $settings->swiftRouting) {
                        // Регистрация события активации роутинга
                        Yii::$app->monitoring->extUserLog('ActivateSwiftRouting');
                    } elseif ($swiftRouting && !$settings->swiftRouting) {
                        // Регистрация события деактивации роутинга
                        Yii::$app->monitoring->extUserLog('DeactivateSwiftRouting');
                    }

                    if (!$deliveryExport && $settings->deliveryExport) {
                        // Регистрация события активации экспорта документов mt011
                        Yii::$app->monitoring->extUserLog('ActivateSwiftDocumentMTExport');
                    } elseif ($deliveryExport && !$settings->deliveryExport) {
                        // Регистрация события деактивации экспорта документов mt011
                        Yii::$app->monitoring->extUserLog('DeactivateSwiftDocumentMTExport');
                    }

                    /**
                     * @todo monitor event for export checksum
                     */
					Yii::$app->session->setFlash('info', Yii::t('app', 'Terminal configuration saved'));
				}
			}
		}

		return null;
	}

	public function setupPrint()
	{
		if (Yii::$app->request->isPost) {

            $settings = $this->module->settings;
			$printSetup = Yii::$app->request->post('mt');
			// Запоминаем вектор настроек в settings модуля
            $settings->autoPrintMt = is_array($printSetup) ? array_flip($printSetup) : [];
			if ($settings->save()) {
                if (!empty($settings->autoPrintMt)) {
                    // Регистрация события активации печати документов
                    Yii::$app->monitoring->extUserLog('ActivateSwiftDocumentPrint', ['types' => $printSetup]);
                }

				Yii::$app->session->setFlash('info', Yii::t('app', 'Document print configuration saved'));
			}
		}

		return $this->getDocumentTypes();
	}

	public function setupApproval()
	{
        $settings = $this->module->settings;

		if (Yii::$app->request->isPost) {

			$approvalSetup = Yii::$app->request->post('mt');
			if (is_array($approvalSetup)) {
				$approvalSetup = array_flip($approvalSetup);
			} else {
				$approvalSetup = [];
			}

			$formats = $settings->approvalFormats;
			foreach(array_keys($approvalSetup) as $format) {
				if (!isset($formats[$format])) {
					$formats[$format] = [];
				}
			}
			foreach(array_keys($formats) as $format) {
				$formats[$format]['enabled'] = array_key_exists($format, $approvalSetup);
			}

			// Запоминаем вектор настроек в settings модуля
            $settings->approvalFormats = $formats;
			if ($settings->save()) {
				Yii::$app->session->setFlash('info', Yii::t('app', 'Document approval configuration saved'));
			}
		}

		return $this->getApprovalFormats();
	}

	public function getDocumentTypes()
	{
		$documentTypesArray = [];
		// Текущие настройки печати из Терминала для отображения текущего состояния формы
		$currentSetup = $this->module->settings->autoPrintMt;

		// Формируем данные для dataProvider
		foreach (array_keys(Yii::$app->registry->getModuleTypes($this->module->serviceId)) as $docTypeName) {
			$documentTypesArray[] = [
				'type' => $docTypeName,
				'name' => $docTypeName,
				'checked' => array_key_exists($docTypeName, $currentSetup),
			];
		}

		return new ArrayDataProvider([
			'allModels' => $documentTypesArray,
			'key' => 'type',
			'pagination' => false
		]);
	}

	public function getApprovalFormats()
	{
		$documentTypesArray = [];

		$approvalFormats = $this->module->settings->approvalFormats;

		foreach (array_keys(Yii::$app->registry->getModuleTypes($this->module->serviceId)) as $docTypeName) {
			$documentTypesArray[] = [
				'type' => $docTypeName,
				'name' => $docTypeName,
				'checked' => isset($approvalFormats[$docTypeName]['enabled']) ? $approvalFormats[$docTypeName]['enabled'] : false
			];
		}

		return new ArrayDataProvider([
			'allModels' => $documentTypesArray,
			'key' => 'type',
			'pagination' => false
		]);
	}

    public function setupUserVerification()
    {
        $settings = $this->module->settings;

        if (Yii::$app->request->isPost && Yii::$app->request->post('SwiftfinSettings')) {

            // Параметры POST-запроса
            $post = Yii::$app->request->post('settings');

            // Получение из запроса данных о настройке необходимости верификации счетов
            $accountsVerification = $post['accountsVerification'];

            // Запись значения настройки необходимости верификации счетов
            $settings->accountsVerification = $accountsVerification;

            $rules = Yii::$app->request->post('mt');
            $settings->userVerificationRules = (is_array($rules) ? array_values($rules) : []);

            if ($settings->save()) {

                if (!empty($settings->userVerificationRules)) {
                    // Регистрация события активации верификации документов
                    Yii::$app->monitoring->extUserLog('ActivateSwiftDocumentVerification', ['types' => $rules]);
                }

                Yii::$app->session->setFlash('info', Yii::t('doc', 'Document verification configuration saved'));
            }
        }

        foreach (array_keys($this->module->getUserVerificationDocumentType()) as $docTypeName) {
            $documentTypesArray[] = [
                'type' => $docTypeName,
                'name' => $docTypeName,
                'checked' => in_array($docTypeName, $this->module->settings->userVerificationRules) ?: false
            ];
        }

        return new ArrayDataProvider([
            'allModels' => (isset($documentTypesArray) ? $documentTypesArray : []),
            'key' => 'type',
            'pagination' => false
        ]);
    }

}
