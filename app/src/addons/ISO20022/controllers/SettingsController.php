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

/**
 * Контроллер содержит методы для работы с настройками ISO20022
 */
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
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        // Модель поиска сертификатов Криптопро
        $certModel = CryptoproCertSearch::getInstance('ISO20022');
        // Модель поиска ключей Криптопро
        $keysModel = new CryptoproKeySearch();

        // Если была отправка данных из формы через POST
        if (!empty(Yii::$app->request->isPost)) {
            // Получить текущие настройки
            $currentSettings = $this->module->settings;

            // Сохранить текущие атрибуты настроек в отдельных переменных
            $importSearchSenderReceiver = $currentSettings->importSearchSenderReceiver;
            $keepOriginalFilename = $currentSettings->keepOriginalFilename;
            $enableCryptoProSign = $currentSettings->enableCryptoProSign;
            $useSerial = $currentSettings->useSerial;
            $useUniqueAttachmentName = $currentSettings->useUniqueAttachmentName;
            $exportIBankFormat = $currentSettings->exportIBankFormat;
            $exportReceipts = $currentSettings->exportReceipts;

            // Активная вкладка
            $tabMode = Yii::$app->request->get('tabMode');
            // Имя формы в данном модуле
            $formName = $this->module->settings->formName();
            // Получить атрибуты из формы
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

            // Установить в настройках новые атрибуты
            $this->module->settings->setAttributes($attributes);
            // Нормализовать путь sftp
            $this->module->settings->sftpPath = '/' . trim($this->module->settings->sftpPath, '/') . '/';

            // Если модель настроек успешно сохранена в БД
            if ($this->module->settings->save()) {
                if (!$tabMode || $tabMode == 'tabCommon') {

                    // Если старые атрибуты не совпадают с новыми, то настройки изменились.
                    // Для каждого атрибута определить, он был включён или выключен,
                    // и создать события изменения в мониторинге

                    // Атрибут: Искать отправителя и получателя внутри документа при импорте
                    if ($importSearchSenderReceiver != $this->module->settings->importSearchSenderReceiver) {
                        $eventType = $importSearchSenderReceiver ? 'DeactivateSearchSenderReceiver' : 'ActivateSearchSenderReceiver';
                        // Зарегистрировать событие изменения атрибута в модуле мониторинга
                        Yii::$app->monitoring->extUserLog($eventType);
                    }

                    // Атрибут: Сохранять имя исходного документа при экспорте
                    if ($keepOriginalFilename != $this->module->settings->keepOriginalFilename) {
                        $eventType = $keepOriginalFilename ? 'DeactivateKeepOriginalFilename' : 'ActivateKeepOriginalFilename';
                        // Зарегистрировать событие изменения атрибута в модуле мониторинга
                        Yii::$app->monitoring->extUserLog($eventType);
                    }

                    // Атрибут: Обеспечивать уникальность имени файла вложения
                    if ($useUniqueAttachmentName != $this->module->settings->useUniqueAttachmentName) {
                        $eventType = $useUniqueAttachmentName ? 'DeactivateUseUniqueAttachmentName' : 'ActivateUseUniqueAttachmentName';
                        // Зарегистрировать событие изменения атрибута в модуле мониторинга
                        Yii::$app->monitoring->extUserLog($eventType);
                    }

                    // Атрибут: Экспортировать документы в формате iBank
                    if ($exportIBankFormat != $this->module->settings->exportIBankFormat) {
                        $eventType = $exportIBankFormat ? 'DeactivateISO20022ExportIBankFormat' : 'ActivateISO20022ExportIBankFormat';
                        // Зарегистрировать событие изменения атрибута в модуле мониторинга
                        Yii::$app->monitoring->extUserLog($eventType);
                    }

                    // Атрибут: Экспортировать квитанции
                    if ($exportReceipts != $this->module->settings->exportReceipts) {
                        $eventType = $exportReceipts ? 'DeactivateISO20022ReceiptsExport' : 'ActivateISO20022ReceiptsExport';
                        // Зарегистрировать событие изменения атрибута в модуле мониторинга
                        Yii::$app->monitoring->extUserLog($eventType);
                    }
                } else if ($tabMode == 'tabSFTP') {
                    // Зарегистрировать событие изменения настроек SFTP в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('EditIsoSftpSettings');
                } else if ($tabMode == 'tabCryptoPro') {
                    // Атрибут: Активировать подписание КриптоПро
                    if ($enableCryptoProSign != $this->module->settings->enableCryptoProSign) {
                        $eventType = $enableCryptoProSign ? 'DeactivateIsoCryptoProSigning' : 'ActivateIsoCryptoProSigning';
                        // Зарегистрировать событие изменения атрибута в модуле мониторинга
                        Yii::$app->monitoring->extUserLog($eventType);
                    }
                    // Атрибут: Использовать серийный номер сертификата вместо отпечатка
                    if ($useSerial != $this->module->settings->useSerial) {
                        $eventType = $useSerial ? 'DeactivateIsoUseSerialNumberCert' : 'ActivateIsoUseSerialNumberCert';
                        // Зарегистрировать событие изменения атрибута в модуле мониторинга
                        Yii::$app->monitoring->extUserLog($eventType);
                    }
                }
                // Поместить в сессию флаг сообщения об успешном сохранении настроек
                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Settings saved'));
            } else {
                // Поместить в сессию флаг сообщения об ошибке сохранения настроек
                Yii::$app->session->setFlash('error', Yii::t('app/error', 'Error! Settings not saved!'));
            }
        }

        // Запись ссылки настроек для верного отображения кнопки "назад"
        // на странице настроек сертификата КриптоПро
        Yii::$app->cache->set('crypto-pro-back-link' . Yii::$app->session->id, Yii::$app->request->url);

        // Вывести страницу
        return $this->render('index', [
            'settings' => $this->module->settings,
            'cryptoproKeys' => $keysModel->search(Yii::$app->request->post()),
            'cryptoproKeysSearch' => $keysModel,
            'cryptoproCert' => $certModel->search(Yii::$app->request->post()),
            'cryptoproCertSearch' => $certModel,
        ]);
    }

    /**
     * Метод удаляет код настройки
     * @param type $code
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionDeleteCode($code)
    {
        // Получить список кодов настроек
        $codes = $this->module->settings->typeCodes;
        // Если не найден или не указан код, выбросить исключение
        if (!is_null($code) && !isset($codes[$code])) {
            throw new NotFoundHttpException();
        }
        // Удалить код из списка кодов
        unset($codes[$code]);
        // Обновить настройки модуля
        $this->module->settings->typeCodes = $codes;

        // Сохранить модель в БД
        $this->module->settings->save();

        // Перенаправить на страницу индекса
        return $this->redirect(['index', 'tabMode' => 'tabCodes']);
    }

    /**
     * Метод обновляет код настройки
     * @param type $code
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionUpdateCode($code = null)
    {
        // Получить список кодов настроек
        $codes = $this->module->settings->typeCodes;
        // Если не найден или не указан код, выбросить исключение
        if (!is_null($code) && !isset($codes[$code])) {
            throw new NotFoundHttpException();
        }
        // Создать модель формы редактирования кода
        $model = new TypeCodeForm();
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Загрузить данные модели из формы в браузере
            $model->load(Yii::$app->request->post());
            // Если данные модели валидны
            if ($model->validate()) {
                $codes[$model->code] = [
                    'ru' => $model->ru,
                    'en' => $model->en,
                ];
                // Обновить настройки модуля
                $this->module->settings->typeCodes = $codes;

                // Сохранить модель в БД
                $this->module->settings->save();

                // Зарегистрировать событие изменения настроек ключа в модуле мониторинга
                Yii::$app->monitoring->extUserLog('addNewIsoTypeCode', ['id' => $model->code]);

                // Перенаправить на страницу индекса
                return $this->redirect(['index', 'tabMode' => 'tabCodes']);
            }
        }

        // Если был указан код
        if ($code) {
            // Загрузить атрибуты кода для редактирования
            $model->code = $code;
            $model->setAttributes($codes[$code]);
        }

        // Вывести страницу редактирования кода
        return $this->render('codeEdit', compact('model'));
    }

}
