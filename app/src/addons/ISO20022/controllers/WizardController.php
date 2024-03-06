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

    public function actionIndex()
    {
        /** @var WizardForm $form */
        $form = $this->getCachedDocument();

        if (!$form) {
            $form = new WizardForm();
        } else {
            // пока что тупо удалим все файлы, бывшие на шаге 2
            $form->file = null;
            $form->subject = null;
            $form->descr = null;

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

        $form->sender = Yii::$app->terminals->defaultTerminal->terminalId;

        if ($form->load(Yii::$app->request->post())) {
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

            $form->recipient = (string)$recipient;
            $this->cacheDocument($form);

            return $this->redirect(['step2']);
        }

        $this->cacheDocument($form);

        // Далее отрисовываем первый шаг визарда
        return $this->render('index', [
            'model'       => $form,
            'currentStep' => 1,
            'errors'      => !empty($form->errors) ? $form->errors : false
        ]);
    }

    public function actionStep2()
    {
        $form = $this->getCachedDocument();

        // Если в кэше нет документа, возвращаемся на первый шаг визарда
        if (!$form) {
            Yii::$app->session->setFlash('error', Yii::t('doc', 'No wizard data available'));

            return $this->redirect(['index']);
        }

        if (Yii::$app->session->hasFlash('error')) {
            $form->validate();
        }

        // Далее будет отрисован 2-й шаг визарда в нужном режиме отображения
        return $this->render('index', [
            'model'       => $form,
            'currentStep' => 2,
            'settings' => $this->module->settings
        ]);
    }

    public function actionStep3()
    {
        $form = $this->getCachedDocument();
        // Если в кэше нет документа, возвращаемся на первый шаг визарда
        if (!$form) {
            Yii::$app->session->setFlash('error', Yii::t('doc', 'No wizard data available'));
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->isPost) {
            if (empty(Yii::$app->request->post('wizardComplete'))) {

                $form->load(Yii::$app->request->post());
                $this->cacheDocument($form);

                if (!$form->validate()) {
                    Yii::$app->session->setFlash('error', Yii::t('doc', 'Invalid wizard data'));

                    return $this->redirect(['step2']);
                }
            } else {

                $documentId = $this->createAuth026Document($form);
                if (empty($documentId)) {
                    Yii::$app->session->setFlash('error', Yii::t('doc', '{type} document creation failed', ['type' => 'auth.026']));

                    return $this->redirect(['step2']);
                }

                $this->clearCachedDocument();

                Yii::$app->session->setFlash('success', Yii::t('doc', '{type} document created', ['type' => Auth026Type::TYPE]));

                return $this->redirect(['/ISO20022/documents/view', 'id' => $documentId]);
            }
        }

        return $this->render('index', [
            'model'       => $form,
            'currentStep' => 3,
        ]);
    }

    public function createAuth026Document(WizardForm $form)
    {
        $typeModel = new Auth026Type([
            'typeCode' => $form->typeCode,
            'dateCreated' => time(),
            'sender' => $form->sender,
            'receiver' => $form->recipient,
            'subject' => $form->subject,
            'descr' => $form->descr,
            'numberOfItems' => 1,
        ]);

        $attachedFile = $this->getAttachedFile($form);

        if (!empty($attachedFile)) {
            if (RosbankHelper::isGatewayTerminal($form->recipient)) {
                $typeModel->addEmbeddedAttachment($attachedFile->name, $attachedFile->path);
            } else {
                ISO20022Helper::attachZipContent($typeModel, [$attachedFile]);
            }
        }

        $typeModel->buildXML();

        $terminal = Terminal::findOne(['terminalId' =>  $typeModel->sender]);

        $docAttributes = [
            'direction' => Document::DIRECTION_OUT,
            'origin' => Document::ORIGIN_WEB,
            'sender' => $typeModel->sender,
            'receiver' => $typeModel->receiver,
            'terminalId' => $terminal->id
        ];

        $extModelAttributes = [
            'typeCode' => $form->typeCode,
            'subject' => $form->subject,
            'descr' => $form->descr,
            'fileName' => $attachedFile ? $attachedFile->name : null,
            'extStatus' => ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING,
            'msgId' => $typeModel->msgId
        ];

        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            $docAttributes,
            $extModelAttributes
        );

        if (!isset($context['document'])) {
            return false;
        }

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
        Yii::$app->cache->delete('ISO20022/wizard/doc-' . Yii::$app->session->id);
    }

    private function getAttachedFile(WizardForm $form)
    {
        $fileData = $form->file;

        if (is_array($fileData)) {
            $attachFilename = $fileData['name'];

            $fileParts = FileHelper::mb_pathinfo($attachFilename);
            $attachFilename = 'attach_' . $fileParts['filename']; // mb_substr($fileParts['filename'], 0, 62);

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