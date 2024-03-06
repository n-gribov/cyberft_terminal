<?php

namespace addons\swiftfin\controllers;

use addons\swiftfin\helpers\SwiftfinHelper;
use addons\swiftfin\models\form\WizardForm;
use addons\swiftfin\models\SwiftFinType;
use addons\swiftfin\SwiftfinModule;
use backend\controllers\helpers\TerminalCodes;
use common\base\BaseServiceController;
use common\components\TerminalId;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\helpers\UserHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

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
                        'roleParams' => ['serviceId' => SwiftfinModule::SERVICE_ID],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        /**
         * Get WizardForm from step1 cache
         *
         * @var WizardForm $form
         */
        $form = $this->getCachedStep1();

        if (!$form) {
            $form = new WizardForm();
        } else {
            /*
             * @todo Обратное преобразование получателя, если мы вернулись на шаг 1
             * (если модель уже существует)
             * Необходимо для правильной инициализации Select2 для выбора получателя
             */
            if (($extracted = TerminalId::extract($form->recipient))) {
                $recipient			 = $extracted->participantId;
                $form->recipient	 = $recipient;
                $form->terminalCode	 = $extracted->terminalCode;
            }
        }

        if (empty($form->sender)) {
            $form->sender = Yii::$app->terminals->defaultTerminal->terminalId;
        }

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
            $form->recipient = (string) $recipient;

            $cachedDocument = $this->getCachedDocument();

            if ($cachedDocument) {
                $form->setContent($cachedDocument->getContent());
            }

            $this->cacheDocument($form);

            /**
             * Save step1 to cache or clear cache
             */

            $this->cacheStep1($form);

            return $this->redirect(['step2']);
        } else {
            if (Yii::$app->cache->exists('swiftfin/template-text')) {

                // Если использован шаблон, то кэшируем данные для следующих шагов
                $template = Yii::$app->cache->get('swiftfin/template-text');
                $form->setContent($template);
                $this->cacheDocument($form);
            }
        }

        // Далее отрисовываем первый шаг визарда
        return $this->render('index',
            [
                'model' => $form,
                'currentStep' => 1,
                //'errors' => !empty($errors) ? $errors : false
            ]);
    }

    public function actionStep2()
    {
        $viewMode	 = 'view';

        $form		 = $this->getCachedDocument();

        // Ajax-валидация данных формы
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format	 = Response::FORMAT_JSON;
            $model						 = $form->contentModel;
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format	 = Response::FORMAT_JSON;
            // @todo разрулить корректную отдачу сообщений об ошибках валидации
            $result						 = ActiveForm::validate($model);
            $key						 = key($result);
            $newKey						 = str_replace('-0', '', $key);
            return [
                $newKey => $result[$key]
            ];
        }

        // Если в кэше нет документа, возвращаемся на первый шаг визарда
        if (empty($form)) {
            return $this->redirect(['index']);
        }

        // Если вошли в форму через запрос POST
        if (Yii::$app->request->isPost) {
            $form->unsetContentModel(); // Создаем НОВЫЙ вложенный документ
            $model = $form->getContentModel();
            // Если запрос пришел из текстового представления документа
            if (Yii::$app->request->post('rawMode')) {
                $post			 = Yii::$app->request->post($model->formName());
                $form->rawMode	 = true;
                $form->setContent(!empty($post['body']) ? $post['body'] : '');
            } else {
                // сетим атрибуты из полей формы
                $form->rawMode = false;
                $model->load(Yii::$app->request->post());
            }

            /**
             * указываем чтобы ошибки не очищались, т.к. если читали из текста в MtUniversal то нужно
             * отобразить ошибки в форме при переключении режимов
             */
            $model->validate(null, false);
            $this->cacheDocument($form);

            // Выполняем проверку данных документа
            if (!$model->hasErrors()) {
                // Если это не переключение режима отображения формы-визарда,
                // переходим к шагу 3
                if (!Yii::$app->request->post('viewmode')) {
                    return $this->redirect(['step3']);
                }
            }
        } else { // Данные не требуется обновлять
            $model = $form->getContentModel(); // Берем ранее закешированные данные
            $model->clearErrors(); // чтобы убрать закешированные ошибки
        }

        /**
         * @todo костыль, рефакторинг
         * Данное свойство есть только у MtXXXDocument, если его нет, то считаем что ожидается
         * отображение в режиме формы
         */

        if (!isset($model->formable) || $model->formable === true) {
            $viewMode = 'form';
        }

        // Далее будет отрисован 2-й шаг визарда в нужном режиме отображения
        return $this->render('index',
            [
                'model' => $model,
                'currentStep' => 2,
                'errors' => !empty($model->errors) ? $model->errors : false,
                'viewMode' => $viewMode,
                'formable' => isset($model->formable) && $model->formable, // только у MtUniversal
                'textEdit' => isset($model->textEdit) && $model->textEdit, // только у MtUniversal
                'documentId'  => $this->getCachedEditData(),
            ]);
    }

    public function actionStep3()
    {
        $form = $this->getCachedDocument();

        if (!$form) {
            Yii::$app->session->setFlash('error',
                Yii::t('doc', 'No wizard data available'));
            return $this->redirect(['/swiftfin/wizard/index']);
        }

        if (!$form->validate()) {
            Yii::$app->session->setFlash('error',
                Yii::t('doc', 'Invalid wizard data').': '.$this->getErrorMessage($form->getErrors()));
            return $this->redirect(['/swiftfin/wizard/step2']);
        }

        $swt = $form->getSwtContainer();

        if (!$swt->validate()) {
            Yii::$app->session->setFlash('error',
                Yii::t('doc', 'Invalid wizard data').': '.$this->getErrorMessage($swt->getErrors()));
            return $this->redirect(['/swiftfin/wizard/step2']);
        }

        $documentId = $this->getCachedEditData();

        $swt->scenario = 'default';

        if (Yii::$app->request->isPost) {
            // Проверка на дублирование референса операции
            $operationReference = $swt->getOperationReference();

            $referenceDuplicate = SwiftfinHelper::checkOperationReferenceExisted($operationReference, $form->sender);

            if ($referenceDuplicate) {
                Yii::$app->session->setFlash('error', Yii::t('app/swiftfin',
                    'Reference {id} is already used in an another operation', [
                        'id' => $operationReference
                    ]
                ));
                return $this->redirect(['/swiftfin/wizard/index']);
            }

            if (!$swt->validate()) {
                return $this->redirect(['/swiftfin/wizard/index']);
            }

            $tempFile		 = Yii::getAlias('@temp/' . FileHelper::uniqueName() . '.swt');
            $swt->sourceFile = $tempFile;
            $swt->save();

            $typeModel = SwiftFinType::createFromFile($tempFile);

            // Находим объект терминала по его наименованию
            $terminal = Terminal::findOne(['terminalId' => $form->sender]);

            if (empty($terminal)) {
                $terminal = Yii::$app->terminals->defaultTerminal;
            }

            /** @var Document $document */
            $document = (!empty($documentId))
                    ? $this->editDocument($documentId, $typeModel)
                    : $this->createDocument($typeModel, $terminal->id);
			if ($document !== false && !$document->hasErrors()) {
				$this->clearCachedDocument();
                $this->clearCachedEditData();
                $this->clearCachedStep1();

                // Очищаем кэш шаблона, если он существует
                if (Yii::$app->cache->exists('swiftfin/template-text')) {
                    Yii::$app->cache->delete('swiftfin/template-text');
                }

				return $this->redirect(['/swiftfin/documents/view/', 'id' => $document->id]);

			} else {
				Yii::$app->session->setFlash('error', Yii::t('doc', 'Invalid wizard data'));
			}
        }

        $errors = $swt->getErrors();

        // Далее будет отрисован 3-й шаг визарда
        return $this->render('index',
            [
                'model' => $swt,
                'currentStep' => 3,
                'errors' => !empty($errors) ? $errors : false,
                'documentId'  => $documentId,
            ]);
    }

    public function actionWizardPrint()
    {
        $model = $this->getCachedDocument()->getSwtContainer();
        if (!$model || !$model->validate()) {
            Yii::$app->session->setFlash('error', Yii::t('doc', 'Invalid wizard data'));
            return $this->redirect(['index']);
        }

        return $this->renderPartial('wizprint', [
            'model' => $model
        ]);
    }

    /**
     * Edit action
     *
     * @param integer $id Document ID
     * @return mixed
     * @throws NotFoundHttpException
     */
	public function actionEdit($id)
	{
		$document = Yii::$app->terminalAccess->findModel(Document::className(), $id);

        $model = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
        if ($model === false){
            Yii::warning("Get type model for document ID[{$document->id}] in storage ID[{$document->getValidStoredFileId()}] error!");

            throw new NotFoundHttpException();
        }

        $form               = new WizardForm();
        $form->recipient    = $model->recipient;
        $form->sender       = $model->sender;
        $form->terminalCode = TerminalId::extract($form->recipient)->terminalCode;
        $form->contentType  = $model->contentType;
        $form->setContent($model->source->content);

		$this->cacheEditData($document->id);
		$this->cacheDocument($form);
        $this->cacheStep1($form);

        return $this->redirect(['index']);
	}

    public function actionClearWizardCache()
    {
        $this->clearCachedDocument();
        $this->clearCachedStep1();
    }

    /**
     * Создание документа на основе имеющегося
     * @param $id
     */
    public function actionCreateFromExistingDocument($id)
    {
        if (!Yii::$app->user->can(DocumentPermission::CREATE, ['serviceId' => SwiftfinModule::SERVICE_ID])) {
            throw new ForbiddenHttpException('You have not permissions to create Swiftfin documents');
        }

        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        $container = $typeModel->source;

        // Кэширование данных нового документа
        $wizard = new WizardForm;
        $wizard->contentType = $typeModel->contentType;
        $wizard->sender = $typeModel->sender;
        $wizard->recipient = $typeModel->recipient;
        $wizard->terminalCode = $typeModel->terminalCode;
        $wizard->bankPriority = $container->bankPriority;

        Yii::$app->cache->set('swiftfin/wizard/step1-' . Yii::$app->session->id, $wizard);
        Yii::$app->cache->set('swiftfin/template-text', $container->content);

        return $this->redirect(['/swiftfin/wizard/']);
    }

    /**
	 * Create document
	 *
	 * @param Document $model Document model
	 * @return Document|boolean
	 * @throws Exception
	 */
    protected function createDocument($model, $terminalId)
    {
        try {
            $document = DocumentHelper::reserveDocument(
                    $model->getType(),
                    Document::DIRECTION_OUT,
                    Document::ORIGIN_WEB,
                    $terminalId
            );

            if ($document) {

                DocumentHelper::createCyberXml($document, $model);

                // Регистрация события создания документа
                Yii::$app->monitoring->log('user:createDocument', 'document', $document->id, [
                        'userId' => Yii::$app->user->id,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );

                return $document;
            } else {
                throw new Exception(yii::t('app', 'Save document error'));
            }

		} catch (Exception $ex) {
			Yii::$app->session->setFlash('error', $ex->getMessage());
		}

        return false;
    }

    /**
     * Edit document
     *
     * @param integer  $documentId Document ID
     * @param Document $model      Document model
     * @return boolean
     * @throws Exception
     */
    protected function editDocument($documentId, $model)
    {
        try {
            $document = Yii::$app->terminalAccess->findModel(Document::className(), $documentId);

            $params = [
                'code' => 'DocumentEdit',
                'entity' => 'document',
                'entityId' => $document->id,
                'typeModel' => serialize($model),
            ];

            $result = Yii::$app->commandBus->addCommand(Yii::$app->user->id, 'DocumentEdit', $params);

            if (!$result) {
                throw new Exception(Yii::t('doc', 'Add command error'));
            }

            // Регистрация события изменения документа
            Yii::$app->monitoring->extUserLog('EditDocument', ['documentId' => $document->id]);

            return $document;
        } catch (\Exception $ex) {
            Yii::$app->session->setFlash('error', $ex->getMessage());

            return false;
        }
    }

    /**
     * Save document to cache
     *
     * @param WizardForm|Document $doc
     */
    protected function cacheDocument($doc)
    {
        Yii::$app->cache->set('swiftfin/wizard/doc-' . Yii::$app->session->id, $doc);
    }

    /**
     * Get document from cache
     *
     * @return WizardForm|Document|FALSE
     */
    protected function getCachedDocument()
    {
        $doc = Yii::$app->cache->get('swiftfin/wizard/doc-' . Yii::$app->session->id);

        if (!($doc instanceof WizardForm)) {
            return false;
        }

        return $doc;
    }

    /**
     * Clear document cache
     */
    protected function clearCachedDocument()
    {
        if (Yii::$app->cache->exists('swiftfin/wizard/doc-' . Yii::$app->session->id)) {
            Yii::$app->cache->delete('swiftfin/wizard/doc-' . Yii::$app->session->id);
        }
    }

    /**
     * Set step 1 to cache
     *
     * @param WizardForm $doc
     */
    protected function cacheStep1($doc)
    {
        Yii::$app->cache->set('swiftfin/wizard/step1-' . Yii::$app->session->id, $doc);
    }

    /**
     * Get step1 from cache
     *
     * @return WizardForm|FALSE
     */
    protected function getCachedStep1()
    {
        $doc = Yii::$app->cache->get('swiftfin/wizard/step1-' . Yii::$app->session->id);
        if (!($doc instanceof WizardForm)) {
            return false;
        }

        return $doc;
    }

    /**
     * Delete step1 from cache
     */
    protected function clearCachedStep1()
    {
        if (Yii::$app->cache->exists('swiftfin/wizard/step1-' . Yii::$app->session->id)) {
            Yii::$app->cache->delete('swiftfin/wizard/step1-' . Yii::$app->session->id);
        }
    }

	/**
	 * Save to cache document ID
	 *
	 * @param integer $documentId Document ID
	 */
	protected function cacheEditData($documentId)
	{
		Yii::$app->cache->set('swiftfin/wizard/edit-' . Yii::$app->session->id, $documentId);
	}

	/**
	 * Get edit document ID from cache
	 *
	 * @return integer
	 */
	protected function getCachedEditData()
	{
		return Yii::$app->cache->get('swiftfin/wizard/edit-' . Yii::$app->session->id);
	}

	/**
	 * Clear edit document cache
	 */
	protected function clearCachedEditData()
	{
		Yii::$app->cache->delete('swiftfin/wizard/edit-' . Yii::$app->session->id);
	}

    /**
     * Get first error message
     *
     * @param mixed $errors Error string or array
     * @return string
     */
    protected function getErrorMessage($errors)
    {
        if (!is_array($errors)) {
            return $errors;
        }

        foreach ($errors as $value) {
            if (is_array($value)) {
                return $this->getErrorMessage($value);
            }

            return $value;
        }
    }

}