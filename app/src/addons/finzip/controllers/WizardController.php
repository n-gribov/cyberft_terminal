<?php
namespace addons\finzip\controllers;

use addons\finzip\FinZipModule;
use addons\finzip\models\FinZipType;
use addons\finzip\models\form\WizardForm;
use backend\controllers\helpers\TerminalCodes;
use common\base\BaseServiceController;
use common\components\TerminalId;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\helpers\UserHelper;
use common\helpers\Uuid;
use common\models\Terminal;
use common\modules\autobot\models\Autobot;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\UploadedFile;
use ZipArchive;

class WizardController extends BaseServiceController
{
    use TerminalCodes;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    $this->traitBehaviorsRules,
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => ['serviceId' => FinZipModule::SERVICE_ID],
                    ],
                ],
            ],
        ];
    }

    public function actionDelete($id)
    {
        // Получить кэшированный документ
        $form = $this->getCachedDocument();

        if (!in_array($id, array_keys($form->getFiles()))) {
            // Поместить в сессию флаг сообщения об отсутствующем файле
            Yii::$app->session->setFlash('error', Yii::t('app/error', 'File not found!'));
        } else {
            $form->removeFile($id);
            // Поместить в сессию флаг сообщения об успешном удалении файла
            Yii::$app->session->setFlash('success', Yii::t('app', 'File deleted'));
            // Кэшировать форму редактирования
            $this->cacheDocument($form);
        }

        // Перенаправить на страницу 2-го шага визарда
        $this->redirect(['step2']);
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        /** @var WizardForm $form */
        // Получить кэшированный документ
        $form = $this->getCachedDocument();

        if (!$form) {
            $form = new WizardForm();
        } else {
            // пока что тупо удалим все файлы, бывшие на шаге 2
            $form->clearFiles();
            $form->clearErrors();
            $form->subject = null;
            $form->descr = null;
            // Кэшировать форму редактирования
            $this->cacheDocument($form);
            /*
             * @todo Обратное преобразование получателя, если мы вернулись на шаг 1
             * (если модель уже существует)
             * Необходимо для правильной инициализации Select2 для выбора получателя
             */
            if (($extracted = TerminalId::extract($form->recipient))) {
                $recipient = $extracted->participantId;
                $form->recipient = $recipient;
                $form->terminalCode = $extracted->terminalCode;
            }
        }

        $form->sender = Yii::$app->exchange->defaultTerminal->terminalId;
        $form->uuid = Uuid::generate();

        // Если данные модели успешно загружены из формы в браузере
        if ($form->load(Yii::$app->request->post())) {

            // Проверить активный ключ контролера для отправителя
            if (!$this->isAutobotKey($form->sender)) {
                // Поместить в сессию флаг сообщения о ненайденном пользователе
                Yii::$app->session->setFlash('error',
                    Yii::t(
                        'app/autobot',
                        'Not found the used for signing key of the controller to the terminal {terminalId}',
                        ['terminalId' => $form->sender]
                    )
                );

                // Вывести страницу
                return $this->render('index', [
                    'model' => $form,
                    'currentStep' => 1,
                ]);
            }

            $recipient = TerminalId::extract($form->recipient);
            /**
             * Если указанный адрес получателя является адресом участника, то
             * учитываем код терминала для формирования финального адреса
             */
            if ($recipient->getType() === TerminalId::TYPE_PARTICIPANT) {
                $recipient->terminalCode = strlen($form->terminalCode) == 1
                    ? $form->terminalCode
                    : TerminalId::DEFAULT_TERMINAL_CODE;
            }
            $form->recipient = (string) $recipient;
            // Кэшировать форму редактирования
            $this->cacheDocument($form);
            // Перенаправить на страницу 2-го шага визарда
            return $this->redirect(['step2']);
        }

        // Вывести первый шаг визарда
        return $this->render('index', [
            'model'       => $form,
            'currentStep' => 1,
            'errors'      => !empty($errors) ? $errors : false
        ]);
    }

    public function actionStep2()
    {
        $fromId = Yii::$app->request->get('fromId');

        if ($fromId) {
            $this->createFromExistingDocument($fromId);
        }

        // Получить кэшированный документ
        $form = $this->getCachedDocument();

        $signNum = $this->module->getSignaturesNumber($form->sender);
        $userCanSignDocuments = \Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => FinZipModule::SERVICE_ID]);

        // Если в кэше нет документа, возвращаемся на первый шаг визарда
        if (!$form) {
            // Поместить в сессию флаг сообщения об отсутствии данных визарда
            Yii::$app->session->setFlash('error', Yii::t('doc', 'No wizard data available'));
            // Перенаправить на страницу индекса
            return $this->redirect('index');
        }

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('WizardForm');

            $form->subject = $post['subject'];
            $form->descr = $post['descr'];

            $files = UploadedFile::getInstancesByName('file');

            // Если выбраны файлы, то добавляем их в форму
            if ($files) {

                $totalSize = 0;
                $formFiles = $form->getFiles();
                foreach ($formFiles as $file) {
                    $totalSize += $file['size'];
                }

                foreach ($files as $file) {
                    $totalSize += $file->size;
                }

                if ($totalSize > 1024 * 1024 * 100) {
                    // Поместить в сессию флаг сообщения о превышении размера файла
                    Yii::$app->session->setFlash('error', Yii::t('doc', 'Files total size must be less than 100 Mb'));
                } else {
                    // Попытка добавить файлы
                    try {
                        $form->clearErrors('file');
                        $resourceTemp = Yii::$app->registry->getTempResource($this->module->getServiceId());

                        foreach ($files as $file) {
                            $fileInfo = $resourceTemp->putFile($file->tempName, $file->name);

                            if (!$form->addFile($file->name, $fileInfo['path'], $file->size)) {
                                break;
                            }
                        }

                        $form->validate();
                    } catch (Exception $ex) {
                        // Если во время добавления файлов возникла ошибка
                        // Поместить в сессию флаг сообщения об ошибке добавления файлов
                        Yii::$app->session->setFlash(
                            'error',
                            Yii::t('doc', 'Adding files error: ' . $ex->getMessage() . ' ' . $ex->getFile() . ':' . $ex->getLine())
                        );
                    }
                }
                // Кэшировать форму редактирования
                $this->cacheDocument($form);

                // Перенаправить на страницу 2-го шага визарда
                return $this->redirect(['step2']);
            } else {
                // Иначе переходим к созданию документа

                // Валидируем документ
                if (!$form->validate()) {
                    // Ошибка валидации документа
                    // Кэшировать форму редактирования
                    $this->cacheDocument($form);

                    // Перенаправить на страницу 2-го шага визарда
                    return $this->redirect(['step2']);
                }

                // Создание документа

                $document = $this->createFinZipDocument($form);
                if (!$document) {
                    // Поместить в сессию флаг сообщения об ошибке создания документа
                    Yii::$app->session->setFlash('error', Yii::t('doc', '{type} document creation failed', ['type' => 'FinZip']));

                    // Перенаправить на страницу 2-го шага визарда
                    return $this->redirect(['step2']);
                }

                Url::remember();
                // Очистить кэшированный документ
                $this->clearCachedDocument();
                // Отправить документ на обработку в транспортном уровне
                DocumentTransportHelper::processDocument($document, true);

                if ($document->status == Document::STATUS_FORSIGNING) {
                    // Перенаправить на страницу просмотра
                    return $this->redirect(['/finzip/default/view', 'id' => $document->id, 'triggerSigning' => 1]);
                }

                // Перенаправить на страницу индекса
                return $this->redirect(['/finzip/default/index']);
            }
        }
        // Вывести страницу
        return $this->render('index', [
            'model'       => $form,
            'currentStep' => 2,
            'dataProvider' => new ArrayDataProvider(['allModels' => $form->getFiles()]),
            'userCanSignDocuments' => $userCanSignDocuments,
            'signNum' => $signNum
        ]);
    }

    /**
     * Формирует FinZip + CyberXml документы из данных кешированной формы
     * @param type $form
     * @return object
     */
    public function createFinZipDocument($form)
    {
        $typeModel = new FinZipType([
            'subject' => $form->subject,
            'descr' => $form->descr,
            'sender' => $form->sender,
            'recipient' => $form->recipient,
        ]);

        // Находим объект терминала по его наименованию
        $terminal = Terminal::findOne(['terminalId' => $typeModel->sender]);

        if (empty($terminal)) {
            $terminal = Yii::$app->exchange->defaultTerminal;
        }

        $fileList = $form->hasFiles() ? $form->getFiles() : [];

        // Сохраняем зип в storage
        if (!$this->module->saveFinZip($typeModel, $fileList)) {
            Yii::error('Error saving zip archive to storage');

            return false;
        }

        // Удаление временных файлов вложений
        foreach($fileList as $file) {
            unlink($file['path']);
        }

        Yii::$app->exchange->setCurrentTerminalId($typeModel->sender);
        // Атрибуты документа
        $docAttributes = [
            'direction' => Document::DIRECTION_OUT,
            'origin' => Document::ORIGIN_WEB,
            'sender' => $typeModel->sender,
            'receiver' => $typeModel->recipient,
            'terminalId' => $terminal->id,
            'isEncrypted' => true
        ];

        // Атрибуты расширяющей модели
        $extModelAttributes = [
            'fileCount' => $typeModel->fileCount,
            'subject' => Yii::$app->xmlsec->encryptData($typeModel->subject, true),
            'descr' => Yii::$app->xmlsec->encryptData($typeModel->descr, true),
            'zipStoredFileId' => $typeModel->zipStoredFileId
        ];

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            $docAttributes,
            $extModelAttributes
        );

        return $context ? $context['document'] : false;
    }

    /**
     * @param WizardForm $doc
     */
    protected function cacheDocument($doc)
    {
        Yii::$app->cache->set('finzip/wizard/doc-' . Yii::$app->session->id, $doc);
    }

    /**
     * Метод возвращает кэшированный документ
     * @return WizardForm
     */
    protected function getCachedDocument()
    {
        $doc = Yii::$app->cache->get('finzip/wizard/doc-' . Yii::$app->session->id);
        if (!($doc instanceof WizardForm)) {
            return null;
        }

        return $doc;
    }

    protected function clearCachedDocument()
    {
        Yii::$app->cache->delete('finzip/wizard/doc-' . Yii::$app->session->id);
    }

    protected function createFromExistingDocument($id)
    {
        $document = Document::findOne($id);

        if (!$document) {
            throw new Exception('Could not find the document from which the new one should be created');
        }

        $extModel = $document->extModel;

        if (!$extModel) {
            throw new Exception('Failed to get document extModel');
        }

        Yii::$app->exchange->setCurrentTerminalId($document->sender);

        $form = new WizardForm();
        $form->subject = Yii::$app->xmlsec->decryptData($extModel->subject, true);
        $form->descr = Yii::$app->xmlsec->decryptData($extModel->descr, true);
        $form->sender = $document->sender;
        $form->recipient = $document->receiver;

        $storedFile = Yii::$app->storage->get($extModel->zipStoredFileId);
        $data = Yii::$app->storage->decryptStoredFile($storedFile->id);

        $resourceTemp = Yii::$app->registry->getTempResource($this->module->getServiceId());
        $fileInfo = $resourceTemp->putData($data);

        $filePath = $fileInfo['path'];

        $extractPath = Yii::getAlias('@temp/' . FileHelper::uniqueName());

        $zipStoredFile = new ZipArchive;
        $zipStoredFile->open($filePath);

        $files = [];
        for ($i = 0; $i < $zipStoredFile->numFiles; $i++) {
            $filename = $zipStoredFile->getNameIndex($i);

            if ($filename == 'FINZIP_message.txt') {
                continue;
            }

            $files[] = $filename;
        }

        $zipStoredFile->extractTo($extractPath, $files);
        $zipStoredFile->close();

        foreach ($files as $file) {
            $extractedFilePath = $extractPath . '/' . $file;

            $basename = FileHelper::mb_basename($extractedFilePath);;
            $filesize = filesize($extractedFilePath);

            $fileInfo = $resourceTemp->putFile($extractedFilePath, $basename);

            $form->addFile($basename, $fileInfo['path'], $filesize);
        }

        FileHelper::removeDirectory($extractPath);
        // Кэшировать форму редактирования
        $this->cacheDocument($form);
    }

    private function isAutobotKey($terminal)
    {
        $autobot = Autobot::find()
            ->joinWith('controller.terminal')
            ->where([
                'primary' => true,
                'autobot.status' => Autobot::STATUS_USED_FOR_SIGNING,
                'terminal.terminalId' => $terminal
            ])
            ->one();

        return $autobot;
    }

}
