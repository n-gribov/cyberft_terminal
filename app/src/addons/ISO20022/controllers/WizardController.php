<?php
namespace addons\ISO20022\controllers;

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\helpers\RosbankHelper;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\form\WizardForm;
use addons\ISO20022\models\ISO20022DocumentExt;
use backend\controllers\helpers\TerminalCodes;
use common\base\BaseServiceController;
use common\components\TerminalId;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\models\listitem\AttachedFile;
use common\models\Terminal;
use Yii;
use yii\filters\AccessControl;

/**
 * Класс визарда содержит методы для создания документа
 */
class WizardController extends BaseServiceController
{
    use TerminalCodes;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    $this->traitBehaviorsRules,
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => ['serviceId' => ISO20022Module::SERVICE_ID],
                    ],
                ],
            ],
        ];
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
            $form->file = null;
            $form->subject = null;
            $form->descr = null;

            /**
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

        // Если данные модели успешно загружены из формы в браузере
        if ($form->load(Yii::$app->request->post())) {
            $recipient = TerminalId::extract($form->recipient);
            // Если указанный адрес получателя является адресом участника, то
            // учитываем код терминала для формирования финального адреса
            if ($recipient->getType() === TerminalId::TYPE_PARTICIPANT) {
                $recipient->terminalCode = strlen($form->terminalCode) == 1
                    ? $form->terminalCode
                    : TerminalId::DEFAULT_TERMINAL_CODE;
            }
            // Присвоить получателя в форме редактирования
            $form->recipient = (string)$recipient;
            // Кэшировать форму редактирования
            $this->cacheDocument($form);

            // Перенаправить на страницу 2-го шага визарда
            return $this->redirect(['step2']);
        }
        // Кэшировать форму редактирования
        $this->cacheDocument($form);

        // Вывести страницу индекса визарда
        return $this->render('index', [
            'model' => $form,
            'currentStep' => 1,
            'errors' => !empty($form->errors) ? $form->errors : false
        ]);
    }

    /**
     * Метод обрабатывает шаг 2 визарда
     * @return type
     */
    public function actionStep2()
    {
        // Получить кэшированный документ
        $form = $this->getCachedDocument();

        // Если в кэше нет документа, вернуться на первый шаг визарда
        if (!$form) {
            // Поместить в сессию флаг сообщения об отсутствии данных визарда
            Yii::$app->session->setFlash('error', Yii::t('doc', 'No wizard data available'));

            // Перенаправить на страницу индекса
            return $this->redirect('index');
        }

        if (Yii::$app->session->hasFlash('error')) {
            $form->validate();
        }

        // Вывести страницу 2-го шага визарда в нужном режиме отображения
        return $this->render('index', [
            'model' => $form,
            'currentStep' => 2,
            'settings' => $this->module->settings
        ]);
    }

    public function actionStep3()
    {
        // Получить кэшированный документ
        $form = $this->getCachedDocument();
        // Если в кэше нет документа, вернуться на первый шаг визарда
        if (!$form) {
            // Поместить в сессию флаг сообщения об отсутствии данных визарда
            Yii::$app->session->setFlash('error', Yii::t('doc', 'No wizard data available'));
            // Перенаправить на страницу индекса
            return $this->redirect('index');
        }
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            if (empty(Yii::$app->request->post('wizardComplete'))) {
                // Загрузить данные модели из формы в браузере
                $form->load(Yii::$app->request->post());
                // Кэшировать форму редактирования
                $this->cacheDocument($form);
                // Если форма не валидируется
                if (!$form->validate()) {
                    // Поместить в сессию флаг сообщения о некорректных данных визарда
                    Yii::$app->session->setFlash('error', Yii::t('doc', 'Invalid wizard data'));

                    // Перенаправить на страницу 2-го шага визарда
                    return $this->redirect(['step2']);
                }
            } else {
                $documentId = $this->createAuth026Document($form);
                if (empty($documentId)) {
                    // Поместить в сессию флаг сообщения об ошибке создания документа
                    Yii::$app->session->setFlash('error', Yii::t('doc', '{type} document creation failed', ['type' => 'auth.026']));

                    // Перенаправить на страницу 2-го шага визарда
                    return $this->redirect(['step2']);
                }
                // Очистить кэшированный документ
                $this->clearCachedDocument();

                // Поместить в сессию флаг сообщения об успешном создании документа
                Yii::$app->session->setFlash('success', Yii::t('doc', '{type} document created', ['type' => Auth026Type::TYPE]));

                // Перенаправить на страницу просмотра
                return $this->redirect(['/ISO20022/documents/view', 'id' => $documentId]);
            }
        }

        // Вывести страницу 3-го шага визарда
        return $this->render('index', [
            'model' => $form,
            'currentStep' => 3,
        ]);
    }

    /**
     * Метод создаёт документ auth.026
     * @param WizardForm $form
     * @return bool
     */
    public function createAuth026Document(WizardForm $form)
    {
        // Создать тайп-модель из данных формы визарда
        $typeModel = new Auth026Type([
            'typeCode' => $form->typeCode,
            'dateCreated' => time(),
            'sender' => $form->sender,
            'receiver' => $form->recipient,
            'subject' => $form->subject,
            'descr' => $form->descr,
            'numberOfItems' => 1,
        ]);

        // Получить вложение из данных формы визарда
        $attachedFile = $this->getAttachedFile($form);

        // Если есть вложение
        if (!empty($attachedFile)) {
            // Если терминал является шлюзом Росбанка
            if (RosbankHelper::isGatewayTerminal($form->recipient)) {
                // Внедрить вложение в модель
                $typeModel->addEmbeddedAttachment($attachedFile->name, $attachedFile->path);
            } else {
                // Поместить вложения в модель в виде zip
                ISO20022Helper::attachZipContent($typeModel, [$attachedFile]);
            }
        }
        // Сформировать XML
        $typeModel->buildXML();
        // Найти терминал отправителя
        $terminal = Terminal::findOne(['terminalId' => $typeModel->sender]);
        // Атрибуты документа
        $docAttributes = [
            'direction' => Document::DIRECTION_OUT,
            'origin' => Document::ORIGIN_WEB,
            'sender' => $typeModel->sender,
            'receiver' => $typeModel->receiver,
            'terminalId' => $terminal->id
        ];

        // Атрибуты расширяющей модели
        $extModelAttributes = [
            'typeCode' => $form->typeCode,
            'subject' => $form->subject,
            'descr' => $form->descr,
            'fileName' => $attachedFile ? $attachedFile->name : null,
            'extStatus' => ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING,
            'msgId' => $typeModel->msgId
        ];

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            $docAttributes,
            $extModelAttributes
        );

        // Если контекст не создался, вернуть ошибку
        if (!isset($context['document'])) {
            return false;
        }

        // Получить документ из контекста
        $document = $context['document'];
        Yii::$app->resque->enqueue('addons\ISO20022\jobs\CryptoProSignJob', ['id' => $document->id]);

        return $document->id;
    }

    /**
     * @param WizardForm $doc
     */
    protected function cacheDocument($doc)
    {
        Yii::$app->cache->set('ISO20022/wizard/doc-' . Yii::$app->session->id, $doc);
    }

    /**
     * Метод возвращает кэшированный документ
     * @return WizardForm
     */
    protected function getCachedDocument()
    {
        $doc = Yii::$app->cache->get('ISO20022/wizard/doc-' . Yii::$app->session->id);
        if (!($doc instanceof WizardForm)) {
            return null;
        }

        return $doc;
    }

    protected function clearCachedDocument()
    {
        // Удалить кешированный документ
        Yii::$app->cache->delete('ISO20022/wizard/doc-' . Yii::$app->session->id);
    }

    private function getAttachedFile(WizardForm $form)
    {
        $fileData = $form->file;

        if (is_array($fileData)) {
            $attachFilename = $fileData['name'];

            $fileParts = FileHelper::mb_pathinfo($attachFilename);
            $attachFilename = 'attach_' . $fileParts['filename'];

            if (Yii::$app->settings->get('ISO20022:ISO20022')->useUniqueAttachmentName) {
                $attachFilename .= '_' . $form->sender . date('YmdHis');
            }

            if (isset($fileParts['extension'])) {
                $attachFilename .= '.' . $fileParts['extension'];
            }

            return new AttachedFile([
                'name' => $attachFilename,
                'path' => $fileData['path']
            ]);
        }

        return null;
    }
}
