<?php
namespace addons\ISO20022\controllers;

use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022Search;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\UserHelper;
use common\helpers\ZipHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\participant\helpers\ParticipantHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
* Класс контроллера обслуживает операции с документами
*/
class DocumentsController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => ISO20022Module::SERVICE_ID],
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

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = [
            'class' => 'common\actions\documents\DeleteAction',
            'serviceId' => ISO20022Module::SERVICE_ID,
        ];
        return $actions;
    }

    /**
     * Метод возвращает список документов определённого типа для вывода в статистике кабинета
     * @param type $type
     * @param type $page
     * @param type $q
     * @return type
     */
    public function actionList($type, $page, $q = null)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        // Поисковая модель для ISO20022
        $searchModel = new ISO20022Search();

        // Выбрать тип документов
    	switch ($page) {
            case 'index':
                // Найти все документы ISO20022
                $dataProvider = $searchModel->search([]);
                break;
            case 'freeFormat':
                // Найти документы свободного формата
            	$dataProvider = $searchModel->searchFreeFormat([]);
            	break;
            case 'payments':
                // Найти документы платежных поручений
            	$dataProvider = $searchModel->searchPayments([]);
            	break;
            case 'statements':
                // Найти документы выписок
            	$dataProvider = $searchModel->searchStatements([]);
            	break;
            case 'foreign-currency-control':
                // Найти документы валютного контроля
            	$dataProvider = $searchModel->searchForeignCurrencyControl([]);
            	break;
        }

        // Выбрать тип фильтрации по отправителю или получателю
        switch ($type) {
            case 'sender':
                // Найти список отправителей для выбранных документов
                $out['results'] = ParticipantHelper::getSenderListForDocumentSearch($dataProvider, $q);
                break;
            case 'receiver':
                // Найти список получателей для выбранных документов
                $out['results'] = ParticipantHelper::getReceiverListForDocumentSearch($dataProvider, $q);
                break;

    	}

        return $out;
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        // Поисковая модель для документов ISO20022
        $searchModel = new ISO20022Search();
        // Найти список всех документов ISO20022 в БД
    	$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $settingsTypeCodes = $this->module->settings->typeCodes;

        // Вывести страницу индекса
        return $this->render('index', [
            'model'        => new Document(),
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'urlParams'    => $this->getSearchUrl('ISO20022Search'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'isoIndex',
            'settingsTypeCodes' => $settingsTypeCodes,
        ]);
    }

    /**
     * Метод обрабатывает страницу валютного контроля
     * @return type
     */
    public function actionForeignCurrencyControl()
    {
        // Поисковая модель для документов ISO20022
        $searchModel = new ISO20022Search();
        // Найти список документов валютного контроля в БД
        $dataProvider = $searchModel->searchForeignCurrencyControl(Yii::$app->request->queryParams);
        $settingsTypeCodes = $this->module->settings->typeCodes;

        // Вывести страницу валютного контроля
        return $this->render('foreignCurrencyControl', [
            'model'        => new Document(),
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'urlParams'    => $this->getSearchUrl('ISO20022Search'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'isoIndex',
            'settingsTypeCodes' => $settingsTypeCodes,
        ]);
    }

    /**
     * Метод обрабатывает страницу свободного формата - auth.026, auth.024
     * 
     */
    public function actionFreeFormat()
    {
        // Поисковая модель для документов ISO20022
        $searchModel = new ISO20022Search();
        // Найти список документов свободного формата в БД
        $dataProvider = $searchModel->searchFreeFormat(Yii::$app->request->queryParams);
        $settingsTypeCodes = $this->module->settings->typeCodes;

        // Вывести страницу свободного формата
        return $this->render('freeFormat', [
            'model'        => new Document(),
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'urlParams'    => $this->getSearchUrl('ISO20022Search'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'settingsTypeCodes' => $settingsTypeCodes,
            'listType' => 'isoFree',
        ]);
    }

    /**
     * Метод обрабатывает страницу выписок - camt.053, camt.054
     */
    public function actionStatements()
    {
        // Поисковая модель для документов ISO20022
        $searchModel = new ISO20022Search();
        // Найти список документов выписок в БД
        $dataProvider = $searchModel->searchStatements(Yii::$app->request->queryParams);

        // Вывести страницу выписок
        return $this->render('statements', [
            'model'        => new Document(),
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'urlParams'    => $this->getSearchUrl('ISO20022Search'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'isoStatements',
        ]);
    }

    /**
     * Метод обрабатывает страницу платежных документов - pain.001
     */
    public function actionPayments()
    {
        // Поисковая модель для документов ISO20022
        $searchModel = new ISO20022Search();
        // Найти список документов платежей в БД
    	$dataProvider = $searchModel->searchPayments(Yii::$app->request->queryParams);
        // Вывести страницу платежей
        return $this->render('payments', [
            'model'        => new Document(),
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'urlParams'    => $this->getSearchUrl('ISO20022Search'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'isoPayments',
        ]);
    }

    /**
     * Метод обрабатывает страницу просмотра документа
     * @param type $id
     * @param type $mode
     * @return type
     */
    public function actionView($id, $mode = '')
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        // Найти ссылающиеся документы
        $referencingDataProvider = new ActiveDataProvider([
            'query' => $document->findReferencingDocuments(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // Зарегистрировать событие просмотра документа
        // только если это новый просмотр (т.е. не переход по вкладкам)
        if (empty($mode)) {
            $previousUrl = Url::previous();
            $currentUrl = Url::current();

            // Если текущий url не равен предыдущему, запомнить его
            if (empty($previousUrl) || $previousUrl !== $currentUrl) {
                Url::remember();
            }

            // Если текущий url не равен предыдущему
            if ($previousUrl !== $currentUrl) {
                // Если документ не был просмотрен
                if (!$document->viewed) {
                    // Установить признак просмотра
                    $document->viewed = 1;
                    // Сохранить документ в БД
                    $document->save(false, ['viewed']);
                }

                // Зарегистрировать событие просмотра документа в модуле мониторинга
                Yii::$app->monitoring->log(
                    'user:viewDocument',
                    'document',
                    $id,
                    [
                        'userId' => Yii::$app->user->id,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );
            }
        }

        // Получить экс-модель документа
        $extModel = $document->extModel;
        // Выбрать режим отображения подписей
        $showSignaturesMask = is_null($extModel) ? 0 : Document::SIGNATURES_ALL;

        // Вывести страницу просмотра
        return $this->render('view', [
            'model' => $document,
            'mode' => $mode,
            'urlParams' => $this->getSearchUrl('ISO20022Search'),
            'referencingDataProvider' => $referencingDataProvider,
            'showSignaturesMask' => $showSignaturesMask
        ]);
    }

    /**
     * Метод обрабатывает страницу скачивания вложений
     * @param type $id
     * @return type
     */
    public function actionDownloadAttachment($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);
        // Получить тайп-модель из документа
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        // Список вложений
        $attachments = [];
        // Если модель использует сжатие в zip
        if ($typeModel->useZipContent) {
            // Распаковать архив
            $zipArchive = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
            // Получить список файлов в архиве
            $files = $zipArchive->getFileList();
            foreach ($files as $index => $file) {
                // Если имя файла начинается на 'attach_', то это вложение
                if (substr($file, 0, 7) == 'attach_') {
                    $fileName = substr($file, 7);
                    // Поместить вложение в список вложений
                    $attachments[] = [$fileName, $zipArchive->getFromIndex($index)];
                }
            }
        } else if ($typeModel instanceof Auth026Type && !empty($typeModel->embeddedAttachments)) {
            // Получить список имён файлов из модели
            $fileNames = ArrayHelper::getColumn($typeModel->getAttachedFileList(), 'name');
            // Получить файлы из модели
            $attachments = array_map(null, $fileNames, $typeModel->embeddedAttachments);
        }

        // Скачиваемый контент
        $downloadContent = null;
        // Имя скачиваемого файла
        $downloadName = null;
        if (count($attachments) == 1) {
            // Если вложение только одно, то поместить его в контент
            $downloadContent = $attachments[0][1];
            // Конвертировать кодировку имени файла
            $downloadName = mb_convert_encoding($attachments[0][0], 'utf8', 'cp866');
        } else if (count($attachments) > 1) {
            // Если вложений больше 1, поместить их во временный архив
            $zip = ZipHelper::createTempArchiveFileZip();
            foreach ($attachments as $fileData) {
                list($fileName, $downloadContent) = $fileData;
                $zip->addFromString($downloadContent, $fileName);
            }
            // Поместить созданный архив в скачиваемый контент
            $downloadContent = $zip->asString();
            // Удалить временный архив
            $zip->purge();
            $downloadName = $typeModel->zipFilename;
        } else {
            // Вложений нет, перенаправить на страницу просмотра
            return $this->redirect(['view', 'id' => $id]);
        }

        // Послать в браузер скачиваемый контент с именем файла
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->sendContentAsFile(
            $downloadContent,
            $downloadName,
            ['mimeType' => 'application/octet-stream']
        );
    }

    /**
     * Метод скачивает вложение по номеру
     * @param type $id тд документа
     * @param type $pos порядковый номер вложения
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDownloadAttachmentByNumber($id, $pos = 0)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        // Получить тайп-модель из документа
        /** @var Auth026Type $typeModel */
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        // Получить вложения из тайп-модели
        $attachedFiles = $typeModel->getAttachedFileList();

        try {
            // Если отсутствует вложение с порядковым номером, выбросить исключение
            if (!isset($attachedFiles[$pos])) {
                throw new \Exception("File offset $pos not found");
            }

            // Получить файл с порядковым номером
            $file = $attachedFiles[$pos];
            // Если модель использует сжатие в zip
            if ($typeModel->useZipContent) {
                // Создать зип-архив из контента модели
                $zip = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
                // Получить список файлов из архива
                $zipFiles = $zip->getFileList('cp866');
                // Найти в списке индекс файла по имени
                $fileIndex = array_search($file['path'], $zipFiles);
                if ($fileIndex === false) {
                    throw new \Exception('Zip archive does not contain file ' . $file['path']);
                }
                // Получить контент из файла по индексу
                $content = $zip->getFromIndex($fileIndex);
                $zip->purge();
            } else {
                // Получить контент из встроенныхъ в модель вложений
                $content = $typeModel->embeddedAttachments[$pos];
            }

            Yii::$app->response->sendContentAsFile($content, $file['name']);
        } catch (\Exception $exception) {
            Yii::warning("Failed to send attachment, caused by: $exception");

            throw new NotFoundHttpException();
        }
    }

    /**
     * Метод ищет модель в БД по первичному ключу.
     * @return Document
     */
    protected function findModel($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(ISO20022Search::className(), $id);
    }
}
