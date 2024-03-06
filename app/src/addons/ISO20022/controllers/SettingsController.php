<?php

namespace addons\ISO20022\controllers;

use addons\ISO20022\models\form\TypeCodeForm;
use common\base\BaseServiceController;
use common\models\CryptoproCertSearch;
use common\models\CryptoproKeySearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

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
	 * @return mixed
	 */

	public function actionIndex()
	{
        $certModel = CryptoproCertSearch::getInstance('ISO20022');
        $keysModel = new CryptoproKeySearch();

        if (!empty(Yii::$app->request->isPost)) {

            // Получение текущих значений настроек
            $currentSettings = $this->module->settings;
            $importSearchSenderReceiver = $currentSettings->importSearchSenderReceiver;
            $keepOriginalFilename = $currentSettings->keepOriginalFilename;
            $enableCryptoProSign = $currentSettings->enableCryptoProSign;
            $useSerial = $currentSettings->useSerial;
            $useUniqueAttachmentName = $currentSettings->useUniqueAttachmentName;
            $exportIBankFormat = $currentSettings->exportIBankFormat;
            $exportReceipts = $currentSettings->exportReceipts;

            $tabMode = Yii::$app->request->get('tabMode');

        	$formName = $this->module->settings->formName();

            $attributes = Yii::$app->request->post($formName);
            /**
             * Fix for autotest: при вызове из теста с выключенным чекбоксом в посте не приходит поле совсем,
             * поэтому в setAttributes оно не попадает и не обновляется
             */
            if ($tabMode == 'tabCommon' || empty($tabMode)) {
                $testAttributes = [
                    'importSearchSenderReceiver',
                    'keepOriginalFilename',
                    'useUniqueAttachmentName',
                ];
                // если нет атрибута, считаем, что он выключен (не случится при работе из обычного браузера)
                foreach($testAttributes as $attr) {
                    if (!isset($attributes[$attr])) {
                        $attributes[$attr] = false;
                    }
                }
            }

            $this->module->settings->setAttributes($attributes);
            $this->module->settings->sftpPath = '/' . trim($this->module->settings->sftpPath, '/') . '/';
            if ($this->module->settings->save()) {
                if (!$tabMode || $tabMode == 'tabCommon') {
                    if (!$importSearchSenderReceiver && $this->module->settings->importSearchSenderReceiver) {
                        // Регистрация события поиска отправителя/получателя внутри документа
                        Yii::$app->monitoring->extUserLog('ActivateSearchSenderReceiver');
                    } else if ($importSearchSenderReceiver && !$this->module->settings->importSearchSenderReceiver) {
                        // Регистрация события деактивации поиска отправителя/получателя внутри документа
                        Yii::$app->monitoring->extUserLog('DeactivateSearchSenderReceiver');
                    }

                    if (!$importSearchSenderReceiver && $this->module->settings->importSearchSenderReceiver) {
                        // Регистрация события поиска отправителя/получателя внутри документа
                        Yii::$app->monitoring->extUserLog('ActivateSearchSenderReceiver');
                    } else if ($importSearchSenderReceiver && !$this->module->settings->importSearchSenderReceiver) {
                        // Регистрация события деактивации поиска отправителя/получателя внутри документа
                        Yii::$app->monitoring->extUserLog('DeactivateSearchSenderReceiver');
                    }

                    if (!$keepOriginalFilename && $this->module->settings->keepOriginalFilename) {
                        // Регистрация события активации сохранения имени файла pain.001 при экспорте
                        Yii::$app->monitoring->extUserLog('ActivateKeepOriginalFilename');
                    } else if ($keepOriginalFilename && !$this->module->settings->keepOriginalFilename) {
                        // Регистрация события деактивации сохранения имени файла pain.001 при экспорте
                        Yii::$app->monitoring->extUserLog('DeactivateKeepOriginalFilename');
                    }

                    // Уникальность имени файла вложения
                    if (!$useUniqueAttachmentName && $this->module->settings->useUniqueAttachmentName) {
                        // Активация
                        Yii::$app->monitoring->extUserLog('ActivateUseUniqueAttachmentName');
                    } else if ($useUniqueAttachmentName && !$this->module->settings->useUniqueAttachmentName) {
                        // Деактивация
                        Yii::$app->monitoring->extUserLog('DeactivateUseUniqueAttachmentName');
                    }

                    // Экспорт в формат iBank
                    if (!$exportIBankFormat && $this->module->settings->exportIBankFormat) {
                        // Активация
                        Yii::$app->monitoring->extUserLog('ActivateISO20022ExportIBankFormat');
                    } else if ($exportIBankFormat && !$this->module->settings->exportIBankFormat) {
                        // Деактивация
                        Yii::$app->monitoring->extUserLog('DeactivateISO20022ExportIBankFormat');
                    }

                    if ($exportReceipts != $this->module->settings->exportReceipts) {
                        $eventType = $exportReceipts == 0 ? 'ActivateISO20022ReceiptsExport' : 'DeactivateISO20022ReceiptsExport';
                        Yii::$app->monitoring->extUserLog($eventType);
                    }
                } else if ($tabMode == 'tabSFTP') {
                    // Регистрация события изменения настроек SFTP
                    Yii::$app->monitoring->extUserLog('EditIsoSftpSettings');
                } else if ($tabMode == 'tabCryptoPro') {
                    if (!$enableCryptoProSign && $this->module->settings->enableCryptoProSign) {
                        // Регистрация события изменения настройки использования подписания CryptoPro
                        Yii::$app->monitoring->extUserLog('ActivateIsoCryptoProSigning');
                    } else if ($enableCryptoProSign && !$this->module->settings->enableCryptoProSign) {
                        // Регистрация события деакцивации изменения настройки использования подписания CryptoPro
                        Yii::$app->monitoring->extUserLog('DeactivateIsoCryptoProSigning');
                    }
                    if (!$useSerial && $this->module->settings->useSerial) {
                        // Регистрация события изменения настройки использования серийного номера сертификата вместо отпечатка
                        Yii::$app->monitoring->extUserLog('ActivateIsoUseSerialNumberCert');
                    } else if ($useSerial && !$this->module->settings->useSerial) {
                        // Регистрация события изменения настройки использования серийного номера сертификата вместо отпечатка
                        Yii::$app->monitoring->extUserLog('DeactivateIsoUseSerialNumberCert');
                    }
                }
                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Settings saved'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/error', 'Error! Settings not saved!'));
            }
        }

        // Запись ссылки настроек для верного
        // отображения кнопки назад на странице настроек сертификата КриптоПро
        Yii::$app->cache->set('crypto-pro-back-link' . Yii::$app->session->id, Yii::$app->request->url);

        return $this->render('index', [
			'settings' => $this->module->settings,
            'cryptoproKeys' => $keysModel->search(Yii::$app->request->post()),
            'cryptoproKeysSearch' => $keysModel,
            'cryptoproCert' => $certModel->search(Yii::$app->request->post()),
            'cryptoproCertSearch' => $certModel,
		]);
	}


    public function actionDeleteCode($code)
    {
        $codes = $this->module->settings->typeCodes;

        if (!is_null($code) && !isset($codes[$code])) {
            throw new NotFoundHttpException();
        }

        unset($codes[$code]);
        $this->module->settings->typeCodes = $codes;
        $this->module->settings->save();

        return $this->redirect(['index', 'tabMode' => 'tabCodes']);
    }

    public function actionUpdateCode($code = null)
    {
        $codes = $this->module->settings->typeCodes;

        if (!is_null($code) && !isset($codes[$code])) {
            throw new NotFoundHttpException();
        }

        $model = new TypeCodeForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                $codes[$model->code] = [
                    'ru' => $model->ru,
                    'en' => $model->en,
                ];

                $this->module->settings->typeCodes = $codes;
                $this->module->settings->save();

                // Регистрация события изменения настроек ключа
                Yii::$app->monitoring->extUserLog('addNewIsoTypeCode', ['id' => $model->code]);

                return $this->redirect(['index', 'tabMode' => 'tabCodes']);
            }
        }

        if ($code) {
            $model->code = $code;
            $model->setAttributes($codes[$code]);
        }

        return $this->render('codeEdit', ['model' => $model]);
    }

}
