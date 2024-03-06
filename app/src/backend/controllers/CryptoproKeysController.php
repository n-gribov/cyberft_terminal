<?php

namespace backend\controllers;

use addons\fileact\models\FileactCryptoproCert;
use addons\ISO20022\models\ISO20022CryptoproCert;
use common\components\cryptography\drivers\DriverCryptoPro;
use common\helpers\ArchiveFileZip;
use common\helpers\CryptoProHelper;
use common\helpers\FileHelper;
use common\models\CryptoproKey;
use common\models\CryptoproKeyBeneficiary;
use common\models\CryptoproKeySearch;
use common\models\CryptoproKeyTerminal;
use common\models\Terminal;
use common\models\User;
use common\modules\certManager\models\Cert;
use common\modules\participant\models\BICDirParticipant;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

/**
 * CryptoproKeysController implements the CRUD actions for CryptoproKey model.
 */
class CryptoproKeysController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['user'],
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all CryptoproKey models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CryptoproKeySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CryptoproKey model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $user = Yii::$app->user->identity;

        // Пользователь может
        // смотреть только доступные ему сертификаты
        if ($user->role != User::ROLE_ADMIN) {
            if ($model->userId != $user->id) {
                throw new ForbiddenHttpException();
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new CryptoproKey model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CryptoproKey();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CryptoproKey model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = Yii::$app->user->identity;

        // Пользователь может смотреть только доступные ему сертификаты
        if ($user->role != User::ROLE_ADMIN && $model->userId != $user->id) {
            throw new ForbiddenHttpException('Access denied');
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            // Если сертификат активен, то его можно редактировать только пользователю-владельцу
            if ($model->active && Yii::$app->user->id != $model->userId) {
                throw new HttpException('403', '');
            }

            if (Yii::$app->user->id == $model->userId && !empty($model->password) && $model->active ) {
                $model->status = CryptoproKey::STATUS_READY;
                $passwordKey = getenv('COOKIE_VALIDATION_KEY');
                $model->password = base64_encode(Yii::$app->security->encryptByKey($model->password, $passwordKey));
            } else {
                $model->status = CryptoproKey::STATUS_NOT_READY;
                $model->password = null;
                $model->active = 0;
            }

            if ($model->save()) {
                // Регистрация события изменения настроек ключа
                Yii::$app->monitoring->extUserLog('EditCryptoProKeySettings', ['fingerprint' => $model->keyId]);
                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Key updated'));

                // Редирект, если есть соответствующий параметр
                $redirect = Yii::$app->request->get('redirect');
                if ($redirect) {

                    if ($model->active == 0) {
                        Yii::$app->session->setFlash('success', Yii::t('app/cert', 'Key deactivated'));
                    }

                    return $this->redirect(Yii::$app->request->referrer);
                }
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Error! Update failure'));

                // Редирект, если есть соответствующий параметр
                $redirect = Yii::$app->request->get('redirect');
                if ($redirect) {
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
        } else {
            // Если сертификат активен, то его можно редактировать только пользователю-владельцу
            if ($model->active && Yii::$app->user->id != $model->userId) {
                throw new HttpException('403', '');
            }
        }

//        $terminals = $model->terminals;
//        $filterTerminals = [];
//        foreach($terminals as $terminal) {
//            $filterTerminals[] = $terminal->id;
//        }
//
//        $terminalList = Terminal::find()
//            ->where(['status' => Terminal::STATUS_ACTIVE])
//            ->andWhere(['not in', 'id', $filterTerminals])
//            ->all();
//
//        $freeTerminals = ArrayHelper::map($terminalList, 'id', 'terminalId');

        $data = $this->getKeyTerminalData($model->id);
        $data['model'] = $model;
	
//	\Yii::info(var_export(array_keys($data), true));

        return $this->render('update', $data);

    }

    public function actionDetachTerminal($keyId, $terminalId)
    {
        $model = CryptoproKeyTerminal::findOne([
            'keyId' => $keyId,
            'terminalId' => $terminalId,
        ]);

        if ($model) {
            $model->delete();
        }

        return $this->redirect(['update', 'id' => $keyId]);
    }

    private function deleteCertContainer($model)
    {
        $containerName = $model->ownerName;

        $collection = CryptoProHelper::getCertInfo(
            null,
            [DriverCryptoPro::SHA1_HASH => $model->keyId]
        );
        $certInfo = $collection->first();

        if ($certInfo) {
            $output = CryptoProHelper::getCommandOutput(
                'certmgr', 'delete', ['thumbprint' => $certInfo[DriverCryptoPro::SHA1_HASH]]
            );

            $collection = CryptoProHelper::getCertInfo($output);

            if (!in_array($collection->errorCode, ['0x00000000', '0x8010002c'])) {
                Yii::$app->session->setFlash('error', Yii::t('app/cert', 'Error deleting key certificate'));

                return false;
            }

            if (isset($certInfo[DriverCryptoPro::CONTAINER])) {
                $containerName = $certInfo[DriverCryptoPro::CONTAINER];
            }

        }

        if (!$containerName) {
            return true;
        }

        // Удаление контейнера
        $output = CryptoProHelper::getCommandOutput(
            'certmgr', 'delete',
            ['cont' => "'" . '\\\\.\HDIMAGE\\' . $containerName . "'"]
        );

        $collection = CryptoProHelper::getCertInfo($output);

        if (!in_array($collection->errorCode, ['0x00000000', '0x80090019'. '0x80090019'])) {
            Yii::$app->session->setFlash('error', Yii::t('app/cert', 'Error deleting the key container'));

            return false;
        }

        return true;
    }

    /**
     * Deletes an existing CryptoproKey model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Если сертификат активен, то его можно редактировать только пользователю-владельцу
        if ($model->active) {
            throw new HttpException('403', '');
        }

        $addons = Yii::$app->addon->getRegisteredAddons();

        // поиск сертификатов в базе
        $where = ['keyId' => $model->keyId];

        // 'ISO20022', 'SBBOL', 'edm', 'fileact', 'finzip', 'swiftfin', 'VTB'
        $foundCert = ISO20022CryptoproCert::findOne($where);
        if (!$foundCert && array_key_exists('fileact', $addons)) {
            $foundCert = FileactCryptoproCert::findOne($where);
        }

        if (!$foundCert && array_key_exists('VTB', $addons)) {
            /** @var \addons\VTB\VTBModule $module */
            $module = Yii::$app->addon->getModule('VTB');
            $certModel = $module->getCryptoProCertModel();
            $foundCert = $certModel::findOne($where);
        }

        $result = true;
        if (!$foundCert) {
            $result = $this->deleteCertContainer($model);
        }

        if ($result) {
            $model->delete();
            Yii::$app->session->setFlash('success', Yii::t('app/cert', 'The key was successfully deleted'));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the CryptoproKey model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CryptoproKey the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CryptoproKey::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);

        if (!empty($model->certData)) {
            CryptoProHelper::downloadCertificate($model->ownerName, $model->keyId, $model->certData);
        } else {
            throw new NotFoundHttpException('The requested file does not exist.');
        }
    }

    /**
     * ajax-добавление нового терминала для сертификата
     * @return mixed
     */
    public function actionAddTerminal()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            // Данные из ajax-запроса
            $keyId = $data['keyId'];
            $terminalId = $data['terminalId'];

            if ($keyId && $terminalId) {
                // Добавляем запись в таблицу терминалов ключей КриптоПро
                if ($terminalId) {
                    $terminal = new CryptoproKeyTerminal();
                    $terminal->keyId = $keyId;
                    $terminal->terminalId = $terminalId;
                    $terminal->save();
                } else {

                    // Если не указан id конкретного терминала, то добавляем в список все доступные id терминалов
                    $terminals = Terminal::find()->where(['status' => Terminal::STATUS_ACTIVE])->all();

                    foreach($terminals as $terminal) {
                        $newTerminal = new CryptoproKeyTerminal();
                        $newTerminal->keyId = $keyId;
                        $newTerminal->terminalId = $terminal->id;
                        $newTerminal->save();
                    }
                }
            }
            // Вывод списка терминалов пользователя
            return $this->renderUserTerminals($keyId);
        }
    }

    /**
     * ajax-добавление нового получателя для сертификата
     * @return mixed
     */
    public function actionAddBeneficiary()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            // Данные из ajax-запроса
            $keyId = $data['keyId'];
            $terminalId = $data['beneficiaryId'];

            if ($keyId && $terminalId) {
                // Добавляем запись в таблицу получателей для ключа КриптоПро
                if ($terminalId) {
                    $terminal = new CryptoproKeyBeneficiary();
                    $terminal->keyId = $keyId;
                    $terminal->terminalId = $terminalId;
                    $terminal->save();
                } else {

                    // Если не указан id конкретного терминала, то добавляем в список все доступные id терминалов
                    $terminals = Terminal::find()->where(['status' => Terminal::STATUS_ACTIVE])->all();

                    foreach($terminals as $terminal) {
                        $newTerminal = new CryptoproKeyBeneficiary();
                        $newTerminal->keyId = $keyId;
                        $newTerminal->terminalId = $terminal->id;
                        $newTerminal->save();
                    }
                }
            }
            // Вывод списка терминалов пользователя
            return $this->renderUserBeneficiaries($keyId);
        }
    }


    /**
     * Удаление терминала у ключа
     * @return string
     */
    public function actionDeleteTerminal()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            // Данные из ajax-запроса
            $keyId = $data['keyId'];
            $terminalId = $data['terminalId'];

            if ($keyId && $terminalId) {
                // Находим и удаляем запись из таблицы терминалов пользователей
                CryptoproKeyTerminal::deleteAll(['keyId' => $keyId, 'terminalId' => $terminalId]);

                // Вывод списка терминалов пользователя
                return $this->renderUserTerminals($keyId);
            }
        }
    }

    /**
     * Удаление получателя для ключа
     * @return string
     */
    public function actionDeleteBeneficiary()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            // Данные из ajax-запроса
            $keyId = $data['keyId'];
            $terminalId = $data['beneficiaryId'];

            if ($keyId && $terminalId) {
                // Находим и удаляем запись из таблицы терминалов пользователей
                CryptoproKeyBeneficiary::deleteAll(['keyId' => $keyId, 'terminalId' => $terminalId]);

                // Вывод списка терминалов пользователя
                return $this->renderUserBeneficiaries($keyId);
            }
        }
    }


    /**
     * Рендеринг блока выбора терминалов для ajax-запросов
     * @param $keyId
     * @return string
     * @throws NotFoundHttpException
     */
    protected function renderUserTerminals($keyId)
    {
        return $this->renderPartial('_keysTerminalList', $this->getKeyTerminalData($keyId));
    }
    
    protected function renderUserBeneficiaries($keyId)
    {
        return $this->renderPartial('_keysBeneficiaryList', $this->getKeyTerminalData($keyId));
    }


    private function getKeyTerminalData($keyId)
    {
        $model = $this->findModel($keyId);

        // Получение массивов с terminalId, с терминалами, которые уже выбраны для ключа
        $table = CryptoproKeyTerminal::tableName();
        $keyTerminals = CryptoproKeyTerminal::find()
                        ->select($table . '.*, t.title, t.terminalId as terminalCharacterId')
                        ->innerJoin(Terminal::tableName() . ' t', 't.id = ' . $table . '.terminalId')
                        ->where(['keyId' => $keyId])
                        ->orderBy(['keyId' => SORT_ASC])
                        ->asArray()->all();

	// То же самое для списка получателей
        $keyBeneficiaries = CryptoproKeyBeneficiary::find()
                        ->where(['keyId' => $keyId])
                        ->orderBy(['keyId' => SORT_ASC])
                        ->asArray()->all();		

        // Получение всех активных терминалов, кроме тех, которые уже выбраны
        $terminals = Terminal::find()->where(['status' => Terminal::STATUS_ACTIVE])
            ->andFilterWhere(['not in', 'id', ArrayHelper::getColumn($keyTerminals, 'terminalId')])
            ->all();

        // Формирование списка доступных для выбора терминалов
        $terminalList = [];

        foreach ($terminals as $terminal) {
            // Добавляем название терминала в наименование, если оно есть
            $terminalList[$terminal->id] = $terminal->terminalId
                                            . ($terminal->title ? '(' . $terminal->title . ')' : null);
        }

	/*
	 * Смотрим в таблицу сертификатов. Если название терминала уже есть в таблице ключей для получателей,
	 * то помещаем его в список выбранных ключей. Если нет, то добавляем его в выпадающий список для выбора ключей.
	 */
	$beneficiaries = Cert::find()->all();
	$keyBeneficiariesTerminalIds = ArrayHelper::getColumn($keyBeneficiaries, 'terminalId') ?
		ArrayHelper::getColumn($keyBeneficiaries, 'terminalId') : [];
	$beneficiaryList = [];	
	$keyBeneficiariesData = [];
	
        foreach ($beneficiaries as $beneficiary) {
	    $terminalId = $beneficiary->terminalId->value;
	    $participantBIC = $beneficiary->terminalId->bic . $beneficiary->participantUnitCode;
            // Ищем наименование терминала из реестра участников CyberFT
	    $bicDirParticipant = BICDirParticipant::findOne(['participantBIC' => $participantBIC]);	    
	    	    
	    if (!in_array($terminalId, $keyBeneficiariesTerminalIds)) {
		$terminalName = $bicDirParticipant ? ' ('.$bicDirParticipant->name.')' : '';
		$beneficiaryList[$terminalId] = $terminalId . $terminalName;		    
	    } else {
		$title = $bicDirParticipant ? $bicDirParticipant->name : '';
		$keyBeneficiariesData[] = ['terminalId' => $terminalId, 'title' => $title];
	    }	    
        }
	
        return [
            'keyId' => $keyId,
            'terminalList' => $terminalList,
	    'beneficiaryList' => $beneficiaryList,
            'dataProvider' => new ArrayDataProvider(['allModels' => $keyTerminals, 'pagination' => false]),
            'dataProviderBeneficiary' => new ArrayDataProvider(['allModels' => $keyBeneficiariesData, 'pagination' => false])
        ];
    }
    
    public function actionFileUpload()
    {
        // Только ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException('AJAX requests only');
        }

        // Если файлы не выбраны
        if (empty($_FILES)) {
            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'No files selected')]);
        }

        // Если не выбраны файлы сертификата или контейнера
        if (!isset($_FILES['cert'])) {
            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Certificate file has not been selected')]);
        }

        if (!isset($_FILES['container'])) {
            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Container file has not been selected')]);
        }

        $cert = $_FILES['cert'];
        $container = $_FILES['container'];

        // Массив для хранения базовых ошибок проверки файлов
        $basicErrors = [];

        // Проверяем расширение переданного файла с сертификатом
        $certFileInfo = FileHelper::mb_pathinfo($cert['name']);

        if (!isset($certFileInfo['extension'])
            || (
                $certFileInfo['extension'] != 'crt'
                && $certFileInfo['extension'] != 'pem'
                && $certFileInfo['extension'] != 'cer'
               )
        ) {
            $basicErrors['cert'] = true;
        }

        // Проверяем валидность zip-файла
        $containerMimeType = mime_content_type($container['tmp_name']);
        $zipExtension = FileHelper::getExtensionByMimeType($containerMimeType);

        if ($zipExtension != 'zip') {
            $basicErrors['container'] = true;
        }

        if (count($basicErrors) == 2) {
            return json_encode([
                'status' => 'error',
                'msg' => Yii::t(
                    'app/cert', 'Certificate is invalid {br} Container is not a zip file',
                    ['br' => '<br>']
                )
            ]);
        } else if (array_key_exists('container', $basicErrors)) {
            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Container file must be a valid zip archive')]);
        } else if (array_key_exists('cert', $basicErrors)) {
            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Invalid certificate')]);
        }

        $uploadDir = Yii::getAlias('@temp');

        // Директория для хранения сертификатов прошедших валидацию и готовых к загрузке
        $certsDir = $uploadDir . '/cert';

        // Директория для хранения файлов контейнера перед валидацией
        $tempCertsDir = $uploadDir . '/temp_cert';

        // Создание директории для временного хранения сертификатов
        FileHelper::createDirectory($tempCertsDir);

        // Перемещение сертификата во временную директорию проекта
        $certFilePath = $tempCertsDir . '/' . basename($cert['name']);

        if (!move_uploaded_file($cert['tmp_name'], $certFilePath)) {
            // Если не удалось скопировать, возвращаем ошибку
            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Failed to move the certificate file to a temporary directory')]);
        }

        // Перемещение контейнера во временную директорию проекта
        $containerFilePath = $uploadDir . '/' . basename($container['name']);

        if (!move_uploaded_file($container['tmp_name'], $containerFilePath)) {
            // Если не удалось скопировать, возвращаем ошибку
            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Failed to move the container file to a temporary directory')]);
        }

        $arch = new ArchiveFileZip();

        try {
            if (!$arch->extract($containerFilePath, $tempCertsDir)) {
                // Удаление загруженного архива и файла сертификата
                unlink($certFilePath);
                unlink($containerFilePath);

                return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Error extracting zip archive')]);
            }
        } catch (\Exception $ex) {
            // Удаление загруженного архива и файла сертификата
            unlink($certFilePath);
            unlink($containerFilePath);

            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Error opening zip archive')]);
        }

        // Получаем имя извлеченной директории
        $fileStat = $arch->statIndex(null, 0);
        $extractPath = $tempCertsDir . '/' . $fileStat['name'];
        //$extractPath = $tempCertsDir . '/' . basename($container['name'], '.zip');

        // Проверяем что директория существует
        if (!is_dir($extractPath) || !file_exists($extractPath)) {
            // Удаление загруженного архива и файла сертификата
            unlink($certFilePath);
            unlink($containerFilePath);

            return json_encode(['status' => 'error', 'msg' => Yii::t('app/cert', 'Error access the extracted data archive')]);
        }

        $containerFiles = array_diff(scandir($extractPath), array('..', '.'));

        // Проверка на количество файлов контейнера
        if (count($containerFiles) != 6) {

            // Удаление загруженного архива и файла сертификата
            unlink($certFilePath);
            unlink($containerFilePath);
            FileHelper::removeDirectory($extractPath);

            return json_encode([
                'status' => 'error',
                'msg' => Yii::t('app/cert', 'Directory container does not contain all the required files, or contains extra')
            ]);
        }

        // Если все ок распаковываем все файлы в temp/cert

        // Создаем директорию certs, для хранения загруженного сертификата
        FileHelper::createDirectory($certsDir);
        FileHelper::copyDirectory($tempCertsDir, $certsDir);

        // Удаление загруженного архива и файла сертификата
        unlink($containerFilePath);
        unlink($certFilePath);
        FileHelper::removeDirectory($extractPath);

        return json_encode([
            'status' => 'processing',
            'msg' => Yii::t('app/cert', 'The certificate is processing')
        ]);
    }

    /*
     * Получение статуса загрузки сертификата
     */
    public function actionGetFileUploadStatus()
    {
        // Только ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException('AJAX requests only');
        }

        // Получение файла с состоянием загрузки
        $statusPath = Yii::getAlias('@temp/cert-load-status.json');

        if (file_exists($statusPath)) {
            $statusContent = file_get_contents($statusPath);
            $statusArray = json_decode($statusContent);
            unlink($statusPath);

            if ($statusArray->status == 'error') {
                // Добавляем информацию о fingerprint,
                // если ошибка связана с тем, что сертификат уже существует
                if (isset($statusArray->container)) {
                    $driver =
                    // Находим сертификат, к которому привязан контейнер
                    $fingerprint = $this->getFingerprintByContainer($statusArray->container);
                    $statusArray->msg = $statusArray->msg . " ({$fingerprint})";
                    $statusContent = json_encode($statusArray);
                }

                return $statusContent;
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app/cert', 'The certificate has been added'));
                $redirectUrl = Yii::$app->cache->get('crypto-pro-back-link' . Yii::$app->session->id);

                return $this->redirect($redirectUrl);
            }
        } else {
            return json_encode([
                'status' => 'processing',
                'msg' => ''
            ]);
        }
    }

    /**
     * Поиск значения fingerprint среди добавленных сертификатов КриптоПро по имени контейнера
     */
    private function getFingerprintByContainer($containerName)
    {
        $collection = CryptoProHelper::getCertInfo(null, [DriverCryptoPro::CONTAINER => $containerName]);
        $container = $collection->first();
        if ($container && isset($container[DriverCryptoPro::SHA1_HASH])) {
            return $container[DriverCryptoPro::SHA1_HASH];
        }

        return '';
    }

}